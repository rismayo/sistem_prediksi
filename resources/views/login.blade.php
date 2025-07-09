<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UPT Puskesmas Jiwan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: url("{{ asset('image/bg.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .main-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            width: 80%;
            max-width: 1200px;
            flex-direction: column;
        }

        .title-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .title-container h3 {
            font-weight: bold;
            font-size: 30px;
        }

        .title-container p {
            font-size: 18px;
            margin-top: -10px;
        }

        .content-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 50px;
            width: 100%;
        }

        .logo-container img {
            width: 100%;
            max-width: 400px;
            height: auto;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .login-box h3 {
            font-weight: bold;
            color: black;
        }

        .login-box p {
            font-size: 18px;
            color: black;
            margin-bottom: 20px;
        }

        .form-label {
            text-align: left;
            display: block;
            font-weight: bold;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
        }

        .btn-login {
            background: linear-gradient(90deg, #00aaff, #0049ff);
            color: white;
            font-weight: bold;
            border-radius: 25px;
            padding: 12px;
            transition: 0.3s;
            width: 100%;
        }

        .btn-login:hover {
            transform: scale(1.05);
            background: linear-gradient(90deg, #0049ff, #00aaff);
        }

        @media (max-width: 768px) {
            .content-container {
                flex-direction: column;
                text-align: center;
            }

            .logo-container img,
            .login-box {
                max-width: 85%;
            }
        }
    </style>
</head>
<body>

    <div class="main-container">
        <div class="title-container">
            <h3>UPT PUSKESMAS JIWAN</h3>
            <p>Prediksi Persediaan Obat</p>
        </div>

        <div class="content-container">
            <div class="logo-container">
                <img src="{{ asset('image/logo.png') }}" alt="Logo Puskesmas">
            </div>

            <div class="login-box">
                <h3>Selamat Datang!</h3>

                @if ($errors->has('login_gagal'))
                    <div class="alert alert-danger text-start">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $errors->first('login_gagal') }}
                    </div>
                @endif

                <form action="{{ route('proses_login') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="mb-3 text-start">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username">
                    </div>

                    <div class="mb-3 text-start">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password">
                            <span class="input-group-text" onclick="togglePasswordVisibility()" style="cursor: pointer;">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login">LOG IN</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
<script>
function togglePasswordVisibility() {
    const passwordField = document.getElementById("password");
    const icon = document.getElementById("togglePasswordIcon");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</html>
