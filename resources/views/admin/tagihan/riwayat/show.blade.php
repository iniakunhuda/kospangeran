@extends('layouts.admin')

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('tagihan.riwayat.index') }}" class="btn btn-sm btn-outline-light mb-2">
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
                            <h4 class="mb-0">Detail Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="dataTable table-responsive color-black">
                                    <tbody>
                                        <tr>
                                            <th width="100px">Penyewa</th>
                                            <td>{{ $data->penyewa->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Whatsapp</th>
                                            <td>{{ $data->penyewa->nomor_wa }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kamar</th>
                                            <td>{{ $data->kamar->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Area</th>
                                            <td>{{ $data->area->judul }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Bayar</th>
                                            <td>{{ $data->tanggal_bayar->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Bayar</th>
                                            <td>@currency($data->total_bayar)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block">
                            <h4 class="mb-0">Detail Tagihan</h4>
                            <span class="text-muted opacity-50">
                                List ini dicatat ketika pembayaran dilakukan
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table dataTable" cellspacing="0" width="100%">
                                    <thead class="thead-primary">
                                        <th>Tagihan</th>
                                        <th>Total Tagihan</th>
                                        <th>Total Bayar</th>
                                        <th>Sisa Tagihan</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->detail_tagihan as $tagihan)
                                            <tr>
                                                @if(isset($tagihan_groups[$tagihan['tagihan_id']]))
                                                <td>{{ \Carbon\Carbon::parse($tagihan_groups[$tagihan['tagihan_id']]['tanggal_tagihan_dibuat'])->format('d M Y') }}</td>
                                                @else
                                                <td>-</td>
                                                @endif
                                                <td>@currency($tagihan['total_tagihan'])</td>
                                                <td>@currency($tagihan['total_bayar'])</td>
                                                <td>@currency($tagihan['sisa_tagihan'])</td>

                                                @if($tagihan['status'] == "0")
                                                    <td><span class="badge badge-danger">Belum lunas</span></td>
                                                @elseif ($tagihan['status'] == "2")
                                                    <td><span class="badge badge-warning">Lunas Sebagian</span></td>
                                                @else
                                                    <td><span class="badge badge-success">Lunas</span></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block">
                            <h4 class="mb-0">Bukti Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <img src="{{ $data->bukti_pembayaran_url }}" alt="bukti bayar" width="100%">
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

