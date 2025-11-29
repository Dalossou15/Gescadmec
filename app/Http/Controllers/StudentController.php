<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with(['inscriptions', 'payments']);

        // Recherche et filtrage
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        if ($request->has('level')) {
            $query->where('level', $request->input('level'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'level' => 'required|string|max:100',
            'matricule' => 'required|string|unique:students,matricule|max:50',
            'status' => 'required|in:active,inactif,diplômé'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $student = Student::create($request->all());

        return redirect()->route('students.index')
                         ->with('success', 'Étudiant créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {

        $students = Student::all();
        $student->load(['inscriptions.payments', 'needs']);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'level' => 'required|string|max:100',
            'matricule' => 'required|string|unique:students,matricule,' . $student->id . '|max:50',
            'status' => 'required|in:active,inactif,diplômé'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $student->update($request->all());

        return redirect()->route('students.index')
                         ->with('success', 'Étudiant mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Vérifier s'il y a des inscriptions actives
        if ($student->inscriptions()->where('status', 'active')->exists()) {
            return redirect()->route('students.index')
                           ->with('error', 'Impossible de supprimer un étudiant avec des inscriptions actives.');
        }

        $student->delete();

        return redirect()->route('students.index')
                         ->with('success', 'Étudiant supprimé avec succès.');
    }
}
