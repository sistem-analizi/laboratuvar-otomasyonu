@extends('layouts.app')

@section('content')
    <div class="py-5" style="background-color: #f8fafc; min-height: 100vh;">
        <div class="container-xl" style="max-width: 1100px;">

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm mb-4 rounded-3 border-0">
                    <i class="fa-solid fa-triangle-exclamation"></i> <strong>Kayıt Başarısız!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('basari'))
                <div class="alert alert-success shadow-sm mb-4 rounded-3 border-0">
                    <i class="fa-solid fa-circle-check"></i> {{ session('basari') }}
                </div>
            @endif

            <div class="row g-4 justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header border-0 p-4" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                            <h4 class="mb-0 text-white fw-bold"><i class="fa-solid fa-shield-halved me-2 opacity-75"></i> Kalıcı Demirbaş Ataması</h4>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <form action="/kalici-zimmet-kaydet" method="POST">
                                @csrf

                                <div class="p-4 mb-4 rounded-4" style="background-color: #fef2f2; border: 1px solid #fecaca;">
                                    <h6 class="fw-bold mb-4" style="color: #b91c1c;"><i class="fa-solid fa-box-open me-2"></i>Atama Bilgileri</h6>

                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-bold">VERİLECEK CİHAZ (BARKODLU) <span class="text-danger">*</span></label>
                                        <select name="demirbas_id" id="cihaz_secici_kalici" class="form-select select2-premium shadow-sm" required>
                                            <option value="">Aramak için yazın...</option>
                                            @foreach($bostaki_cihazlar as $cihaz)
                                                <option value="{{ $cihaz->demirbas_id }}">[{{ $cihaz->urun_kodu }}] - {{ $cihaz->urun_adi }} (Seri: {{ $cihaz->seri_no }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-bold">TESLİM ALAN PERSONEL <span class="text-danger">*</span></label>
                                        <select name="kullanici_id" class="form-select select2-premium shadow-sm" required>
                                            <option value="">Aramak için yazın...</option>
                                            @foreach($personeller as $personel)
                                                <option value="{{ $personel->kullanici_id }}">{{ $personel->ad }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label text-muted small fw-bold">AÇIKLAMA VEYA NOT</label>
                                        <textarea name="aciklama" class="form-control shadow-sm" rows="3" placeholder="Zimmet durumu ile ilgili notlar..."></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn w-100 fw-bold py-3 shadow-sm rounded-pill" style="background-color: #ef4444; color: #fff; font-size: 1.1rem; border: none;">
                                    <i class="fa-solid fa-save me-2"></i> Onayla ve Kaydet
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-header bg-white border-0 p-4 text-center pb-0">
                            <h6 class="mb-0 fw-bold text-muted"><i class="fa-solid fa-eye me-2"></i> Cihaz Önizleme</h6>
                        </div>
                        <div class="card-body p-4" id="onizleme_karti_kalici">
                            <div class="text-center text-muted" style="padding: 60px 0;">
                                <i class="fa-solid fa-microchip mb-3" style="font-size: 4rem; color: #e2e8f0;"></i>
                                <p class="mb-0">Detayları görmek için yandaki listeden bir cihaz seçin.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Şık arama kutusu
            $('.select2-premium').select2({ placeholder: "Aramak için yazın...", width: '100%' });

            // Anında Önizleme (Senin AJAX yapın korunarak görseli iyileştirildi)
            $('#cihaz_secici_kalici').on('change', function() {
                let cihaz_id = $(this).val();
                let onizleme = $('#onizleme_karti_kalici');

                if (!cihaz_id) {
                    onizleme.html('<div class="text-center text-muted" style="padding: 60px 0;"><i class="fa-solid fa-microchip mb-3" style="font-size: 4rem; color: #e2e8f0;"></i><p class="mb-0">Detayları görmek için yandaki listeden bir cihaz seçin.</p></div>');
                    return;
                }

                onizleme.html('<div class="text-center mt-5"><div class="spinner-border text-danger"></div><p class="mt-2 text-muted fw-bold">Yükleniyor...</p></div>');

                $.get('/api/cihaz-detay/' + cihaz_id, function(data) {
                    if(data) {
                        let resim = data.resim_yolu ? '/' + data.resim_yolu : '/uploads/varsayilan.jpg';
                        let marka = data.marka_adi ? data.marka_adi : 'Markasız';
                        onizleme.html(`
                            <div class="text-center mb-3">
                                <img src="${resim}" class="img-fluid rounded-3 mb-3 shadow-sm" style="max-height: 160px; object-fit: contain;">
                                <h5 class="fw-bold text-dark">${data.urun_adi}</h5>
                                <span class="badge bg-danger rounded-pill px-3 py-2">${marka}</span>
                            </div>
                            <ul class="list-group list-group-flush text-start mt-3">
                                <li class="list-group-item px-0 py-3 border-light bg-transparent">
                                    <small class="text-muted d-block mb-1"><i class="fa-solid fa-barcode me-1"></i> Ürün Kodu</small>
                                    <span class="fw-medium text-dark">${data.urun_kodu}</span>
                                </li>
                                <li class="list-group-item px-0 py-3 border-light bg-transparent">
                                    <small class="text-muted d-block mb-1"><i class="fa-solid fa-hashtag me-1"></i> Seri / Barkod No</small>
                                    <span class="fw-bold text-dark bg-light px-2 py-1 rounded border">${data.seri_no}</span>
                                </li>
                            </ul>
                        `);
                    }
                });
            });
        });
    </script>
@endsection
