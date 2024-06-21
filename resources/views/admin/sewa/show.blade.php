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

            @include('layouts.components.alert')

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-block">
                            <h4 class="mb-0">Detail Rekening</h4>
                            {{-- <p class="m-0 p-0 subtitle opacity-50"></p> --}}
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="dataTable table-responsive">
                                <tbody>
                                    <tr>
                                        <th width="20%">Nama Pembayaran</th>
                                        <td>{{ $rekening->nama_pembayaran }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Rekening</th>
                                        <td>{{ $rekening->nama_rekening }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nomor Rekening</th>
                                        <td>{{ $rekening->nomor_rekening }}</td>
                                    </tr>
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

