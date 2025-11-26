@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h3>Liste des inscriptions</h3>
        <a href="{{ route('inscriptions.create') }}" class="btn btn-primary">
            Nouvelle inscription
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Année académique</th>
                        <th>Niveau</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($inscriptions as $inscription)
                        <tr>
                            <td>{{ $inscription->student->first_name }} {{ $inscription->student->last_name }}</td>
                            <td>{{ $inscription->academic_year }}</td>
                            <td>{{ $inscription->level }}</td>
                            <td>{{ number_format($inscription->total_fees, 0, ',', ' ') }} F CFA</td>

                            <td>
                                <span class="badge bg-{{ 
                                    $inscription->status == 'confirmed' ? 'success' : 
                                    ($inscription->status == 'pending' ? 'warning' : 'danger')
                                }}">
                                    {{ ucfirst($inscription->status) }}
                                </span>
                            </td>

                            <td>{{ $inscription->created_at->format('d/m/Y') }}</td>

                            <td>
                                <a href="{{ route('inscriptions.show', $inscription) }}" class="btn btn-info btn-sm">Voir</a>
                                <a href="{{ route('inscriptions.edit', $inscription) }}" class="btn btn-warning btn-sm">Modifier</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Aucune inscription trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2">
                {{ $inscriptions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
