@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.css">
@endpush

@section('title')
    {{ $pageTitle }}
@endsection


@push('scripts')
    <script src="{{asset('admin')}}/vendor/select2/js/select2.full.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function () {
        $('#tbl_list').DataTable({
            layout: {
                topStart: {
//                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    buttons: [
                        {
                            'extend': 'copyHtml5',
                            exportOptions: { columns: ':visible:not(:last-child)' }
                        },
                        {
                            'extend': 'excelHtml5',
                            exportOptions: {
                                columns: ':visible:not(:last-child)',
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br\s*\/?>/ig, "\r\n");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            'extend': 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            exportOptions: {
                                columns: ':visible:not(:last-child)',
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br\s*\/?>/ig, "\r\n");
                                        }
                                        return data;
                                    }
                                }
                            },
                            customize: function (doc) {
                                doc.content[1].table.widths =Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                                doc.defaultStyle.alignment = 'center';
                                doc.styles.tableHeader.alignment = 'center';
                            }
                        }
                    ]
                }
            }
        });
        $('.single-select').select2({})
    });
    </script>
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
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Pembayaran</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Riwayat Pembayaran</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne"  data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <h5 class="mb-3">
                                    Filter
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form action="" method="GET">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Area</label>
                                            <div class="col-sm-10">
                                                <select name="area_id" class="form-control single-select">
                                                    <option value="">Semua Area</option>
                                                    @foreach($filter_areas as $area)
                                                        <option value="{{ $area->id }}" {{ request()->area_id == $area->id ? 'selected' : '' }}>{{ $area->judul }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Kamar</label>
                                            <div class="col-sm-10">
                                                <select name="kamar_id" class="form-control single-select">
                                                    <option value="">Semua Kamar</option>
                                                    @foreach($filter_kamars as $kamar)
                                                        <option value="{{ $kamar->id }}" {{ request()->kamar_id == $kamar->id ? 'selected' : '' }}>{{ $kamar->option_label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Penyewa</label>
                                            <div class="col-sm-10">
                                                <select name="penyewa_id" class="form-control single-select">
                                                    <option value="">Semua Penyewa</option>
                                                    @foreach($filter_penyewas as $penyewa)
                                                        <option value="{{ $penyewa->id }}" {{ request()->penyewa_id == $penyewa->id ? 'selected' : '' }}>{{ $penyewa->nama }} ({{ $penyewa->nomor_wa }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Tanggal</label>
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label class="opacity-50">Tanggal Awal</label>
                                                        <input type="date" name="tgl_awal" class="form-control" value="{{ request()->tgl_awal }}">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="opacity-50">Tanggal Akhir</label>
                                                        <input type="date" name="tgl_akhir" class="form-control" value="{{ request()->tgl_akhir }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-md btn-primary">
                                                    <i class="fa fa-search mr-2"></i>
                                                    Filter
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


            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Riwayat Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <div class="table-responsive">
                                <table id="tbl_list" class="dataTable table" cellspacing="0" width="100%">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Penyewa</th>
                                            <th>Area</th>
                                            <th>Kamar</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Rekening</th>
                                            <th>Total Bayar</th>
                                            <th>Durasi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataTable as $key => $item)
                                            <tr>
                                                <td class="text-center">{{ $key+1 }}</td>
                                                <td>{{ $item->penyewa->nama }}</td>
                                                <td>
                                                    {{ $item->area->judul }}
                                                </td>
                                                <td>
                                                    {{ $item->kamar->nama }}
                                                </td>
                                                <td>{{ $item->tanggal_bayar->format('d M Y') }}</td>
                                                <td>{{ $item->rekening->nama_pembayaran }} <br>{{ $item->rekening->nama_rekening }} <br>{{ $item->rekening->nomor_rekening }}
                                                </td>
                                                <td>@currency($item->total_bayar)</td>
                                                <td>{{ $item->durasi }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('tagihan.riwayat.show', $item->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
