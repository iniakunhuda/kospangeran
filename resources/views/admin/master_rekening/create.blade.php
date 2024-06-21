@extends('layouts.admin')

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('master.rekening.index') }}" class="btn btn-sm btn-outline-light mb-2">
                        <i class="fa fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Atur Rekening</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('master.rekening.store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama Pembayaran</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nama_pembayaran" class="form-control" placeholder="misal: Bank BCA">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama Rekening</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nama_rekening" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nomor Rekening</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nomor_rekening" class="form-control" placeholder="">
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

