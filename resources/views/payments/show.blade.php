@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails du Paiement</h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-success btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Reçu
                        </a>
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Informations du paiement</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Référence:</th>
                                    <td>{{ $payment->receipt_number }}</td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Montant:</th>
                                    <td><strong>{{ number_format($payment->amount, 2) }} FCFA</strong></td>
                                </tr>
                                <tr>
                                    <th>Méthode:</th>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($payment->payment_type) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($payment->reference)
                                    <tr>
                                        <th>Référence externe:</th>
                                        <td>{{ $payment->reference }}</td>
                                    </tr>
                                @endif
                                @if($payment->notes)
                                    <tr>
                                        <th>Notes:</th>
                                        <td>{{ $payment->notes }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Informations de l'étudiant</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Nom:</th>
                                    <td>{{ $payment->student->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Matricule:</th>
                                    <td>{{ $payment->student->matricule }}</td>
                                </tr>
                                <tr>
                                    <th>Niveau:</th>
                                    <td>{{ $payment->student->level }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $payment->student->email }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone:</th>
                                    <td>{{ $payment->student->phone }}</td>
                                </tr>
                            </table>

                            <h6 class="text-muted mt-4">Informations de l'inscription</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Année académique:</th>
                                    <td>{{ $payment->inscription->academic_year }}</td>
                                </tr>
                                <tr>
                                    <th>Niveau:</th>
                                    <td>{{ $payment->inscription->level }}</td>
                                </tr>
                                <tr>
                                    <th>Frais totaux:</th>
                                    <td>{{ number_format($payment->inscription->total_fees, 2) }} FCFA</td>
                                </tr>
                                <tr>
                                    <th>Montant payé:</th>
                                    <td>{{ number_format($payment->inscription->paid_amount, 2) }} FCFA</td>
                                </tr>
                                <tr>
                                    <th>Solde restant:</th>
                                    <td>
                                        @if($payment->inscription->remaining_balance <= 0)
                                            <span class="badge bg-success">Payé</span>
                                        @else
                                            <span class="badge bg-warning">{{ number_format($payment->inscription->remaining_balance, 2) }} FCFA</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection