@extends('layouts.admin')

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('penyewa.index') }}" class="btn btn-sm btn-outline-light mb-2">
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
                            <a href="{{ $penyewa->link_wa }}" class="badge badge-primary mb-2">
                                {{ $penyewa->nomor_wa }}
                            </a>
                            <h4 class="mb-0">{{ $penyewa->nama }}</h4>
                            <ul class="mt-3 color-black">
                                @php
                                    $sewa_harga = $penyewa->sewa->total_bayar ?? 0;
                                    $sewa_durasi = $penyewa->sewa->durasi_format ?? 'Bulan';
                                    $sewa_masuk = ($penyewa->sewa != null ? $penyewa->sewa->tanggal_bayar->format('d M Y') : '-') ?? '-';
                                @endphp
                                <li><i class="fa fa-home mr-1"></i> Kamar : {{ $penyewa->kamar->nama ?? 'Belum ada kamar' }}</li>
                                <li><i class="fa fa-money mr-1"></i> Harga : @currency($sewa_harga)/{{ $sewa_durasi }}</li>
                                <li><i class="fa fa-calendar mr-1"></i> Masuk : {{ $sewa_masuk }}</li>
                                <li><i class="fa fa-calendar mr-1"></i> Pekerjaan : {{ $penyewa->pekerjaan }}</li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <p class="mb-0 mt-2 opacity-50 color-black">
                                Deskripsi :
                                <br>
                                {{ $penyewa->deskripsi }}
                            </p>
                            <div class="row">
                                <div class="col-12"><br></div>
                                <div class="col-sm-6 mt-1">
                                    <label><strong>Foto Penyewa</strong></label><br>
                                    <img src="{{ $penyewa->foto_penyewa_url }}" style="height:200px" class="img-fluid rounded">
                                </div>
                                <div class="col-sm-6 mt-1">
                                    <label><strong>Foto KTP</strong></label><br>
                                    <img src="{{ $penyewa->foto_ktp_url }}" style="height:200px" class="img-fluid rounded">
                                </div>
                            </div>
                            @if($penyewa->sewa != null)
                            <div class="row">
                                <div class="col-12"><br></div>
                                <div class="col-md-6 mt-2">
                                    <a href="{{ route('sewa.pindah.create', $penyewa->sewa->id) }}" class="btn btn-outline-primary btn-block">
                                        <i class="fa fa-exchange mr-2"></i>
                                        Pindah Kamar
                                    </a>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <a href="{{ route('tagihan.riwayat.index') }}?penyewa_id={{ $penyewa->id }}" class="btn btn-outline-primary btn-block">
                                        <i class="fa fa-history mr-2"></i>
                                        Riwayat Bayar
                                    </a>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <a href="{{ route('sewa.berhenti.create', $penyewa->sewa->id) }}" class="btn btn-outline-primary btn-block">
                                        <i class="fa fa-user-times mr-2"></i>
                                        Berhenti Sewa
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-12"><br></div>
                                <div class="col-md-6">
                                    <a href="{{ route('sewa.baru.create') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fa fa-sign-in mr-2"></i>
                                        Pilih Kamar
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('tagihan.riwayat.index') }}?penyewa_id={{ $penyewa->id }}" class="btn btn-outline-primary btn-block">
                                        <i class="fa fa-history mr-2"></i>
                                        Riwayat Bayar
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($penyewa->sewa != null)
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kamar saat ini</h4>
                        </div>
                        <div class="card-body color-black">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Area</label>
                                <div class="col-sm-10">
                                    <strong>{{ $penyewa->sewa->kamar->area->judul }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Nama Kamar</label>
                                <div class="col-sm-10">
                                    <strong>{{ $penyewa->sewa->kamar->nama }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Tipe Kamar</label>
                                <div class="col-sm-10">
                                    <strong>{{ $penyewa->sewa->kamar->tipe_kamar->nama }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Nomor</label>
                                <div class="col-sm-10">
                                    <strong>{{ $penyewa->sewa->kamar->nomor }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Lantai</label>
                                <div class="col-sm-10">
                                    <strong>{{ $penyewa->sewa->kamar->lantai }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Harga</label>
                                <div class="col-sm-10">
                                    <strong>@currency($penyewa->sewa->total_bayar) / {{ $penyewa->sewa->durasi_format }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block mb-0">
                            <h4 class="mb-0">Tunggakan Pembayaran</h4>
                        </div>
                        <div class="card-body mt-0 pt-0">
                            @php
                                $total_tunggakan = 0;
                                foreach ($tunggakans as $tunggakan){
                                    $total_tunggakan += $tunggakan['total_tagihan'];
                                }
                            @endphp

                            @if(count($tunggakans) > 0)
                            <h3 class="text-danger">
                                @currency($total_tunggakan)
                            </h3>
                            <a href="{{ route('tagihan.bayar.create') }}?penyewa_id={{ $penyewa->id }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-money mr-2"></i> Bayar Tagihan
                            </a>
                            <br><br><br>
                            @else
                                Tidak ada tunggakan
                            @endif

                            @foreach ($tunggakans as $tunggakan)
                                <div class="mb-0">
                                    <div class="media mb-3">
                                        <div class="media-body">
                                            <strong>{{ $tunggakan['area']['judul'] }} - {{ $tunggakan['kamar']['nama'] }}</strong><br>
                                            <strong class="text-danger">
                                                @currency($tunggakan['total_tagihan'])
                                            </strong><br>
                                        </div>
                                    </div>
                                    <ul>
                                        @foreach ($tunggakan['tagihan'] as $tagihan)
                                            <li>
                                                <span>{{ $tagihan['tanggal_tagihan_dibuat_formatted'] }}</span>
                                                <span class="text-danger ml-5">
                                                    @currency($tagihan['sisa_tagihan'])
                                                </span>
                                            </li>
                                            <hr class="border-dark">
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            @include('admin.riwayat_kamar.per_penyewa.index')


        </div>
    </div>
@endsection

