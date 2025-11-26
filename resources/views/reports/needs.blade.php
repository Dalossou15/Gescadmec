@extends('layouts.app')

@section('title', 'Rapport des Besoins')
@section('page-title', 'Rapport des Besoins Étudiants')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistiques des Besoins</h3>
                <div class="card-tools">
                    <form method="GET" action="{{ route('reports.needs') }}" class="form-inline">
                        <div class="input-group input-group-sm">
                            <select name="status" class="form-control mr-2">
                                <option value="">Tous les statuts</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Résolus</option>
                            </select>
                            <select name="priority" class="form-control mr-2">
                                <option value="">Toutes les priorités</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Faible</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Besoins</span>
                                <span class="info-box-number">{{ $totalNeeds }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">En Attente</span>
                                <span class="info-box-number">{{ $pendingNeeds }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Résolus</span>
                                <span class="info-box-number">{{ $resolvedNeeds }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">En Retard</span>
                                <span class="info-box-number">{{ $overdueNeeds }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Besoins par Type</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Nombre</th>
                                                <th>Pourcentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($needsByType as $type => $count)
                                            <tr>
                                                <td>{{ ucfirst($type) }}</td>
                                                <td>{{ $count }}</td>
                                                <td>{{ number_format(($count / $totalNeeds) * 100, 1) }}%</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Besoins par Priorité</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Priorité</th>
                                                <th>Nombre</th>
                                                <th>Pourcentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($needsByPriority as $priority => $count)
                                            <tr>
                                                <td>{{ ucfirst($priority) }}</td>
                                                <td>{{ $count }}</td>
                                                <td>{{ number_format(($count / $totalNeeds) * 100, 1) }}%</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Derniers Besoins</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Étudiant</th>
                                                <th>Type</th>
                                                <th>Priorité</th>
                                                <th>Statut</th>
                                                <th>Date Limite</th>
                                                <th>Jours Restants</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentNeeds as $need)
                                            <tr>
                                                <td>{{ $need->student->first_name }} {{ $need->student->last_name }}</td>
                                                <td>{{ ucfirst($need->type) }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $need->priority == 'urgent' ? 'danger' : ($need->priority == 'high' ? 'warning' : ($need->priority == 'medium' ? 'info' : 'secondary')) }}">
                                                        {{ ucfirst($need->priority) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $need->status == 'resolved' ? 'success' : 'warning' }}">
                                                        {{ $need->status == 'resolved' ? 'Résolu' : 'En attente' }}
                                                    </span>
                                                </td>
                                                <td>{{ $need->deadline ? $need->deadline->format('d/m/Y') : '-' }}</td>
                                                <td class="{{ $need->isOverdue() ? 'text-danger' : '' }}">
                                                    {{ $need->deadline ? $need->deadline->diffInDays(now()) . ' jours' : '-' }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection