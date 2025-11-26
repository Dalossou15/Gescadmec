<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = Inscription::with(['student', 'payments']);

    if ($request->academic_year) {
        $query->where('academic_year', $request->academic_year);
    }

    if ($request->level) {
        $query->where('level', $request->level);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->student_id) {
        $query->where('student_id', $request->student_id);
    }

    $inscriptions = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('inscriptions.index', compact('inscriptions'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $students = Student::where('status', 'active')->get();
        $student_id = $request->input('student_id');
        
        return view('inscriptions.create', compact('students', 'student_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'academic_year' => 'required|string|max:20',
            'level' => 'required|string|max:100',
            'total_fees' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        Inscription::create($validator->validated());

        return redirect()->route('inscriptions.index')
                 ->with('success', 'Inscription créée avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Inscription $inscription)
    {
        $inscription->load(['student', 'payments']);
        return view('inscriptions.show', compact('inscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inscription $inscription)
    {
        $students = Student::where('status', 'active')->get();
        return view('inscriptions.edit', compact('inscription', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inscription $inscription)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'academic_year' => 'required|string|max:20',
            'level' => 'required|string|max:100',
            'total_fees' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $inscription->update($validator->validated());

        return redirect()->route('inscriptions.show', $inscription)
                         ->with('success', 'Inscription mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inscription $inscription)
    {
        // Vérifier s'il y a des paiements associés
        if ($inscription->payments()->exists()) {
            return redirect()->route('inscriptions.index')
                           ->with('error', 'Impossible de supprimer une inscription avec des paiements associés.');
        }

        $inscription->delete();

        return redirect()->route('inscriptions.index')
                         ->with('success', 'Inscription supprimée avec succès.');
    }
}