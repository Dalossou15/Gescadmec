<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement - {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .header .subtitle {
            color: #6c757d;
            font-size: 16px;
            margin-top: 5px;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .receipt-number {
            background: #e3f2fd;
            padding: 10px 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .receipt-date {
            background: #f1f3f4;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h3 {
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            text-align: center;
            background: #d4edda;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .payment-method {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .method-cash { background: #fff3cd; color: #856404; }
        .method-check { background: #d1ecf1; color: #0c5460; }
        .method-bank_transfer { background: #d4edda; color: #155724; }
        .method-card { background: #f8d7da; color: #721c24; }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h1>REÇU DE PAIEMENT</h1>
            <div class="subtitle">Gestion des Étudiants</div>
        </div>

        <div class="receipt-info">
            <div class="receipt-number">
                <strong>Numéro de reçu:</strong><br>
                {{ $payment->receipt_number }}
            </div>
            <div class="receipt-date">
                <strong>Date d'émission:</strong><br>
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="section">
            <h3><i class="fas fa-user-graduate"></i> Informations de l'Étudiant</h3>
            <div class="info-row">
                <span class="info-label">Nom complet:</span>
                <span class="info-value">{{ $payment->student->full_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Matricule:</span>
                <span class="info-value">{{ $payment->student->matricule }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Niveau:</span>
                <span class="info-value">{{ $payment->inscription->level ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $payment->student->email ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone:</span>
                <span class="info-value">{{ $payment->student->phone ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="section">
            <h3><i class="fas fa-info-circle"></i> Détails du Paiement</h3>
            <div class="info-row">
                <span class="info-label">Date du paiement:</span>
                <span class="info-value">{{ $payment->payment_date->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Type de paiement:</span>
                <span class="info-value">
                    @switch($payment->payment_type)
                        @case('registration') Inscription @break
                        @case('tuition') Scolarité @break
                        @case('other') Autre @break
                        @default {{ ucfirst($payment->payment_type) }}
                    @endswitch
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Méthode de paiement:</span>
                <span class="info-value">
                    <span class="payment-method method-{{ $payment->payment_method }}">
                        @switch($payment->payment_method)
                            @case('cash') Espèce @break
                            @case('check') Chèque @break
                            @case('bank_transfer') Virement bancaire @break
                            @case('card') Carte bancaire @break
                            @default {{ ucfirst($payment->payment_method) }}
                        @endswitch
                    </span>
                </span>
            </div>
            @if($payment->reference)
            <div class="info-row">
                <span class="info-label">Référence:</span>
                <span class="info-value">{{ $payment->reference }}</span>
            </div>
            @endif
            @if($payment->notes)
            <div class="info-row">
                <span class="info-label">Notes:</span>
                <span class="info-value">{{ $payment->notes }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Statut:</span>
                <span class="info-value">
                    <span class="status-badge">Payé</span>
                </span>
            </div>
        </div>

        <div class="section">
            <h3><i class="fas fa-calculator"></i> Montant</h3>
            <div class="amount">
                {{ number_format($payment->amount, 2) }} FCFA
            </div>
        </div>

        <div class="section">
            <h3><i class="fas fa-graduation-cap"></i> Détails de l'Inscription</h3>
            <div class="info-row">
                <span class="info-label">Année académique:</span>
                <span class="info-value">{{ $payment->inscription->academic_year }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Frais d'inscription:</span>
                <span class="info-value">{{ number_format($payment->inscription->fees, 2) }} FCFA</span>
            </div>
            <div class="info-row">
                <span class="info-label">Montant payé:</span>
                <span class="info-value">{{ number_format($payment->inscription->paid_amount, 2) }} FCFA</span>
            </div>
            <div class="info-row">
                <span class="info-label">Solde restant:</span>
                <span class="info-value">{{ number_format($payment->inscription->remaining_balance, 2) }} FCFA</span>
            </div>
        </div>

        <div class="qr-code">
            <div style="width: 100px; height: 100px; border: 2px solid #007bff; margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                <div style="font-size: 12px; text-align: center; color: #6c757d;">
                    QR Code<br>{{ $payment->receipt_number }}
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>Ce reçu est généré automatiquement et constitue une preuve de paiement valide.</strong></p>
            <p>Conservez ce reçu pour vos dossiers. Pour toute question, contactez l'administration.</p>
            <p style="margin-top: 10px; font-size: 12px;">
                Généré le {{ now()->format('d/m/Y H:i:s') }} | 
                Système de Gestion des Étudiants
            </p>
        </div>
    </div>
</body>
</html>