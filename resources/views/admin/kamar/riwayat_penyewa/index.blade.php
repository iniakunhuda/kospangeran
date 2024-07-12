@extends('layouts.admin')

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('kamar.index') }}" class="btn btn-sm btn-outline-light mb-2">
                        <i class="fa fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            @include('layouts.components.alert')

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block">
                            <span class="badge badge-primary mb-2">{{ $kamar->tipe_kamar->nama }}</span>
                            <h4 class="mb-0">{{ $kamar->nama }}</h4>
                            <ul class="mt-3">
                                <li><i class="fa fa-home mr-1"></i> Lantai {{ $kamar->lantai }}</li>
                                <li><i class="fa fa-money mr-1"></i> @currency($kamar->harga)/bulan</li>
                                <li><i class="fa fa-tag mr-1"></i> Status : {{ $kamar->status }}</li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <p class="mb-2 mt-2">
                                {{ $kamar->deskripsi }}
                            </p>
                            <div class="row">
                                <div class="col-12"><br></div>
                                @foreach ($kamar->foto_url as $foto)
                                <div class="col-sm-3">
                                    <img src="{{ $foto }}" class="img-fluid rounded">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block">
                            <h4 class="mb-0">Riwayat Penyewa</h4>
                        </div>
                        <div class="card-body">
                            @if(count($kamar->sewa) < 1)
                                Belum ada riwayat
                            @endif

                            @foreach ($kamar->sewa as $riwayat)
                                <div class="media">
                                    <div class="mr-3">
                                        <img style="width:150px" src="{{ $riwayat->penyewa->foto_penyewa_url }}"  class="img-fluid rounded">
                                    </div>
                                    <div class="media-body">
                                        <strong>{{ $riwayat->penyewa->nama }}</strong><br>
                                        <div>
                                            <a class="text-success" href="{{ $riwayat->penyewa->link_wa }}">{{ $riwayat->penyewa->nomor_wa }}</a>
                                        </div>
                                        <div>@currency($riwayat->total_bayar)</div>

                                        <div class="mt-3">Mulai sewa: {{ $riwayat->tanggal_bayar->format('d M Y') }}</div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

