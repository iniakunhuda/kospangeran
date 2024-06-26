@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin') }}/vendor/select2/css/select2.min.css">
@endpush


@push('scripts')
    <script src="{{ asset('admin') }}/vendor/select2/js/select2.full.min.js"></script>
    <script>
        $('.single-select').select2({})
    </script>
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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Pembayaran</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Belum Bayar</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <a href="{{ route('tagihan.generate.index') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-3"></i> Buat Tagihan Otomatis
                    </a>
                    <br><br>
                </div>
            </div>

            {{-- <div class="alert alert-dark">
                <i class="fa fa-info-circle mr-3"></i>
                <strong>Informasi</strong><br><br>
                <ul>
                    <li><strong>Tagihan dibuat otomatis oleh sistem ketika</strong></li>
                    <li>Tiap tanggal 1 pada bulan berjalan</li>
                    <li>Penyewa sewa kamar baru / pindah kamar</li>
                </ul>
            </div> --}}

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                <h5 class="mb-3">
                                    Filter
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordion">
                                <div class="card-body">
                                    <form action="" method="GET">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Area</label>
                                            <div class="col-sm-10">
                                                <select name="area_id" class="form-control single-select">
                                                    <option value="">Semua Area</option>
                                                    @foreach ($filter_areas as $area)
                                                        <option value="{{ $area->id }}"
                                                            {{ request()->area_id == $area->id ? 'selected' : '' }}>
                                                            {{ $area->judul }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Kamar</label>
                                            <div class="col-sm-10">
                                                <select name="kamar_id" class="form-control single-select">
                                                    <option value="">Semua Kamar</option>
                                                    @foreach ($filter_kamars as $kamar)
                                                        <option value="{{ $kamar->id }}"
                                                            {{ request()->kamar_id == $kamar->id ? 'selected' : '' }}>
                                                            {{ $kamar->option_label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Penyewa</label>
                                            <div class="col-sm-10">
                                                <select name="penyewa_id" class="form-control single-select">
                                                    <option value="">Semua Penyewa</option>
                                                    @foreach ($filter_penyewas as $penyewa)
                                                        <option value="{{ $penyewa->id }}"
                                                            {{ request()->penyewa_id == $penyewa->id ? 'selected' : '' }}>
                                                            {{ $penyewa->nama }} ({{ $penyewa->nomor_wa }})</option>
                                                    @endforeach
                                                </select>
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
                        <div class="card-header">
                            <h4>Belum Bayar / Tagihan</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <div class="table-responsive">
                                @foreach ($dataTable as $data)
                                    <div class="mb-4">
                                        <div class="media mb-3">
                                            <div class="mr-3">
                                                <img style="height:100px" src="{{ $data['penyewa']['foto_penyewa_url'] }}"
                                                    class="img-fluid rounded">
                                            </div>
                                            <div class="media-body">
                                                <span>{{ $data['area']['judul'] }} -
                                                    {{ $data['kamar']['nama'] }}</span><br>
                                                <h4 class="text-dark">
                                                    <a href="{{ route('penyewa.show', $data['penyewa']['_id']) }}"
                                                        class="text-dark">
                                                        {{ $data['penyewa']['nama'] }}
                                                    </a>
                                                </h4>
                                                <h4 class="text-danger">
                                                    @currency($data['total_tagihan'])
                                                </h4><br>
                                            </div>
                                        </div>
                                        <table class="table">

                                            @foreach ($data['tagihan'] as $tagihan)
                                                <tr>
                                                    <td style="width: 40%">
                                                        <span class="text-dark">
                                                            {{ $tagihan['tanggal_tagihan_dibuat_formatted'] }}
                                                        </span>
                                                    </td>
                                                    <td style="width: 80%">
                                                        <span class="text-dark ml-3">
                                                            @currency($tagihan['sisa_tagihan'])
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <form
                                                            action="{{ route('tagihan.belumbayar.destroy', $tagihan['_id']) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="d-none">
        <div id="tmpl_penyewa">
            <div class="media">
                <div class="mr-3">
                    <img style="height:50px" src="" class="img-fluid rounded">
                </div>
                <div class="media-body">
                    <strong class="nama_penyewa"></strong><br>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {});
    </script>
@endpush
