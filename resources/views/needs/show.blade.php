@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du Besoin Étudiant</h3>
                    <div class="card-tools">
                        <a href="{{ route('needs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                        <a href="{{ route('needs.edit', $need) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Informations sur l'Étudiant</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Matricule:</th>
                                            <td>{{ $need->student->matricule }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nom Complet:</th>
                                            <td>{{ $need->student->first_name }} {{ $need->student->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $need->student->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Téléphone:</th>
                                            <td>{{ $need->student->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Niveau:</th>
                                            <td>{{ $need->student->level }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h4 class="card-title">Détails du Besoin</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Type de Besoin:</th>
                                            <td>{{ $need->need_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Priorité:</th>
                                            <td>
                                                @php
                                                    $priorityColors = [
                                                        'low' => 'success',
                                                        'medium' => 'warning',
                                                        'high' => 'danger',
                                                        'urgent' => 'dark'
                                                    ];
                                                    $priorityLabels = [
                                                        'low' => 'Faible',
                                                        'medium' => 'Moyenne',
                                                        'high' => 'Haute',
                                                        'urgent' => 'Urgente'
                                                    ];
                                                @endphp
                                                <span class="badge badge-{{ $priorityColors[$need->priority] }}">
                                                    {{ $priorityLabels[$need->priority] }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Statut:</th>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'in_progress' => 'info',
                                                        'resolved' => 'success',
                                                        'cancelled' => 'secondary'
                                                    ];
                                                    $statusLabels = [
                                                        'pending' => 'En attente',
                                                        'in_progress' => 'En cours',
                                                        'resolved' => 'Résolu',
                                                        'cancelled' => 'Annulé'
                                                    ];
                                                @endphp
                                                <span class="badge badge-{{ $statusColors[$need->status] }}">
                                                    {{ $statusLabels[$need->status] }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Date de Demande:</th>
                                            <td>{{ $need->request_date->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date de Résolution:</th>
                                            <td>
                                                @if($need->resolution_date)
                                                    {{ $need->resolution_date->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">Non résolu</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title">Description Détaillée</h4>
                                </div>
                                <div class="card-body">
                                    <p>{{ $need->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($need->notes)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title">Notes Additionnelles</h4>
                                </div>
                                <div class="card-body">
                                    <p>{{ $need->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title">Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('needs.edit', $need) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        @if($need->status !== 'resolved')
                                            <form action="{{ route('needs.mark-resolved', $need) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir marquer ce besoin comme résolu ?')">
                                                    <i class="fas fa-check"></i> Marquer comme Résolu
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('needs.destroy', $need) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce besoin ?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
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