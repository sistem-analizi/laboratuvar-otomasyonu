@extends('layouts.app')

@section('title', 'Hızlı Stok Girişi | LabSistem')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* BUZLU CAM FORM KARTI */
        .rich-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1), 0 0 0 1px rgba(255,255,255,0.8) inset;
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative; overflow: hidden; max-width: 700px; margin: 3rem auto; padding: 3rem;
        }

        .rich-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
            background: linear-gradient(90deg, #10b981, #34d399, #059669);
        }

        .icon-box {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #d1fae5, #ecfdf5);
            color: #059669;
            border-radius: 16px; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.15); border: 1px solid #ffffff;
        }

        .form-label-rich { font-size: 0.85rem; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .form-label-rich i { color: #94a3b8; font-size: 1.1rem; }

        .rich-input {
            background-color: rgba(248, 250, 252, 0.8);
            border: 1px solid #cbd5e1; border-radius: 12px; padding: 0.8rem 1.2rem;
            transition: all 0.3s; font-weight: 600; color: #1e293b; font-size: 1.05rem;
        }

        .rich-input:focus {
            background-color: #ffffff; border-color: #10b981;
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.1), 0 0 0 4px rgba(16, 185, 129, 0.15); transform: translateY(-1px);
        }

        .quantity-input {
            font-size: 1.5rem !important; text-align: center; font-weight: 800 !important; color: #059669 !important; letter-spacing: 1px;
        }

        .btn-rich {
            background: linear-gradient(135deg, #10b981, #047857); border: none; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); transition: all 0.3s;
        }
        .btn-rich:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(16, 185, 129, 0.4); }
    </style>
@endsection

@section('content')
    <div class="container pb-5">

        <div class="rich-card">
            <div class="d-flex align-items-center mb-5 border-bottom border-light-subtle pb-4">
                <div class="icon-box me-4">
                    <i class="fa-solid fa-boxes-packing fa-2x"></i>
                </div>
                <div>
                    <h3 class="fw-bolder mb-1" style="color: #0f172a;">Stok Girişi</h3>
                    <p class="text-muted mb-0 fw-medium">Sistemde kayıtlı bir donanımı rafa dizin.</p>
                </div>
            </div>

            @if(session('basari'))
                <div class="alert alert-success" style="border-radius: 10px; background: #d1fae5; color: #065f46; border: 1px solid #10b981;">
                    <i class="fa-solid fa-check-circle me-2"></i> {{ session('basari') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="border-radius: 10px; background: #fee2e2; color: #991b1b; border: 1px solid #ef4444;">
                    <strong>Dur! Bir şeyler ters gitti:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $hata)
                            <li>{{ $hata }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/stok-giris" method="POST" novalidate>
                @csrf

                <div class="mb-4">
                    <label class="form-label-rich"><i class="fa-solid fa-microchip"></i> Mevcut Cihazı Seçin <span class="text-danger ms-1">*</span></label>
                    <select name="urun_id" class="form-select rich-input select2-arama @error('urun_id') is-invalid @enderror" required>
                        <option value="">Stok eklenecek cihazı arayın veya seçin...</option>
                        @foreach($urunler as $urun)
                            <option value="{{ $urun->urun_id }}" {{ old('urun_id') == $urun->urun_id ? 'selected' : '' }}>
                                {{ $urun->urun_kodu }} | {{ $urun->urun_adi }}
                            </option>
                        @endforeach
                    </select>
                    @error('urun_id') <div class="invalid-feedback fw-bold mt-2">{{ $message }}</div> @enderror
                </div>

                <!-- YENİ TERTEMİZ FATURA ALANI -->
                <div class="mb-4 p-3 rounded-3" style="background: rgba(241, 245, 249, 0.7); border: 1px dashed #cbd5e1;">
                    <label class="form-label fw-bold text-muted">
                        <i class="fa-solid fa-file-invoice-dollar text-success"></i> ALIM / FATURA BİLGİSİ
                    </label>
                    <!-- Artık yeni butonu yok, sadece listeden seçilecek -->
                    <select name="fatura_id" id="fatura_secimi" class="form-select shadow-sm select2-premium" required>
                        <option value="">Fatura arayın veya seçin...</option>
                        @foreach($faturalar as $fatura)
                            <option value="{{ $fatura->fatura_id }}">
                                {{ $fatura->satici_firma }} - {{ \Carbon\Carbon::parse($fatura->fatura_tarihi)->format('d.m.Y') }} (Kayıt No: {{ $fatura->fatura_id }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted mt-2 d-block"><i class="fa-solid fa-info-circle"></i> Yeni bir fatura tanımlamak için "Sistem Ayarları" sayfasını kullanın.</small>
                </div>

                <div class="mb-4 border-top border-light-subtle pt-4">
                    <label class="form-label-rich"><i class="fa-solid fa-map-location-dot"></i> Raf / Dolap Konumu <span class="text-danger ms-1">*</span></label>
                    <select name="konum_id" class="form-select rich-input select2-arama @error('konum_id') is-invalid @enderror" required>
                        <option value="">Bu cihazlar fiziksel olarak nereye konulacak?</option>
                        @foreach($konumlar as $konum)
                            <option value="{{ $konum->konum_id }}" {{ old('konum_id') == $konum->konum_id ? 'selected' : '' }}>
                                {{ $konum->dolap_adi }} - {{ $konum->raf_numarasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('konum_id') <div class="invalid-feedback fw-bold mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-5 g-4 border-top border-light-subtle pt-3">
                    <div class="col-md-6">
                        <label class="form-label-rich"><i class="fa-solid fa-layer-group"></i> Eklenecek Adet <span class="text-danger ms-1">*</span></label>
                        <input type="number" name="adet" class="form-control rich-input quantity-input @error('adet') is-invalid @enderror" placeholder="0" min="1" max="500" value="{{ old('adet') ?? 1 }}" required>
                        @error('adet') <div class="invalid-feedback fw-bold text-center mt-2">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted"><i class="fa-solid fa-user-tie text-danger"></i> KALICI SORUMLU PERSONEL</label>
                        <!-- Personel seçimi için de select2 arama özelliğini ekledim -->
                        <select name="sorumlu_kullanici_id" class="form-select rich-input select2-arama">
                            <option value=""> Zimmet Yapılmayacak / Rafa Kalkacak </option>
                            @foreach($personeller as $personel)
                                <option value="{{ $personel->kullanici_id }}">{{ $personel->ad }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted mt-1 d-block"><i class="fa-solid fa-info-circle"></i> Seçim yaparsanız, eklenen tüm cihazlar otomatik olarak bu kişiye zimmetlenir.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-rich"><i class="fa-solid fa-clock"></i> Kayıt Saati</label>
                        <input type="text" class="form-control rich-input text-center text-muted bg-light" value="{{ date('d.m.Y H:i') }}" disabled>
                    </div>
                </div>

                <div class="text-end pt-4 border-top border-light-subtle">
                    <a href="/" class="btn btn-light px-4 me-3 border fw-bold text-secondary rounded-3 py-2">Vazgeç</a>
                    <button type="submit" class="btn btn-primary btn-rich px-5 fw-bold py-2 rounded-3 text-white">
                        <i class="fa-solid fa-truck-ramp-box me-2"></i>Stoklara Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Select2 Arama özelliği tüm seçim kutularına uygulandı
            $('.select2-arama').select2({
                tags: false,
                placeholder: "Aramak için yazın veya seçin...",
                width: '100%',
                selectionCssClass: 'form-control border-0 py-1 bg-transparent'
            });
        });
    </script>
@endsection
