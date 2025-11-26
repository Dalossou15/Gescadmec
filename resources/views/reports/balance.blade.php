@extends('layouts.app')

@section('title', 'Rapport de Balance')
@section('page-title', 'Rapport de Balance des Étudiants')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Balance des Étudiants par Niveau</h3>
                <div class="card-tools">
                    <a href="{{ route('reports.export', ['type' => 'balance']) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Exporter Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Niveau</th>
                                <th>Nombre d'Étudiants</th>
                                <th>Total des Frais</th>
                                <th>Total Payé</th>
                                <th>Balance Totale</th>
                                <th>Moyenne Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($balanceByLevel as $level => $data)
                            <tr>
                                <td>{{ $level }}</td>
                                <td>{{ $data['count'] }}</td>
                                <td>{{ number_format($data['total_fees'], 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($data['total_paid'], 0, ',', ' ') }} FCFA</td>
                                <td class="{{ $data['total_balance'] > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($data['total_balance'], 0, ',', ' ') }} FCFA
                                </td>
                                <td class="{{ $data['average_balance'] > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($data['average_balance'], 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                               <th>{{ array_sum(array_column($balanceByLevel, 'count')) }}</th>
                               <th>{{ number_format(array_sum(array_column($balanceByLevel, 'total_fees')), 0, ',', ' ') }} FCFA</th>
                               <th>{{ number_format(array_sum(array_column($balanceByLevel, 'total_paid')), 0, ',', ' ') }} FCFA</th>
                               <th class="{{ array_sum(array_column($balanceByLevel, 'total_balance')) > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format(array_sum(array_column($balanceByLevel, 'total_balance')), 0, ',', ' ') }} FCFA
                                </th>

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
                <h3 class="card-title">Résumé Global</h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Étudiants</span>
                        <span class="info-box-number">{{ $totalStudents }}</span>
                    </div>
                </div>
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Payé</span>
                        <span class="info-box-number">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Balance Totale</span>
                        <span class="info-box-number">{{ number_format($totalBalance, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Étudiants avec Balance</h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Étudiants avec Balance > 0</span>
                        <span class="info-box-number">{{ $studentsWithBalance }}</span>
                    </div>
                </div>
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Étudiants Payés</span>
                        <span class="info-box-number">{{ $studentsPaid }}</span>
                    </div>
                </div>
                <div class="progress-group">
                    Taux de Paiement
                    <span class="float-right"><b>{{ $studentsPaid }}</b>/{{ $totalStudents }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $totalStudents > 0 ? ($studentsPaid / $totalStudents) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
