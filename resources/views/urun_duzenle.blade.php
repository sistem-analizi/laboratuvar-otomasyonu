@extends('layouts.app')
@section('title', 'Cihazı Düzenle | LabSistem')

@section('styles')
    <style>
        /* Buzlu Cam Teması (Aynısı) */
        .rich-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1), 0 0 0 1px rgba(255,255,255,0.8) inset; border: 1px solid rgba(255, 255, 255, 0.5); position: relative; overflow: hidden; max-width: 900px; margin: 2rem auto; padding: 3rem; }
        .rich-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, #f59e0b, #d97706); } /* Düzenleme için Turuncu tema */
        .icon-box { width: 54px; height: 54px; background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.15); border: 1px solid #ffffff; }
        .form-label-rich { font-size: 0.8rem; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .rich-input { background-color: rgba(248, 250, 252, 0.8); border: 1px solid #cbd5e1; border-radius: 10px; padding: 0.75rem 1.2rem; transition: all 0.3s; font-weight: 500; color: #1e293b; }
        .rich-input:focus { background-color: #ffffff; border-color: #f59e0b; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.1), 0 0 0 4px rgba(245, 158, 11, 0.15); transform: translateY(-1px); }
        .btn-warning-rich { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border: none; box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3); transition: all 0.3s; }
        .btn-warning-rich:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(245, 158, 11, 0.4); color: white; }
    </style>
@endsection

@section('content')
    <div class="container pb-5">
        <div class="rich-card">
            <div class="d-flex align-items-center mb-5 border-bottom pb-4">
                <div class="icon-box me-4"><i class="fa-solid fa-pen-to-square fa-xl"></i></div>
                <div>
                    <h3 class="fw-bolder mb-1" style="color: #0f172a;">Cihazı Düzenle</h3>
                    <p class="text-muted mb-0 fw-medium"><strong>{{ $urun->urun_kodu }}</strong> kodlu cihazın bilgilerini güncelliyorsunuz.</p>
                </div>
            </div>

            <form action="/urun-guncelle/{{ $urun->urun_id }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="mb-4">
                    <label class="form-label-rich"><i class="fa-solid fa-tag"></i> Ürün Adı <span class="text-danger ms-1">*</span></label>
                    <input type="text" name="urun_adi" class="form-control rich-input @error('urun_adi') is-invalid @enderror" value="{{ old('urun_adi', $urun->urun_adi) }}">
                </div>

                <div class="row mb-4 g-4">
                    <div class="col-md-6">
                        <label class="form-label-rich"><i class="fa-solid fa-code-branch"></i> Kategori (Alt Kategori) <span class="text-danger ms-1">*</span></label>
                        <select name="alt_kategori_id" class="form-select rich-input">
                            @foreach($alt_kategoriler as $alt)
                                <option value="{{ $alt->alt_kategori_id }}" {{ $urun->alt_kategori_id == $alt->alt_kategori_id ? 'selected' : '' }}>{{ $alt->alt_kategori_adi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-rich"><i class="fa-solid fa-layer-group"></i> Ürün Tipi <span class="text-danger ms-1">*</span></label>
                        <select name="tip_id" class="form-select rich-input">
                            @foreach($tipler as $tip)
                                <option value="{{ $tip->tip_id }}" {{ $urun->tip_id == $tip->tip_id ? 'selected' : '' }}>{{ $tip->tip_adi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-rich"><i class="fa-solid fa-industry"></i> Marka / Üretici</label>
                    <select name="marka_id" class="form-select rich-input select2-marka">
                        <option value=""></option>
                        @foreach($markalar as $marka)
                            <option value="{{ $marka->marka_id }}"
                                {{ (old('marka_id', $urun->marka_id) == $marka->marka_id) ? 'selected' : '' }}>
                                {{ $marka->marka_adi }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted mt-1 d-block">İpucu: Listede yoksa yazıp boşluğa tıklayın.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label-rich"><i class="fa-solid fa-image text-primary"></i> Yeni Görsel Yükle (Değiştirmek İstemiyorsanız Boş Bırakın)</label>
                    <input type="file" name="resim" class="form-control rich-input p-2" accept="image/jpeg,image/png">
                </div>

                <div class="mb-5">
                    <label class="form-label-rich"><i class="fa-solid fa-file-lines"></i> Teknik Detay & Açıklama</label>
                    <textarea name="teknik_detay" class="form-control rich-input" rows="4">{{ old('teknik_detay', $urun->teknik_detay) }}</textarea>
                </div>

                <div class="text-end pt-3 border-top">
                    <a href="/" class="btn btn-light px-4 me-3 border fw-bold text-secondary">İptal Et</a>
                    <button type="submit" class="btn btn-warning-rich px-5 fw-bold py-2"><i class="fa-solid fa-save me-2"></i>Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>
@endsection
