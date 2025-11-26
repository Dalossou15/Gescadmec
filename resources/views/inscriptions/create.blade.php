@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Créer une Nouvelle Inscription</h3>
                    <div class="card-tools">
                        <a href="{{ route('inscriptions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('inscriptions.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">Étudiant *</label>
                                    <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un étudiant</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                                    <label for="academic_year">Année Académique *</label>
                                    <select name="academic_year" id="academic_year" class="form-control @error('academic_year') is-invalid @enderror" required>
                                        <option value="">Sélectionner une année</option>
                                        @for($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                            <option value="{{ $year }}-{{ $year + 1 }}" {{ old('academic_year') == "$year-" . ($year + 1) ? 'selected' : '' }}>
                                                {{ $year }}-{{ $year + 1 }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('academic_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="level">Niveau *</label>
                                    <select name="level" id="level" class="form-control @error('level') is-invalid @enderror" required>
                                        <option value="">Sélectionner un niveau</option>
                                        <option value="L1" {{ old('level') == 'L1' ? 'selected' : '' }}>L1</option>
                                        <option value="L2" {{ old('level') == 'L2' ? 'selected' : '' }}>L2</option>
                                        <option value="L3" {{ old('level') == 'L3' ? 'selected' : '' }}>L3</option>
                                        <option value="M1" {{ old('level') == 'M1' ? 'selected' : '' }}>M1</option>
                                        <option value="M2" {{ old('level') == 'M2' ? 'selected' : '' }}>M2</option>
                                    </select>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fees">Frais d'Inscription (FCFA) *</label>
                                    <input type="number" name="fees" id="fees" class="form-control @error('fees') is-invalid @enderror" value="{{ old('fees') }}" min="0" step="1000" required>
                                    @error('fees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inscription_date">Date d'Inscription *</label>
                                    <input type="date" name="inscription_date" id="inscription_date" class="form-control @error('inscription_date') is-invalid @enderror" value="{{ old('inscription_date', date('Y-m-d')) }}" required>
                                    @error('inscription_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Créer l'Inscription
                                </button>
                                <a href="{{ route('inscriptions.index') }}" class="btn btn-secondary">
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