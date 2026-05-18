@extends('layouts.app')

@section('content')

    <style>
        /* Zarif Kaydırma Çubuğu Stili */
        .ozel-scroll {
            max-height: 350px; /* Listenin maksimum boyu */
            overflow-y: auto;
            padding-right: 5px;
        }
        .ozel-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .ozel-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        .ozel-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .ozel-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Arama Kutusu İkon Hizalaması */
        .arama-kapsayici {
            position: relative;
        }
        .arama-kapsayici i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .arama-input {
            padding-left: 40px !important;
            border-radius: 20px !important; /* Daha oval yaptık */
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .arama-input:focus {
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        /* Tarih Seçici Özel Stili */
        .tarih-input {
            border-radius: 20px !important;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            color: #64748b;
            padding-left: 15px;
            padding-right: 15px;
            cursor: pointer;
        }
        .tarih-input:focus {
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }
    </style>

    <div class="py-5" style="background-color: #f8fafc; min-height: 100vh;">
        <div class="container-xl" style="max-width: 1200px;">

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; border-top: 4px solid #3b82f6;">
                <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-4 mb-3 mb-md-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($kullanici->ad . ' ' . $kullanici->soyad) }}&background=0d6efd&color=fff&size=80" class="rounded-circle shadow-sm" alt="Avatar">

                        <div>
                            <h4 class="fw-bold mb-2 text-dark">{{ $kullanici->ad }} {{ $kullanici->soyad }}</h4>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-id-badge text-muted me-1"></i> {{ $kullanici->okul_no ?? $kullanici->kullanici_id }}</span>
                                <span class="badge {{ $kullanici->rol_adi == 'Ogrenci' ? 'bg-info text-dark' : 'bg-dark' }} px-3 rounded-pill shadow-sm">
                                    {{ $kullanici->rol_adi ?? 'Öğrenci' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $geciken_sayisi = 0;
                // Editörün hata vermemesi için güvenli atama yapıyoruz
                $kontrol_edilecekler = $aktif_oduncler ?? [];

                foreach($kontrol_edilecekler as $odunc) {
                    if(isset($odunc->planlanan_iade_tarihi) && \Carbon\Carbon::parse($odunc->planlanan_iade_tarihi)->isPast()) {
                        $geciken_sayisi++;
                    }
                }
            @endphp

            @if($geciken_sayisi > 0)
                <div class="alert alert-danger shadow-sm mb-4 d-flex align-items-center" role="alert" style="border-radius: 12px; border-left: 5px solid #b02a37;">
                    <i class="fa-solid fa-triangle-exclamation fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading fw-bold mb-1">DİKKAT: İade Tarihi Geçmiş Cihazlar Var!</h5>
                        <p class="mb-0">Üzerinizde teslim tarihi geçmiş <strong>{{ $geciken_sayisi }} adet</strong> cihaz bulunmaktadır. Lütfen en kısa sürede laboratuvara teslim ediniz.</p>
                    </div>
                </div>
            @endif

            <div class="row g-4 mb-4">

                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #dc3545; border-radius: 12px;">
                        <div class="card-header bg-white border-0 p-4 pb-2 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-laptop-code me-2"></i> Üzerindeki Cihazlar</h6>
                                <small class="text-muted" style="font-size: 0.8rem;">Şu an sizde olan ödünç cihazlar.</small>
                            </div>
                            <div class="d-flex flex-wrap gap-2 flex-grow-1 justify-content-end">
                                <input type="date" id="tarih_demirbas" class="form-control form-control-sm shadow-sm tarih-input" style="max-width: 135px;" title="Tarihe Göre Filtrele">
                                <div class="arama-kapsayici flex-grow-1" style="max-width: 200px;">
                                    <i class="fa-solid fa-search"></i>
                                    <input type="text" class="form-control form-control-sm arama-input" id="arama_demirbas" placeholder="Kelime ara...">
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="table-responsive ozel-scroll">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="sticky-top bg-white">
                                    <tr class="text-muted border-bottom" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <th>CİHAZ / SERİ NO</th>
                                        <th>VERİLİŞ TARİHİ</th>
                                        <th>SON İADE</th>
                                        <th>DURUM</th>
                                    </tr>
                                    </thead>
                                    <tbody id="aktifCihazlarListesi">
                                    @forelse($aktif_oduncler as $odunc)
                                        <tr class="cihaz-satiri border-bottom">
                                            <td class="py-3">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $odunc->urun_adi }}</h6>
                                                <small class="text-muted"><i class="fa-solid fa-barcode opacity-50"></i> {{ $odunc->seri_no }}</small>
                                            </td>
                                            <td class="text-muted" style="font-size: 0.9rem;">
                                                {{ isset($odunc->verilis_tarihi) ? \Carbon\Carbon::parse($odunc->verilis_tarihi)->format('d.m.Y H:i') : '-' }}
                                            </td>
                                            <td>
                                                    <span class="text-dark fw-bold" style="font-size: 0.9rem;">
                                                        {{ isset($odunc->planlanan_iade_tarihi) && $odunc->planlanan_iade_tarihi ? \Carbon\Carbon::parse($odunc->planlanan_iade_tarihi)->format('d.m.Y H:i') : 'Süresiz (Kalıcı)' }}
                                                    </span>
                                            </td>
                                            <td>
                                                @php
                                                    $geciktiMi = isset($odunc->planlanan_iade_tarihi) && $odunc->planlanan_iade_tarihi ? \Carbon\Carbon::parse($odunc->planlanan_iade_tarihi)->isPast() : false;
                                                @endphp

                                                @if($geciktiMi)
                                                    <span class="badge bg-danger text-white rounded-pill px-3 shadow-sm"><i class="fa-solid fa-triangle-exclamation me-1"></i>Gecikti</span>
                                                @else
                                                    <span class="badge bg-warning text-dark rounded-pill px-3 shadow-sm">Kullanımda</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="/iade-al/{{ $odunc->odunc_id }}" class="btn btn-sm btn-success rounded-pill shadow-sm px-3 fw-bold">
                                                    <i class="fa-solid fa-rotate-left me-1"></i> İade Al
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-muted text-center py-4">Üzerinde cihaz bulunmamaktadır.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #198754; border-radius: 12px;">
                        <div class="card-header bg-white border-0 p-4 pb-2 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h6 class="fw-bold text-success mb-1"><i class="fa-solid fa-clock-rotate-left me-2"></i> İade Geçmişi</h6>
                                <small class="text-muted" style="font-size: 0.8rem;">Teslim ettiğiniz cihazlar.</small>
                            </div>
                            <div class="d-flex flex-wrap gap-2 flex-grow-1 justify-content-end">
                                <input type="date" id="tarih_gecmis" class="form-control form-control-sm shadow-sm tarih-input" style="max-width: 135px;" title="Tarihe Göre Filtrele">
                                <div class="arama-kapsayici flex-grow-1" style="max-width: 160px;">
                                    <i class="fa-solid fa-search"></i>
                                    <input type="text" class="form-control form-control-sm arama-input" id="gecmisArama" placeholder="Kelime ara...">
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="list-group list-group-flush ozel-scroll">
                                @forelse($gecmis_oduncler as $gecmis)
                                    <li class="list-group-item px-0 py-3 border-light gecmis-satiri">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">{{ $gecmis->urun_adi }}</h6>
                                                <div class="text-muted" style="font-size: 0.8rem;">
                                                    <span class="d-block mb-1"><i class="fa-regular fa-calendar-plus me-1"></i>Veriliş: {{ isset($gecmis->verilis_tarihi) ? \Carbon\Carbon::parse($gecmis->verilis_tarihi)->format('d.m.Y H:i') : '-' }}</span>
                                                    <span class="d-block"><i class="fa-regular fa-calendar-check me-1"></i>Teslim: {{ \Carbon\Carbon::parse($gecmis->gerceklesen_iade_tarihi)->format('d.m.Y H:i') }}</span>
                                                </div>
                                            </div>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                                <i class="fa-solid fa-check me-1"></i> İade Edildi
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <div class="text-center text-muted py-4">İade geçmişi bulunmamaktadır.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-top: 4px solid #ffc107; border-radius: 12px;">
                <div class="card-header bg-white border-0 p-4 pb-2 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <h6 class="fw-bold text-warning mb-1" style="color: #d39e00 !important;"><i class="fa-solid fa-box-open me-2"></i> Sarf Malzemesi Kullanım Raporu</h6>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill shadow-sm">Toplam: {{ $sarf_gecmisi->count() }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2 flex-grow-1 justify-content-end">
                        <input type="date" id="tarih_sarf" class="form-control form-control-sm shadow-sm tarih-input" style="max-width: 135px;" title="Tarihe Göre Filtrele">
                        <div class="arama-kapsayici flex-grow-1" style="max-width: 250px;">
                            <i class="fa-solid fa-search"></i>
                            <input type="text" class="form-control form-control-sm arama-input" id="sarfArama" placeholder="Malzeme veya açıklama ara...">
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive ozel-scroll">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="sticky-top bg-white">
                            <tr class="text-muted border-bottom" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <th>TÜKETİLEN MALZEME</th>
                                <th>MİKTAR</th>
                                <th>İŞLEM TARİHİ</th>
                                <th>AÇIKLAMA / PROJE</th>
                            </tr>
                            </thead>
                            <tbody id="liste_sarf">
                            @forelse($sarf_gecmisi as $sarf)
                                <tr class="border-bottom sarf-satiri">
                                    <td class="py-3 fw-bold text-dark" style="font-size: 0.9rem;">{{ $sarf->urun_adi }}</td>
                                    <td><span class="badge bg-dark rounded-pill px-3 py-2">{{ $sarf->kullanilan_miktar ?? '1' }} Adet</span></td>
                                    <td class="text-muted" style="font-size: 0.9rem;"><i class="fa-regular fa-calendar opacity-50 me-1"></i> {{ \Carbon\Carbon::parse($sarf->islem_tarihi)->format('d.m.Y H:i') }}</td>
                                    <td class="text-muted" style="font-size: 0.9rem;">{{ $sarf->aciklama ?? 'Laboratuvar çalışması için verildi.' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center py-4">Tüketim kaydı bulunmamaktadır.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function birlesikFiltrele(textInputId, dateInputId, satirSinifi) {
                const aramaKutusu = document.getElementById(textInputId);
                const tarihKutusu = document.getElementById(dateInputId);

                function filtreyiUygula() {
                    let metinFiltresi = aramaKutusu ? aramaKutusu.value.toLocaleLowerCase('tr-TR') : '';

                    // Takvimden seçilen 'YYYY-MM-DD' formatını tablodaki 'DD.MM.YYYY' yapısına çeviriyoruz
                    let tarihFiltresi = '';
                    if (tarihKutusu && tarihKutusu.value) {
                        let parcalar = tarihKutusu.value.split('-'); // [Yıl, Ay, Gün]
                        if(parcalar.length === 3) {
                            tarihFiltresi = parcalar[2] + '.' + parcalar[1] + '.' + parcalar[0];
                        }
                    }

                    let satirlar = document.querySelectorAll(satirSinifi);

                    satirlar.forEach(function(satir) {
                        // ROZET VE BUTONLARI ARAMADAN GİZLEME HİLESİ (Aynen korunuyor)
                        let geciciKlon = satir.cloneNode(true);
                        geciciKlon.querySelectorAll('.badge, .btn').forEach(el => el.remove());

                        let temizIcerik = geciciKlon.innerText.toLocaleLowerCase('tr-TR');
                        let orijinalIcerik = satir.innerText.toLocaleLowerCase('tr-TR');

                        let metinEslesti = metinFiltresi === '' || temizIcerik.includes(metinFiltresi);
                        // Sadece tarihi (DD.MM.YYYY) arar, saati umursamaz
                        let tarihEslesti = tarihFiltresi === '' || orijinalIcerik.includes(tarihFiltresi);

                        if (metinEslesti && tarihEslesti) {
                            satir.style.display = '';
                        } else {
                            satir.style.display = 'none';
                        }
                    });
                }

                if (aramaKutusu) aramaKutusu.addEventListener('keyup', filtreyiUygula);
                if (tarihKutusu) tarihKutusu.addEventListener('input', filtreyiUygula);
            }

            birlesikFiltrele('arama_demirbas', 'tarih_demirbas', '.cihaz-satiri');
            birlesikFiltrele('gecmisArama', 'tarih_gecmis', '.gecmis-satiri');
            birlesikFiltrele('sarfArama', 'tarih_sarf', '.sarf-satiri');

        });
    </script>
@endsection
