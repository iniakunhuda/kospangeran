@extends('layouts.admin')

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('area.index') }}" class="btn btn-sm btn-outline-light mb-2">
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
                            <span class="badge badge-primary mb-2">{{ $area->jenis }}</span>
                            <h4 class="mb-0">{{ $area->judul }}</h4>
                            <p class="m-0 p-0 subtitle opacity-50">{{ $area->alamat}}</p>
                        </div>
                        <div class="card-body">
                            <p>
                                {{ $area->deskripsi }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block">
                            <h4 class="mb-0">Statistik</h4>
                            <p class="m-0 p-0 subtitle opacity-50">Statistik Bangunan</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border border-primary">
                                        <div class="card-header">
                                            <h5 class="card-title">Jumlah Kamar</h5>
                                        </div>
                                        <div class="card-body mb-0">
                                            <p class="card-text p-0 m-0">Total : {{ $area->statistik['jumlah_kamar'] }} Kamar</p>
                                            <p class="card-text p-0 m-0">Kosong : {{ $area->statistik['jumlah_kamar_kosong'] }} Kamar</p>
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
                                                @currency($area->statistik['estimasi_pendapatan'])
                                            </strong>
                                            <p class="card-text p-0 m-0">Tiap bulan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.area.fasilitas_area.index')

            @include('admin.tipe_kamar.index_table')

        </div>
    </div>
@endsection

