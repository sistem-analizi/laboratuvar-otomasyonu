@extends('layouts.app')

@section('content')
    <style>
        .kullanici-sayfasi { background-color: #f4f7f6; min-height: 100vh; padding-bottom: 50px; }

        /* Ana Kart Tasarımı (Stok Girişi ile Uyumlu) */
        .premium-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-top: 5px solid #3b82f6; /* Üstteki şık mavi çizgi */
            background: #fff;
        }

        /* Arama Çubuğu */
        .search-container {
            background-color: #f8fafc;
            border-radius: 50px;
            padding: 5px 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .search-container:focus-within { border-color: #3b82f6; background-color: #fff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .search-input { border: none !important; box-shadow: none !important; background: transparent; padding: 10px 5px; width: 100%; outline: none; }

        /* Pills Sekmeler */
        .nav-pills .nav-link { color: #64748b; font-weight: 600; border-radius: 50px; padding: 10px 24px; transition: all 0.3s; border: 1px solid transparent; }
        .nav-pills .nav-link:hover { background-color: #f8fafc; border-color: #e2e8f0; }
        .nav-pills .nav-link.active { background-color: #3b82f6; color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }

        /* Bağımsız Satır (Kutucuk) Tasarımı */
        .custom-table { border-collapse: separate; border-spacing: 0 12px; }
        .custom-table thead th { border: none; font-weight: 600; color: #94a3b8; font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 0; }
        .custom-table tbody tr.arama-satiri { background-color: #f8fafc; transition: all 0.3s ease; }
        .custom-table tbody tr.arama-satiri:hover { background-color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transform: translateY(-2px); }
        .custom-table tbody td { border: none; padding: 16px 20px; vertical-align: middle; }

        /* Satırların Köşelerini Yuvarlatma */
        .custom-table tbody td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; border-left: 3px solid transparent; transition: all 0.3s; }
        .custom-table tbody td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

        /* Satır Hover Durumunda Sol Çizgi Efekti */
        .custom-table tbody tr.arama-satiri:hover td:first-child { border-left: 3px solid #3b82f6; }

        .avatar-circle { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; font-size: 1.1rem; }
        .btn-detay { background-color: #eff6ff; color: #3b82f6; border: none; font-weight: 600; transition: all 0.2s; }
        .btn-detay:hover { background-color: #3b82f6; color: #fff; }
    </style>

    <div class="kullanici-sayfasi py-5">
        <div class="container-xl"> <div class="text-center mb-5">
                <h2 class="fw-bold text-dark"><i class="fa-solid fa-users text-primary me-2"></i>Kullanıcı Yönetimi</h2>
                <p class="text-muted">Laboratuvar sistemine kayıtlı tüm öğrenci ve personeller.</p>
            </div>

            <div class="card premium-card mx-auto" style="max-width: 1000px;">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                        <ul class="nav nav-pills" id="kullaniciTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="ogrenci-tab" data-bs-toggle="pill" data-bs-target="#ogrenci" type="button" role="tab">
                                    <i class="fa-solid fa-user-graduate me-2"></i>Öğrenciler
                                </button>
                            </li>
                            <li class="nav-item ms-2" role="presentation">
                                <button class="nav-link" id="personel-tab" data-bs-toggle="pill" data-bs-target="#personel" type="button" role="tab">
                                    <i class="fa-solid fa-user-tie me-2"></i>Personel
                                </button>
                            </li>
                        </ul>

                        <div class="search-container d-flex align-items-center w-100" style="max-width: 350px;">
                            <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            <input type="text" id="canliArama" class="search-input ms-2" placeholder="İsim veya numara ara...">
                        </div>
                    </div>

                    <div class="tab-content mt-4" id="kullaniciTabsContent">

                        <div class="tab-pane fade show active" id="ogrenci" role="tabpanel">
                            <div class="table-responsive" style="overflow-x: hidden;">
                                <table class="table custom-table w-100">
                                    <thead>
                                    <tr>
                                        <th class="ps-3">Kullanıcı Bilgisi</th>
                                        <th>Öğrenci No</th>
                                        <th class="text-end pe-3">İşlem</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ogrenciler as $kisi)
                                        <tr class="arama-satiri">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white shadow-sm me-3">
                                                        {{ mb_strtoupper(mb_substr($kisi->ad, 0, 1, 'UTF-8') . mb_substr($kisi->soyad, 0, 1, 'UTF-8'), 'UTF-8') }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark">{{ $kisi->ad }} {{ $kisi->soyad }}</h6>
                                                        <small class="text-muted"><i class="fa-solid fa-graduation-cap me-1"></i>Öğrenci</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-medium text-secondary" style="font-size: 1.05rem;">{{ $kisi->okul_no }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('ogrenci.detay', $kisi->kullanici_id) }}" class="btn btn-sm btn-detay rounded-pill px-4 shadow-sm">
                                                    Detay Gör <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.8rem;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="personel" role="tabpanel">
                            <div class="table-responsive" style="overflow-x: hidden;">
                                <table class="table custom-table w-100">
                                    <thead>
                                    <tr>
                                        <th class="ps-3">Kullanıcı Bilgisi</th>
                                        <th>Sicil No</th>
                                        <th class="text-end pe-3">İşlem</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($personeller as $kisi)
                                        <tr class="arama-satiri">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-dark text-white shadow-sm me-3">
                                                        {{ mb_strtoupper(mb_substr($kisi->ad, 0, 1, 'UTF-8') . mb_substr($kisi->soyad, 0, 1, 'UTF-8'), 'UTF-8') }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark">{{ $kisi->ad }} {{ $kisi->soyad }}</h6>
                                                        <small class="text-muted"><i class="fa-solid fa-briefcase me-1"></i>Personel / Akademisyen</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-medium text-secondary" style="font-size: 1.05rem;">{{ $kisi->okul_no }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('ogrenci.detay', $kisi->kullanici_id) }}" class="btn btn-sm btn-detay rounded-pill px-4 shadow-sm">
                                                    Detay Gör <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.8rem;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const aramaKutusu = document.getElementById('canliArama');
            const satirlar = document.querySelectorAll('.arama-satiri');

            aramaKutusu.addEventListener('keyup', function() {
                let filtre = this.value.toLocaleLowerCase('tr-TR');

                satirlar.forEach(function(satir) {
                    let satirMetni = satir.innerText.toLocaleLowerCase('tr-TR');
                    if (satirMetni.includes(filtre)) {
                        satir.style.display = '';
                    } else {
                        satir.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
