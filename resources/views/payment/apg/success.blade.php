<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Chatvoo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #38ef7d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .success-icon svg {
            width: 50px;
            height: 50px;
            stroke: white;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #11998e;
            margin: 20px 0;
        }

        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #11998e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #0d7a6f;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="success-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1>Payment Successful!</h1>
        <p>Thank you for your payment. Your transaction has been completed successfully.</p>

        <div class="amount">PKR {{ number_format($transaction->amount, 2) }}</div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Transaction ID:</span>
                <span class="detail-value">{{ $transaction->apg_transaction_id ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Reference Number:</span>
                <span class="detail-value">{{ $transaction->transaction_reference_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span
                    class="detail-value">{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value" style="color: #38ef7d;">Paid</span>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn">Return to Dashboard</a>
    </div>
</body>

</html>