@extends('layouts.app')

@section('title', 'Paiements par Niveau')
@section('page-title', 'Rapport des Paiements par Niveau')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Paiements par Niveau</h3>
                <div class="card-tools">
                    <form method="GET" action="{{ route('reports.payments-by-level') }}" class="form-inline">
                        <div class="input-group input-group-sm">
                            <select name="academic_year" class="form-control mr-2">
                                <option value="">Toutes les années</option>
                                @for($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                    <option value="{{ $year }}-{{ $year + 1 }}" {{ request('academic_year') == "$year-" . ($year + 1) ? 'selected' : '' }}>
                                        {{ $year }}-{{ $year + 1 }}
                                    </option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Niveau</th>
                                <th>Nombre d\'Inscriptions</th>
                                <th>Nombre de Paiements</th>
                                <th>Montant Total</th>
                                <th>Montant Moyen</th>
                                <th>Taux de Paiement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentsByLevel as $level => $data)
                            <tr>
                                <td>{{ $level }}</td>
                                <td>{{ $data['inscriptions_count'] }}</td>
                                <td>{{ $data['payments_count'] }}</td>
                                <td>{{ number_format($data['total_amount'], 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($data['average_amount'], 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar {{ $data['payment_rate'] >= 80 ? 'bg-success' : ($data['payment_rate'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                             style="width: {{ $data['payment_rate'] }}%">
                                            {{ number_format($data['payment_rate'], 1) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th>Total</th>
                                <th>{{ $paymentsByLevel->sum('inscriptions_count') }}</th>
                                <th>{{ $paymentsByLevel->sum('payments_count') }}</th>
                                <th>{{ number_format($paymentsByLevel->sum('total_amount'), 0, ',', ' ') }} FCFA</th>
                                <th>{{ number_format($paymentsByLevel->avg('average_amount'), 0, ',', ' ') }} FCFA</th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistiques Globales</h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total des Paiements</span>
                        <span class="info-box-number">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Nombre de Paiements</span>
                        <span class="info-box-number">{{ $totalCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Meilleurs Niveaux</h3>
            </div>
            <div class="card-body">
                @php
                    $sortedLevels = $paymentsByLevel->sortByDesc('total_amount')->take(3);
                @endphp
                @foreach($sortedLevels as $level => $data)
                <div class="info-box {{ $loop->first ? 'bg-warning' : ($loop->last ? 'bg-danger' : 'bg-info') }}">
                    <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $level }}</span>
                        <span class="info-box-number">{{ number_format($data['total_amount'], 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection