<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Inscription;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'inscription']);

        // Filtrage par étudiant
        if ($request->has('student_id')) {
            $query->where('student_id', $request->input('student_id'));
        }

        // Filtrage par inscription
        if ($request->has('inscription_id')) {
            $query->where('inscription_id', $request->input('inscription_id'));
        }

        // Filtrage par méthode de paiement
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        // Filtrage par type de paiement
        if ($request->has('payment_type')) {
            $query->where('payment_type', $request->input('payment_type'));
        }

        // Filtrage par date
        if ($request->has('date_from')) {
            $query->whereDate('payment_date', '>=', $request->input('date_from'));
        }

        if ($request->has('date_to')) {
            $query->whereDate('payment_date', '<=', $request->input('date_to'));
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);
        $students = Student::where('status', 'active')->get();

        return view('payments.index', compact('payments', 'students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $students = Student::where('status', 'active')->get();
        $inscriptions = collect();
        
        if ($request->has('student_id')) {
            $inscriptions = Inscription::where('student_id', $request->input('student_id'))
                                      ->where('status', 'active')
                                      ->get();
        }

        return view('payments.create', compact('students', 'inscriptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'inscription_id' => 'required|exists:inscriptions,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,check,bank_transfer,card',
            'reference' => 'nullable|string|max:100',
            'payment_type' => 'required|in:registration,tuition,other',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Vérifier que l'inscription appartient bien à l'étudiant
        $inscription = Inscription::find($request->inscription_id);
        if ($inscription->student_id != $request->student_id) {
            return redirect()->back()
                           ->with('error', 'Cette inscription ne correspond pas à l\'étudiant sélectionné.')
                           ->withInput();
        }

        // Vérifier que le montant ne dépasse pas le solde restant
        $remainingBalance = $inscription->remaining_balance;
        if ($request->amount > $remainingBalance) {
            return redirect()->back()
                           ->with('error', 'Le montant du paiement ne peut pas dépasser le solde restant de ' . number_format($remainingBalance, 2) . ' FCFA')
                           ->withInput();
        }

        $payment = Payment::create($request->all());

        // Vérifier si l'inscription est maintenant entièrement payée
        if ($inscription->fresh()->isFullyPaid()) {
            $inscription->update(['status' => 'completed']);
        }

        return redirect()->route('payments.show', $payment)
                         ->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['student', 'inscription']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $students = Student::where('status', 'active')->get();
        $inscriptions = Inscription::where('student_id', $payment->student_id)
                                  ->where('status', 'active')
                                  ->get();

        return view('payments.edit', compact('payment', 'students', 'inscriptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'inscription_id' => 'required|exists:inscriptions,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,check,bank_transfer,card',
            'reference' => 'nullable|string|max:100',
            'payment_type' => 'required|in:registration,tuition,other',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Calculer la différence de montant
        $oldAmount = $payment->amount;
        $newAmount = $request->amount;
        $amountDifference = $newAmount - $oldAmount;

        // Vérifier que le nouveau montant ne dépasse pas le solde restant
        $inscription = Inscription::find($request->inscription_id);
        $remainingBalance = $inscription->remaining_balance + $oldAmount;
        
        if ($newAmount > $remainingBalance) {
            return redirect()->back()
                           ->with('error', 'Le montant du paiement ne peut pas dépasser le solde restant de ' . number_format($remainingBalance, 2) . ' FCFA')
                           ->withInput();
        }

        $payment->update($request->all());

        return redirect()->route('payments.show', $payment)
                         ->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $inscription = $payment->inscription;
        $payment->delete();

        // Remettre l'inscription en statut actif si elle était complétée
        if ($inscription->status === 'completed') {
            $inscription->update(['status' => 'active']);
        }

        return redirect()->route('payments.index')
                         ->with('success', 'Paiement supprimé avec succès.');
    }

    /**
     * Générer le reçu PDF pour un paiement.
     */
    public function receipt(Payment $payment)
    {
        $payment->load(['student', 'inscription']);
        
        $pdf = Pdf::loadView('payments.receipt', compact('payment'));
        
        return $pdf->download('receipt_' . $payment->receipt_number . '.pdf');
    }
}
