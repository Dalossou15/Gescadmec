@extends('layouts.app')

@section('title', 'Tableau de bord - Gestion des Étudiants')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3 mb-4">
        <div class="card card-stats bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold">{{ $stats['total_students'] }}</h4>
                        <p class="mb-0">Total Étudiants</p>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-white-50">
                        <i class="bi bi-circle-fill text-success me-1"></i>
                        {{ $stats['active_students'] }} Actifs
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card card-stats bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold">{{ $stats['total_inscriptions'] }}</h4>
                        <p class="mb-0">Inscriptions</p>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-journal-text"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-white-50">
                        <i class="bi bi-circle-fill text-light me-1"></i>
                        {{ $stats['active_inscriptions'] }} Actives
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card card-stats bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold">{{ number_format($stats['total_payments']) }}</h4>
                        <p class="mb-0">Paiements</p>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-credit-card"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-white-50">
                        <i class="bi bi-currency-exchange me-1"></i>
                        {{ number_format($stats['total_revenue'], 0, ',', ' ') }} F CFA
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card card-stats bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold">{{ $stats['pending_needs'] }}</h4>
                        <p class="mb-0">Besoins</p>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-white-50">
                        <i class="bi bi-clock me-1"></i>
                        En attente
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Students by Level Chart -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>
                    Répartition des étudiants par niveau
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($studentsByLevel as $level)
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">{{ $level->level }}</span>
                            <span class="badge bg-primary">{{ $level->count }}</span>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ $stats['total_students'] > 0 ? ($level->count / $stats['total_students']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Payments -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Derniers paiements
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Méthode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                            <tr>
                                <td>
                                    @if($payment->student)
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 30px; height: 30px;">
                                            <span class="text-white fw-bold text-uppercase">
                                                {{ substr($payment->student->first_name, 0, 1) }}{{ substr($payment->student->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $payment->student->full_name }}</div>
                                            <small class="text-muted">{{ $payment->student->matricule }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted">Étudiant non trouvé</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        {{ number_format($payment->amount, 0, ',', ' ') }} F CFA
                                    </span>
                                </td>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ ucfirst($payment->payment_method) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Aucun paiement récent
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

<div class="row">
    <!-- Urgent Needs -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Besoins urgents
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Type</th>
                                <th>Priorité</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($urgentNeeds as $need)
                            <tr>
                                <td>
                                    @if($need->student)
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 30px; height: 30px;">
                                            <span class="text-white fw-bold text-uppercase">
                                                {{ substr($need->student->first_name, 0, 1) }}{{ substr($need->student->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $need->student->full_name }}</div>
                                            <small class="text-muted">{{ $need->student->matricule }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted">Étudiant non trouvé</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($need->need_type) }}</td>
                                <td>
                                    <span class="badge bg-danger">
                                        {{ ucfirst($need->priority) }}
                                    </span>
                                </td>
                                <td>{{ $need->request_date->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Aucun besoin urgent
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Revenue -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Revenus mensuels
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($monthlyRevenue as $revenue)
                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">
                                {{ \Carbon\Carbon::create($revenue->year, $revenue->month)->format('F Y') }}
                            </span>
                            <span class="badge bg-info">
                                {{ number_format($revenue->total, 0, ',', ' ') }} F CFA
                            </span>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-info" 
                                 style="width: {{ $monthlyRevenue->max('total') > 0 ? ($revenue->total / $monthlyRevenue->max('total')) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection