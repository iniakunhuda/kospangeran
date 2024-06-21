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

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Atur Penyewa</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('penyewa.update', $penyewa->id) }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nama" class="form-control" placeholder="" value="{{ $penyewa->nama }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nomor WA</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nomor_wa" class="form-control" placeholder="" value="{{ $penyewa->nomor_wa }}">
                                        <label class="text-muted opacity-50">Harus diawali dengan +62 bukan 0 (Contoh: +6281213112)</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal Masuk</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="tanggal_masuk" class="form-control" placeholder="" value="{{ $penyewa->tanggal_masuk }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pekerjaan</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="pekerjaan" class="form-control" placeholder="" value="{{ $penyewa->pekerjaan }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="deskripsi" rows="4">{{ $penyewa->deskripsi }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Foto</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <label for="">Foto Penyewa</label>
                                                <input type="file" target-preview="imagePreview1" name="foto_penyewa_upload" class="image-upload form-control" placeholder="">
                                                <img src="{{ $penyewa->foto_penyewa_url }}" class="img-fluid mt-2" style="max-height: 200px;" id="imagePreview1" alt="">
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="">Foto KTP</label>
                                                <input type="file" target-preview="imagePreview2" name="foto_ktp_upload" class="image-upload form-control" placeholder="">
                                                <img src="{{ $penyewa->foto_ktp_url }}" class="img-fluid mt-2" style="max-height: 200px;" id="imagePreview2" alt="">
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

