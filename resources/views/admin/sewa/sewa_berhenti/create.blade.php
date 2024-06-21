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

        $('#penyewa').select2();

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

            <div class="alert alert-dark">
                <i class="fa fa-info-circle mr-3"></i>
                Berhenti sewa tidak menghapus tagihan kamar lama
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kamar lama</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Area</label>
                                <div class="col-sm-10">
                                    <strong>{{ $sewa->kamar->area->judul }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Nama Kamar</label>
                                <div class="col-sm-10">
                                    <strong>{{ $sewa->kamar->nama }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Tipe Kamar</label>
                                <div class="col-sm-10">
                                    <strong>{{ $sewa->kamar->tipe_kamar->nama }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Nomor</label>
                                <div class="col-sm-10">
                                    <strong>{{ $sewa->kamar->nomor }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Lantai</label>
                                <div class="col-sm-10">
                                    <strong>{{ $sewa->kamar->lantai }}</strong>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label pt-0">Harga</label>
                                <div class="col-sm-10">
                                    <strong>@currency($sewa->total_bayar) / {{ $sewa->durasi_format }}</strong>
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
                            <h4>Konfirmasi berhenti sewa</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('sewa.berhenti.create', $sewa->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="kamar_id" value="{{ $sewa->kamar_id }}">
                                <input type="hidden" name="area_id" value="{{ $sewa->area_id }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Penyewa</label>
                                    <div class="col-sm-10">
                                        <select id="penyewa" name="penyewa_id" class="form-control">
                                            <option value="{{ $sewa->penyewa->id }}" selected>{{ $sewa->penyewa->nama }} {{ $sewa->penyewa->nomor_wa }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal Berhenti</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="tanggal_berhenti" value="{{ date('Y-m-d') }}" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Alasan Berhenti</label>
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

