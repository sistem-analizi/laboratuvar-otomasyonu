@extends('layouts.app')

@section('title', 'Cihaz Teslim İşlemleri | LabSistem')
@section('header_title', 'Onaylanan Cihazları Teslim Et (Zimmetle)')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            @if(session('basari'))
                <div class="alert alert-success fw-bold"><i class="fa-solid fa-handshake me-2"></i>{{ session('basari') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-secondary">
                    <tr>
                        <th>Öğrenci</th>
                        <th>Onaylanan Ürün</th>
                        <th>Fiziksel Cihaz (Barkod) Seçimi</th>
                        <th>İade Tarihi</th>
                        <th class="text-end">İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($talepler as $talep)
                        <tr>
                            <form action="/teslimat" method="POST">
                                @csrf
                                <input type="hidden" name="talep_id" value="{{ $talep->talep_id }}">
                                <input type="hidden" name="kullanici_id" value="{{ $talep->kullanici_id }}">

                                <td class="fw-bold text-dark">{{ $talep->ad }} {{ $talep->soyad }}</td>
                                <td class="fw-semibold text-primary">{{ $talep->urun_adi }}</td>

                                <td>
                                    <select name="demirbas_id" class="form-select form-select-sm" required>
                                        <option value="">Raftaki cihazlardan seçin...</option>
                                        @foreach($cihazlar as $cihaz)
                                            @if($cihaz->urun_id == $talep->urun_id)
                                                <option value="{{ $cihaz->demirbas_id }}">Seri No: {{ $cihaz->seri_no }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="date" name="iade_tarihi" class="form-control form-control-sm" required>
                                </td>

                                <td class="text-end">
                                    <button type="submit" class="btn btn-sm btn-primary fw-bold">
                                        <i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Teslim Et
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fa-solid fa-box-open fs-1 mb-3 opacity-50"></i><br>
                                Şu an teslim edilmeyi bekleyen (onaylanmış) bir talep bulunmuyor.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
