@extends('layouts.app')

@section('title', 'Liste des Étudiants')
@section('page-title', 'Gestion des Étudiants')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" action="{{ route('students.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher par nom, matricule ou email..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
            <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Diplômé</option>
        </select>
    </div>
    <div class="col-md-3 text-end">
        <a href="{{ route('students.create') }}" class="btn btn-gradient">
            <i class="bi bi-plus-circle me-2"></i>
            Nouvel Étudiant
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-people me-2"></i>
            Liste des Étudiants ({{ $students->total() }})
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Matricule</th>
                        <th>Nom Complet</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Niveau</th>
                        <th>Statut</th>
                        <th>Date d'Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">{{ $student->matricule }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 35px; height: 35px;">
                                    <span class="text-white fw-bold text-uppercase">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $student->full_name }}</div>
                                    <small class="text-muted">{{ $student->birth_date->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                {{ $student->email }}
                            </a>
                        </td>
                        <td>
                            <a href="tel:{{ $student->phone }}" class="text-decoration-none">
                                {{ $student->phone }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $student->level }}</span>
                        </td>
                        <td>
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
                        </td>
                        <td>{{ $student->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-success" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirmDelete('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-4"></i>
                            <p class="mt-2">Aucun étudiant trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($students->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Affichage de {{ $students->firstItem() }} à {{ $students->lastItem() }} sur {{ $students->total() }} étudiants
            </div>
            <div>
                {{ $students->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection