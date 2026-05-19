@extends('layouts.app')

@section('title', 'Gelen Talepler | Yönetim Paneli')
@section('header_title', 'Onay Bekleyen Cihaz Talepleri')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            @if(session('basari'))
                <div class="alert alert-success fw-bold"><i class="fa-solid fa-check me-2"></i>{{ session('basari') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-secondary">
                    <tr>
                        <th>Öğrenci Bilgisi</th>
                        <th>İstenen Cihaz</th>
                        <th>Tarih</th>
                        <th class="text-end">İşlem Yap</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($talepler as $talep)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $talep->ad }} {{ $talep->soyad }}</div>
                                <div class="small text-muted">No: {{ $talep->okul_no }}</div>
                            </td>
                            <td class="fw-semibold text-primary">{{ $talep->urun_adi }}</td>
                            <td><i class="fa-regular fa-calendar text-muted me-2"></i> {{ $talep->istenen_kullanim_tarihi }}</td>
                            <td class="text-end">
                                <form action="{{ url('talep-cevapla') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="talep_id" value="{{ $talep->talep_id }}">

                                    <button type="submit" name="islem" value="onay" class="btn btn-sm btn-success fw-bold px-3">
                                        <i class="fa-solid fa-check me-1"></i> Onayla
                                    </button>

                                    <button type="submit" name="islem" value="red" class="btn btn-sm btn-outline-danger fw-bold px-3 ms-1">
                                        <i class="fa-solid fa-xmark me-1"></i> Reddet
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="fa-regular fa-face-smile-wink fs-1 mb-3 opacity-50"></i><br>
                                Şu an bekleyen hiçbir talep yok. Kahvenizi yudumlayabilirsiniz!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
