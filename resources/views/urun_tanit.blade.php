@extends('layouts.app')

@section('title', 'Yeni Cihaz Tanıt | LabSistem')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>


        /* BUZLU CAM FORM KARTI TASARIMI */
        .rich-card {
            background: rgba(255, 255, 255, 0.85); /* Şeffaf form arka planı */
            backdrop-filter: blur(24px); /* Güçlü buzlu cam efekti */
            -webkit-backdrop-filter: blur(24px);
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1), 0 0 0 1px rgba(255,255,255,0.8) inset;
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative; overflow: hidden; max-width: 900px; margin: 2rem auto; padding: 3rem;
        }

        .rich-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
            background: linear-gradient(90deg, #1e40af, #3b82f6, #38bdf8);
        }

        .icon-box {
            width: 54px; height: 54px; background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb;
            border-radius: 14px; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 10px rgba(37,99,235,0.15); border: 1px solid #ffffff;
        }

        .form-label-rich { font-size: 0.8rem; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .form-label-rich i { color: #94a3b8; font-size: 1rem; }

        /* Kutuların içi hafif şeffaf */
        .rich-input {
            background-color: rgba(248, 250, 252, 0.8);
            border: 1px solid #cbd5e1; border-radius: 10px; padding: 0.75rem 1.2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-weight: 500; color: #1e293b;
        }

        .rich-input:focus {
            background-color: #ffffff; border-color: #3b82f6;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1), 0 0 0 4px rgba(59, 130, 246, 0.15); transform: translateY(-1px);
        }

        .btn-rich {
            background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); transition: all 0.3s;
        }
        .btn-rich:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(37, 99, 235, 0.4); }

        /* Hata Durumu (Validasyon) İçin Zengin Tasarım */
        .rich-input.is-invalid {
            border-color: #ef4444 !important; /* Canlı Kırmızı Çerçeve */
            background-color: rgba(254, 226, 226, 0.7) !important; /* İçi çok hafif kırmızı */
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important; /* Dış kırmızı parlama */
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 6px;
        }
    </style>
@endsection

@section('content')
    <div class="container pb-5">

        <div class="rich-card">

            <div class="d-flex align-items-center mb-5 border-bottom pb-4">

                <div class="d-flex align-items-center mb-4">
                    <!-- İkon Kısmı (Mavi Tonlarında) -->
                    <div class="d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 60px; height: 60px; background-color: #e0f2fe; color: #0284c7;">
                        <i class="fa-solid fa-microchip fa-2x"></i>
                    </div>
                    <!-- Başlık ve Açıklama Kısmı -->
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">Yeni Cihaz Kimliği</h4>
                        <p class="mb-0 text-muted small">Sisteme yeni bir donanım veya sarf malzemesi tanıtın.</p>
                    </div>
                </div>
            </div>

            @if(session('basari'))
                <div class="alert alert-success rounded-3 mb-4 fw-bold shadow-sm border-0 bg-success text-white">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('basari') }}
                </div>
            @endif

            <form action="/urun-tanit" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="mb-4">
                    <label class="form-label-rich"><i class="fa-solid fa-tag"></i> Ürün Adı <span class="text-danger ms-1">*</span></label>
                    <input type="text" name="urun_adi" class="form-control rich-input @error('urun_adi') is-invalid @enderror" placeholder="Örn: NodeMCU ESP8266 Geliştirme Kartı" value="{{ old('urun_adi') }}">
                    @error('urun_adi') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-4 g-4">
                    <div class="col-md-6">
                        <label class="form-label-rich"><i class="fa-solid fa-folder-tree"></i> Ana Kategori <span class="text-danger ms-1">*</span></label>
                        <select class="form-select rich-input select2-arama @error('ana_kategori') is-invalid @enderror" id="ana_kategori" name="ana_kategori">
                            <option value="">Kategori Seçiniz...</option>
                            @foreach($ana_kategoriler as $ana)
                                <option value="{{ $ana->kategori_id }}" {{ old('ana_kategori') == $ana->kategori_id ? 'selected' : '' }}>{{ $ana->kategori_adi }}</option>
                            @endforeach
                        </select>
                        @error('ana_kategori') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-rich"><i class="fa-solid fa-code-branch"></i> Alt Kategori <span class="text-danger ms-1">*</span></label>
                        <select name="alt_kategori_id" id="alt_kategori" class="form-select rich-input select2-arama @error('alt_kategori_id') is-invalid @enderror">                            <option value="">Önce Ana Kategori Seçin</option>
                            @foreach($alt_kategoriler as $alt)
                                <option value="{{ $alt->alt_kategori_id }}" data-parent="{{ $alt->kategori_id }}" {{ old('alt_kategori_id') == $alt->alt_kategori_id ? 'selected' : '' }}>{{ $alt->alt_kategori_adi }}</option>
                            @endforeach
                        </select>
                        @error('alt_kategori_id') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-4 g-4">
                    <div class="col-md-4">
                        <label class="form-label-rich"><i class="fa-solid fa-layer-group"></i> Ürün Tipi <span class="text-danger ms-1">*</span></label>
                        <select name="tip_id" class="form-select rich-input select2-arama @error('tip_id') is-invalid @enderror">
                            <option value="">Tip Seçiniz...</option>
                            @foreach($tipler as $tip)
                                <option value="{{ $tip->tip_id }}" {{ old('tip_id') == $tip->tip_id ? 'selected' : '' }}>{{ $tip->tip_adi }}</option>
                            @endforeach
                        </select>
                        @error('tip_id') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-rich"><i class="fa-solid fa-copyright"></i> MARKA</label>
                        <select name="marka_id" id="marka_id" class="form-select rich-input select2-marka">
                            <option value=""></option> @foreach($markalar ?? [] as $marka)
                                <option value="{{ $marka->marka_id }}">{{ $marka->marka_adi }}</option>
                            @endforeach
                        </select>
                    </div>


                </div>

                <div class="mb-4">
                    <label class="form-label-rich text-primary"><i class="fa-solid fa-image text-primary"></i> Cihaz Görseli</label>
                    <input type="file" name="resim" class="form-control rich-input p-2 @error('resim') is-invalid @enderror" accept="image/jpeg,image/png">
                    @error('resim') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                    <div class="form-text small mt-2 fw-medium text-muted"><i class="fa-solid fa-circle-info me-1"></i>Maksimum 2MB, .jpg veya .png formatında yükleyebilirsiniz.</div>
                </div>

                <div class="mb-5">
                    <label class="form-label-rich"><i class="fa-solid fa-file-lines"></i> Teknik Detay & Açıklama</label>
                    <textarea name="teknik_detay" class="form-control rich-input" rows="4" placeholder="Cihazın voltaj değerleri, pin çıkışları, özel uyarılar...">{{ old('teknik_detay') }}</textarea>
                </div>

                <div class="text-end pt-3 border-top">
                    <a href="/" class="btn btn-light px-4 me-3 border fw-bold text-secondary">İptal Et</a>
                    <button type="submit" class="btn btn-primary btn-rich px-5 fw-bold py-2"><i class="fa-solid fa-save me-2"></i>Donanımı Kaydet</button>
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
            // 1. STANDART ARAMA KUTULARI
            $('.select2-arama').select2({
                placeholder: "Aramak için yazın...",
                width: '100%',
                selectionCssClass: 'rich-input border-0 py-1'
            });

            // 2. MARKA SEÇİMİ (Enter sorunu bitti!)
            $('.select2-marka').select2({
                tags: true,
                selectOnClose: true, // SİHİRLİ AYAR: Ekranda boşluğa tıklasan bile yazdığını alır!
                placeholder: "Marka seçin veya yeni yazın...",
                width: '100%',
                selectionCssClass: 'rich-input border-0 py-1'
            });

            // 3. ANA KATEGORİ - ALT KATEGORİ BAĞLANTISI
            $('#ana_kategori').on('change', function() {
                let secilenAnaId = this.value;
                let $altKategoriSelect = $('#alt_kategori');

                $altKategoriSelect.val("").trigger('change');

                if (secilenAnaId) {
                    $altKategoriSelect.prop('disabled', false);
                    $altKategoriSelect.find('option').each(function() {
                        let parentId = $(this).data('parent');
                        if (!parentId || parentId == secilenAnaId) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                } else {
                    $altKategoriSelect.prop('disabled', true);
                }
            });
        });
    </script>

@endsection
