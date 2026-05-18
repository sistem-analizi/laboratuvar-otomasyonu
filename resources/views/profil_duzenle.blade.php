@extends('layouts.app')

@section('content')
    <div class="py-5" style="background-color: #f8fafc; min-height: 100vh;">
        <div class="container-xl" style="max-width: 800px;">

            <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header border-0 p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="mb-0 text-white fw-bold">Profil Bilgilerini Düzenle</h4>
                            <p class="mb-0 text-info small">Lütfen güncellemek istediğiniz bilgileri girin.</p>
                        </div>
                        <a href="/profil" class="btn btn-outline-light btn-sm rounded-pill ms-auto">
                            <i class="fa-solid fa-arrow-left me-1"></i> Geri Dön
                        </a>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="/profil/guncelle" method="POST">
                        @csrf

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">AD</label>
                                <input type="text" name="ad" class="form-control shadow-sm" value="{{ $user->ad ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">SOYAD</label>
                                <input type="text" name="soyad" class="form-control shadow-sm" value="{{ $user->soyad ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">E-POSTA ADRESİ</label>
                                <input type="email" name="email" class="form-control shadow-sm" value="{{ $user->email ?? '' }}">
                            </div>
                        </div>

                        <div class="p-4 mb-4 rounded-4 bg-light border border-light">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-key me-2 text-warning"></i> Şifre Değiştirme (Opsiyonel)</h6>
                            <p class="text-muted small mb-4">Şifrenizi değiştirmek istemiyorsanız bu alanları boş bırakabilirsiniz.</p>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">YENİ ŞİFRE</label>
                                    <input type="password" name="sifre" class="form-control shadow-sm" placeholder="En az 6 karakter">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-bold py-3 shadow-sm rounded-pill" style="background-color: #3b82f6; color: #fff; font-size: 1.1rem; border: none;">
                            <i class="fa-solid fa-check me-2"></i> Bilgilerimi Kaydet
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
