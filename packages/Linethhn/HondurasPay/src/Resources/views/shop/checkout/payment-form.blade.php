<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Seguro - Honduras Pay</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .payment-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
        }
        .payment-header {
            background: linear-gradient(135deg, #D4AF37, #b8962e);
            padding: 24px;
            text-align: center;
            color: #fff;
        }
        .payment-header h2 {
            font-size: 1.3rem;
            margin-bottom: 4px;
        }
        .payment-header .amount {
            font-size: 2rem;
            font-weight: 700;
        }
        .payment-header .order-ref {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-top: 4px;
        }
        .payment-body { padding: 32px 24px; }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s;
            outline: none;
        }
        .form-group input:focus {
            border-color: #D4AF37;
        }
        .form-row {
            display: flex;
            gap: 12px;
        }
        .form-row .form-group { flex: 1; }
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.8rem;
            color: #666;
        }
        .security-badge .lock { color: #28a745; font-weight: bold; }
        .btn-pay {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #D4AF37, #b8962e);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
        }
        .btn-pay:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .btn-cancel {
            display: block;
            text-align: center;
            margin-top: 12px;
            color: #999;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .btn-cancel:hover { color: #666; }
        .error-msg {
            background: #fff5f5;
            color: #c53030;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 0.9rem;
            border: 1px solid #fed7d7;
        }
        .cards-accepted {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #eee;
            font-size: 0.75rem;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h2>Pago Seguro</h2>
            <div class="amount">
                L {{ number_format($paymentData['amount'] ?? 0, 2) }}
            </div>
            <div class="order-ref">Orden #{{ $paymentData['order_id'] ?? '' }}</div>
        </div>

        <div class="payment-body">
            @if(session('error'))
                <div class="error-msg">{{ session('error') }}</div>
            @endif

            <div class="security-badge">
                <span class="lock">&#128274;</span>
                Conexión segura SSL - Sus datos están protegidos
            </div>

            <form method="POST" action="{{ route('honduras-pay.process-form') }}" id="payment-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label>Nombre en la Tarjeta</label>
                    <input type="text" name="card_name" placeholder="Como aparece en la tarjeta"
                           required autocomplete="cc-name" value="{{ old('card_name') }}">
                </div>

                <div class="form-group">
                    <label>Número de Tarjeta</label>
                    <input type="text" name="card_number" placeholder="0000 0000 0000 0000"
                           required autocomplete="cc-number" maxlength="19"
                           inputmode="numeric" id="card-number">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha de Expiración</label>
                        <input type="text" name="card_expiry" placeholder="MM/AA"
                               required autocomplete="cc-exp" maxlength="5"
                               id="card-expiry">
                    </div>

                    <div class="form-group">
                        <label>CVV</label>
                        <input type="password" name="card_cvv" placeholder="***"
                               required autocomplete="cc-csc" maxlength="4"
                               inputmode="numeric">
                    </div>
                </div>

                <button type="submit" class="btn-pay" id="pay-btn">
                    Pagar L {{ number_format($paymentData['amount'] ?? 0, 2) }}
                </button>

                <a href="{{ route('honduras-pay.cancel') }}" class="btn-cancel">
                    Cancelar y volver a la tienda
                </a>
            </form>

            <div class="cards-accepted">
                VISA | MasterCard | American Express
            </div>
        </div>
    </div>

    <script>
        // Format card number with spaces
        document.getElementById('card-number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = value;
        });

        // Format expiry date
        document.getElementById('card-expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        // Prevent double submission
        document.getElementById('payment-form').addEventListener('submit', function() {
            var btn = document.getElementById('pay-btn');
            btn.disabled = true;
            btn.textContent = 'Procesando...';
        });
    </script>
</body>
</html>
