@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Liste des besoins des étudiants</h2>
        <a href="{{ route('needs.create') }}" class="btn btn-primary">
            + Ajouter un besoin
        </a>
    </div>

    {{-- Messages de succès --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body">

            @php
                $statusColors = [
                    'pending' => 'warning',
                    'in_progress' => 'info',
                    'resolved' => 'success',
                    'cancelled' => 'secondary',
                    'rejected' => 'danger' // ✅ correction ajoutée
                ];

                $statusLabels = [
                    'pending' => 'En attente',
                    'in_progress' => 'En cours',
                    'resolved' => 'Résolu',
                    'cancelled' => 'Annulé',
                    'rejected' => 'Rejeté' // ✅ correction ajoutée
                ];
            @endphp

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Étudiant</th>
                        <th>Description du besoin</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($needs as $need)
                        @php
                            $color = $statusColors[$need->status] ?? 'secondary';
                            $label = $statusLabels[$need->status] ?? ucfirst($need->status);
                        @endphp

                        <tr>
                            <td>{{ $need->id }}</td>
                            <td>
                                @if($need->student)
                                    {{ $need->student->last_name }} {{ $need->student->first_name }}
                                @else
                                    <span class="text-danger">Aucun étudiant lié</span>
                                @endif
                            </td>
                            <td>{{ $need->description }}</td>

                            <td>
                                <span class="badge bg-{{ $color }}">
                                    {{ $label }}
                                </span>
                            </td>

                            <td>{{ $need->created_at->format('d/m/Y') }}</td>

                            <td class="d-flex gap-2">
                                <a href="{{ route('needs.show', $need->id) }}" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{ route('needs.edit', $need->id) }}" class="btn btn-sm btn-warning">Modifier</a>

                                <form action="{{ route('needs.destroy', $need->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce besoin ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Aucun besoin trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $needs->links() }} {{-- Pagination --}}
            </div>

        </div>
    </div>

</div>

@endsection
