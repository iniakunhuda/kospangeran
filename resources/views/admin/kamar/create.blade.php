@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/select2/css/select2.min.css">
@endpush


@push('scripts')
    <script src="{{asset('admin')}}/vendor/select2/js/select2.full.min.js"></script>
    <script>
        function resetInput() {
            $('#tipe_kamar').val([]).trigger('change');
            $('input[name="nama"]').val('');
            $('input[name="harga"]').val('');
            $('.fasilitas').html('');
            $('.fasilitas1').val('');
        }

        function setValueInput(tipeKamar) {
            $('input[name="nama"]').val(tipeKamar.nama);
            $('input[name="harga"]').val(tipeKamar.harga);
            tipeKamar.fasilitas.forEach((value, index) => addFasilitas(value, index));
        }

        function addFasilitas(value, index) {
            if(index == 0) {
                $('.fasilitas').html('');
                $('.fasilitas1').val(value);
                return;
            }

            let htmlFormat = $('#tmpl_fasilitas').find('.row').clone();
            htmlFormat.find('input').val(value);
            $('.fasilitas').append(htmlFormat);
        }

        function removeFasilitas(el) {
            $(el).parent().remove();
        }

        $("#area_bangunan").select2({
            placeholder: 'Pilih area',
            ajax: {
                delay: 250,
                url: '{{ route("api.area.fetch") }}',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true,
            }
        }).on('select2:selecting', function(e) {
            resetInput();
        });

        $("#tipe_kamar").select2({
            placeholder: 'Pilih area terlebih dahulu',
            ajax: {
                delay: 250,
                url: '{{ route("api.tipe_kamar.fetch") }}',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term,
                        area_id: $('#area_bangunan').val()
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true,
            }
        }).on('select2:selecting', function(e) {
            let data = e.params.args.data;
            setValueInput(data);
        });
    </script>
@endpush

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('kamar.index') }}" class="btn btn-sm btn-outline-light mb-2">
                        <i class="fa fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Atur Kamar</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('kamar.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Area Bangunan</label>
                                    <div class="col-sm-10">
                                        <select name="area_id" id="area_bangunan" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tipe Kamar</label>
                                    <div class="col-sm-10">
                                        <select name="tipe_kamar_id" id="tipe_kamar" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama Kamar</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nama" class="form-control" placeholder="" value="{{ old('nama') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nomor Kamar</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nomor" class="form-control" placeholder="misal: 20" value="{{ old('nomor') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Lantai berapa</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="lantai" class="form-control" placeholder="misal: 1" value="{{ old('lantai') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Harga Kamar</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="harga" class="form-control" placeholder="" value="{{ old('harga') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Fasilitas</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="fasilitas[]" class="fasilitas1 form-control" placeholder="">
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
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Durasi Sewa</label>
                                    <div class="col-sm-10">
                                        <select name="durasi" class="form-control">
                                            <option value="Bulanan">Bulanan</option>
                                            <option value="Tahunan">Tahunan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="deskripsi" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Foto Kamar</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <label for="">Foto 1</label>
                                                <input type="file" target-preview="imagePreview1" name="image[]" class="image-upload form-control" placeholder="">
                                                <img src="" class="img-fluid mt-2" style="max-height: 200px;" id="imagePreview1" alt="">
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="">Foto 2</label>
                                                <input type="file" target-preview="imagePreview2" name="image[]" class="image-upload form-control" placeholder="">
                                                <img src="" class="img-fluid mt-2" style="max-height: 200px;" id="imagePreview2" alt="">
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

    <div class="d-none" id="tmpl_fasilitas">
        <div class="row mb-3">
            <input type="text" name="fasilitas[]" class="form-control col-md-11" placeholder="">
            <div class="text-danger col-md-1 mt-1" onclick="removeFasilitas(this)">
                Hapus
            </div>
        </div>
    </div>
@endsection

