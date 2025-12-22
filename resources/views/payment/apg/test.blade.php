<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APG Payment Test - Chatvoo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .test-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .test-card h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .test-option {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }

        .test-option:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .amount-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .info-box h4 {
            color: #1976d2;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-box ul {
            margin-left: 20px;
            color: #555;
            font-size: 13px;
        }

        .info-box li {
            margin-bottom: 5px;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .warning-box strong {
            color: #856404;
        }

        .payment-method {
            margin: 10px 0;
        }

        .payment-method label {
            display: block;
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .payment-method select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .payment-method select:focus {
            outline: none;
            border-color: #667eea;
        }

        .method-info {
            font-size: 11px;
            color: #888;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🔐 APG Payment Test</h1>
        <p class="subtitle">Alfa Payment Gateway Integration Testing</p>

        <div class="warning-box">
            <strong>⚠️ Production Environment</strong><br>
            This will process real payments. Use small amounts for testing.
        </div>

        <div class="test-card">
            <h3>Quick Test Payments</h3>

            <!-- Test 1: PKR 10 -->
            <div class="test-option">
                <form action="{{ route('payment.apg.initiate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" value="10">
                    <input type="hidden" name="transaction_type" value="test">
                    <div class="amount-label">Small Test</div>
                    <div class="amount-value">PKR 10</div>

                    <div class="payment-method">
                        <label for="payment_method_1">Payment Method:</label>
                        <select name="payment_method" id="payment_method_1">
                            <option value="">All Payment Methods</option>
                            <option value="1">💰 Alfa Wallet</option>
                            <option value="2">🏦 Alfalah Bank Account</option>
                            <option value="3" selected>💳 Credit/Debit Card</option>
                        </select>
                        <div class="method-info">Select how you want to pay</div>
                    </div>

                    <button type="submit" class="btn">Pay PKR 10</button>
                </form>
            </div>

            <!-- Test 2: PKR 100 -->
            <div class="test-option">
                <form action="{{ route('payment.apg.initiate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" value="100">
                    <input type="hidden" name="transaction_type" value="test">
                    <div class="amount-label">Medium Test</div>
                    <div class="amount-value">PKR 100</div>

                    <div class="payment-method">
                        <label for="payment_method_2">Payment Method:</label>
                        <select name="payment_method" id="payment_method_2">
                            <option value="">All Payment Methods</option>
                            <option value="1">💰 Alfa Wallet</option>
                            <option value="2">🏦 Alfalah Bank Account</option>
                            <option value="3" selected>💳 Credit/Debit Card</option>
                        </select>
                        <div class="method-info">Select how you want to pay</div>
                    </div>

                    <button type="submit" class="btn">Pay PKR 100</button>
                </form>
            </div>

            <!-- Test 3: PKR 1000 -->
            <div class="test-option">
                <form action="{{ route('payment.apg.initiate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" value="1000">
                    <input type="hidden" name="transaction_type" value="subscription">
                    <input type="hidden" name="related_id" value="1">
                    <div class="amount-label">Subscription Test</div>
                    <div class="amount-value">PKR 1,000</div>

                    <div class="payment-method">
                        <label for="payment_method_3">Payment Method:</label>
                        <select name="payment_method" id="payment_method_3">
                            <option value="">All Payment Methods</option>
                            <option value="1">💰 Alfa Wallet</option>
                            <option value="2">🏦 Alfalah Bank Account</option>
                            <option value="3" selected>💳 Credit/Debit Card</option>
                        </select>
                        <div class="method-info">Select how you want to pay</div>
                    </div>

                    <button type="submit" class="btn">Pay PKR 1,000</button>
                </form>
            </div>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ route('payment.apg.debug') }}" target="_blank"
                style="display: inline-block; padding: 12px 24px; background: #2196f3; color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                🔍 Open Debug Console (Monitor Logs in Real-Time)
            </a>
        </div>

        <div class="info-box">
            <h4>📋 What happens when you click Pay:</h4>
            <ul>
                <li>Transaction created in database</li>
                <li>Handshake request sent to APG</li>
                <li>You'll be redirected to APG payment page</li>
                <li>Enter card details and complete payment</li>
                <li>Redirected back to success/failed page</li>
                <li>Transaction status updated automatically</li>
            </ul>
        </div>

        <div class="info-box" style="background: #f3e5f5; border-color: #9c27b0; margin-top: 15px;">
            <h4 style="color: #7b1fa2;">🔍 Monitor Transactions:</h4>
            <ul>
                <li>Database: <code>apg_transactions</code> table</li>
                <li>Logs: <code>apg_payment_logs</code> table</li>
                <li>Laravel logs: <code>storage/logs/laravel.log</code></li>
            </ul>
        </div>
    </div>
</body>

</html>