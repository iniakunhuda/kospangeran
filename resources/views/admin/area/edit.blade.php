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

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Atur Area Bangunan</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('area.update', $area->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama Area</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="judul" class="form-control" value="{{ $area->judul }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Jenis Area</label>
                                    <div class="col-sm-10">
                                        <select name="jenis" class="form-control">
                                            <option value="Kos-Kosan" {{ $area->jenis == 'Kos-Kosan' ? 'selected' : '' }}>Kos-Kosan</option>
                                            <option value="Kios" {{ $area->jenis == 'Kios' ? 'selected' : '' }}>Kios</option>
                                            <option value="Kontrakan" {{ $area->jenis == 'Kontrakan' ? 'selected' : '' }}>Kontrakan</option>
                                            <option value="Rumah" {{ $area->jenis == 'Rumah' ? 'selected' : '' }}>Rumah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Alamat</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="alamat" class="form-control" value="{{ $area->alamat }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="deskripsi" rows="4" id="comment">{{ $area->deskripsi }}</textarea>
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

