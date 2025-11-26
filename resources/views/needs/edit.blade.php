@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modifier le Besoin Étudiant</h3>
                    <div class="card-tools">
                        <a href="{{ route('needs.show', $need) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Voir les détails
                        </a>
                        <a href="{{ route('needs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('needs.update', $need) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">Étudiant *</label>
                                    <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
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
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="need_type">Type de Besoin *</label>
                                    <input type="text" name="need_type" id="need_type" class="form-control @error('need_type') is-invalid @enderror" value="{{ old('need_type', $need->need_type) }}" required>
                                    @error('need_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Ex: Matériel scolaire, Assistance financière, Support académique, etc.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priorité *</label>
                                    <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" required>
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
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Statut *</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="">Sélectionner un statut</option>
                                        <option value="pending" {{ old('status', $need->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="in_progress" {{ old('status', $need->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                        <option value="resolved" {{ old('status', $need->status) == 'resolved' ? 'selected' : '' }}>Résolu</option>
                                        <option value="cancelled" {{ old('status', $need->status) == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="request_date">Date de Demande *</label>
                                    <input type="date" name="request_date" id="request_date" class="form-control @error('request_date') is-invalid @enderror" value="{{ old('request_date', $need->request_date->format('Y-m-d')) }}" required>
                                    @error('request_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="resolution_date">Date de Résolution</label>
                                    <input type="date" name="resolution_date" id="resolution_date" class="form-control @error('resolution_date') is-invalid @enderror" value="{{ old('resolution_date', $need->resolution_date ? $need->resolution_date->format('Y-m-d') : '') }}">
                                    @error('resolution_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Laisser vide si non résolu</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description Détaillée *</label>
                                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $need->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Notes Additionnelles</label>
                                    <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $need->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Mettre à jour le Besoin
                                </button>
                                <a href="{{ route('needs.show', $need) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection