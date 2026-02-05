<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirigiendo al Banco...</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .redirect-container {
            text-align: center;
            padding: 40px;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(212, 175, 55, 0.3);
            border-top-color: #D4AF37;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 24px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        h2 { margin-bottom: 8px; color: #D4AF37; }
        p { color: rgba(255,255,255,0.7); font-size: 0.95rem; }
    </style>
</head>
<body>
    <div class="redirect-container">
        <div class="spinner"></div>
        <h2>Redirigiendo a la pasarela de pago...</h2>
        <p>Por favor espere, no cierre esta ventana.</p>

        <form id="redirect-form" method="POST" action="{{ $actionUrl }}">
            @foreach($formData as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>

    <script>
        document.getElementById('redirect-form').submit();
    </script>
</body>
</html>
