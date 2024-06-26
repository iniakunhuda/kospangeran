@extends('layouts.admin')


@push('styles')
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/select2/css/select2.min.css">
@endpush


@push('scripts')
    <script>
        $(document).ready(function () {
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
                    <ol class="breadcrumb
                    ">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Profil</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Ubah Profil</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('profile.update') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama Lengkap</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ $user->name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="email" class="form-control" placeholder="Email" value="{{ $user->email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-10 col-form-label text-danger">Isi jika ingin mengubah ke password baru</label>
                                    <div class="col-sm-2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Ubah Password Baru</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="password" class="form-control" placeholder="" value="">
                                        <label for="">Minimum 6 karakter</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Konfirmasi Password Baru</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="password_confirmation" class="form-control" placeholder="" value="">
                                        <label for="">Harus sama dengan di atas</label>
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

