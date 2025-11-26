<?php

namespace App\Http\Controllers;

use App\Models\StudentNeed;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentNeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StudentNeed::with(['student']);

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->has('need_type')) {
            $query->where('need_type', $request->input('need_type'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('student', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('matricule', 'like', "%{$search}%");
                  });
            });
        }

        $needs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('needs.index', compact('needs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::where('status', 'active')->orderBy('last_name')->orderBy('first_name')->get();
        
        return view('needs.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'need_type' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'request_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';

        StudentNeed::create($validated);

        return redirect()->route('needs.index')
                        ->with('success', 'Besoin étudiant enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentNeed $need)
    {
        $need->load(['student']);
        
        return view('needs.show', compact('need'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentNeed $need)
    {
        $students = Student::where('status', 'active')->orderBy('last_name')->orderBy('first_name')->get();
        
        return view('needs.edit', compact('need', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentNeed $need)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'need_type' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,resolved,cancelled',
            'request_date' => 'required|date',
            'resolution_date' => 'nullable|date|after_or_equal:request_date',
            'notes' => 'nullable|string',
        ]);

        $need->update($validated);

        return redirect()->route('needs.index')
                        ->with('success', 'Besoin étudiant mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentNeed $need)
    {
        $need->delete();

        return redirect()->route('needs.index')
                        ->with('success', 'Besoin étudiant supprimé avec succès.');
    }

    /**
     * Marquer un besoin comme résolu.
     */
    public function markAsResolved(StudentNeed $need)
    {
        $need->markAsResolved();

        return redirect()->route('needs.index')
                        ->with('success', 'Besoin marqué comme résolu.');
    }

    public function markResolved(StudentNeed $need)
{
    $need->status = 'resolved';
    $need->save();

    return redirect()->route('needs.index')->with('success', 'Besoin marqué comme résolu.');
}

}
