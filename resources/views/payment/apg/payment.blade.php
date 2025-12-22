<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment - Chatvoo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            margin-bottom: 20px;
        }

        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="spinner"></div>
        <h2>Redirecting to Secure Payment</h2>
        <div class="amount">PKR {{ number_format($transaction->amount, 2) }}</div>
        <p>Please wait while we redirect you to Alfa Payment Gateway...</p>
        <p><small>Transaction: {{ $transaction->transaction_reference_number }}</small></p>
    </div>

    <form id="paymentForm" action="{{ $paymentUrl }}" method="POST" style="display: none;">
        @foreach($params as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>

    <script>
        // Auto-submit form immediately
        document.getElementById('paymentForm').submit();
    </script>
</body>

</html>