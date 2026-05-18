<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol | LabSistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .login-container { background-color: #fff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; max-width: 900px; width: 100%; }
        .login-image { background: linear-gradient(rgba(37, 99, 235, 0.8), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158'); background-size: cover; color: white; padding: 40px; display: flex; flex-direction: column; justify-content: center; }
        .form-control { background-color: #f8fafc; border: 1px solid #e2e8f0; border-left: none; }
        .input-group-text { background-color: #f8fafc; border: 1px solid #e2e8f0; border-right: none; color: #64748b; }
    </style>
</head>
<body>
<div class="container">
    <div class="row login-container mx-auto">
        <div class="col-md-5 login-image d-none d-md-flex">
            <h3 class="fw-bold mb-3"><i class="fa-solid fa-microchip me-2"></i>LabSistem</h3>
            <p>Laboratuvar sistemine katılmak için kayıt oluşturun.</p>
        </div>
        <div class="col-md-7 p-5">
            <h4 class="fw-bold text-dark mb-4">Yeni Kayıt Oluştur</h4>

            <form action="/kayit" method="POST">
                @csrf

                @if(session('hata'))
                    <div class="alert alert-danger small py-2">{{ session('hata') }}</div>
                @endif

                <div class="row mb-3">
                    <div class="col">
                        <label class="small text-secondary fw-semibold">Adınız</label>
                        <input type="text" name="ad" class="form-control px-3" required>
                    </div>
                    <div class="col">
                        <label class="small text-secondary fw-semibold">Soyadınız</label>
                        <input type="text" name="soyad" class="form-control px-3" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary fw-semibold">Okul Numaranız</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                        <input type="text" name="okul_no" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="small text-secondary fw-semibold">Şifre Belirleyin</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="sifre" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Kayıt Ol</button>
                <div class="text-center">
                    <a href="/giris" class="small text-decoration-none">Zaten hesabın var mı? Giriş Yap</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
