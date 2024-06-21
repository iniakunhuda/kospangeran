@extends('layouts.admin')


@section('content')
    <div class="content-body">
        <div class="container-fluid">

            @php
                if(session('AREA_ID') && session('AREA_ID') != 'all') {
                    $route = route('area.show', session('AREA_ID'));
                } else {
                    $route = route('tipe_kamar.index');
                }
            @endphp

            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ $route }}" class="btn btn-sm btn-outline-light mb-2">
                        <i class="fa fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            @include('layouts.components.alert')

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body d-block">
                            <span class="badge badge-primary mb-2">{{ $tipe_kamar->area->jenis }}</span>
                            <h4 class="mb-2">{{ $tipe_kamar->nama }}</h4>
                            <p class="m-0 p-0 subtitle opacity-50">
                                <i class="fa fa-home mr-2"></i>
                                {{ $tipe_kamar->area->judul }}
                            </p>
                            <p class="m-0 p-0 subtitle opacity-50">
                                <i class="fa fa-money mr-2"></i>
                                @currency($tipe_kamar->harga) / bulan
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block">
                            <h4 class="mb-0">Statistik</h4>
                            <p class="m-0 p-0 subtitle opacity-50">Statistik Kamar</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border border-primary">
                                        <div class="card-header">
                                            <h5 class="card-title">Jumlah Kamar</h5>
                                        </div>
                                        <div class="card-body mb-0">
                                            <p class="card-text p-0 m-0">Total : {{ $tipe_kamar->statistik['jumlah_kamar'] }} Kamar</p>
                                            <p class="card-text p-0 m-0">Kosong : {{ $tipe_kamar->statistik['jumlah_kamar_kosong'] }} Kamar</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border border-primary">
                                        <div class="card-header">
                                            <h5 class="card-title">Estimasi Pendapatan</h5>
                                        </div>
                                        <div class="card-body mb-0">
                                            <strong>
                                                @currency($tipe_kamar->statistik['estimasi_pendapatan'])
                                            </strong>
                                            <p class="card-text p-0 m-0">Tiap bulan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Fasilitas</h4>
                        </div>
                        <div class="card-body">
                            <table id="tbl_fasilitas_area" class="dataTable table" cellspacing="0">
                                <tbody>
                                    @foreach ($tipe_kamar->fasilitas as $item)
                                        <tr>
                                            <td>{{ $item }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TODO: List kamar -->
                @include('admin.kamar.index_table')

            </div>

        </div>
    </div>
@endsection

