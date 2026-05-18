
<?php

use App\Http\Controllers\KullaniciController;
use App\Http\Controllers\OduncController;
use App\Http\Controllers\UrunController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;


// 1. ANA SAYFA (KATALOG)

Route::get('/', [DashboardController::class, 'index']);


// 2. GİRİŞ, KAYIT VE ÇIKIŞ İŞLEMLERİ

Route::get('/giris', [AuthController::class, 'giris_sayfasi']);
Route::post('/giris', [AuthController::class, 'giris_yap']);

Route::get('/kayit', [AuthController::class, 'kayit_sayfasi']);
Route::post('/kayit', [AuthController::class, 'kayit_ol']);

Route::get('/cikis', [AuthController::class, 'cikis']);

Route::get('/urun-tanit', [UrunController::class, 'urun_tanit_sayfasi']);
Route::post('/urun-tanit', [UrunController::class, 'urun_ekle']);

Route::get('/urun-detay/{id}', [UrunController::class, 'urun_detay']);
Route::post('/urun-detay/{id}', [UrunController::class, 'urun_detay_ekle']);

Route::get('/stok-giris', [UrunController::class, 'stok_giris_sayfasi']);
Route::post('/stok-giris', [UrunController::class, 'stok_ekle']);

// ÜRÜN DÜZENLEME VE SİLME İŞLEMLERİ
Route::get('/urun-duzenle/{id}', [UrunController::class, 'duzenle_sayfasi']);
Route::post('/urun-guncelle/{id}', [UrunController::class, 'urun_guncelle']);
//Route::get('/urun-sil/{id}', [UrunController::class, 'urun_sil']);
Route::delete('/urun-sil/{id}', [App\Http\Controllers\UrunController::class, 'urun_sil']);

Route::get('/urun/{id}', [UrunController::class, 'urun_detay']); // Yeni Detay Sayfası


Route::get('/ayarlar', [App\Http\Controllers\AyarlarController::class, 'index']);
Route::post('/ayarlar/kategori-ekle', [App\Http\Controllers\AyarlarController::class, 'kategori_ekle']);
Route::post('/ayarlar/alt-kategori-ekle', [App\Http\Controllers\AyarlarController::class, 'alt_kategori_ekle']);
Route::post('/ayarlar/konum-ekle', [App\Http\Controllers\AyarlarController::class, 'konum_ekle']);
Route::post('/ayarlar/tedarikci-ekle', [App\Http\Controllers\AyarlarController::class, 'tedarikci_ekle']);

// Ürün tipi
Route::post('/ayarlar/tip-ekle', [App\Http\Controllers\AyarlarController::class, 'tip_ekle']);

//TEDARİK TİPİ EKLEME
Route::post('/ayarlar/tedarik-tip-ekle', [App\Http\Controllers\AyarlarController::class, 'tedarik_tip_ekle']);
//FATURA EKLEME
Route::post('/ayarlar/fatura-ekle', [App\Http\Controllers\AyarlarController::class, 'fatura_ekle']);
// Ayarlar Sayfası Silme Rotaları
Route::delete('/ayarlar/ana-kategori-sil/{id}', [App\Http\Controllers\AyarlarController::class, 'ana_kategori_sil']);
Route::delete('/ayarlar/alt-kategori-sil/{id}', [App\Http\Controllers\AyarlarController::class, 'alt_kategori_sil']);
Route::delete('/ayarlar/konum-sil/{id}', [App\Http\Controllers\AyarlarController::class, 'konum_sil']);
Route::delete('/ayarlar/tip-sil/{id}', [App\Http\Controllers\AyarlarController::class, 'tip_sil']);
Route::delete('/ayarlar/fatura-sil/{id}', [App\Http\Controllers\AyarlarController::class, 'fatura_sil']);


Route::post('/fatura-ekle-ajax', [App\Http\Controllers\StokController::class, 'fatura_ekle_ajax']);

// KALICI ZİMMET
Route::get('/kalici-zimmet-ver', [App\Http\Controllers\ZimmetController::class, 'kalici_zimmet_sayfasi']);
Route::post('/kalici-zimmet-kaydet', [App\Http\Controllers\ZimmetController::class, 'kalici_zimmet_kaydet']);

//ÖDÜNÇ
Route::get('/odunc-ver', [App\Http\Controllers\ZimmetController::class, 'odunc_sayfasi']);
Route::post('/odunc-kaydet', [App\Http\Controllers\ZimmetController::class, 'odunc_kaydet']);

// AJAX
Route::get('/api/cihaz-detay/{id}', [App\Http\Controllers\ZimmetController::class, 'cihaz_detay']);

Route::delete('/ayarlar/tedarikci-sil/{id}', [App\Http\Controllers\AyarlarController::class, 'tedarikci_sil']);
Route::delete('/ayarlar/tedarik-tip-sil/{id}', [App\Http\Controllers\AyarlarController::class, 'tedarik_tip_sil']);

//AYARLAR DUZENLE
Route::post('/ayarlar/ana-kategori-duzenle/{id}', [App\Http\Controllers\AyarlarController::class, 'ana_kategori_duzenle']);
Route::post('/ayarlar/alt-kategori-duzenle/{id}', [App\Http\Controllers\AyarlarController::class, 'alt_kategori_duzenle']);
Route::post('/ayarlar/konum-duzenle/{id}', [App\Http\Controllers\AyarlarController::class, 'konum_duzenle']);
Route::post('/ayarlar/tedarik-tip-duzenle/{id}', [App\Http\Controllers\AyarlarController::class, 'tedarik_tip_duzenle']);
Route::post('/ayarlar/tip-duzenle/{id}', [App\Http\Controllers\AyarlarController::class, 'tip_duzenle']);
Route::post('/ayarlar/tedarikci-duzenle/{id}', [App\Http\Controllers\AyarlarController::class, 'tedarikci_duzenle']);
Route::post('/ayarlar/fatura-duzenle/{id}', [App\Http\Controllers\AyarlarController::class, 'fatura_duzenle']);


Route::post('/ayarlar/marka-ekle', [App\Http\Controllers\AyarlarController::class, 'marka_ekle']);
Route::post('/ayarlar/marka-duzenle/{id}', [App\Http\Controllers\AyarlarController::class, 'marka_duzenle']);
Route::delete('/ayarlar/marka-sil/{id}', [App\Http\Controllers\AyarlarController::class, 'marka_sil']);

Route::get('/ogrenci-detay/{id}', [KullaniciController::class, 'kullanici_detay'])->name('ogrenci.detay');
Route::get('/kullanicilar', [KullaniciController::class, 'kullanici_listesi'])->name('kullanicilar.index');

// Zimmet / Sarf Çıkış İşlemi Rotası
Route::post('/cihaz-cikis', [OduncController::class, 'cihaz_cikis_yap'])->name('cihaz.cikis');

Route::get('/odunc-ver', [App\Http\Controllers\OduncController::class, 'odunc_ver_sayfasi'])->name('odunc.ver');
Route::post('/odunc-kaydet', [App\Http\Controllers\OduncController::class, 'cihaz_cikis_yap']);


Route::get('/iade-al/{odunc_id}', [App\Http\Controllers\OduncController::class, 'iade_al']);

// Profil Sayfası Rotası
Route::get('/profil', [App\Http\Controllers\KullaniciController::class, 'profil_sayfasi'])->name('profil');

Route::get('/profil/duzenle', [App\Http\Controllers\KullaniciController::class, 'profil_duzenle_sayfasi']);
Route::post('/profil/guncelle', [App\Http\Controllers\KullaniciController::class, 'profil_guncelle']);

Route::get('/geciken-teslimatlar', [OduncController::class, 'gecikenler'])->name('odunc.gecikenler');
