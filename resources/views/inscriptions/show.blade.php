@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de l'Inscription</h3>
                    <div class="card-tools">
                        <a href="{{ route('inscriptions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Informations de l'Étudiant</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Matricule:</th>
                                            <td>{{ $inscription->student->matricule }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nom Complet:</th>
                                            <td>{{ $inscription->student->first_name }} {{ $inscription->student->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $inscription->student->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Téléphone:</th>
                                            <td>{{ $inscription->student->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Niveau:</th>
                                            <td>{{ $inscription->student->level }}</td>
                                        </tr>
                                        <tr>
                                            <th>Statut:</th>
                                            <td>
                                                @if($inscription->student->status === 'active')
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactif</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h4 class="card-title">Détails de l'Inscription</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Année Académique:</th>
                                            <td>{{ $inscription->academic_year }}</td>
                                        </tr>
                                        <tr>
                                            <th>Niveau:</th>
                                            <td>{{ $inscription->level }}</td>
                                        </tr>
                                        <tr>
                                            <th>Frais d'Inscription:</th>
                                            <td class="font-weight-bold">{{ number_format($inscription->fees, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                        <tr>
                                            <th>Montant Payé:</th>
                                            <td class="text-success">{{ number_format($inscription->paid_amount, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                        <tr>
                                            <th>Solde Restant:</th>
                                            <td class="font-weight-bold {{ $inscription->balance > 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($inscription->balance, 0, ',', ' ') }} FCFA
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Statut:</th>
                                            <td>
                                                @switch($inscription->status)
                                                    @case('paid')
                                                        <span class="badge badge-success">Payé</span>
                                                        @break
                                                    @case('partial')
                                                        <span class="badge badge-warning">Partiel</span>
                                                        @break
                                                    @case('unpaid')
                                                        <span class="badge badge-danger">Non Payé</span>
                                                        @break
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Date d'Inscription:</th>
                                            <td>{{ $inscription->inscription_date->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Notes:</th>
                                            <td>{{ $inscription->notes ?? 'Aucune note' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historique des paiements -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h4 class="card-title">Historique des Paiements</h4>
                                </div>
                                <div class="card-body">
                                    @if($inscription->payments->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Montant</th>
                                                        <th>Mode</th>
                                                        <th>Type</th>
                                                        <th>Référence</th>
                                                        <th>Notes</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($inscription->payments as $payment)
                                                        <tr>
                                                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                                            <td class="font-weight-bold text-success">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                                                            <td>
                                                                @switch($payment->payment_method)
                                                                    @case('cash')
                                                                        <span class="badge badge-success">Espèces</span>
                                                                        @break
                                                                    @case('bank_transfer')
                                                                        <span class="badge badge-info">Virement</span>
                                                                        @break
                                                                    @case('check')
                                                                        <span class="badge badge-warning">Chèque</span>
                                                                        @break
                                                                    @case('mobile_money')
                                                                        <span class="badge badge-primary">Mobile Money</span>
                                                                        @break
                                                                @endswitch
                                                            </td>
                                                            <td>
                                                                @switch($payment->payment_type)
                                                                    @case('inscription')
                                                                        <span class="badge badge-primary">Inscription</span>
                                                                        @break
                                                                    @case('monthly')
                                                                        <span class="badge badge-info">Mensuel</span>
                                                                        @break
                                                                    @case('exam')
                                                                        <span class="badge badge-warning">Examen</span>
                                                                        @break
                                                                    @case('other')
                                                                        <span class="badge badge-secondary">Autre</span>
                                                                        @break
                                                                @endswitch
                                                            </td>
                                                            <td>{{ $payment->reference ?? '-' }}</td>
                                                            <td>{{ $payment->notes ?? '-' }}</td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info" title="Voir">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-warning" title="Reçu" target="_blank">
                                                                        <i class="fas fa-receipt"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="table-success">
                                                        <th colspan="1">Total Payé:</th>
                                                        <th class="font-weight-bold">{{ number_format($inscription->paid_amount, 0, ',', ' ') }} FCFA</th>
                                                        <th colspan="5"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <p>Aucun paiement enregistré pour cette inscription.</p>
                                            <a href="{{ route('payments.create', ['student_id' => $inscription->student_id, 'inscription_id' => $inscription->id]) }}" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Ajouter un Paiement
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('inscriptions.edit', $inscription) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('inscriptions.destroy', $inscription) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                            <a href="{{ route('inscriptions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Liste des Inscriptions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection