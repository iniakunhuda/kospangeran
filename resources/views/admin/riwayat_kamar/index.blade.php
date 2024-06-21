@extends('layouts.admin')


@push('styles')
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/select2/css/select2.min.css">
@endpush


@push('scripts')
    <script>
        $(document).ready(function () {
            $('#tbl_riwayat').DataTable({
                "ordering": false
            });
            $('.collapse').collapse();
        });
    </script>

    <script src="{{asset('admin')}}/vendor/select2/js/select2.full.min.js"></script>
    <script src="{{asset('admin')}}/js/plugins-init/select2-init.js"></script>
@endpush


@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="row page-titles mx-0">
                <div class="col-md-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Selamat Datang, {{ Auth::user()->name }} !</h4>
                        <p class="mb-0">Sistem Management Kos Pangeran</p>
                    </div>
                </div>
                <div class="col-md-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-none d-md-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Manajemen Kos</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Manajemen Kamar</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Riwayat Kamar</a></li>
                    </ol>
                </div>
            </div>


            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne"  data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <h5 class="mb-3">
                                    Filter
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form action="" method="GET">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Area</label>
                                            <div class="col-sm-10">
                                                <select name="area_id" class="form-control single-select">
                                                    <option value="">Semua Area</option>
                                                    @foreach($filter_areas as $area)
                                                        <option value="{{ $area->id }}" {{ request()->area_id == $area->id ? 'selected' : '' }}>{{ $area->judul }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Kamar</label>
                                            <div class="col-sm-10">
                                                <select name="kamar_id" class="form-control single-select">
                                                    <option value="">Semua Kamar</option>
                                                    @foreach($filter_kamars as $kamar)
                                                        <option value="{{ $kamar->id }}" {{ request()->kamar_id == $kamar->id ? 'selected' : '' }}>{{ $kamar->option_label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Penyewa</label>
                                            <div class="col-sm-10">
                                                <select name="penyewa_id" class="form-control single-select">
                                                    <option value="">Semua Penyewa</option>
                                                    @foreach($filter_penyewas as $penyewa)
                                                        <option value="{{ $penyewa->id }}" {{ request()->penyewa_id == $penyewa->id ? 'selected' : '' }}>{{ $penyewa->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Tanggal</label>
                                            <div class="col-sm-10">
                                                <input type="date" name="tanggal" class="form-control" value="{{ request()->tanggal }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-md btn-primary">
                                                    <i class="fa fa-search mr-2"></i>
                                                    Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header mb-0">
                            <h4>Riwayat Kamar</h4>
                            <a href="{{ route('riwayat_kamar.create') }}" class="btn btn-sm btn-primary mb-2">
                                <i class="fa fa-plus mr-2"></i>
                                Tambah
                            </a>
                        </div>
                        <div class="card-body pt-0">

                            <div class="alert alert-dark">
                                <i class="fa fa-info-circle mr-3"></i>
                                Riwayat kamar tidak bisa dihapus / diedit
                            </div>

                            @include('layouts.components.alert')
                            <div class="table-responsive">
                                <table id="tbl_riwayat" class="dataTable table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Penyewa</th>
                                            <th>Deskripsi</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataTable_riwayat as $riwayat)
                                            <tr>
                                                <td class="d-md">
                                                    {{ $riwayat->kamar->nama }}
                                                    <br>
                                                    {{ $riwayat->area->judul }}
                                                </td>
                                                <td>
                                                    @if(isset($riwayat->penyewa))
                                                    <a target="_blank" class="text-primary" href="{{ $riwayat->penyewa->link_wa }}">
                                                        {{ $riwayat->penyewa->nama }}
                                                    </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $riwayat->deskripsi }}
                                                </td>
                                                <td>
                                                    {{ $riwayat->tanggal->format('d M Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
