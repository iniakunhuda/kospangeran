@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/select2/css/select2.min.css">
@endpush


@push('scripts')
    <script src="{{asset('admin')}}/vendor/select2/js/select2.full.min.js"></script>
    <script>
        function resetInput() {
            $('#kamar').val([]).trigger('change');
            $('#durasi').val('').trigger('change');
            $('input[name="total_bayar"]').val(0);
        }

        function setValueInput(data) {
            let kamar = data.kamar;
            $('input[name="total_bayar"]').val(kamar.harga);
            $('#durasi').val(kamar.durasi).trigger('change');;
        }

        $('#penyewa').select2({
            placeholder: 'Pilih penyewa',
            ajax: {
                delay: 250,
                url: '{{ route("api.penyewa.fetch") }}',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        type_request: 'select2',
                        is_belum_punya_kamar: true,
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            templateResult: (data) => {
                if(data.penyewa == undefined) {
                    return data.text;
                }

                let tmpl = $('#tmpl_penyewa').clone();
                tmpl.find('img').attr('src', data.penyewa.foto_penyewa_url);
                tmpl.find('.nama_penyewa').text(data.text + ' ' + data.penyewa.nomor_wa);
                return tmpl.html();
            },
            templateSelection: (data) => {
                if(data.penyewa == undefined) {
                    return data.text;
                }
                return data.text + ' ' + data.penyewa.nomor_wa;
            },
            escapeMarkup: function (markup) { return markup; } // let our custom formatter work
        });

        $('#durasi').select2({
            placeholder: 'Pilih durasi',
        });
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

        $("#kamar").select2({
            placeholder: 'Pilih area terlebih dahulu',
            ajax: {
                delay: 250,
                url: '{{ route("api.kamar.fetch") }}',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        type_request: 'select2',
                        search: params.term,
                        is_kosong: true,
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
            },
            templateResult: (data) => {
                if(data.kamar == undefined) {
                    return data.text;
                }
                return data.text + ' - Lantai ' + data.kamar.lantai + ' - Nomor ' + data.kamar.lantai;
            },
            templateSelection: (data) => {
                if(data.kamar == undefined) {
                    return data.text;
                }
                return data.text + ' - Lantai ' + data.kamar.lantai + ' - Nomor ' + data.kamar.lantai;
            },
            escapeMarkup: function (markup) { return markup; } // let our custom formatter work
        }).on('select2:selecting', function(e) {
            let data = e.params.args.data;
            setValueInput(data);
        });
    </script>
@endpush

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            {{-- <div class="row mb-3">
                <div class="col-md-12">
                    <a href="" class="btn btn-sm btn-outline-light mb-2">
                        <i class="fa fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div> --}}

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sewa Kamar Baru</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('sewa.baru.create') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Area Bangunan</label>
                                    <div class="col-sm-10">
                                        <select name="area_id" id="area_bangunan" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Kamar</label>
                                    <div class="col-sm-10">
                                        <select name="kamar_id" id="kamar" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Penyewa</label>
                                    <div class="col-sm-10">
                                        <select name="penyewa_id" id="penyewa" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Durasi Tagihan</label>
                                    <div class="col-sm-10">
                                        <select name="durasi" id="durasi" class="form-control">
                                            <option value="">Pilih Durasi</option>
                                            <option value="Bulanan">Bulanan</option>
                                            <option value="Tahunan">Tahunan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal Bayar</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Total Bayar</label>
                                    <div class="col-sm-10">
                                        <input type="number" min="0" name="total_bayar" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="catatan" rows="4"></textarea>
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

