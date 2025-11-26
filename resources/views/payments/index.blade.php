@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des Paiements</h5>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouveau Paiement
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtres -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="student_id" class="form-label">Étudiant</label>
                                <select name="student_id" id="student_id" class="form-select">
                                    <option value="">Tous les étudiants</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="payment_method" class="form-label">Méthode</label>
                                <select name="payment_method" id="payment_method" class="form-select">
                                    <option value="">Toutes</option>
                                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Espèce</option>
                                    <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Chèque</option>
                                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Virement</option>
                                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Carte</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="payment_type" class="form-label">Type</label>
                                <select name="payment_type" id="payment_type" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="registration" {{ request('payment_type') == 'registration' ? 'selected' : '' }}>Inscription</option>
                                    <option value="tuition" {{ request('payment_type') == 'tuition' ? 'selected' : '' }}>Scolarité</option>
                                    <option value="other" {{ request('payment_type') == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">Date du</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="date_to" class="form-label">Date au</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary">Filtrer</button>
                            </div>
                        </div>
                    </form>

                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Référence</th>
                                        <th>Étudiant</th>
                                        <th>Inscription</th>
                                        <th>Montant</th>
                                        <th>Méthode</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                            <td>{{ $payment->receipt_number }}</td>
                                            <td>{{ $payment->student->full_name }}</td>
                                            <td>{{ $payment->inscription->academic_year }} - {{ $payment->inscription->level }}</td>
                                            <td>{{ number_format($payment->amount, 2) }} FCFA</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst($payment->payment_method) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst($payment->payment_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-outline-success" title="Reçu">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Aucun paiement trouvé.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection