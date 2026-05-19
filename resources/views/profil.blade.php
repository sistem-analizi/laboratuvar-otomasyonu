@extends('layouts.app')

@section('content')

    <style>
        /* Zarif Kaydırma Çubuğu Stili */
        .ozel-scroll {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 5px;
        }
        .ozel-scroll::-webkit-scrollbar { width: 6px; }
        .ozel-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .ozel-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .ozel-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Arama Kutusu İkon Hizalaması */
        .arama-kapsayici {
            position: relative;
            display: flex;
            align-items: center;
        }
        .arama-kapsayici i {
            position: absolute; left: 15px; color: #94a3b8; pointer-events: none;
        }
        .arama-input {
            padding-left: 36px !important; border-radius: 20px !important;
            border: 1px solid #e2e8f0; background-color: #f8fafc; width: 100%;
        }
        .arama-input:focus {
            background-color: #fff; box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        /* Tarih Seçici Özel Stili */
        .tarih-input {
            border-radius: 20px !important; border: 1px solid #e2e8f0;
            background-color: #f8fafc; color: #64748b; padding-left: 15px; padding-right: 15px; cursor: pointer;
        }
        .tarih-input:focus {
            background-color: #fff; box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }
    </style>

    <div class="py-5" style="background-color: #f8fafc; min-height: 100vh;">
        <div class="container-xl" style="max-width: 1200px;">

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; border-top: 4px solid #3b82f6;">
                <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-4 mb-3 mb-md-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->ad . ' ' . $user->soyad) }}&background=0d6efd&color=fff&size=80" class="rounded-circle shadow-sm" alt="Avatar">
                        <div>
                            <h4 class="fw-bold mb-2">{{ $user->ad }} {{ $user->soyad }}</h4>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-id-card text-muted me-1"></i> {{ $user->okul_no ?? $user->kullanici_id }}</span>
                                @if($rol_id == 1)
                                    <span class="badge bg-info text-dark rounded-pill px-3">Öğrenci</span>
                                @elseif($rol_id == 2)
                                    <span class="badge bg-primary rounded-pill px-3">Laboratuvar Sorumlusu</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3">Personel</span>
                                @endif
                            </div>
                            <div class="text-muted" style="font-size: 0.9rem;">
                                <i class="fa-solid fa-envelope me-2 opacity-75"></i>{{ $user->eposta ?? $user->email ?? 'E-posta eklenmemiş' }}
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ url('profil/duzenle') }}" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-pen me-2"></i>Düzenle</a>
                    </div>
                </div>
            </div>

            @php
                $geciken_sayisi = 0;
                $kontrol_edilecekler = $aktif_cihazlar ?? [];
                foreach($kontrol_edilecekler as $cihaz) {
                    if(isset($cihaz->planlanan_iade_tarihi) && \Carbon\Carbon::parse($cihaz->planlanan_iade_tarihi)->isPast()) {
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

                @if($rol_id == 2)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #dc3545; border-radius: 12px;">
                            <div class="card-header bg-white border-0 p-4 pb-2 d-flex flex-wrap justify-content-between align-items-center gap-3">
                                <div>
                                    <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-laptop-code me-2"></i> Kalıcı Zimmetlerim</h6>
                                    <small class="text-muted" style="font-size: 0.8rem;">Üzerinize kayıtlı laboratuvar demirbaşları.</small>
                                </div>
                                <div class="d-flex flex-nowrap align-items-center gap-2">
                                    <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border shadow-sm">
                                        <input type="date" id="tarih_bas_demirbas" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" style="max-width: 110px;" title="Başlangıç Tarihi">
                                        <span class="text-muted fw-bold mx-1">-</span>
                                        <input type="date" id="tarih_bit_demirbas" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" style="max-width: 110px;" title="Bitiş Tarihi">
                                    </div>
                                    <div class="arama-kapsayici" style="min-width: 150px; max-width: 180px;">
                                        <i class="fa-solid fa-search"></i>
                                        <input type="text" class="form-control form-control-sm arama-input" id="arama_demirbas" placeholder="Ara...">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4 pt-0">
                                <div class="table-responsive ozel-scroll">
                                    <table class="table table-borderless align-middle mb-0">
                                        <thead class="sticky-top bg-white">
                                        <tr class="text-muted border-bottom" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <th>CİHAZ ADI</th>
                                            <th>ZİMMET MİKTARI</th>
                                            <th>DURUM</th>
                                            <th class="text-end">İŞLEM</th>
                                        </tr>
                                        </thead>
                                        <tbody id="liste_demirbas">
                                        @php
                                            $gruplanmis_cihazlar = collect($aktif_cihazlar)->groupBy('urun_adi');
                                            $sayac = 0;
                                        @endphp

                                        @forelse($gruplanmis_cihazlar as $urun_adi => $cihazlar)
                                            @php $sayac++; @endphp
                                            <tr class="border-bottom cihaz-satiri">
                                                <td class="py-3">
                                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $urun_adi }}</div>
                                                    <small class="text-muted" style="font-size: 0.8rem;">
                                                        <i class="fa-solid fa-hashtag opacity-50 text-primary me-1"></i>{{ $cihazlar->first()->urun_kodu ?? $cihazlar->first()->urun_id ?? '-' }}
                                                    </small>
                                                    <div class="d-none">@foreach($cihazlar as $c) {{ $c->seri_no }} @endforeach</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark rounded-pill px-3 py-2 shadow-sm" style="font-size: 0.85rem;">{{ $cihazlar->count() }} Adet</span>
                                                </td>
                                                <td><span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">Kullanımda</span></td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="collapse" data-bs-target="#detay-{{ $sayac }}">
                                                        Barkodlar <i class="fa-solid fa-chevron-down ms-1"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <tr id="detay-{{ $sayac }}" class="collapse bg-light">
                                                <td colspan="4" class="p-0 border-bottom">
                                                    <div style="max-height: 220px; overflow-y: auto;" class="p-3 m-2 border rounded bg-white shadow-sm ozel-scroll border-start border-4 border-primary">
                                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                                            <h6 class="fw-bold text-muted mb-0" style="font-size: 0.85rem;"><i class="fa-solid fa-barcode me-2"></i>Barkod Listesi</h6>
                                                        </div>
                                                        <div class="row g-2">
                                                            @foreach($cihazlar as $cihaz)
                                                                <div class="col-md-6 col-lg-4">
                                                                    <div class="d-flex justify-content-between align-items-center p-2 border rounded" style="background-color: #f8fafc;">
                                                                        <div class="text-dark fw-bold" style="font-size: 0.85rem;"><i class="fa-solid fa-barcode opacity-50 me-2 text-primary"></i>{{ $cihaz->seri_no }}</div>
                                                                        <small class="text-muted">{{ isset($cihaz->verilis_tarihi) ? \Carbon\Carbon::parse($cihaz->verilis_tarihi)->format('d.m.Y') : '-' }}</small>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-muted text-center py-4">Üzerinizde kayıtlı demirbaş bulunmamaktadır.</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #dc3545; border-radius: 12px;">
                            <div class="card-header bg-white border-0 p-4 pb-2 d-flex flex-wrap justify-content-between align-items-center gap-3">
                                <div>
                                    <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-laptop-code me-2"></i> Üzerindeki Cihazlar</h6>
                                    <small class="text-muted" style="font-size: 0.8rem;">Şu an sizde olan ödünç cihazlar.</small>
                                </div>
                                <div class="d-flex flex-nowrap align-items-center gap-2">
                                    <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border shadow-sm">
                                        <input type="date" id="tarih_bas_demirbas" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" style="max-width: 110px;" title="Başlangıç Tarihi">
                                        <span class="text-muted fw-bold mx-1">-</span>
                                        <input type="date" id="tarih_bit_demirbas" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" style="max-width: 110px;" title="Bitiş Tarihi">
                                    </div>
                                    <div class="arama-kapsayici" style="min-width: 150px; max-width: 180px;">
                                        <i class="fa-solid fa-search"></i>
                                        <input type="text" class="form-control form-control-sm arama-input" id="arama_demirbas" placeholder="Ara...">
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
                                        <tbody id="liste_demirbas">
                                        @forelse($aktif_cihazlar as $cihaz)
                                            <tr class="border-bottom cihaz-satiri">
                                                <td class="py-3">
                                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $cihaz->urun_adi }}</div>
                                                    <small class="text-muted" style="font-size: 0.8rem;"><i class="fa-solid fa-barcode opacity-50"></i> {{ $cihaz->seri_no }}</small>
                                                </td>
                                                <td class="text-muted" style="font-size: 0.9rem;">
                                                    {{ isset($cihaz->verilis_tarihi) ? \Carbon\Carbon::parse($cihaz->verilis_tarihi)->format('d.m.Y H:i') : '-' }}
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold" style="font-size: 0.9rem;">
                                                        {{ isset($cihaz->planlanan_iade_tarihi) && $cihaz->planlanan_iade_tarihi ? \Carbon\Carbon::parse($cihaz->planlanan_iade_tarihi)->format('d.m.Y H:i') : 'Süresiz (Kalıcı)' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php $geciktiMi = isset($cihaz->planlanan_iade_tarihi) && $cihaz->planlanan_iade_tarihi ? \Carbon\Carbon::parse($cihaz->planlanan_iade_tarihi)->isPast() : false; @endphp
                                                    @if($geciktiMi)
                                                        <span class="badge bg-danger text-white rounded-pill px-3 shadow-sm"><i class="fa-solid fa-triangle-exclamation me-1"></i>Gecikti</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">Kullanımda</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-muted text-center py-4">Üzerinizde cihaz bulunmamaktadır.</td></tr>
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
                                <div class="d-flex flex-nowrap align-items-center gap-2">
                                    <div class="d-flex align-items-center bg-light rounded-pill px-1 py-1 border shadow-sm">
                                        <input type="date" id="tarih_bas_gecmis" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" style="max-width: 105px;" title="Başlangıç Tarihi">
                                        <span class="text-muted fw-bold mx-1">-</span>
                                        <input type="date" id="tarih_bit_gecmis" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" style="max-width: 105px;" title="Bitiş Tarihi">
                                    </div>
                                    <div class="arama-kapsayici" style="min-width: 120px; max-width: 140px;">
                                        <i class="fa-solid fa-search"></i>
                                        <input type="text" class="form-control form-control-sm arama-input" id="gecmisArama" placeholder="Ara...">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4 pt-2">
                                <div class="list-group list-group-flush ozel-scroll">
                                    @forelse($iade_gecmisi as $iade)
                                        <div class="list-group-item px-0 py-3 border-light gecmis-satiri border-0 border-bottom">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">{{ $iade->urun_adi }}</h6>
                                                    <div class="text-muted" style="font-size: 0.8rem;">
                                                        <span class="d-block mb-1"><i class="fa-regular fa-calendar-plus me-1"></i>Veriliş: {{ isset($iade->verilis_tarihi) ? \Carbon\Carbon::parse($iade->verilis_tarihi)->format('d.m.Y H:i') : '-' }}</span>
                                                        <span class="d-block"><i class="fa-regular fa-calendar-check me-1"></i>Teslim: {{ \Carbon\Carbon::parse($iade->gerceklesen_iade_tarihi)->format('d.m.Y H:i') }}</span>
                                                    </div>
                                                </div>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2"><i class="fa-solid fa-check me-1"></i> İade Edildi</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-muted py-4">İade geçmişi bulunmamaktadır.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            @if($rol_id != 2)
                <div class="card border-0 shadow-sm mb-4" style="border-top: 4px solid #ffc107; border-radius: 12px;">
                    <div class="card-header bg-white border-0 p-4 pb-2 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h6 class="fw-bold text-warning mb-1" style="color: #d39e00 !important;"><i class="fa-solid fa-box-open me-2"></i> Sarf Malzemesi Kullanım Raporu</h6>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill shadow-sm">Toplam: {{ $sarf_gecmisi->count() }} İşlem</span>
                        </div>
                        <div class="d-flex flex-nowrap align-items-center gap-2">
                            <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border shadow-sm">
                                <input type="date" id="tarih_bas_sarf" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" style="max-width: 110px;" title="Başlangıç Tarihi">
                                <span class="text-muted fw-bold mx-1">-</span>
                                <input type="date" id="tarih_bit_sarf" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" style="max-width: 110px;" title="Bitiş Tarihi">
                            </div>
                            <div class="arama-kapsayici" style="min-width: 150px; max-width: 180px;">
                                <i class="fa-solid fa-search"></i>
                                <input type="text" class="form-control form-control-sm arama-input" id="sarfArama" placeholder="Ara...">
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
                                        <td><span class="badge bg-dark rounded-pill px-3 py-2">{{ $sarf->kullanilan_miktar ?? $sarf->miktar ?? '1' }} Adet</span></td>
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
            @endif

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function aralikliFiltrele(textInputId, dateBasId, dateBitId, satirSinifi) {
                const aramaKutusu = document.getElementById(textInputId);
                const basKutusu = document.getElementById(dateBasId);
                const bitKutusu = document.getElementById(dateBitId);

                function filtreyiUygula() {
                    let metinFiltresi = aramaKutusu ? aramaKutusu.value.toLocaleLowerCase('tr-TR') : '';

                    let basTarih = basKutusu && basKutusu.value ? new Date(basKutusu.value) : null;
                    if(basTarih) basTarih.setHours(0,0,0,0);

                    let bitTarih = bitKutusu && bitKutusu.value ? new Date(bitKutusu.value) : null;
                    if(bitTarih) bitTarih.setHours(23,59,59,999);

                    let satirlar = document.querySelectorAll(satirSinifi);
                    const tarihRegex = /(\d{2})\.(\d{2})\.(\d{4})/g;

                    satirlar.forEach(function(satir) {
                        let geciciKlon = satir.cloneNode(true);
                        geciciKlon.querySelectorAll('.badge, .btn').forEach(el => el.remove());

                        let temizIcerik = geciciKlon.innerText.toLocaleLowerCase('tr-TR');
                        let metinEslesti = metinFiltresi === '' || temizIcerik.includes(metinFiltresi);

                        let tarihEslesti = true;

                        if (basTarih || bitTarih) {
                            tarihEslesti = false;
                            let orijinalIcerik = satir.innerText;
                            let eslesmeler = [...orijinalIcerik.matchAll(tarihRegex)];

                            if (eslesmeler.length > 0) {
                                let sonTarih = eslesmeler[eslesmeler.length - 1];
                                let gun = parseInt(sonTarih[1], 10);
                                let ay = parseInt(sonTarih[2], 10) - 1;
                                let yil = parseInt(sonTarih[3], 10);
                                let satirTarihi = new Date(yil, ay, gun);

                                let basGec = !basTarih || satirTarihi >= basTarih;
                                let bitGec = !bitTarih || satirTarihi <= bitTarih;

                                if (basGec && bitGec) {
                                    tarihEslesti = true;
                                }
                            }
                        }

                        if (metinEslesti && tarihEslesti) {
                            satir.style.display = '';
                        } else {
                            satir.style.display = 'none';
                        }
                    });
                }

                if (aramaKutusu) aramaKutusu.addEventListener('keyup', filtreyiUygula);
                if (basKutusu) basKutusu.addEventListener('input', filtreyiUygula);
                if (bitKutusu) bitKutusu.addEventListener('input', filtreyiUygula);
            }

            aralikliFiltrele('arama_demirbas', 'tarih_bas_demirbas', 'tarih_bit_demirbas', '.cihaz-satiri');
            aralikliFiltrele('gecmisArama', 'tarih_bas_gecmis', 'tarih_bit_gecmis', '.gecmis-satiri');
            aralikliFiltrele('sarfArama', 'tarih_bas_sarf', 'tarih_bit_sarf', '.sarf-satiri');

        });
    </script>
@endsection
