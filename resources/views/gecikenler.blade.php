@extends('layouts.app')

@section('content')
    <style>
        .premium-card { border: none; border-radius: 16px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); background: #fff; border-top: 4px solid #dc3545; }
        .tarih-input { border-radius: 20px; border: 1px solid #e2e8f0; background-color: #f8fafc; cursor: pointer; }
        .arama-input { padding-left: 40px !important; border-radius: 20px; border: 1px solid #e2e8f0; background-color: #f8fafc; }
        .arama-kapsayici { position: relative; }
        .arama-kapsayici i { position: absolute; top: 50%; left: 15px; transform: translateY(-50%); color: #94a3b8; }
    </style>

    <div class="py-4" style="background-color: #f4f7f6; min-height: 100vh;">
        <div class="container-xl mx-auto" style="max-width: 1200px;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-clock-rotate-left text-danger me-2"></i>Geciken Teslimatlar</h4>
                <div class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">Toplam: {{ $gecikenler->count() }} Gecikme</div>
            </div>

            <div class="card premium-card">
                <div class="card-header bg-white border-0 p-4 pb-2 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="text-muted small"><i class="fa-solid fa-circle-info me-1"></i> Listede sadece iade tarihi geçmiş aktif ödünçler yer alır.</div>

                    <div class="d-flex flex-wrap gap-2 flex-grow-1 justify-content-end">
                        <div class="d-flex align-items-center gap-1 bg-light rounded-pill px-3 py-1 border shadow-sm">
                            <small class="text-muted fw-bold me-1">Aralık:</small>
                            <input type="date" id="baslangicFiltre" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" title="Başlangıç Tarihi">
                            <span class="text-muted fw-bold">-</span>
                            <input type="date" id="bitisFiltre" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" title="Bitiş Tarihi">
                        </div>

                        <div class="arama-kapsayici" style="max-width: 300px;">
                            <i class="fa-solid fa-search"></i>
                            <input type="text" id="genelArama" class="form-control form-control-sm arama-input" placeholder="Öğrenci, Cihaz veya No ara...">
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table align-middle mb-0">
                            <thead class="sticky-top bg-white text-muted" style="font-size: 0.8rem; z-index: 5;">
                            <tr class="border-bottom">
                                <th>ÖĞRENCİ BİLGİLERİ</th>
                                <th>CİHAZ / SERİ NO</th>
                                <th>VERİLİŞ</th>
                                <th>PLANLANAN İADE</th>
                                <th class="text-center">GECİKME</th>
                                <th class="text-end">İŞLEM</th>
                            </tr>
                            </thead>
                            <tbody id="gecikenTablo">
                            @forelse($gecikenler as $odunc)
                                <tr class="geciken-satiri border-bottom">
                                    <td class="py-3">
                                        <div class="fw-bold text-dark">{{ $odunc->ad }} {{ $odunc->soyad }}</div>
                                        <small class="text-muted">{{ $odunc->okul_no }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-danger">{{ $odunc->urun_adi }}</div>
                                        <small class="text-muted"><i class="fa-solid fa-barcode opacity-50"></i> {{ $odunc->seri_no }}</small>
                                    </td>
                                    <td class="text-muted" style="font-size: 0.85rem;">
                                        {{ \Carbon\Carbon::parse($odunc->verilis_tarihi)->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="fw-bold text-dark" style="font-size: 0.85rem;">
                                        {{ \Carbon\Carbon::parse($odunc->planlanan_iade_tarihi)->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="text-center">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">
                                        {{ \Carbon\Carbon::parse($odunc->planlanan_iade_tarihi)->diffForHumans(null, true) }}
                                    </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ url('ogrenci-detay/' . $odunc->kullanici_id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 me-1">Profil</a>
                                        <a href="{{ url('iade-al/' . $odunc->odunc_id) }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">İade Al</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fa-solid fa-circle-check text-success fa-3x mb-3 opacity-25"></i>
                                        <h6 class="text-muted">Şu an teslimi geciken cihaz bulunmuyor.</h6>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
                <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-hourglass-half text-warning me-2"></i>Yaklaşan Teslimatlar</h4>
                <div class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">Önümüzdeki 30 Gün: {{ $yaklasanlar->count() }} Cihaz</div>
            </div>

            <div class="card premium-card mb-5" style="border-top: 4px solid #ffc107;">
                <div class="card-header bg-white border-0 p-4 pb-2 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="text-muted small"><i class="fa-solid fa-circle-info me-1"></i> Listede teslimat süresi henüz dolmamış, yaklaşan cihazlar yer alır.</div>

                    <div class="d-flex flex-wrap gap-2 flex-grow-1 justify-content-end">
                        <div class="d-flex align-items-center gap-1 bg-light rounded-pill px-3 py-1 border shadow-sm">
                            <small class="text-muted fw-bold me-1">Aralık:</small>
                            <input type="date" id="baslangicYaklasan" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" title="Başlangıç Tarihi">
                            <span class="text-muted fw-bold">-</span>
                            <input type="date" id="bitisYaklasan" class="form-control form-control-sm border-0 bg-transparent text-muted px-1" title="Bitiş Tarihi">
                        </div>

                        <div class="arama-kapsayici" style="max-width: 300px;">
                            <i class="fa-solid fa-search"></i>
                            <input type="text" id="aramaYaklasan" class="form-control form-control-sm arama-input" placeholder="Öğrenci, Cihaz veya No ara...">
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 pt-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table align-middle mb-0">
                            <thead class="sticky-top bg-white text-muted" style="font-size: 0.8rem; z-index: 5;">
                            <tr class="border-bottom">
                                <th>ÖĞRENCİ BİLGİLERİ</th>
                                <th>CİHAZ / SERİ NO</th>
                                <th>VERİLİŞ</th>
                                <th>PLANLANAN İADE</th>
                                <th class="text-center">KALAN SÜRE</th>
                                <th class="text-end">İŞLEM</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($yaklasanlar as $odunc)
                                <tr class="border-bottom yaklasan-satiri">
                                    <td class="py-3">
                                        <div class="fw-bold text-dark">{{ $odunc->ad }} {{ $odunc->soyad }}</div>
                                        <small class="text-muted">{{ $odunc->okul_no }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-warning" style="color: #d39e00 !important;">{{ $odunc->urun_adi }}</div>
                                        <small class="text-muted"><i class="fa-solid fa-barcode opacity-50"></i> {{ $odunc->seri_no }}</small>
                                    </td>
                                    <td class="text-muted" style="font-size: 0.85rem;">
                                        {{ \Carbon\Carbon::parse($odunc->verilis_tarihi)->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="fw-bold text-dark" style="font-size: 0.85rem;">
                                        {{ \Carbon\Carbon::parse($odunc->planlanan_iade_tarihi)->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="text-center">
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill">
    {{ \Carbon\Carbon::parse($odunc->planlanan_iade_tarihi)->locale('tr')->diffForHumans(['parts' => 2, 'syntax' => 1]) }} Kaldı
</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ url('ogrenci-detay/' . $odunc->kullanici_id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 me-1">Profil</a>
                                        <a href="{{ url('iade-al/' . $odunc->odunc_id) }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">İade Al</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fa-regular fa-calendar-check text-warning fa-3x mb-3 opacity-50"></i>
                                        <h6 class="text-muted">Önümüzdeki 30 gün içinde teslim edilecek cihaz bulunmuyor.</h6>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    function tabloFiltrele(textInputId, dateBasId, dateBitId, satirSinifi) {
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

                            satirlar.forEach(satir => {
                                let klon = satir.cloneNode(true);
                                klon.querySelectorAll('.badge, .btn').forEach(el => el.remove());

                                let temizMetin = klon.innerText.toLocaleLowerCase('tr-TR');
                                let tamMetin = satir.innerText;

                                let kelimeUygun = metinFiltresi === '' || temizMetin.includes(metinFiltresi);
                                let tarihUygun = true;

                                if (basTarih || bitTarih) {
                                    tarihUygun = false;
                                    let eslesmeler = [...tamMetin.matchAll(tarihRegex)];

                                    if (eslesmeler.length > 0) {
                                        let sonTarih = eslesmeler[eslesmeler.length - 1]; // Sadece Son İade Tarihine bakar
                                        let gun = parseInt(sonTarih[1], 10);
                                        let ay = parseInt(sonTarih[2], 10) - 1;
                                        let yil = parseInt(sonTarih[3], 10);
                                        let satirTarihi = new Date(yil, ay, gun);

                                        let basUygun = !basTarih || satirTarihi >= basTarih;
                                        let bitUygun = !bitTarih || satirTarihi <= bitTarih;

                                        if (basUygun && bitUygun) {
                                            tarihUygun = true;
                                        }
                                    }
                                }

                                satir.style.display = (kelimeUygun && tarihUygun) ? '' : 'none';
                            });
                        }

                        if(aramaKutusu) aramaKutusu.addEventListener('keyup', filtreyiUygula);
                        if(basKutusu) basKutusu.addEventListener('input', filtreyiUygula);
                        if(bitKutusu) bitKutusu.addEventListener('input', filtreyiUygula);
                    }

                    // Gecikenler Tablosu İçin Filtre
                    tabloFiltrele('genelArama', 'baslangicFiltre', 'bitisFiltre', '.geciken-satiri');

                    // Yaklaşanlar Tablosu İçin Filtre
                    tabloFiltrele('aramaYaklasan', 'baslangicYaklasan', 'bitisYaklasan', '.yaklasan-satiri');

                });
            </script>
@endsection
