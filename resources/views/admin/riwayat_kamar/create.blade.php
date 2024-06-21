@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/select2/css/select2.min.css">
@endpush

@push('scripts')
    <script src="{{asset('admin')}}/vendor/select2/js/select2.full.min.js"></script>
    <script>
        function resetInput() {
            $('#kamar').val([]).trigger('change');
        }

        function kategoriChanged(that) {
            if($(that).val() == 'Berhenti Sewa') {
                $('.berhenti').removeClass('d-none');
            } else {
                $('.berhenti').addClass('d-none');
            }
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
                        area_id: $('#area_bangunan').val(),
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
            var penyewa = data.kamar.penyewa;
            if(penyewa != null) {
                $('input[name="penyewa_nama"]').val(penyewa.nama);
                $('input[name="penyewa_id"]').val(penyewa._id);
            } else {
                $('input[name="penyewa_nama"]').val('');
                $('input[name="penyewa_id"]').val('');
            }
        });
    </script>
@endpush

@section('content')
    <div class="content-body">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('riwayat_kamar.index') }}" class="btn btn-sm btn-outline-light mb-2">
                        <i class="fa fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Atur riwayat kamar</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <form action="{{ route('riwayat_kamar.store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pilih Area</label>
                                    <div class="col-sm-10">
                                        <select id="area_bangunan" class="form-control" name="area_id">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pilih Kamar</label>
                                    <div class="col-sm-10">
                                        <select id="kamar" class="form-control" name="kamar_id">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Penyewa</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="penyewa_nama" readonly value="">
                                        <input type="hidden" class="form-control" name="penyewa_id" readonly value="">
                                        <label class="text-muted opacity-50">Opsional, terisi otomatis jika ada penyewa</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="tanggal" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="deskripsi" rows="4" id="comment"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Kategori</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" onchange="kategoriChanged(this)" name="kategori">
                                            <option name="">Pilih Status</option>
                                            <option name="Berhenti Sewa">Berhenti Sewa</option>
                                            <option name="Pindah">Pindah</option>
                                            <option name="Renovasi">Renovasi</option>
                                            <option name="Transaksi">Transaksi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row berhenti d-none">
                                    <p class="offset-sm-2 col-sm-10 text-muted opacity-50">
                                        Berhenti sewa tidak mengganti status kamar, hanya mencatat histori saja
                                    </p>
                                    <label class="col-sm-2 col-form-label">Catatan Berhenti</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="catatan_berhenti" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row berhenti d-none">
                                    <label class="col-sm-2 col-form-label">Tanggal Berhenti</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="tanggal_berhenti" class="form-control" placeholder="">
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

