<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment - Chatvoo</title>
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
    </style>
</head>

<body>
    <div class="container">
        <div class="spinner"></div>
        <h2>Processing Your Payment</h2>
        <p>Please wait while we redirect you to the payment gateway...</p>
        <p><small>Transaction: {{ $transaction->transaction_reference_number }}</small></p>
    </div>

    <form id="handshakeForm" action="{{ route('payment.apg.callback') }}" method="GET" style="display: none;">
        <input type="hidden" name="auth_token" value="{{ $authToken }}">
    </form>

    <script>
        // Auto-submit form after 1 second
        setTimeout(function () {
            document.getElementById('handshakeForm').submit();
        }, 1000);
    </script>
</body>

</html>