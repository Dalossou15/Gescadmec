@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Modifier le Paiement</h5>
                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.update', $payment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Étudiant</label>
                                    <input type="text" class="form-control" value="{{ $payment->student->full_name }} - {{ $payment->student->matricule }}" disabled readonly>
                                    <input type="hidden" name="student_id" value="{{ $payment->student_id }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inscription_id" class="form-label">Inscription</label>
                                    <select name="inscription_id" id="inscription_id" class="form-select @error('inscription_id') is-invalid @enderror" required>
                                        @foreach($inscriptions as $inscription)
                                            <option value="{{ $inscription->id }}" {{ old('inscription_id', $payment->inscription_id) == $inscription->id ? 'selected' : '' }}>
                                                {{ $inscription->academic_year }} - {{ $inscription->level }} (Solde: {{ number_format($inscription->remaining_balance + $payment->amount, 2) }} FCFA)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('inscription_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Montant <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $payment->amount) }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Date de paiement <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Méthode de paiement <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Espèce</option>
                                        <option value="check" {{ old('payment_method', $payment->payment_method) == 'check' ? 'selected' : '' }}>Chèque</option>
                                        <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                                        <option value="card" {{ old('payment_method', $payment->payment_method) == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_type" class="form-label">Type de paiement <span class="text-danger">*</span></label>
                                    <select name="payment_type" id="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        <option value="registration" {{ old('payment_type', $payment->payment_type) == 'registration' ? 'selected' : '' }}>Inscription</option>
                                        <option value="tuition" {{ old('payment_type', $payment->payment_type) == 'tuition' ? 'selected' : '' }}>Scolarité</option>
                                        <option value="other" {{ old('payment_type', $payment->payment_type) == 'other' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('payment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reference" class="form-label">Référence (optionnel)</label>
                                    <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference', $payment->reference) }}" maxlength="100">
                                    @error('reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes (optionnel)</label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" maxlength="500">{{ old('notes', $payment->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour le paiement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection