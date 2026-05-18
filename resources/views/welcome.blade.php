@extends('layouts.app')

@section('title', 'Cihaz Kataloğu | LabSistem')

@section('styles')
    <style>
        /* BUZLU CAM (Glassmorphism) FİLTRE PANELİ */
        .filter-panel { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-radius: 12px; padding: 1.5rem; box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05); border: 1px solid rgba(255, 255, 255, 0.8); margin-bottom: 2rem; }
        .product-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.9); overflow: hidden; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease; height: 100%; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(59, 130, 246, 0.1); }
        .card-img-wrapper { height: 180px; background: linear-gradient(180deg, rgba(248, 250, 252, 0.8) 0%, rgba(255, 255, 255, 0.2) 100%); display: flex; align-items: center; justify-content: center; padding: 1rem; border-bottom: 1px solid rgba(241, 245, 249, 0.8); }
        .card-img-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .product-code-badge { background-color: #f1f5f9; color: #475569; font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; font-weight: 700; letter-spacing: 0.5px; }
        .product-title { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 10px; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-category { font-size: 0.85rem; color: #64748b; font-weight: 500; }
        .stock-status { font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 6px; }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-4 pb-5">
        <div class="text-center mb-4 mt-3">
            <h2 class="fw-bolder" style="color: #0f172a;">Laboratuvar Donanım Kataloğu</h2>
            <p class="text-muted">Laboratuvarımızda bulunan tüm cihazları ve anlık stok durumlarını inceleyebilirsiniz.</p>
        </div>

        @if(count($urunler ?? []) > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                @foreach($urunler as $urun)
                    <div class="col">
                        <a href="/urun/{{ $urun->urun_id }}" class="text-decoration-none">
                            <div class="product-card">
                                <div class="card-img-wrapper">
                                    @if(isset($urun->resim_yolu) && $urun->resim_yolu)
                                        <img src="{{ asset($urun->resim_yolu) }}" alt="{{ $urun->urun_adi }}">
                                    @else
                                        <div class="text-center text-muted opacity-25">
                                            <i class="fa-solid fa-microchip fa-3x mb-2 d-block"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                    <div><span class="product-code-badge">{{ $urun->urun_kodu ?? 'Kod Bekliyor' }}</span></div>
                                    <h5 class="product-title" title="{{ $urun->urun_adi }}">{{ $urun->urun_adi }}</h5>
                                    <p class="product-category mb-2">{{ $urun->alt_kategori_adi ?? 'Kategori Yok' }}</p>

                                    <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                        @if($urun->stok_adedi > 0)
                                            <div class="stock-status text-success"><i class="fa-solid fa-circle-check"></i> {{ $urun->stok_adedi }} adet</div>
                                        @else
                                            <div class="stock-status text-danger"><i class="fa-solid fa-circle-xmark"></i> Tükendi</div>
                                        @endif
                                        <span class="btn btn-sm btn-outline-primary rounded-pill fw-bold" style="font-size: 0.75rem;">İncele <i class="fa-solid fa-arrow-right ms-1"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 mt-3">
                <div class="bg-white rounded-3 p-5 shadow-sm border border-secondary border-opacity-10 border-dashed mx-auto" style="max-width: 600px;">
                    <i class="fa-solid fa-box-open fa-3x text-secondary opacity-25 mb-3"></i>
                    <p class="text-muted fs-5 mb-0">Sistemde henüz kayıtlı cihaz bulunmuyor.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
