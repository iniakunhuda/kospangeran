@extends('layouts.admin')


@push('styles')
    <link rel="stylesheet" href="{{ asset('admin') }}/vendor/select2/css/select2.min.css">
@endpush


@push('scripts')
    <script>
        $(document).ready(function() {});
    </script>

    <script src="{{ asset('admin') }}/vendor/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('admin') }}/js/plugins-init/select2-init.js"></script>
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
                    <ol class="breadcrumb
                    ">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Generate Tagihan</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Buat tagihan otomatis</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('tagihan.generate.run') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pilih Bulan</label>
                                    <div class="col-sm-10">
                                        <select name="month" class="form-control">
                                            <option value="">Pilih Bulan</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pilih Tahun</label>
                                    <div class="col-sm-10">
                                        <select name="year" class="form-control">
                                            <option value="">Pilih Tahun</option>
                                            @php
                                                $year = date('Y');
                                                $year_next = $year + 5;
                                                $year_prev = $year - 2;
                                            @endphp
                                            @for ($i = $year_prev; $i <= $year_next; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tipe Pembayaran</label>
                                    <div class="col-sm-10">
                                        <select name="tipe_bayar" class="form-control">
                                            <option value="Bulanan">Bulanan</option>
                                            <option value="Tahunan">Tahunan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-md btn-primary">
                                            <i class="fa fa-save mr-2"></i>
                                            Generate Tagihan
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
@endsection
