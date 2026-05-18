<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index() {
        if (session('rol_id') != 2 && session('rol_id') != 3) return redirect('/');

        $urunler = DB::table('urun_katalogu')
            ->where('tip_id', 1)
            ->orderBy('urun_adi', 'asc')
            ->get();

        return view('stok_giris', ['urunler' => $urunler]);

    }

    public function fatura_ekle_ajax(Request $request) {
        try {
            $gelen_metin = $request->kaynak_adi_input;

            if (empty($gelen_metin)) {
                return response()->json(['success' => false, 'mesaj' => 'Bütçe/Kurum adı boş olamaz!'], 422);
            }

            // 1. ADIM: Tedarik kaynağını bul veya yeni oluştur
            $kaynak = DB::table('tedarik_kaynaklari')
                ->where('kaynak_adi', $gelen_metin)
                ->first();

            if ($kaynak) {
                $tedarik_id = $kaynak->tedarik_id;
            } else {
                $tedarik_id = DB::table('tedarik_kaynaklari')->insertGetId([
                    'kaynak_adi' => $gelen_metin
                ]);
            }

            // 2. ADIM: Faturayı bu tedarik_id ile kaydet
            $yeni_fatura_id = DB::table('faturalar')->insertGetId([
                'tedarik_id'    => $tedarik_id,
                'satici_firma'  => $request->satici_firma,
                'fatura_tarihi' => $request->fatura_tarihi,
                'toplam_tutar'  => $request->toplam_tutar ?? 0
            ]);

            return response()->json([
                'success' => true,
                'id' => $yeni_fatura_id,
                'metin' => "Kayıt: $yeni_fatura_id - " . $request->satici_firma
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'mesaj' => 'Veritabanı Hatası: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request) {

    }
}
