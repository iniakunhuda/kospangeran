@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/select2/css/select2.min.css">
@endpush

@php
    $total_tagihan = 0;
    if(request()->penyewa_id) {
        foreach($tagihan as $data) {
            $total_tagihan += $data['total_tagihan'];
        }
    }
@endphp

@push('scripts')
    <script src="{{asset('admin')}}/vendor/select2/js/select2.full.min.js"></script>
    <script>
        $('.single-select').select2({})
        var total_tagihan = {{ $total_tagihan }};

        function changePenyewa(that) {
            let penyewa_id = $(that).val();
            window.location.href = "{{ route('tagihan.bayar.create') }}?penyewa_id=" + penyewa_id
        }

        function changeTotalBayar(that) {
            let total_bayar = $(that).val();
            if(total_bayar > 0) {
                if(total_bayar >= total_tagihan) {
                    $('#status').val('Lunas');
                } else {
                    $('#status').val('Sebagian Lunas');
                }
            }
        }
    </script>
@endpush

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="alert alert-dark">
                <i class="fa fa-info-circle mr-3"></i>
                <strong>Informasi</strong>
                <br><br>
                <ul>
                    <li>Bisa membayar tagihan secara parsial</li>
                    <li>Belum bisa membayar tagihan yang belum ada di list (tagihan bulan depan)</li>
                </ul>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Catat Pembayaran Baru</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('tagihan.bayar.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Penyewa</label>
                                    <div class="col-sm-10">
                                        <select name="penyewa_id" onchange="changePenyewa(this)" class="form-control single-select">
                                            <option value="">Pilih Penyewa</option>
                                            @foreach ($penyewas as $penyewa)
                                                <option value="{{ $penyewa->id }}" {{ request()->penyewa_id == $penyewa->id ? 'selected' : '' }}>{{ $penyewa->nama }} ({{ $penyewa->nomor_wa }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(request()->penyewa_id)
                                <div class="form-group row mb-0">
                                    <div class="offset-sm-2 col-sm-10">
                                        @foreach($tagihan as $data)
                                        <div class="mb-0">
                                            <div class="media mb-3">
                                                <div class="mr-3">
                                                        <img style="height:50px" src="{{ $data['penyewa']['foto_penyewa_url'] }}" class="img-fluid rounded">
                                                </div>
                                                <div class="media-body">
                                                    <span>{{ $data['area']['judul'] }} - {{ $data['kamar']['nama'] }}</span><br>
                                                    <strong class="text-dark">
                                                        {{ $data['penyewa']['nama'] }}
                                                    </strong><br>
                                                    <strong class="text-danger">
                                                        @currency($data['total_tagihan'])
                                                    </strong><br>
                                                </div>
                                            </div>
                                            <ul>
                                                @foreach ($data['tagihan'] as $tagihan)
                                                    <li>
                                                        <span>{{ $tagihan['tanggal_tagihan_dibuat_formatted'] }}</span>
                                                        <span class="text-danger ml-3">
                                                            @currency($tagihan['sisa_tagihan'])
                                                        </span>
                                                    </li>
                                                    <hr>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Area Bangunan</label>
                                    <div class="col-sm-10">
                                        <select name="area_id" class="form-control single-select">
                                            <option value="">Pilih Area</option>
                                            @foreach($areas as $area)
                                                <option value="{{ $area['id'] }}">{{ $area['judul'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Kamar</label>
                                    <div class="col-sm-10">
                                        <select name="kamar_id" class="form-control single-select">
                                            <option value="">Pilih Kamar</option>
                                            @foreach($kamars as $kamar)
                                                <option value="{{ $kamar['id'] }}">{{ $kamar['option_label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Jenis Tagihan</label>
                                    <div class="col-sm-10">
                                        <select name="durasi" class="form-control single-select">
                                            <option value="Bulanan">Bulanan</option>
                                            <option value="Tahunan">Tahunan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Rekening</label>
                                    <div class="col-sm-10">
                                        <select name="rekening_id" class="form-control single-select">
                                            <option value="">Pilih Rekening</option>
                                            @foreach ($rekenings as $rek)
                                                <option value="{{ $rek->id }}">{{ $rek->nama_pembayaran }} - ({{ $rek->nomor_rekening }} a/n {{ $rek->nama_rekening }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Total Bayar</label>
                                    <div class="col-sm-10">
                                        <input type="number" onchange="changeTotalBayar(this)" min="0" name="total_bayar" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal Bayar</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" placeholder="" class="form-control">
                                        <label class="text-muted opacity-50">Bukan tanggal tagihan</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="deskripsi" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="status" placeholder="" class="form-control" id="status" readonly>
                                        <label class="text-muted opacity-50">Tidak bisa diedit. Menyesuaikan dengan total bayar</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Bukti Pembayaran</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <input type="file" target-preview="imagePreview1" name="bukti_pembayaran_upload" class="image-upload form-control" placeholder="">
                                                <img src="" class="img-fluid mt-2" style="max-height: 200px;" id="imagePreview1" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-md btn-primary">
                                            <i class="fa fa-save mr-2"></i>
                                            Simpan
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

