@extends('layouts.app') {{-- Kendi ana şablon adın neyse onu yaz --}}

@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark">
                <i class="fas fa-microchip text-primary me-2"></i> {{ $urun->urun_adi }} Detayları
            </h4>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kataloga Dön
            </a>
        </div>

        <div class="row">
            <!-- SOL PANEL: ÜRÜN FOTOĞRAFI VE GENEL BİLGİLER -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">

                    <div class="text-center bg-white" style="border-radius: 15px 15px 0 0; border-bottom: 1px solid #eee; height: 220px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        @if(!empty($urun->fotograf))
                            <img src="{{ asset('uploads/' . $urun->fotograf) }}" alt="{{ $urun->urun_adi }}" style="max-height: 100%; max-width: 100%; object-fit: contain; padding: 15px;">
                        @else
                            {{-- FOTOĞRAF YOKSA GEÇİCİ AVATAR ÜRET --}}
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($urun->urun_adi) }}&background=e2e8f0&color=475569&font-size=0.33&size=256" alt="Görsel Yok" style="max-height: 100%; width: 100%; object-fit: cover;">
                        @endif
                    </div>

                    <div class="card-body bg-white" style="border-radius: 0 0 15px 15px;">
                        <span class="badge bg-primary mb-2 px-3 py-2">{{ $urun->urun_kodu }}</span>
                        <h5 class="card-title fw-bold">{{ $urun->urun_adi }}</h5>
                        <hr>
                        <!-- BURAYI GÜNCELLEDİK: Artık Controller'dan gelen $toplam_stok değişkenini kullanıyor -->
                        <p class="mb-0 mt-3 text-success fw-bold" style="font-size: 1.1rem;">
                            <i class="fas fa-box"></i> Toplam Stok: {{ $toplam_stok }} Adet
                        </p>
                    </div>
                </div>
            </div>

            <!-- SAĞ PANEL: STOK DETAYLARI TABLOSU -->
            <div class="col-lg-8">

                @if($urun->tip_id == 1)
                    <!-- SENARYO 1: ÜRÜN DEMİRBAŞ İSE BARKOD TABLOSUNU GÖSTER -->
                    <div class="card shadow-sm border-0" style="border-radius: 15px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                            <h6 class="fw-bold text-secondary"><i class="fas fa-barcode"></i> Sisteme Kayıtlı Cihazlar (Barkod Listesi)</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Seri No / Barkod</th>
                                        <th>Fiziksel Konum</th>
                                        <th>Durum</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($fiziksel_stoklar as $index => $stok)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="badge bg-dark px-2 py-2" style="letter-spacing: 1px;">{{ $stok->seri_no }}</span></td>
                                            <td><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $stok->dolap_adi ?? 'Raf: ' . $stok->konum_id }}</td>
                                            <td>
                                                @if(strtolower($stok->durum) == 'bosta' || strtolower($stok->durum) == 'boşta')
                                                    <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Uygun
                                                </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-user-lock me-1"></i> Zimmetli
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">
                                                <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i><br>
                                                Bu ürüne ait fiziksel stok bulunamadı.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                @elseif($urun->tip_id == 2)
                    <!-- SENARYO 2: ÜRÜN SARF MALZEMESİ İSE KONUM VE MİKTAR TABLOSUNU GÖSTER -->
                    <div class="card shadow-sm border-0" style="border-radius: 15px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                            <h6 class="fw-bold text-secondary"><i class="fas fa-boxes"></i> Stok Konumları ve Mevcut Miktarlar</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Fiziksel Konum (Raf/Dolap)</th>
                                        <th>Mevcut Miktar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($fiziksel_stoklar as $index => $stok)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                <span class="fw-medium">{{ $stok->dolap_adi ?? 'Konum: ' . $stok->konum_id }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary px-3 py-2" style="font-size: 0.9rem;">
                                                    {{ $stok->toplam_miktar }} Adet
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-5">
                                                <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i><br>
                                                Bu sarf malzemesine ait stok kaydı bulunamadı.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
