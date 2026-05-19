@extends('layouts.app')

@section('title', 'Cihaz Vitrini | LabSistem')
@section('header_title', 'Cihaz Vitrini')

@section('styles')

    <style>



        /* BUZLU CAM (Glassmorphism) FİLTRE PANELİ */
        .filter-panel {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary, #0d6efd);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* BUZLU CAM ÜRÜN KARTLARI */
        .product-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.9);
            overflow: hidden;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(59, 130, 246, 0.1);
        }

        .card-img-wrapper {
            height: 180px;
            background: linear-gradient(180deg, rgba(248, 250, 252, 0.8) 0%, rgba(255, 255, 255, 0.2) 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 1rem; border-bottom: 1px solid rgba(241, 245, 249, 0.8);
        }

        .card-img-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .product-code-badge { background-color: #f1f5f9; color: #475569; font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; font-weight: 700; letter-spacing: 0.5px; }
        .product-title { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 10px; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-category { font-size: 0.85rem; color: #64748b; font-weight: 500; }
        .stock-status { font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 6px; }

    </style>
@endsection

@section('content')
    <div class="container-fluid px-4 pb-5">

        <div class="filter-panel mt-3">
            <form action="{{ url('/') }}" method="GET" class="row g-2 align-items-end">

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold text-secondary small mb-1">Kelime, Kod veya Marka</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-search"></i></span>
                        <input type="text" name="arama" id="katalogArama" class="form-control border-start-0 ps-0" placeholder="Örn: Arduino..." value="{{ request('arama') }}">
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold text-secondary small mb-1">Ürün Tipi</label>
                    <select name="tip" class="form-select text-secondary" onchange="this.form.submit()">
                        <option value="">Tüm Tipler</option>
                        @foreach($tipler as $tip)
                            <option value="{{ $tip->tip_id }}" {{ request('tip') == $tip->tip_id ? 'selected' : '' }}>
                                {{ $tip->tip_adi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-4">
                    <label class="form-label fw-semibold text-secondary small mb-1">Ana Kategori</label>
                    <select name="ana_kategori" id="ana_kategori" class="form-select text-secondary" onchange="document.getElementById('alt_kategori').value=''; this.form.submit()">
                        <option value="">Tüm Kategoriler</option>
                        @foreach($ana_kategoriler as $ana)
                            <option value="{{ $ana->kategori_id }}" {{ request('ana_kategori') == $ana->kategori_id ? 'selected' : '' }}>
                                {{ $ana->kategori_adi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label class="form-label fw-semibold text-secondary small mb-1">Alt Kategori</label>
                    <select name="alt_kategori_id" id="alt_kategori" class="form-select text-secondary" onchange="this.form.submit()">
                        <option value="">Tüm Alt Kategoriler</option>
                        @foreach($alt_kategoriler as $alt)
                            <option value="{{ $alt->alt_kategori_id }}" data-parent="{{ $alt->kategori_id }}" {{ request('alt_kategori_id') == $alt->alt_kategori_id ? 'selected' : '' }}>
                                {{ $alt->alt_kategori_adi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Filtrele</button>
                    @if(request()->anyFilled(['arama', 'ana_kategori', 'alt_kategori_id', 'tip']))
                        <a href="{{ url('/') }}" class="btn btn-light border text-danger px-3 shadow-sm" title="Filtreyi Temizle"><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </div>

            </form>
        </div>

        @if(count($urunler) > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">

                @foreach($urunler as $urun)
                    <div class="col urun-karti">
                        <div class="product-card">
                            <div class="card-img-wrapper">
                                @if(isset($urun->resim_yolu) && $urun->resim_yolu)
                                    <img src="{{ asset($urun->resim_yolu) }}" alt="{{ $urun->urun_adi }}">
                                @else
                                    <div class="text-center text-muted opacity-25">
                                        <i class="fa-solid fa-image fa-3x mb-2 d-block"></i>
                                        <span class="small">Görsel Yok</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                <div>
                                    <span class="product-code-badge">{{ $urun->urun_kodu ?? 'Kod Bekliyor' }}</span>
                                </div>
                                <h5 class="product-title" title="{{ $urun->urun_adi }}">{{ $urun->urun_adi }}</h5>
                                <p class="product-category mb-2">{{ $urun->alt_kategori_adi }}</p>

                                <div class="mt-auto pt-2 border-top d-flex flex-column gap-2">
                                    @if($urun->stok_adedi > 0)
                                        <div class="stock-status text-success mb-1">
                                            <i class="fa-solid fa-circle-check"></i> Stokta ({{ $urun->stok_adedi }} adet)
                                        </div>
                                    @else
                                        <div class="stock-status text-danger mb-1">
                                            <i class="fa-solid fa-circle-xmark"></i> Stok Tükendi
                                        </div>
                                    @endif

                                        @if(session('rol_id') == 2)
                                        <div class="d-flex gap-1 mt-1">
                                            <a href="{{ url('urun/' . $urun->urun_id) }}" class="btn btn-sm btn-primary flex-grow-1 fw-bold" style="font-size: 0.8rem;"><i class="fa-solid fa-eye me-1"></i>Detay</a>
                                            <a href="{{ url('urun-duzenle/' . $urun->urun_id) }}" class="btn btn-sm btn-warning text-dark px-2 shadow-sm" title="Düzenle"><i class="fa-solid fa-pen"></i></a>

                                            <form action="{{ url('urun-sil/' . $urun->urun_id) }}" method="POST" class="d-inline sil-formu m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger px-2 shadow-sm sil-butonu" title="Sil">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 mt-3">
                <div class="bg-white rounded-3 p-5 shadow-sm border border-secondary border-opacity-10 border-dashed mx-auto" style="max-width: 600px;">
                    <i class="fa-solid fa-box-open fa-3x text-secondary opacity-25 mb-3"></i>
                    <p class="text-muted fs-5 mb-0">Kayıtlı cihaz bulunamadı veya filtreye uygun sonuç yok.</p>
                </div>
            </div>
        @endif

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. SENİN MEVCUT KATEGORİ FİLTRELEME KODUN (Hiç dokunulmadı) ---
            const anaKategoriSelect = document.getElementById('ana_kategori');
            const altKategoriSelect = document.getElementById('alt_kategori');

            if(anaKategoriSelect && altKategoriSelect) {
                const altOptions = Array.from(altKategoriSelect.querySelectorAll('option'));

                function filterAltKategori() {
                    const secilenAnaId = anaKategoriSelect.value;
                    altOptions.forEach(option => {
                        if (option.value === "") return;
                        if (secilenAnaId === "" || option.getAttribute('data-parent') === secilenAnaId) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                    const selectedAlt = altKategoriSelect.options[altKategoriSelect.selectedIndex];
                    if(selectedAlt && selectedAlt.value !== "" && selectedAlt.getAttribute('data-parent') !== secilenAnaId && secilenAnaId !== "") {
                        altKategoriSelect.value = "";
                    }
                }
                anaKategoriSelect.addEventListener('change', filterAltKategori);
                filterAltKategori();
            }

            // --- 2. YENİ EKLENEN SWEETALERT SİLME ONAY KODU ---
            document.querySelectorAll('.sil-butonu').forEach(button => {
                button.addEventListener('click', function() {
                    let form = this.closest('.sil-formu');

                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Bu cihazı katalogdan silmek üzeresiniz.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Evet, Sil!',
                        cancelButtonText: 'İptal',
                        background: 'rgba(255, 255, 255, 0.95)', // Senin temana uysun diye hafif şeffaf
                        backdrop: `rgba(15, 23, 42, 0.4)`, // Arka plan koyuluğu
                        borderRadius: '15px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Evet denirse formu gönder
                        }
                    });
                });
            });

            // --- 3. CONTROLLER'DAN GELEN HATA/BAŞARI MESAJLARINI POP-UP OLARAK GÖSTER ---
            @if(session('hata'))
            Swal.fire({
                icon: 'error',
                title: 'İşlem Başarısız!',
                text: "{{ session('hata') }}",
                confirmButtonColor: '#dc3545'
            });
            @endif

            @if(session('basari'))
            Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: "{{ session('basari') }}",
                showConfirmButton: false,
                timer: 2000 // 2 saniye sonra kendi kendine kapanır
            });
            @endif

        });
    </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const aramaKutusu = document.getElementById('katalogArama');

                if(aramaKutusu) {
                    aramaKutusu.addEventListener('keyup', function() {
                        // Kullanıcının yazdığı metni küçült (Türkçe karakter uyumlu)
                        let filtre = this.value.toLocaleLowerCase('tr-TR');

                        // Sayfadaki tüm ürün kartlarını bul
                        let kartlar = document.querySelectorAll('.urun-karti');

                        kartlar.forEach(function(kart) {
                            // Kartın içindeki tüm metinleri al ve küçült
                            let icerik = kart.innerText.toLocaleLowerCase('tr-TR');

                            // Eğer yazılan kelime kartın içinde varsa göster, yoksa gizle
                            if (icerik.includes(filtre)) {
                                kart.style.display = '';
                            } else {
                                kart.style.display = 'none';
                            }
                        });
                    });
                }
            });
        </script>
@endsection
