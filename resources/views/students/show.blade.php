@extends('layouts.app')

@section('title', 'Détails de l\'Étudiant')
@section('page-title', 'Profil de ' . $student->full_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 100px; height: 100px;">
                        <span class="text-white fw-bold display-4">
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        </span>
                    </div>
                    <h5 class="my-3">{{ $student->full_name }}</h5>
                    <p class="text-muted mb-1">{{ $student->level }}</p>
                    <p class="text-muted mb-4">{{ $student->address }}</p>
                    @switch($student->status)
                        @case('active')
                            <span class="badge badge-status bg-success">Actif</span>
                            @break
                        @case('inactive')
                            <span class="badge badge-status bg-warning">Inactif</span>
                            @break
                        @case('graduated')
                            <span class="badge badge-status bg-info">Diplômé</span>
                            @break
                        @default
                            <span class="badge badge-status bg-secondary">{{ $student->status }}</span>
                    @endswitch
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <p class="mb-0"><i class="bi bi-person-badge me-2"></i>Matricule</p>
                        </div>
                        <div class="col-sm-7">
                            <p class="text-muted mb-0">{{ $student->matricule }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5">
                            <p class="mb-0"><i class="bi bi-envelope me-2"></i>Email</p>
                        </div>
                        <div class="col-sm-7">
                            <p class="text-muted mb-0">{{ $student->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5">
                            <p class="mb-0"><i class="bi bi-phone me-2"></i>Téléphone</p>
                        </div>
                        <div class="col-sm-7">
                            <p class="text-muted mb-0">{{ $student->phone }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5">
                            <p class="mb-0"><i class="bi bi-calendar-event me-2"></i>Date de Naiss.</p>
                        </div>
                        <div class="col-sm-7">
                            <p class="text-muted mb-0">{{ $student->birth_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5">
                            <p class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Inscrit le</p>
                        </div>
                        <div class="col-sm-7">
                            <p class="text-muted mb-0">{{ $student->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="student-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="inscriptions-tab" data-bs-toggle="tab" data-bs-target="#inscriptions" type="button" role="tab" aria-controls="inscriptions" aria-selected="true">
                                <i class="bi bi-card-list me-2"></i>Inscriptions & Paiements
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="needs-tab" data-bs-toggle="tab" data-bs-target="#needs" type="button" role="tab" aria-controls="needs" aria-selected="false">
                                <i class="bi bi-box-seam me-2"></i>Besoins
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="student-tabs-content">
                        <div class="tab-pane fade show active" id="inscriptions" role="tabpanel" aria-labelledby="inscriptions-tab">
                            @forelse($student->inscriptions as $inscription)
                                <div class="card mb-3">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            Inscription #{{ $inscription->id }} - {{ $inscription->academic_year }} ({{ $inscription->level }})
                                        </h6>
                                        <span class="badge bg-primary">{{ number_format($inscription->amount, 2, ',', ' ') }} FCFA</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Date Paiement</th>
                                                    <th>Montant Payé</th>
                                                    <th>Méthode</th>
                                                    <th>Reçu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($inscription->payments as $payment)
                                                    <tr>
                                                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                                        <td>{{ number_format($payment->amount, 2, ',', ' ') }} FCFA</td>
                                                        <td>{{ $payment->payment_method }}</td>
                                                        <td>
                                                            <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                                                <i class="bi bi-receipt"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Aucun paiement pour cette inscription.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th colspan="1" class="text-end">Total Payé:</th>
                                                    <th colspan="3">{{ number_format($inscription->payments->sum('amount'), 2, ',', ' ') }} FCFA</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="1" class="text-end">Reste à Payer:</th>
                                                    <th colspan="3" class="text-danger">{{ number_format($inscription->amount - $inscription->payments->sum('amount'), 2, ',', ' ') }} FCFA</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted">
                                    <i class="bi bi-inbox display-4"></i>
                                    <p>Aucune inscription trouvée pour cet étudiant.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="tab-pane fade" id="needs" role="tabpanel" aria-labelledby="needs-tab">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Besoin</th>
                                        <th>Description</th>
                                        <th>Quantité</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->needs as $need)
                                        <tr>
                                            <td>{{ $need->need_name }}</td>
                                            <td>{{ $need->description }}</td>
                                            <td>{{ $need->quantity }}</td>
                                            <td>
                                                @if($need->status == 'satisfied')
                                                    <span class="badge bg-success">Satisfait</span>
                                                @else
                                                    <span class="badge bg-warning">En attente</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                <i class="bi bi-inbox display-4"></i>
                                                <p>Aucun besoin enregistré pour cet étudiant.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection