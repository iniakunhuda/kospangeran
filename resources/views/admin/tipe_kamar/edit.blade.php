@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/select2/css/select2.min.css">
@endpush

@push('scripts')
    <script src="{{asset('admin')}}/vendor/select2/js/select2.full.min.js"></script>
    <script>
        $('.single-select').select2({})
    </script>
@endpush

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

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Atur Tipe Kamar</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('tipe_kamar.update', $tipe_kamar->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nama" class="form-control" placeholder="" value="{{ $tipe_kamar->nama }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Area</label>
                                    <div class="col-sm-10">
                                        <select name="area_id" class="form-control single-select">
                                            <option value="">Pilih Area</option>
                                            @foreach ($areas as $area)
                                            <option value="{{ $area->id }}" {{ $tipe_kamar->area_id == $area->id ? 'selected' : '' }}>{{ $area->judul }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Kode</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="kode" class="form-control" placeholder="" value="{{ $tipe_kamar->kode }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Harga</label>
                                    <div class="col-sm-10">
                                        <input type="number" min="0" name="harga" class="form-control" placeholder="" value="{{ $tipe_kamar->harga }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <label class="col-sm-2 col-form-label">Fasilitas</label>
                                    <div class="col-sm-10 mb-0">
                                        @foreach((array) $tipe_kamar->fasilitas as $index => $fasilitas)
                                        @if($index == 0)
                                            <input type="text" name="fasilitas[]" class="form-control mb-3" placeholder="" value="{{ $fasilitas }}">
                                        @else
                                            <div class="col-12">
                                                <div class="row mb-3">
                                                    <input type="text" name="fasilitas[]" class="form-control col-md-11" placeholder="" value="{{ $fasilitas }}">
                                                    <div class="text-danger col-md-1 mt-1" onclick="removeFasilitas(this)">
                                                        Hapus
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <div class="fasilitas col-md-12"></div>
                                        <button type="button" class=" btn btn-sm btn-success" onclick="addFasilitas()">
                                            <i class="fa fa-plus"></i> Tambah Fasilitas
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group row mt-5">
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
    <div class="d-none" id="tmpl_fasilitas">
        <div class="row mb-3">
            <input type="text" name="fasilitas[]" class="form-control col-md-11" placeholder="">
            <div class="text-danger col-md-1 mt-1" onclick="removeFasilitas(this)">
                Hapus
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script type="text/javascript">
    function addFasilitas() {
        $('.fasilitas').append($('#tmpl_fasilitas').html());
    }

    function removeFasilitas(el) {
        $(el).parent().remove();
    }
</script>
@endpush
