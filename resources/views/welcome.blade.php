<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediksi Persediaan Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e3f2fd, #ffffff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 60px;
        }
        .login-link {
            position: absolute;
            top: 20px;
            right: 20px;
            font-weight: bold;
            text-decoration: underline;
            color: #0d6efd;
        }
        .login-link:hover {
            color: #084298;
        }
        .hero {
            max-width: 800px;
        }
    </style>
</head>
<body>

    <!-- Link Login -->
    <a href="{{ url('/login') }}" class="login-link">Login</a>

    <!-- Konten Utama -->
    <div class="text-center hero px-3">
        <h1 class="fw-bold text-primary mb-4">Sistem Prediksi Persediaan Obat</h1>
        <p class="lead text-secondary">
            Sistem ini dirancang untuk membantu UPT Puskesmas Jiwan dalam memprediksi kebutuhan persediaan obat secara efisien dan akurat menggunakan metode <strong>Triple Exponential Smoothing</strong>. 
            Dengan sistem ini, pihak puskesmas dapat mengurangi risiko kekurangan maupun kelebihan stok obat, serta meningkatkan pengelolaan logistik obat secara menyeluruh.
        </p>
        <hr class="my-4">
    </div>

</body>
</html>
