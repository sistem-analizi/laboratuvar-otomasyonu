<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sisteme Giriş | LabSistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #e2e8f0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        /* Devasa ve Şık Konteyner */
        .login-wrapper {
            max-width: 1000px;
            width: 100%;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
            overflow: hidden;
            display: flex;
            min-height: 600px;
        }

        /* Sol Taraf: Görsel ve Cam Efekti */
        .image-section {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80') center/cover;
            position: relative;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .image-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.1), rgba(15, 23, 42, 0.95));
            z-index: 1;
        }

        .image-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        /* Buzlu Cam (Glassmorphism) Kartı */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 20px;
            margin-top: 25px;
        }

        /* Sağ Taraf: Form Alanı */
        .form-section {
            flex: 1;
            padding: 60px 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Modern Yüzen Etiketler (Floating Inputs) */
        .form-floating > .form-control {
            border: none;
            border-bottom: 2px solid #cbd5e1;
            border-radius: 0;
            padding-left: 0;
            background-color: transparent;
            box-shadow: none;
            font-size: 1.05rem;
            font-weight: 500;
        }

        .form-floating > .form-control:focus {
            border-bottom-color: #2563eb;
        }

        .form-floating > label {
            padding-left: 0;
            color: #64748b;
            font-weight: 500;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.2rem;
            transition: color 0.3s;
        }

        .form-floating:focus-within .input-icon {
            color: #2563eb;
        }

        /* Premium Buton Tasarımı */
        .btn-login {
            background: #2563eb;
            color: white;
            padding: 15px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            border: none;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-login:hover {
            background: #1e40af;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.25);
            color: white;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="login-wrapper">

        <div class="image-section d-none d-lg-flex">
            <div class="image-content">
                <h2 class="fw-bold mb-2 display-6"><i class="fa-solid fa-microchip me-3 text-primary"></i>LabSistem</h2>
                <p class="fs-5 opacity-75 fw-light">Akıllı Laboratuvar ve Envanter Yönetim Otomasyonu</p>

                <div class="glass-card">
                    <div class="d-flex align-items-center">
                        <div class="bg-success rounded-circle p-2 me-3 d-flex align-items-center justify-content-center shadow" style="width: 45px; height: 45px;">
                            <i class="fa-solid fa-shield-halved text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold letter-spacing-1">Güvenli Bağlantı</h6>
                            <small class="opacity-75">Tüm verileriniz uçtan uca şifrelenmektedir.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section bg-white">
            <div class="mb-5">
                <h3 class="fw-bolder text-dark mb-2">Hoş Geldiniz 👋</h3>
                <p class="text-muted">Sisteme erişmek için bilgilerinizi girin.</p>
            </div>

            <form action="/2026/laboratuvar-otomasyonu/giris" method="POST">
                @csrf

                @if(session('hata'))
                    <div class="alert alert-danger rounded-3 small border-0 bg-danger bg-opacity-10 text-danger fw-semibold"><i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('hata') }}</div>
                @endif

                @if(session('basari'))
                    <div class="alert alert-success rounded-3 small border-0 bg-success bg-opacity-10 text-success fw-semibold"><i class="fa-solid fa-check-circle me-2"></i>{{ session('basari') }}</div>
                @endif

                <div class="form-floating mb-4 position-relative">
                    <input type="text" name="okul_no" class="form-control" id="okul_no" placeholder="Okul Numarası" required autofocus>
                    <label for="okul_no">Okul Numarası veya Sicil No</label>
                    <i class="fa-solid fa-id-card input-icon"></i>
                </div>

                <div class="form-floating mb-4 position-relative">
                    <input type="password" name="sifre" class="form-control" id="sifre" placeholder="Şifre" required>
                    <label for="sifre">Şifreniz</label>
                    <i class="fa-solid fa-lock input-icon"></i>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="hatirla">
                        <label class="form-check-label small text-muted" for="hatirla">Beni Hatırla</label>
                    </div>
                    <a href="#" class="text-decoration-none small fw-semibold" style="color: #2563eb;">Şifremi Unuttum</a>
                </div>

                <button type="submit" class="btn btn-login w-100">Sisteme Giriş Yap</button>

                <p class="text-center mt-4 text-muted small">
                    Henüz hesabınız yok mu? <a href="/2026/laboratuvar-otomasyonu/kayit" class="text-decoration-none fw-bold ms-1" style="color: #2563eb;">Hemen Kayıt Ol</a>
                </p>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
