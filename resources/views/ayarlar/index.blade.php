@extends('layouts.app')

@section('content')
    <style>
        /* Modern Sekmeler (Tabs) Okunabilirlik Güncellemesi */
        .modern-tabs .nav-link {
            color: #475569 !important; /* Pasif sekmelerin yazı rengini koyu slate yaptık (Çok daha okunaklı) */
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .modern-tabs .nav-link i {
            color: #64748b; /* Pasif sekmelerin ikonlarını da belirginleştirdik */
            transition: all 0.2s ease;
        }

        /* Sekmenin Üzerine Gelince (Hover) Effecti */
        .modern-tabs .nav-link:hover {
            background-color: #f1f5f9; /* Hafif, tatlı bir gri arka plan */
            color: #0f172a !important; /* Yazı rengini tamamen koyu yapıyoruz */
        }

        .modern-tabs .nav-link:hover i {
            color: #3b82f6; /* Üzerine gelinen sekmenin ikonu mavi parlasın */
        }

        /* Aktif (Seçili) Sekme Stili (Senin o güzel mavi buton stilin) */
        .modern-tabs .nav-link.active {
            background-color: #3b82f6 !important; /* Parlak premium mavi */
            color: #ffffff !important; /* Yazı bembeyaz */
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); /* Hafif modern bir gölge */
        }

        .modern-tabs .nav-link.active i {
            color: #ffffff !important; /* Aktif sekmenin ikonu da bembeyaz kalsın */
        }
    </style>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-gray fw-bold" style="text-shadow: 1px 1px 2px rgb(184,172,172);">⚙️ Sistem Ayarları</h2>
        </div>

        @if(session('basari'))
            <div class="alert alert-success fw-bold"
                 style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #10b981; border-radius: 12px;">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('basari') }}
            </div>
        @endif
        @if(session('hata'))
            <div class="alert alert-danger fw-bold"
                 style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #ef4444; border-radius: 12px;">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('hata') }}
            </div>
        @endif

        <div class="main-glass-container p-4">

            <ul class="nav nav-pills mb-4 modern-tabs" id="ayarlarTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="kategori-tab" data-bs-toggle="tab" data-bs-target="#kategori"
                            type="button" role="tab"><i class="fa-solid fa-layer-group me-2"></i>Kategoriler
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="konum-tab" data-bs-toggle="tab" data-bs-target="#konum" type="button"
                            role="tab"><i class="fa-solid fa-location-dot me-2"></i>Fiziksel Konumlar
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="marka-tab" data-bs-toggle="tab" data-bs-target="#marka" type="button" role="tab"><i class="fa-solid fa-copyright me-2"></i>Markalar</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tedarikci-tab" data-bs-toggle="tab" data-bs-target="#tedarikci"
                            type="button" role="tab"><i class="fa-solid fa-truck-fast me-2"></i>Tedarik Kaynakları
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tip-tab" data-bs-toggle="tab" data-bs-target="#tip" type="button"
                            role="tab"><i class="fa-solid fa-box-open me-2"></i>Ürün Tipleri
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="fatura-tab" data-bs-toggle="tab" data-bs-target="#fatura" type="button"
                            role="tab">
                        <i class="fa-solid fa-file-invoice-dollar me-2"></i>Alımlar & Faturalar
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="ayarlarTabsContent">

                <div class="tab-pane fade show active" id="kategori" role="tabpanel">

                    <div class="unified-panel mb-5">
                        <div class="panel-header d-flex justify-content-between align-items-center">
                            <h5 class="text-white mb-0 fw-bold"><i class="fa-solid fa-folder text-primary me-2"></i>Ana
                                Kategori Yönetimi</h5>
                            <div class="search-box-wrapper">
                                <i class="fa-solid fa-search text-muted"></i>
                                <input type="text" class="form-control arama-kutusu" data-tablo="tabloAnaKat"
                                       placeholder="Kategori ara...">
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-md-4 form-side p-4">
                                <p class="text-muted small mb-4">Sisteme yeni bir üst kategori ekleyin.</p>
                                <form action="/ayarlar/kategori-ekle" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label text-white-50 small fw-bold">KATEGORİ ADI</label>
                                        <input type="text" name="kategori_adi" class="form-control modern-input"
                                               required placeholder="Örn: Elektronik Donanımlar">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Kategori
                                        Oluştur
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8 list-side p-0">
                                <div class="table-responsive h-100" style="max-height: 300px;">
                                    <table class="table modern-table mb-0" id="tabloAnaKat">
                                        <tbody>
                                        @foreach($kategoriler as $ana)
                                            <tr class="arama-satiri">
                                                <td class="ps-4 align-middle fw-medium">{{ $ana->kategori_adi }}</td>
                                                <td class="text-end pe-4 align-middle" style="width: 80px;">
                                                    <button type="button"
                                                            class="btn btn-sm btn-icon-warning me-1 ana-kat-duzenle-btn"
                                                            data-id="{{ $ana->kategori_id }}"
                                                            data-ad="{{ $ana->kategori_adi }}" data-bs-toggle="modal"
                                                            data-bs-target="#anaKatDuzenleModal" title="Düzenle"><i
                                                            class="fa-solid fa-pen-to-square"></i></button>

                                                    <form action="/ayarlar/ana-kategori-sil/{{ $ana->kategori_id }}"
                                                          method="POST" class="d-inline sil-formu">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-sm btn-icon-danger sil-butonu"
                                                                title="Sil"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if($kategoriler->isEmpty())
                                            <tr>
                                                <td colspan="2" class="text-center text-muted py-5 border-0">Henüz
                                                    kategori bulunmuyor.
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="unified-panel">
                        <div class="panel-header d-flex justify-content-between align-items-center">
                            <h5 class="text-white mb-0 fw-bold"><i class="fa-solid fa-tags text-info me-2"></i>Alt
                                Kategori Yönetimi</h5>
                            <div class="search-box-wrapper">
                                <i class="fa-solid fa-search text-muted"></i>
                                <input type="text" class="form-control arama-kutusu" data-tablo="tabloAltKat"
                                       placeholder="Alt kategori ara...">
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-md-4 form-side p-4">
                                <form action="/ayarlar/alt-kategori-ekle" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-white-50 small fw-bold">BAĞLI OLDUĞU ANA
                                            KATEGORİ</label>
                                        <select name="kategori_id" class="form-select modern-input" required>
                                            <option value="">Seçiniz...</option>
                                            @foreach($kategoriler as $kat)
                                                <option
                                                    value="{{ $kat->kategori_id }}">{{ $kat->kategori_adi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-white-50 small fw-bold">ALT KATEGORİ ADI</label>
                                        <input type="text" name="alt_kategori_adi" class="form-control modern-input"
                                               required placeholder="Örn: Sensörler">
                                    </div>
                                    <button type="submit" class="btn btn-info w-100 text-white fw-bold py-2 shadow-sm">
                                        Alt Kategori Ekle
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8 list-side p-0">
                                <div class="table-responsive h-100" style="max-height: 350px;">
                                    <table class="table modern-table mb-0" id="tabloAltKat">
                                        <tbody>
                                        @foreach($alt_kategoriler ?? [] as $alt)
                                            <tr class="arama-satiri">
                                                <td class="ps-4 align-middle">
                                                    <span class="d-block fw-medium">{{ $alt->alt_kategori_adi }}</span>
                                                    <small class="text-muted"><i
                                                            class="fa-solid fa-arrow-turn-up fa-rotate-90 me-1"></i> {{ $alt->kategori_adi ?? '' }}
                                                    </small>
                                                </td>
                                                <td class="text-end pe-4 align-middle" style="width: 80px;">
                                                    <button type="button"
                                                            class="btn btn-sm btn-icon-warning me-1 alt-kat-duzenle-btn"
                                                            data-id="{{ $alt->alt_kategori_id }}"
                                                            data-ust="{{ $alt->kategori_id }}"
                                                            data-ad="{{ $alt->alt_kategori_adi }}"
                                                            data-bs-toggle="modal" data-bs-target="#altKatDuzenleModal"
                                                            title="Düzenle"><i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <form action="/ayarlar/alt-kategori-sil/{{ $alt->alt_kategori_id }}"
                                                          method="POST" class="d-inline sil-formu">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-sm btn-icon-danger sil-butonu"
                                                                title="Sil"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
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

                <div class="tab-pane fade" id="konum" role="tabpanel">
                    <div class="unified-panel">
                        <div class="panel-header d-flex justify-content-between align-items-center">
                            <h5 class="text-white mb-0 fw-bold"><i
                                    class="fa-solid fa-map-location-dot text-warning me-2"></i>Fiziksel Konum
                                (Dolap/Raf) Yönetimi</h5>
                            <div class="search-box-wrapper">
                                <i class="fa-solid fa-search text-muted"></i>
                                <input type="text" class="form-control arama-kutusu" data-tablo="tabloKonum"
                                       placeholder="Dolap veya raf ara...">
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-md-4 form-side p-4">
                                <p class="text-muted small mb-4">Cihazların fiziksel olarak bulunacağı laboratuvar
                                    konumlarını tanımlayın.</p>
                                <form action="/ayarlar/konum-ekle" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-white-50 small fw-bold">DOLAP / ALAN ADI</label>
                                        <input type="text" name="dolap_adi" class="form-control modern-input" required
                                               placeholder="Örn: Ana Malzeme Dolabı">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-white-50 small fw-bold">RAF NUMARASI</label>
                                        <input type="text" name="raf_numarasi" class="form-control modern-input"
                                               required placeholder="Örn: Raf-1">
                                    </div>
                                    <button type="submit"
                                            class="btn btn-warning w-100 text-dark fw-bold py-2 shadow-sm">Konumu Kaydet
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8 list-side p-0">
                                <div class="table-responsive h-100" style="max-height: 400px;">
                                    <table class="table modern-table mb-0" id="tabloKonum">
                                        <thead class="sticky-top">
                                        <tr>
                                            <th class="ps-4 text-muted small fw-bold text-uppercase">Dolap / Alan Adı
                                            </th>
                                            <th class="text-muted small fw-bold text-uppercase">Raf Numarası</th>
                                            <th class="text-end pe-4 text-muted small fw-bold text-uppercase">İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($konumlar as $konum)
                                            <tr class="arama-satiri">
                                                <td class="ps-4 align-middle fw-medium">{{ $konum->dolap_adi }}</td>
                                                <td class="align-middle text-info">{{ $konum->raf_numarasi }}</td>
                                                <td class="text-end pe-4 align-middle" style="width: 80px;">
                                                    <button type="button"
                                                            class="btn btn-sm btn-icon-warning me-1 konum-duzenle-btn"
                                                            data-id="{{ $konum->konum_id }}"
                                                            data-dolap="{{ $konum->dolap_adi }}"
                                                            data-raf="{{ $konum->raf_numarasi }}" data-bs-toggle="modal"
                                                            data-bs-target="#konumDuzenleModal" title="Düzenle"><i
                                                            class="fa-solid fa-pen-to-square"></i></button>
                                                    <form action="/ayarlar/konum-sil/{{ $konum->konum_id }}"
                                                          method="POST" class="d-inline sil-formu">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-sm btn-icon-danger sil-butonu"
                                                                title="Sil"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
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

                <div class="tab-pane fade" id="tedarikci" role="tabpanel">

                    <div class="unified-panel mb-5">
                        <div class="panel-header d-flex justify-content-between align-items-center">
                            <h5 class="text-white mb-0 fw-bold"><i class="fa-solid fa-box-open text-success me-2"></i>Kaynak
                                Tipleri (Satın Alma, Hibe vb.)</h5>
                        </div>
                        <div class="row g-0">
                            <div class="col-md-4 form-side p-4">
                                <form action="/ayarlar/tedarik-tip-ekle" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label text-white-50 small fw-bold">KAYNAK TİPİ ADI</label>
                                        <input type="text" name="tip_adi" class="form-control modern-input" required
                                               placeholder="Örn: Proje Desteği">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">Tipi
                                        Sisteme Ekle
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8 list-side p-0">
                                <div class="table-responsive h-100" style="max-height: 300px;">
                                    <table class="table modern-table mb-0">
                                        <tbody>
                                        @foreach($tedarik_tipleri as $tip)
                                            <tr class="arama-satiri">
                                                <td class="ps-4 align-middle fw-medium">{{ $tip->tip_adi }}</td>
                                                <td class="text-end pe-4 align-middle" style="width: 80px;">
                                                    <button type="button"
                                                            class="btn btn-sm btn-icon-warning me-1 tedarik-tip-duzenle-btn"
                                                            data-id="{{ $tip->id }}" data-ad="{{ $tip->tip_adi }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#tedarikTipDuzenleModal" title="Düzenle"><i
                                                            class="fa-solid fa-pen-to-square"></i></button>
                                                    <form action="/ayarlar/tedarik-tip-sil/{{ $tip->id }}" method="POST"
                                                          class="d-inline sil-formu">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-sm btn-icon-danger sil-butonu"
                                                                title="Sil"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if($tedarik_tipleri->isEmpty())
                                            <tr>
                                                <td colspan="2" class="text-center text-muted py-5 border-0">Henüz
                                                    kaynak tipi bulunmuyor.
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="unified-panel">
                        <div class="panel-header d-flex justify-content-between align-items-center">
                            <h5 class="text-white mb-0 fw-bold"><i class="fa-solid fa-building text-danger me-2"></i>Tedarikçi
                                Kurum Yönetimi</h5>
                            <div class="search-box-wrapper">
                                <i class="fa-solid fa-search text-muted"></i>
                                <input type="text" class="form-control arama-kutusu" data-tablo="tabloTedarikci"
                                       placeholder="Kurum ara...">
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-md-5 form-side p-4">
                                <form action="/ayarlar/tedarikci-ekle" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-white-50 small fw-bold">KURUM / KAYNAK ADI</label>
                                        <input type="text" name="kaynak_adi" class="form-control modern-input" required
                                               placeholder="Örn: Teknofest">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-white-50 small fw-bold">KAYNAK TİPİ</label>
                                        <select name="tedarik_tip_id" class="form-select modern-input" required>
                                            <option value="">Seçiniz...</option>
                                            @foreach($tedarik_tipleri as $tip)
                                                <option value="{{ $tip->id }}">{{ $tip->tip_adi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-white-50 small fw-bold">İLETİŞİM
                                            (Opsiyonel)</label>
                                        <textarea name="iletisim_bilgisi" class="form-control modern-input" rows="2"
                                                  placeholder="Telefon veya Kişi Adı"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2 shadow-sm">Kurumu
                                        Kaydet
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-7 list-side p-0">
                                <div class="table-responsive h-100" style="max-height: 450px;">
                                    <table class="table modern-table mb-0" id="tabloTedarikci">
                                        <thead class="sticky-top">
                                        <tr>
                                            <th class="ps-4">Kurum / Kaynak Adı</th>
                                            <th>Kaynak Tipi</th>
                                            <th>İletişim</th>
                                            <th class="text-end pe-4">İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($tedarik_kaynaklari as $kaynak)
                                            <tr class="arama-satiri">
                                                <td class="ps-4 align-middle fw-medium">{{ $kaynak->kaynak_adi }}</td>
                                                <td class="align-middle"><span
                                                        class="badge bg-secondary">{{ $kaynak->tip_adi }}</span></td>
                                                <td class="align-middle text-muted small">{{ $kaynak->iletisim_bilgisi ?? '-' }}</td>
                                                <td class="text-end pe-4 align-middle" style="width: 100px;">
                                                    <!-- İŞTE BAHSETTİĞİN DÜZENLE BUTONU BURADA -->
                                                    <button type="button"
                                                            class="btn btn-sm btn-icon-warning me-1 tedarikci-duzenle-btn"
                                                            data-id="{{ $kaynak->tedarik_id }}"
                                                            data-tip="{{ $kaynak->tedarik_tip_id }}"
                                                            data-isim="{{ $kaynak->kaynak_adi }}"
                                                            data-iletisim="{{ $kaynak->iletisim_bilgisi }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#tedarikciDuzenleModal" title="Düzenle">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <!-- SİL FORMU -->
                                                    <form action="/ayarlar/tedarikci-sil/{{ $kaynak->tedarik_id }}"
                                                          method="POST" class="d-inline sil-formu">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-sm btn-icon-danger sil-butonu"
                                                                title="Sil"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if($tedarik_kaynaklari->isEmpty())
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-5 border-0">Henüz kurum
                                                    eklenmemiş.
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tip" role="tabpanel">
                    <div class="unified-panel">
                        <div class="panel-header d-flex justify-content-between align-items-center">
                            <h5 class="text-white mb-0 fw-bold"><i
                                    class="fa-solid fa-boxes-stacked text-secondary me-2"></i>Ürün Tipi Yönetimi</h5>
                            <div class="search-box-wrapper">
                                <i class="fa-solid fa-search text-muted"></i>
                                <input type="text" class="form-control arama-kutusu" data-tablo="tabloTip"
                                       placeholder="Tip ara...">
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-md-4 form-side p-4">
                                <form action="/ayarlar/tip-ekle" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label text-white-50 small fw-bold">ÜRÜN TİPİ ADI</label>
                                        <input type="text" name="tip_adi" class="form-control modern-input" required
                                               placeholder="Örn: Sarf Malzeme">
                                    </div>
                                    <button type="submit" class="btn btn-light text-dark w-100 fw-bold py-2 shadow-sm">
                                        Ürün Tipini Kaydet
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8 list-side p-0">
                                <div class="table-responsive h-100" style="max-height: 300px;">
                                    <table class="table modern-table mb-0" id="tabloTip">
                                        <tbody>
                                        @foreach($tipler ?? [] as $t)
                                            <tr class="arama-satiri">
                                                <td class="ps-4 align-middle fw-medium">{{ $t->tip_adi ?? 'Belirsiz' }}</td>
                                                <td class="text-end pe-4 align-middle" style="width: 80px;">
                                                    <button type="button" class="btn btn-sm btn-icon-warning me-1 urun-tip-duzenle-btn" data-id="{{ $t->tip_id }}" data-ad="{{ $t->tip_adi }}" data-bs-toggle="modal" data-bs-target="#urunTipDuzenleModal" title="Düzenle"><i class="fa-solid fa-pen-to-square"></i></button>
                                                    <form action="/ayarlar/tip-sil/{{ $t->tip_id ?? $t->id }}"
                                                          method="POST" class="d-inline sil-formu">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-sm btn-icon-danger sil-butonu"
                                                                title="Sil"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
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

                <div class="tab-pane fade" id="fatura" role="tabpanel">
                    <div class="unified-panel">
                        <div class="panel-header d-flex justify-content-between align-items-center">
                            <h5 class="text-white mb-0 fw-bold"><i
                                    class="fa-solid fa-file-invoice-dollar text-success me-2"></i>Fatura ve Alım
                                Yönetimi</h5>
                            <div class="search-box-wrapper">
                                <i class="fa-solid fa-search text-muted"></i>
                                <input type="text" class="form-control arama-kutusu" data-tablo="tabloFatura"
                                       placeholder="Firma veya tarih ara...">
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-md-4 form-side p-4">
                                <form action="/ayarlar/fatura-ekle" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-white-50 small fw-bold">BÜTÇE / DESTEKLEYEN KAYNAK
                                            <span class="text-danger">*</span></label>
                                        <select name="tedarik_id" class="form-select modern-input" required>
                                            <option value="">Seçiniz...</option>
                                            @foreach($tedarik_kaynaklari as $kaynak)
                                                <option
                                                    value="{{ $kaynak->id ?? $kaynak->tedarik_id }}">{{ $kaynak->kaynak_adi }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-white-50 small fw-bold">SATICI FİRMA / MAĞAZA
                                            <span class="text-danger">*</span></label>
                                        <input type="text" name="satici_firma" class="form-control modern-input"
                                               required placeholder="Örn: Robotistan, Teknosa...">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-white-50 small fw-bold">FATURA TARİHİ VE SAATİ
                                            <span class="text-danger">*</span></label>
                                        <!-- datetime-local ile hem tarih hem saat seçtiriyoruz -->
                                        <input type="datetime-local" name="fatura_tarihi"
                                               class="form-control modern-input" required
                                               value="{{ date('Y-m-d\TH:i') }}">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-white-50 small fw-bold">TOPLAM TUTAR (TL)</label>
                                        <input type="number" step="0.01" name="toplam_tutar"
                                               class="form-control modern-input" placeholder="0.00">
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                                        <i class="fa-solid fa-check me-2"></i>Alımı Kaydet
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8 list-side p-0">
                                <div class="table-responsive h-100" style="max-height: 500px;">
                                    <table class="table modern-table mb-0" id="tabloFatura">
                                        <thead class="sticky-top">
                                        <tr>
                                            <th class="ps-4">Satıcı Firma</th>
                                            <th>Tarih & Saat</th>
                                            <th>Bütçe Kaynağı</th>
                                            <th>Tutar</th>
                                            <th class="text-end pe-4">İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($faturalar ?? [] as $fatura)
                                            <tr class="arama-satiri">
                                                <td class="ps-4 align-middle fw-medium">{{ $fatura->satici_firma }}</td>
                                                <td class="align-middle text-muted small">
                                                    {{ \Carbon\Carbon::parse($fatura->fatura_tarihi)->format('d.m.Y - H:i') }}
                                                </td>
                                                <td class="align-middle">
                                                    <span
                                                        class="badge bg-dark border border-secondary">{{ $fatura->kaynak_adi ?? 'Bilinmiyor' }}</span>
                                                </td>
                                                <td class="align-middle text-success fw-bold">{{ $fatura->toplam_tutar }}
                                                    ₺
                                                </td>
                                                <td class="text-end pe-4 align-middle" style="width: 80px;">
                                                    <!-- DÜZENLE BUTONU -->
                                                    <button type="button"
                                                            class="btn btn-sm btn-icon-warning me-2 fatura-duzenle-btn"
                                                            data-id="{{ $fatura->fatura_id }}"
                                                            data-tedarik="{{ $fatura->tedarik_id }}"
                                                            data-firma="{{ $fatura->satici_firma }}"
                                                            data-tarih="{{ \Carbon\Carbon::parse($fatura->fatura_tarihi)->format('Y-m-d\TH:i:s') }}"
                                                            data-tutar="{{ $fatura->toplam_tutar }}"
                                                            data-bs-toggle="modal" data-bs-target="#faturaDuzenleModal"
                                                            title="Düzenle">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <form action="/ayarlar/fatura-sil/{{ $fatura->fatura_id }}"
                                                          method="POST" class="d-inline sil-formu">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-sm btn-icon-danger sil-butonu"
                                                                title="Sil"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if($faturalar->isEmpty())
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-5 border-0">Henüz
                                                    kaydedilmiş bir alım/fatura bulunmuyor.
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="marka" role="tabpanel">
                    <div class="unified-panel">
                        <div class="panel-header d-flex justify-content-between align-items-center">
                            <h5 class="text-white mb-0 fw-bold"><i class="fa-solid fa-copyright text-info me-2"></i>Marka Yönetimi</h5>
                            <div class="search-box-wrapper">
                                <i class="fa-solid fa-search text-muted"></i>
                                <input type="text" class="form-control arama-kutusu" data-tablo="tabloMarka" placeholder="Marka ara...">
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-md-4 form-side p-4">
                                <form action="/ayarlar/marka-ekle" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label text-white-50 small fw-bold">MARKA ADI</label>
                                        <input type="text" name="marka_adi" class="form-control modern-input" required placeholder="Örn: Arduino, Creality...">
                                    </div>
                                    <button type="submit" class="btn btn-info w-100 text-white fw-bold py-2 shadow-sm">Markayı Kaydet</button>
                                </form>
                            </div>
                            <div class="col-md-8 list-side p-0">
                                <div class="table-responsive h-100" style="max-height: 400px;">
                                    <table class="table modern-table mb-0" id="tabloMarka">
                                        <tbody>
                                        @foreach($markalar ?? [] as $marka)
                                            <tr class="arama-satiri">
                                                <td class="ps-4 align-middle fw-medium">{{ $marka->marka_adi }}</td>
                                                <td class="text-end pe-4 align-middle" style="width: 100px;">
                                                    <button type="button" class="btn btn-sm btn-icon-warning me-1 marka-duzenle-btn"
                                                            data-id="{{ $marka->marka_id }}" data-ad="{{ $marka->marka_adi }}"
                                                            data-bs-toggle="modal" data-bs-target="#markaDuzenleModal" title="Düzenle">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <form action="/ayarlar/marka-sil/{{ $marka->marka_id }}" method="POST" class="d-inline sil-formu">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-icon-danger sil-butonu" title="Sil"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(!isset($markalar) || $markalar->isEmpty())
                                            <tr><td colspan="2" class="text-center text-muted py-5 border-0">Henüz marka bulunmuyor.</td></tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Fatura Düzenleme Modalı -->
    <div class="modal fade" id="faturaDuzenleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                 style="background-color: #1e293b; border: 1px solid #334155; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
                <div class="modal-header border-bottom border-secondary border-opacity-25 pb-3">
                    <h5 class="modal-title text-white fw-bold"><i
                            class="fa-solid fa-pen-to-square text-warning me-2"></i>Fatura/Alım Düzenle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form id="faturaDuzenleForm" method="POST" action="">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold">BÜTÇE / DESTEKLEYEN KAYNAK <span
                                    class="text-danger">*</span></label>
                            <select name="tedarik_id" id="edit_tedarik_id" class="form-select modern-input" required>
                                <option value="">Seçiniz...</option>
                                @foreach($tedarik_kaynaklari as $kaynak)
                                    <option
                                        value="{{ $kaynak->id ?? $kaynak->tedarik_id }}">{{ $kaynak->kaynak_adi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold">SATICI FİRMA / MAĞAZA <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="satici_firma" id="edit_satici_firma"
                                   class="form-control modern-input" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold">FATURA TARİHİ VE SAATİ <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" step="1" name="fatura_tarihi" id="edit_fatura_tarihi"
                                   class="form-control modern-input" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white-50 small fw-bold">TOPLAM TUTAR (TL)</label>
                            <input type="number" step="0.01" name="toplam_tutar" id="edit_toplam_tutar"
                                   class="form-control modern-input">
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary border-opacity-25">
                        <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-warning fw-bold px-4"><i class="fa-solid fa-save me-2"></i>Değişiklikleri
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Tedarikçi Kurum Düzenleme Modalı -->
    <div class="modal fade" id="tedarikciDuzenleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                 style="background-color: #1e293b; border: 1px solid #334155; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
                <div class="modal-header border-bottom border-secondary border-opacity-25 pb-3">
                    <h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-building text-danger me-2"></i>Kurum/Kaynak
                        Düzenle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form id="tedarikciDuzenleForm" method="POST" action="">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold">KURUM / KAYNAK ADI <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="kaynak_adi" id="edit_kaynak_adi" class="form-control modern-input"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold">KAYNAK TİPİ <span class="text-danger">*</span></label>
                            <select name="tedarik_tip_id" id="edit_tedarik_tip_id" class="form-select modern-input"
                                    required>
                                <option value="">Seçiniz...</option>
                                @foreach($tedarik_tipleri as $tip)
                                    <option value="{{ $tip->id }}">{{ $tip->tip_adi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white-50 small fw-bold">İLETİŞİM (Opsiyonel)</label>
                            <textarea name="iletisim_bilgisi" id="edit_iletisim_bilgisi"
                                      class="form-control modern-input" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary border-opacity-25">
                        <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-danger fw-bold px-4"><i class="fa-solid fa-save me-2"></i>Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- 1. Ana Kategori Modalı -->
    <div class="modal fade" id="anaKatDuzenleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 12px;"><div class="modal-header border-bottom border-secondary border-opacity-25 pb-3"><h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-folder text-primary me-2"></i>Ana Kategori Düzenle</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form id="anaKatDuzenleForm" method="POST">@csrf <div class="modal-body p-4"><label class="form-label text-white-50 small fw-bold">KATEGORİ ADI</label><input type="text" name="kategori_adi" id="edit_ana_kat_adi" class="form-control modern-input" required></div><div class="modal-footer border-top border-secondary border-opacity-25"><button type="submit" class="btn btn-primary fw-bold w-100">Güncelle</button></div></form>
            </div></div>
    </div>

    <!-- 2. Alt Kategori Modalı -->
    <div class="modal fade" id="altKatDuzenleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 12px;"><div class="modal-header border-bottom border-secondary border-opacity-25 pb-3"><h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-tags text-info me-2"></i>Alt Kategori Düzenle</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form id="altKatDuzenleForm" method="POST">@csrf <div class="modal-body p-4">
                        <div class="mb-3"><label class="form-label text-white-50 small fw-bold">ANA KATEGORİ</label><select name="kategori_id" id="edit_alt_ust_kat" class="form-select modern-input" required>@foreach($kategoriler as $kat)<option value="{{ $kat->kategori_id }}">{{ $kat->kategori_adi }}</option>@endforeach</select></div>
                        <div><label class="form-label text-white-50 small fw-bold">ALT KATEGORİ ADI</label><input type="text" name="alt_kategori_adi" id="edit_alt_kat_adi" class="form-control modern-input" required></div>
                    </div><div class="modal-footer border-top border-secondary border-opacity-25"><button type="submit" class="btn btn-info fw-bold text-white w-100">Güncelle</button></div></form>
            </div></div>
    </div>

    <!-- 3. Konum Modalı -->
    <div class="modal fade" id="konumDuzenleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 12px;"><div class="modal-header border-bottom border-secondary border-opacity-25 pb-3"><h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-map-location-dot text-warning me-2"></i>Konum Düzenle</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form id="konumDuzenleForm" method="POST">@csrf <div class="modal-body p-4">
                        <div class="mb-3"><label class="form-label text-white-50 small fw-bold">DOLAP ADI</label><input type="text" name="dolap_adi" id="edit_konum_dolap" class="form-control modern-input" required></div>
                        <div><label class="form-label text-white-50 small fw-bold">RAF NUMARASI</label><input type="text" name="raf_numarasi" id="edit_konum_raf" class="form-control modern-input" required></div>
                    </div><div class="modal-footer border-top border-secondary border-opacity-25"><button type="submit" class="btn btn-warning fw-bold text-dark w-100">Güncelle</button></div></form>
            </div></div>
    </div>

    <!-- 4. Kaynak Tipi Modalı -->
    <div class="modal fade" id="tedarikTipDuzenleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 12px;"><div class="modal-header border-bottom border-secondary border-opacity-25 pb-3"><h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-box-open text-success me-2"></i>Kaynak Tipi Düzenle</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form id="tedarikTipDuzenleForm" method="POST">@csrf <div class="modal-body p-4"><label class="form-label text-white-50 small fw-bold">KAYNAK TİPİ ADI</label><input type="text" name="tip_adi" id="edit_tedarik_tip_adi" class="form-control modern-input" required></div><div class="modal-footer border-top border-secondary border-opacity-25"><button type="submit" class="btn btn-success fw-bold w-100">Güncelle</button></div></form>
            </div></div>
    </div>

    <!-- 5. Ürün Tipi Modalı -->
    <div class="modal fade" id="urunTipDuzenleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 12px;"><div class="modal-header border-bottom border-secondary border-opacity-25 pb-3"><h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-boxes-stacked text-secondary me-2"></i>Ürün Tipi Düzenle</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form id="urunTipDuzenleForm" method="POST">@csrf <div class="modal-body p-4"><label class="form-label text-white-50 small fw-bold">ÜRÜN TİPİ ADI</label><input type="text" name="tip_adi" id="edit_urun_tip_adi" class="form-control modern-input" required></div><div class="modal-footer border-top border-secondary border-opacity-25"><button type="submit" class="btn btn-light fw-bold text-dark w-100">Güncelle</button></div></form>
            </div></div>
    </div>

    <!-- 6. Marka Düzenleme Modalı -->
    <div class="modal fade" id="markaDuzenleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 12px;"><div class="modal-header border-bottom border-secondary border-opacity-25 pb-3"><h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-copyright text-info me-2"></i>Marka Düzenle</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form id="markaDuzenleForm" method="POST">@csrf <div class="modal-body p-4"><label class="form-label text-white-50 small fw-bold">MARKA ADI</label><input type="text" name="marka_adi" id="edit_marka_adi" class="form-control modern-input" required></div><div class="modal-footer border-top border-secondary border-opacity-25"><button type="submit" class="btn btn-info fw-bold text-white w-100">Güncelle</button></div></form>
            </div></div>
    </div>

    <style>

        .btn-icon-warning {
            background: transparent;
            border: none;
            color: #f59e0b;
            transition: all 0.2s;
            font-size: 1.1rem;
            opacity: 0.8;
        }

        .btn-icon-warning:hover {
            color: #d97706;
            transform: scale(1.2);
            opacity: 1;
        }

        .main-glass-container {
            background: transparent;
        }

        /* --- SEKMELER (TABS) --- */
        .modern-tabs {
            gap: 10px;
            border-bottom: 2px solid #334155;
            padding-bottom: 10px;
        }

        .modern-tabs .nav-link {
            color: #cbd5e1;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .modern-tabs .nav-link:hover {
            color: #ffffff;
            background: #334155;
        }

        .modern-tabs .nav-link.active {
            background: #2563eb !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }

        /* --- BÜTÜNLEŞİK PANELLER (NET RENKLER) --- */
        .unified-panel {
            background: #1e293b; /* Mat, göz yormayan koyu lacivert/gri */
            border: 1px solid #334155; /* Belirgin çerçeve */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Panel Başlığı */
        .panel-header {
            background: #0f172a; /* Başlık kısmı bir tık daha koyu (Derinlik katar) */
            border-bottom: 1px solid #334155;
            padding: 16px 24px;
        }

        /* Form Kısmı (Sol Sütun) */
        .form-side {
            background: #1e293b;
            border-right: 1px solid #334155;
        }

        /* Liste Kısmı (Sağ Sütun) */
        .list-side {
            background: #0f172a; /* Tablo verileri öne çıksın diye daha koyu bir zemin */
        }

        /* --- KULLANIŞLI FORMLAR VE ARAMA KUTUSU --- */
        .modern-input {
            background: #0f172a !important; /* En koyu zemin */
            border: 1px solid #475569 !important; /* Net görünen kenarlık */
            color: #f8fafc !important; /* Bembeyaz, jilet gibi yazı */
            border-radius: 8px !important;
            padding: 12px 16px !important;
            font-size: 0.95rem;
        }

        .modern-input:focus {
            border-color: #3b82f6 !important; /* Tıklanınca parlak mavi çerçeve */
            background: #1e293b !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
        }

        .modern-input::placeholder {
            color: #64748b !important;
        }

        /* Form Etiketleri (Label) - Bootstrap'in text-white-50 sınıfını eziyoruz */
        .form-label.text-white-50 {
            color: #94a3b8 !important;
            letter-spacing: 0.5px;
            opacity: 1 !important;
        }


        /* --- KESİN ÇÖZÜM: ARAMA KUTUSU --- */
        .search-box-wrapper {
            position: relative !important;
            width: 250px !important;
            display: flex !important;
            align-items: center !important;
        }

        .search-box-wrapper i {
            position: absolute !important;
            left: 14px !important;
            color: #94a3b8 !important;
            z-index: 5 !important;
            pointer-events: none !important; /* İkonun tıklamayı engellemesini önler */
        }

        .search-box-wrapper input.form-control {
            background-color: #1e293b !important;
            border: 1px solid #475569 !important;
            color: #ffffff !important;
            padding-left: 38px !important;
            border-radius: 50px !important;
            font-size: 0.85rem !important;
            width: 100% !important;
            height: 36px !important;
            box-shadow: none !important;
        }

        .search-box-wrapper input.form-control::placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
        }

        .search-box-wrapper input.form-control:focus {
            border-color: #3b82f6 !important;
            background-color: #0f172a !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
        }


        /* --- OKUNMASI KOLAY TABLOLAR --- */
        .modern-table {
            color: #f8fafc;
            margin-bottom: 0;
        }

        .modern-table th {
            background: #1e293b !important;
            border-bottom: 2px solid #334155 !important;
            border-top: none !important;
            color: #94a3b8;
            padding: 15px !important;
            font-weight: 600;
        }

        .modern-table td {
            background: transparent !important;
            border-bottom: 1px solid #1e293b !important;
            padding: 16px 15px !important;
            color: #e2e8f0; /* Veriler net ve okunabilir */
        }

        .modern-table tr:hover td {
            background: #1e293b !important;
            color: #ffffff;
        }

        /* Tablo İçi Boş Durum Yazısı (Listelenecek veri bekleniyor vs.) */
        .modern-table .text-muted {
            color: #94a3b8 !important; /* Göz yormayan açık gri renk */
            font-weight: 500;
        }


        /* Zarif Sil Butonu */
        .btn-icon-danger {
            background: transparent;
            border: none;
            color: #ef4444;
            transition: all 0.2s;
            font-size: 1.1rem;
            opacity: 0.8;
        }

        .btn-icon-danger:hover {
            color: #dc2626;
            transform: scale(1.2);
            opacity: 1;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        // Marka Düzenleme
        document.querySelectorAll('.marka-duzenle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('markaDuzenleForm').action = '/ayarlar/marka-duzenle/' + this.dataset.id;
                document.getElementById('edit_marka_adi').value = this.dataset.ad;
            });
        });
        // 1. Ana Kategori Düzenleme
        document.querySelectorAll('.ana-kat-duzenle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('anaKatDuzenleForm').action = '/ayarlar/ana-kategori-duzenle/' + this.dataset.id;
                document.getElementById('edit_ana_kat_adi').value = this.dataset.ad;
            });
        });

        // 2. Alt Kategori Düzenleme
        document.querySelectorAll('.alt-kat-duzenle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('altKatDuzenleForm').action = '/ayarlar/alt-kategori-duzenle/' + this.dataset.id;
                document.getElementById('edit_alt_ust_kat').value = this.dataset.ust;
                document.getElementById('edit_alt_kat_adi').value = this.dataset.ad;
            });
        });

        // 3. Konum Düzenleme
        document.querySelectorAll('.konum-duzenle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('konumDuzenleForm').action = '/ayarlar/konum-duzenle/' + this.dataset.id;
                document.getElementById('edit_konum_dolap').value = this.dataset.dolap;
                document.getElementById('edit_konum_raf').value = this.dataset.raf;
            });
        });

        // 4. Kaynak Tipi Düzenleme
        document.querySelectorAll('.tedarik-tip-duzenle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('tedarikTipDuzenleForm').action = '/ayarlar/tedarik-tip-duzenle/' + this.dataset.id;
                document.getElementById('edit_tedarik_tip_adi').value = this.dataset.ad;
            });
        });

        // 5. Ürün Tipi Düzenleme
        document.querySelectorAll('.urun-tip-duzenle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('urunTipDuzenleForm').action = '/ayarlar/tip-duzenle/' + this.dataset.id;
                document.getElementById('edit_urun_tip_adi').value = this.dataset.ad;
            });
        });

        // Tedarikçi Kurum Düzenleme Butonlarına Tıklanınca Verileri Doldur
        document.querySelectorAll('.tedarikci-duzenle-btn').forEach(button => {
            button.addEventListener('click', function () {
                // Formun gideceği adresi (action) ayarla
                document.getElementById('tedarikciDuzenleForm').action = '/ayarlar/tedarikci-duzenle/' + this.getAttribute('data-id');

                // Inputların içini doldur
                document.getElementById('edit_kaynak_adi').value = this.getAttribute('data-isim');
                document.getElementById('edit_tedarik_tip_id').value = this.getAttribute('data-tip');
                document.getElementById('edit_iletisim_bilgisi').value = this.getAttribute('data-iletisim');
            });
        });

        // Fatura Düzenleme Butonlarına Tıklanınca Verileri Modala Doldur
        document.querySelectorAll('.fatura-duzenle-btn').forEach(button => {
            button.addEventListener('click', function () {
                // Butondaki gizli verileri al
                let id = this.getAttribute('data-id');
                let tedarik = this.getAttribute('data-tedarik');
                let firma = this.getAttribute('data-firma');
                let tarih = this.getAttribute('data-tarih');
                let tutar = this.getAttribute('data-tutar');

                // Modal içindeki form elemanlarına yerleştir
                document.getElementById('faturaDuzenleForm').action = '/ayarlar/fatura-duzenle/' + id;
                document.getElementById('edit_tedarik_id').value = tedarik;
                document.getElementById('edit_satici_firma').value = firma;
                document.getElementById('edit_fatura_tarihi').value = tarih;
                document.getElementById('edit_toplam_tutar').value = tutar;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {

            // 1. SEKME (TAB) HAFIZASI
            let aktifSekme = localStorage.getItem('ayarlarSekmesi');
            if (aktifSekme) {
                let sekmeButonu = document.querySelector(`button[data-bs-target="${aktifSekme}"]`);
                if (sekmeButonu) {
                    new bootstrap.Tab(sekmeButonu).show();
                }
            }
            let tumSekmeler = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tumSekmeler.forEach(buton => {
                buton.addEventListener('shown.bs.tab', function (e) {
                    localStorage.setItem('ayarlarSekmesi', e.target.getAttribute('data-bs-target'));
                });
            });

            // 2. EVRENSEL TABLO FİLTRELEME (ARAMA) MOTORU
            document.querySelectorAll('.arama-kutusu').forEach(kutucuk => {
                kutucuk.addEventListener('keyup', function () {
                    const aranan = this.value.toLowerCase();
                    const hedefTabloId = this.getAttribute('data-tablo');
                    const satirlar = document.querySelectorAll(`#${hedefTabloId} tbody tr.arama-satiri`);

                    satirlar.forEach(satir => {
                        const metin = satir.innerText.toLowerCase();
                        satir.style.display = metin.includes(aranan) ? '' : 'none';
                    });
                });
            });

            // 3. KURŞUN GEÇİRMEZ SİLME İŞLEMİ
            document.addEventListener('click', function (e) {
                let silButonu = e.target.closest('.sil-butonu');
                if (silButonu) {
                    e.preventDefault();
                    let form = silButonu.closest('.sil-formu');

                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Bu kaydı kalıcı olarak silmek üzeresiniz.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#475569',
                        confirmButtonText: '<i class="fa-solid fa-trash-alt me-1"></i> Evet, Sil',
                        cancelButtonText: 'İptal',
                        background: '#1e293b',
                        color: '#f8fafc',
                        borderRadius: '12px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });

        });

        // Canlı Saat Güncelleme - DÜZELTİLDİ
        function updateTime() {
            const timeInput = document.querySelector('input[type="datetime-local"][name="fatura_tarihi"]');

            // Eğer input yoksa veya kullanıcı eliyle değiştirdiyse çık
            if (!timeInput || timeInput.dataset.manualEdit === 'true') {
                return;
            }

            const now = new Date();

            // Türkiye saati (GMT+3) için offset ayarı
            const offset = 3; // GMT+3
            const localTime = new Date(now.getTime() + (offset * 3600 * 1000));

            // YYYY-MM-DDTHH:mm formatına çevir
            const year = localTime.getUTCFullYear();
            const month = String(localTime.getUTCMonth() + 1).padStart(2, '0');
            const day = String(localTime.getUTCDate()).padStart(2, '0');
            const hours = String(localTime.getUTCHours()).padStart(2, '0');
            const minutes = String(localTime.getUTCMinutes()).padStart(2, '0');

            const formattedTime = `${year}-${month}-${day}T${hours}:${minutes}`;

            timeInput.value = formattedTime;
        }

        // Her saniye (1000ms) saati güncelle
        setInterval(updateTime, 1000);
        // Sayfa yüklendiğinde hemen çalıştır
        updateTime();

        // Kullanıcı tarihi manuel değiştirmek isterse canlı güncellemeyi durdur
        const timeInputEl = document.querySelector('input[type="datetime-local"][name="fatura_tarihi"]');
        if (timeInputEl) {
            timeInputEl.addEventListener('input', function () {
                this.dataset.manualEdit = 'true';
            });
        }
    </script>

@endsection
