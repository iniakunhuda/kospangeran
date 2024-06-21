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
                            <div class="row">
                                <div class="col-12"><br></div>
                                <div class="col-md-6">
                                    <a href="{{ route('kamar.riwayat_penyewa', $kamar->id) }}" class="btn btn-outline-primary btn-block">
                                        <i class="fa fa-history mr-2"></i>
                                        Riwayat Penyewa
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('riwayat_kamar.index') }}?kamar_id={{$kamar->id}}" class="btn btn-outline-primary btn-block">
                                        <i class="fa fa-cog mr-2"></i>
                                        Perbaikan Kamar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block">
                            <h4 class="mb-0">Fasilitas</h4>
                        </div>
                        <div class="card-body">
                            @foreach ($kamar->fasilitas as $fasilitas)
                                {{ $fasilitas }}
                                <hr>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block">
                            <h4 class="mb-0">Penyewa saat ini</h4>
                        </div>
                        <div class="card-body">
                            @if(!isset($sewa))
                                <p>
                                    Belum ada penyewa
                                </p>
                                <a href="{{ route('penyewa.index') }}" class="btn btn-outline-primary">
                                    <i class="fa fa-user mr-2"></i>
                                    Pilih penyewa
                                </a>
                            @else
                            <div class="media">
                                <div class="mr-3">
                                    <img src="{{ $kamar->penyewa->foto_penyewa_url }}"  class="img-fluid rounded">
                                </div>
                                <div class="media-body">
                                    <strong>{{ $kamar->penyewa->nama }}</strong><br>
                                    <span>
                                        Tanggal Sewa: {{ $sewa->tanggal_bayar->format('d M Y') }}
                                    </span><br>
                                    <a href="{{ $kamar->penyewa->link_wa }}" class="text-success">
                                        Hubungi via Whatsapp
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

