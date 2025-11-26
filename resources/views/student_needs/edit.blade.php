@extends('layouts.app')

@section('title', 'Modifier Besoin')
@section('page-title', 'Modifier le Besoin Étudiant')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Formulaire de modification d\'un besoin étudiant
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('needs.update', $need) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">Étudiant <span class="text-danger">*</span></label>
                            <select class="form-select @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                                <option value="">Sélectionner un étudiant</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $need->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->first_name }} {{ $student->last_name }} - {{ $student->matricule }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type de Besoin <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Sélectionner un type</option>
                                <option value="financial" {{ old('type', $need->type) == 'financial' ? 'selected' : '' }}>Financier</option>
                                <option value="academic" {{ old('type', $need->type) == 'academic' ? 'selected' : '' }}>Académique</option>
                                <option value="administrative" {{ old('type', $need->type) == 'administrative' ? 'selected' : '' }}>Administratif</option>
                                <option value="technical" {{ old('type', $need->type) == 'technical' ? 'selected' : '' }}>Technique</option>
                                <option value="other" {{ old('type', $need->type) == 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priorité <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="">Sélectionner une priorité</option>
                                <option value="low" {{ old('priority', $need->priority) == 'low' ? 'selected' : '' }}>Faible</option>
                                <option value="medium" {{ old('priority', $need->priority) == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ old('priority', $need->priority) == 'high' ? 'selected' : '' }}>Haute</option>
                                <option value="urgent" {{ old('priority', $need->priority) == 'urgent' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="pending" {{ old('status', $need->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="resolved" {{ old('status', $need->status) == 'resolved' ? 'selected' : '' }}>Résolu</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $need->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description', $need->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="resolution_notes" class="form-label">Notes de Résolution</label>
                        <textarea class="form-control @error('resolution_notes') is-invalid @enderror" 
                                  id="resolution_notes" name="resolution_notes" rows="3">{{ old('resolution_notes', $need->resolution_notes) }}</textarea>
                        @error('resolution_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('needs.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-check-circle me-2"></i>
                            Mettre à jour le Besoin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection