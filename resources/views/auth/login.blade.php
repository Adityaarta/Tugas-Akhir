<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | UD Sentosa</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fontawesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>

        *{
            font-family:'Poppins',sans-serif;
        }

        body{
            margin:0;
            padding:0;
            background:#edf2f7;
            overflow:hidden;
        }

        .login-page{

            width:100%;
            height:100vh;

            display:flex;
        }

        /* ======================
            PANEL KIRI
        ====================== */

        .left-side{

            width:50%;

            position:relative;

            background:
            linear-gradient(rgba(18,48,132,.90),rgba(18,48,132,.90)),
            url('{{ asset('assets/images/bg-login.jpg') }}');

            background-size:cover;

            background-position:center;

            color:white;

            display:flex;

            justify-content:center;

            align-items:center;

            padding:70px;

        }

        .left-content{

            text-align:center;

            max-width:650px;

        }

        .left-content i{

            font-size:90px;

            margin-bottom:25px;

        }

        .left-content h1{

            font-size:65px;

            font-weight:bold;

        }

        .left-content h3{

            font-size:30px;

            margin-top:20px;

            font-weight:400;

            line-height:45px;

        }

        .left-content p{

            margin-top:45px;

            font-size:22px;

            line-height:42px;

        }

        .feature-box{

            margin-top:80px;
        }

        .feature-icon{

            width:80px;

            height:80px;

            border-radius:20px;

            background:#2563eb;

            display:flex;

            justify-content:center;

            align-items:center;

            margin:auto;

            font-size:35px;
        }

        .feature-title{

            margin-top:18px;

            font-size:23px;

            font-weight:600;
        }

        .feature-desc{

            font-size:18px;
        }

        /* =======================
            PANEL KANAN
        ======================== */

        .right-side{

            width:50%;

            display:flex;

            justify-content:center;

            align-items:center;

            background:#f5f7fb;

        }

        .login-card{

            width:600px;

            background:white;

            border-radius:25px;

            padding:45px;

            box-shadow:0 10px 30px rgba(0,0,0,.08);

        }

    </style>

</head>

<body>

<div class="login-page">

    <!-- KIRI -->

    <div class="left-side">

        <div class="left-content">

            <i class="fa-solid fa-truck"></i>

            <h1>UD SENTOSA</h1>

            <h3>

                Sistem Pengelolaan<br>

                Kendaraan Operasional

            </h3>

            <p>

                Sistem informasi berbasis web untuk mengelola data kendaraan,
                servis, peminjaman serta prediksi pemeliharaan menggunakan
                algoritma Naïve Bayes dan Decision Tree.

            </p>

            <div class="row feature-box">

                <div class="col">

                    <div class="feature-icon">

                        <i class="fa-solid fa-car-side"></i>

                    </div>

                    <div class="feature-title">

                        Kelola Kendaraan

                    </div>

                    <div class="feature-desc">

                        Data kendaraan operasional

                    </div>

                </div>

                <div class="col">

                    <div class="feature-icon">

                        <i class="fa-solid fa-screwdriver-wrench"></i>

                    </div>

                    <div class="feature-title">

                        Kelola Servis

                    </div>

                    <div class="feature-desc">

                        Riwayat servis kendaraan

                    </div>

                </div>

                <div class="col">

                    <div class="feature-icon">

                        <i class="fa-solid fa-chart-column"></i>

                    </div>

                    <div class="feature-title">

                        Prediksi

                    </div>

                    <div class="feature-desc">

                        Naïve Bayes & Decision Tree

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- PANEL KANAN -->

    <div class="right-side">

        <div class="login-card">
        
                <div class="text-center mb-4">

                <div class="mb-3">
                    <i class="fa-solid fa-user-shield fa-3x text-primary"></i>
                </div>

                <h2 class="fw-bold">
                    Selamat Datang
                </h2>

                <p class="text-muted">
                    Silakan login untuk melanjutkan
                </p>

            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">

                @csrf

                {{-- Email --}}

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Email

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="fa-solid fa-envelope"></i>

                        </span>

                        <input

                            type="email"

                            name="email"

                            value="{{ old('email') }}"

                            class="form-control @error('email') is-invalid @enderror"

                            placeholder="Masukkan email"

                            required

                            autofocus>

                    </div>

                    @error('email')

                        <small class="text-danger">

                            {{ $message }}

                        </small>

                    @enderror

                </div>

                {{-- Password --}}

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Password

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="fa-solid fa-lock"></i>

                        </span>

                        <input

                            id="password"

                            type="password"

                            name="password"

                            class="form-control @error('password') is-invalid @enderror"

                            placeholder="Masukkan password"

                            required>

                        <button

                            type="button"

                            class="btn btn-outline-secondary"

                            id="togglePassword">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                    @error('password')

                        <small class="text-danger">

                            {{ $message }}

                        </small>

                    @enderror

                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div class="form-check">

                        <input

                            class="form-check-input"

                            type="checkbox"

                            name="remember"

                            id="remember">

                        <label class="form-check-label" for="remember">

                            Remember Me

                        </label>

                    </div>

                    @if (Route::has('password.request'))

                        <a href="{{ route('password.request') }}" class="text-decoration-none">

                            Lupa Password?

                        </a>

                    @endif

                </div>

                <button

                    type="submit"

                    class="btn btn-primary w-100 py-3 fw-semibold">

                    <i class="fa-solid fa-right-to-bracket me-2"></i>

                    Masuk

                </button>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

const togglePassword = document.querySelector('#togglePassword');

const password = document.querySelector('#password');

togglePassword.addEventListener('click', function () {

    const type = password.getAttribute('type') === 'password'
        ? 'text'
        : 'password';

    password.setAttribute('type', type);

    this.innerHTML = type === 'password'
        ? '<i class="fa-solid fa-eye"></i>'
        : '<i class="fa-solid fa-eye-slash"></i>';

});

</script>

</body>

</html>
