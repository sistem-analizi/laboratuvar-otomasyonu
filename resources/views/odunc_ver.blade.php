@extends('layouts.app')

@section('content')
    <!-- Ortalanmış ve genişliği kısıtlanmış ana kapsayıcı -->
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
                <!-- 1. SOL KISIM (FORM) - 8 Birim Genişlik -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header border-0 p-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <h4 class="mb-0 text-white fw-bold"><i class="fa-solid fa-clock-rotate-left me-2 opacity-75"></i> Süreli Ödünç İşlemi</h4>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <form action="/odunc-kaydet" method="POST" id="oduncForm">
                                @csrf

                                <!-- ALICI BİLGİLERİ -->
                                <div class="p-4 mb-4 rounded-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                    <h6 class="fw-bold text-secondary mb-4"><i class="fa-solid fa-user-tag me-2"></i>Alıcı Bilgileri</h6>

                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-bold">ALICI TİPİ <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input tip-radio" type="radio" name="alici_tipi" id="tipOgrenci" value="ogrenci" checked>
                                                <label class="form-check-label fw-medium" for="tipOgrenci">Öğrenci</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input tip-radio" type="radio" name="alici_tipi" id="tipPersonel" value="personel">
                                                <label class="form-check-label fw-medium" for="tipPersonel">Personel / Akademisyen</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="form-label text-muted small fw-bold">SİSTEMDE KAYITLI KİŞİYİ SEÇİN <span class="text-danger">*</span></label>
                                        <select name="kullanici_id" id="alici_secimi" class="form-select shadow-sm" required>
                                            <!-- JS dolduracak -->
                                        </select>
                                    </div>
                                </div>

                                <!-- CİHAZ BİLGİLERİ -->
                                <div class="p-4 mb-4 rounded-4" style="background-color: #fffbeb; border: 1px solid #fde68a;">
                                    <h6 class="fw-bold mb-4" style="color: #d97706;"><i class="fa-solid fa-box-open me-2"></i>Cihaz Bilgileri</h6>

                                    <div class="row">
                                        <div class="col-md-8 mb-4">
                                            <label class="form-label text-muted small fw-bold">VERİLECEK CİHAZ (KATALOGDAN) <span class="text-danger">*</span></label>
                                            <select name="urun_id" id="cihaz_secici_odunc" class="form-select shadow-sm" required>
                                                <option value="">Seçiniz...</option>
                                                @foreach($urun_katalogu as $urun)
                                                    <!-- BURASI DEĞİŞTİ: data-tip eklendi -->
                                                    <option value="{{ $urun->urun_id }}" data-stok="{{ $urun->bostaki_stok }}" data-tip="{{ $urun->tip_id }}">
                                                        {{ $urun->urun_adi }} (Müsait Stok: {{ $urun->bostaki_stok }} Adet)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <label class="form-label text-muted small fw-bold">MİKTAR <span class="text-danger">*</span></label>
                                            <input type="number" name="miktar" id="miktar" class="form-control shadow-sm" required min="1" value="1">
                                            <small id="stokUyari" class="text-danger fw-bold d-none mt-1 d-block">Yetersiz stok!</small>
                                        </div>
                                    </div>

                                    <!-- BURASI DEĞİŞTİ: Gizlenip gösterilebilmesi için div içine alındı ve ID verildi -->
                                    <div id="iade_tarihi_alani">
                                        <label class="form-label text-muted small fw-bold">PLANLANAN İADE TARİHİ <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="planlanan_iade_tarihi" id="iade_tarihi_input" class="form-control shadow-sm" required>
                                    </div>
                                </div>

                                <button type="submit" id="btnKaydet" class="btn w-100 fw-bold py-3 shadow-sm rounded-pill" style="background-color: #f59e0b; color: #fff; font-size: 1.1rem; border: none;">
                                    <i class="fa-solid fa-handshake me-2"></i>Ödünç Ver ve Kaydet
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 2. SAĞ KISIM (ÖNİZLEME) - 4 Birim Genişlik (Daha Dar) -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-header bg-white border-0 p-4 text-center pb-0">
                            <h6 class="mb-0 fw-bold text-muted"><i class="fa-solid fa-eye me-2"></i> Ürün Detayları</h6>
                        </div>
                        <div class="card-body p-4" id="onizleme_karti_odunc">
                            <!-- Boş Durum (İlk Açılış) -->
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

            $('#cihaz_secici_odunc').select2({ placeholder: "Cihaz Seçiniz...", width: '100%' });
            $('#alici_secimi').select2({ placeholder: "Aramak için isim veya numara yazın...", width: '100%' });

            const tumKisiler = @json($kullanicilar);
            const tumUrunler = @json($urun_katalogu);

            // 1. KİŞİ LİSTESİ FİLTRELEME
            function listeyiYenile(secilenTip) {
                $('#alici_secimi').empty().append(new Option('Aramak için isim veya numara yazın...', ''));
                tumKisiler.forEach(function(kisi) {
                    let rol = (kisi.rol_adi || "").toLowerCase();
                    let ogrenciMi = rol.includes('ogrenci') || rol.includes('öğrenci');
                    let gorunenYazi = kisi.okul_no + " - " + kisi.ad + " " + kisi.soyad + " (" + kisi.rol_adi + ")";

                    if (secilenTip === 'ogrenci' && ogrenciMi) {
                        $('#alici_secimi').append(new Option(gorunenYazi, kisi.kullanici_id, false, false));
                    }
                    else if (secilenTip === 'personel' && !ogrenciMi) {
                        $('#alici_secimi').append(new Option(gorunenYazi, kisi.kullanici_id, false, false));
                    }
                });
                $('#alici_secimi').trigger('change');
            }

            $('.tip-radio').on('change', function() { listeyiYenile($(this).val()); });
            $('#tipOgrenci').prop('checked', true).trigger('change');

            // 2. STOK KONTROLÜ
            function stokKontroluYap() {
                let seciliOption = $('#cihaz_secici_odunc').find(':selected');
                if(!seciliOption.val()) return;

                let maxStok = parseInt(seciliOption.data('stok'));
                let girilenMiktar = parseInt($('#miktar').val()) || 0;

                if(girilenMiktar > maxStok) {
                    $('#stokUyari').removeClass('d-none').text("Maksimum: " + maxStok + " adet!");
                    $('#btnKaydet').prop('disabled', true);
                } else {
                    $('#stokUyari').addClass('d-none');
                    $('#btnKaydet').prop('disabled', false);
                }
            }
            $('#cihaz_secici_odunc, #miktar').on('change input', stokKontroluYap);

            // 3. ANINDA ÖNİZLEME VE İADE TARİHİ GİZLEME
            $('#cihaz_secici_odunc').on('change', function() {
                let urun_id = $(this).val();
                let onizleme = $('#onizleme_karti_odunc');

                // Animasyonlu tarih alanı değişkenleri
                let tarihAlani = $('#iade_tarihi_alani');
                let tarihInput = $('#iade_tarihi_input');

                if (!urun_id) {
                    onizleme.html('<div class="text-center text-muted" style="padding: 60px 0;"><i class="fa-solid fa-microchip mb-3" style="font-size: 4rem; color: #e2e8f0;"></i><p class="mb-0">Detayları görmek için yandaki listeden bir cihaz seçin.</p></div>');
                    // Seçim boşsa tarihi geri getir
                    tarihAlani.slideDown(200);
                    tarihInput.prop('required', true);
                    return;
                }

                let urun = tumUrunler.find(u => u.urun_id == urun_id);

                if(urun) {
                    // BURASI YENİ: SARF İSE TARİHİ GİZLE, DEMİRBAŞSA GÖSTER
                    if(urun.tip_id == 2) {
                        tarihAlani.slideUp(200); // 0.2 saniyede tatlı bir şekilde kapanır
                        tarihInput.prop('required', false); // HTML formu bizi engellemesin
                        tarihInput.val(''); // İçindeki tarihi temizle
                    } else {
                        tarihAlani.slideDown(200); // Demirbaş seçildiyse geri aç
                        tarihInput.prop('required', true); // Yeniden zorunlu yap
                    }

                    // Önizleme kartı güncelleme (Aynı kaldı)
                    let ikon = urun.tip_id == 2 ? 'fa-box-open' : 'fa-microchip';
                    let tipMetni = urun.tip_id == 2 ? 'Sarf Malzemesi' : 'Demirbaş';
                    let detayMetni = urun.teknik_detay ? urun.teknik_detay : 'Bu ürün için detaylı açıklama girilmemiş.';

                    onizleme.html(`
                        <div class="text-center mb-4 mt-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle mb-3" style="width: 80px; height: 80px;">
                                <i class="fa-solid ${ikon} fa-3x"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">${urun.urun_adi}</h5>
                            <span class="badge bg-secondary rounded-pill px-3 py-2 shadow-sm">${tipMetni}</span>
                        </div>
                        <ul class="list-group list-group-flush text-start">
                            <li class="list-group-item px-0 py-3 border-light bg-transparent">
                                <small class="text-muted d-block mb-1"><i class="fa-solid fa-barcode me-1"></i> Ürün Kodu</small>
                                <span class="fw-medium text-dark">${urun.urun_kodu || 'Kodsuz Ürün'}</span>
                            </li>
                            <li class="list-group-item px-0 py-3 border-light bg-transparent">
                                <small class="text-muted d-block mb-1"><i class="fa-solid fa-layer-group me-1"></i> Stok Durumu</small>
                                <span class="fw-bold text-success" style="font-size: 1.1rem;">${urun.bostaki_stok} Adet Boşta</span>
                            </li>
                            <li class="list-group-item px-0 py-3 border-light bg-transparent">
                                <small class="text-muted d-block mb-1"><i class="fa-solid fa-circle-info me-1"></i> Teknik Detay</small>
                                <span class="text-secondary" style="font-size: 0.85rem;">${detayMetni}</span>
                            </li>
                        </ul>
                    `);
                }
            });

        });
    </script>
@endsection
