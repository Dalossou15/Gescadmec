<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Inscription;
use App\Models\Payment;
use App\Models\StudentNeed;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Tableau de bord principal avec statistiques.
     */
    public function dashboard()
    {
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('status', 'active')->count(),
            'total_inscriptions' => Inscription::count(),
            'active_inscriptions' => Inscription::where('status', 'active')->count(),
            'total_payments' => Payment::count(),
            'total_revenue' => Payment::sum('amount'),
            'pending_needs' => StudentNeed::where('status', 'pending')->count(),
        ];

        // Statistiques par niveau
        $studentsByLevel = Student::select('level', DB::raw('count(*) as count'))
                                 ->groupBy('level')
                                 ->orderBy('count', 'desc')
                                 ->get();

        // Revenus par mois
        $monthlyRevenue = Payment::select(
                                DB::raw('MONTH(payment_date) as month'),
                                DB::raw('YEAR(payment_date) as year'),
                                DB::raw('SUM(amount) as total')
                            )
                            ->groupBy('year', 'month')
                            ->orderBy('year', 'desc')
                            ->orderBy('month', 'desc')
                            ->limit(12)
                            ->get();

        // Derniers paiements
        $recentPayments = Payment::with(['student', 'inscription'])
                                ->orderBy('payment_date', 'desc')
                                ->limit(10)
                                ->get();

        // Besoins urgents
        $urgentNeeds = StudentNeed::with('student')
                                   ->where('status', 'pending')
                                   ->where('priority', 'high')
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get();

        return view('reports.dashboard', compact(
            'stats', 'studentsByLevel', 'monthlyRevenue', 'recentPayments', 'urgentNeeds'
        ));
    }

    /**
     * Rapport des soldes par étudiant.
     */
    public function balanceReport(Request $request)
{
    $query = Inscription::with(['student', 'payments'])
                        ->where('status', 'active');

    if ($request->has('level')) {
        $query->where('level', $request->input('level'));
    }

    if ($request->has('academic_year')) {
        $query->where('academic_year', $request->input('academic_year'));
    }

    $inscriptions = $query->get();

    // Initialiser les statistiques globales
    $totalStudents = $inscriptions->count();
    $totalPaid = 0;
    $totalBalance = 0;
    $studentsWithBalance = 0;
    $studentsPaid = 0;

    // Calculer le balance par niveau
    $balanceByLevel = [];

    foreach ($inscriptions as $inscription) {
        $level = $inscription->level;
        $paid = $inscription->payments->sum('amount');
        $balance = $inscription->total_fees - $paid;

        $totalPaid += $paid;
        $totalBalance += $balance;

        if ($balance > 0) {
            $studentsWithBalance++;
        } else {
            $studentsPaid++;
        }

        if (!isset($balanceByLevel[$level])) {
            $balanceByLevel[$level] = [
                'count' => 0,
                'total_fees' => 0,
                'total_paid' => 0,
                'total_balance' => 0,
                'average_balance' => 0,
            ];
        }

        $balanceByLevel[$level]['count']++;
        $balanceByLevel[$level]['total_fees'] += $inscription->total_fees;
        $balanceByLevel[$level]['total_paid'] += $paid;
        $balanceByLevel[$level]['total_balance'] += $balance;
    }

    // Calculer la moyenne balance par niveau
    foreach ($balanceByLevel as $level => $data) {
        $balanceByLevel[$level]['average_balance'] = $data['count'] > 0
            ? $data['total_balance'] / $data['count']
            : 0;
    }

    return view('reports.balance', compact(
        'balanceByLevel',
        'totalStudents',
        'totalPaid',
        'totalBalance',
        'studentsWithBalance',
        'studentsPaid'
    ));
}


    /**
     * Rapport des paiements par niveau.
     */
    public function paymentsByLevel(Request $request)
{
    $academicYear = $request->input('academic_year', date('Y') . '-' . (date('Y') + 1));

    $paymentsByLevel = Payment::join('inscriptions', 'payments.inscription_id', '=', 'inscriptions.id')
                               ->select(
                                   'inscriptions.level',
                                   DB::raw('COUNT(payments.id) as payment_count'),
                                   DB::raw('SUM(payments.amount) as total_amount'),
                                   DB::raw('AVG(payments.amount) as average_amount')
                               )
                               ->where('inscriptions.academic_year', $academicYear)
                               ->groupBy('inscriptions.level')
                               ->orderBy('total_amount', 'desc')
                               ->get();

    // Statistiques détaillées par niveau
    $levelStats = Inscription::select(
                            'level',
                            DB::raw('COUNT(*) as total_inscriptions'),
                            DB::raw('SUM(total_fees) as total_expected'),
                            DB::raw('SUM(total_fees - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE inscription_id = inscriptions.id)) as total_remaining')
                        )
                        ->where('academic_year', $academicYear)
                        ->groupBy('level')
                        ->get();

    // ✅ AJOUT : calculer les totaux globaux
    $totalPayments = $paymentsByLevel->sum('total_amount');
    $totalCount = $paymentsByLevel->sum('payment_count');

    return view('reports.payments_by_level', compact(
        'paymentsByLevel',
        'levelStats',
        'academicYear',
        'totalPayments',
        'totalCount'
    ));
}

    /**
     * Rapport des besoins des étudiants.
     */
    public function needsReport(Request $request)
    {
        $query = StudentNeed::with(['student']);

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->has('need_type')) {
            $query->where('need_type', $request->input('need_type'));
        }

        $needs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques des besoins
        $needsStats = [
            'total' => StudentNeed::count(),
            'pending' => StudentNeed::where('status', 'pending')->count(),
            'resolved' => StudentNeed::where('status', 'resolved')->count(),
            'overdue' => StudentNeed::where('status', 'pending')
                                   ->whereDate('request_date', '<', now()->subDays(30))
                                   ->count(),
        ];

        // Répartition par type
        $needsByType = StudentNeed::select('need_type', DB::raw('count(*) as count'))
                                 ->groupBy('need_type')
                                 ->orderBy('count', 'desc')
                                 ->get();

        return view('reports.needs', compact('needs', 'needsStats', 'needsByType'));
    }

    /**
     * Export des données.
     */
    public function export(Request $request, $type)
    {
        switch ($type) {
            case 'students':
                return $this->exportStudents();
            case 'payments':
                return $this->exportPayments($request);
            case 'balance':
                return $this->exportBalanceSummary();
            default:
                return redirect()->back()->with('error', 'Type d\'export non supporté.');
        }
    }

    private function exportStudents()
    {
        $students = Student::with(['inscriptions' => function($query) {
                            $query->where('status', 'active');
                        }])
                        ->orderBy('last_name')
                        ->orderBy('first_name')
                        ->get();

        $pdf = Pdf::loadView('exports.students', compact('students'));
        return $pdf->download('students_list_' . date('Y-m-d') . '.pdf');
    }

    private function exportPayments(Request $request)
    {
        $query = Payment::with(['student', 'inscription'])
                       ->orderBy('payment_date', 'desc');

        if ($request->has('date_from')) {
            $query->whereDate('payment_date', '>=', $request->input('date_from'));
        }

        if ($request->has('date_to')) {
            $query->whereDate('payment_date', '<=', $request->input('date_to'));
        }

        $payments = $query->get();

        $pdf = Pdf::loadView('exports.payments', compact('payments'));
        return $pdf->download('payments_report_' . date('Y-m-d') . '.pdf');
    }

    private function exportBalanceSummary()
    {
        $inscriptions = Inscription::with(['student', 'payments'])
                                  ->where('status', 'active')
                                  ->get();

        $pdf = Pdf::loadView('exports.balance_summary', compact('inscriptions'));
        return $pdf->download('balance_summary_' . date('Y-m-d') . '.pdf');
    }
}
