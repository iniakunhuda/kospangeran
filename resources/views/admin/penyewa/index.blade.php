@extends('layouts.admin')

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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Penyewa</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Semua Penyewa</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Penyewa</h4>
                            <a href="{{ route('penyewa.create') }}" class="btn btn-sm btn-primary mb-2">
                                <i class="fa fa-plus mr-2"></i>
                                Tambah Data
                            </a>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <div class="table-responsive">
                                <table id="tbl_list" class="dataTable table" cellspacing="0" width="100%">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Foto</th>
                                            <th>Nama</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Pekerjaan</th>
                                            <th width="150px">Deskripsi</th>
                                            <th>Kamar</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataTable as $item)
                                            <tr>
                                                <td>
                                                    <img src="{{ $item->foto_penyewa_url }}" class="img-fluid" style="height: 100px" alt="">
                                                </td>
                                                <td>
                                                    {{ $item->nama }} <br>
                                                    <a href="{{ $item->link_wa }}" class="text-success">Whatsapp</a>
                                                </td>
                                                <td>{{ $item->format_tanggal_masuk }}</td>
                                                <td>{{ $item->pekerjaan }}</td>
                                                <td>{{ $item->deskripsi }}</td>

                                                @if($item->kamar != null)
                                                <td>
                                                    {{ $item->kamar->nama ?? '' }} <br>
                                                    {{ $item->kamar->area->judul ?? '' }}
                                                </td>
                                                @else
                                                <td class="text-danger">
                                                    Belum ada kamar
                                                </td>
                                                @endif
                                                <td>
                                                    <a href="{{ route('penyewa.show', $item->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('penyewa.edit', $item->id) }}" class="btn btn-sm btn-light">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @if($item->is_allow_delete)
                                                    <form action="{{ route('penyewa.destroy', $item->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endif
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

@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
   $('#tbl_list').DataTable({
       "order": [[ 1, "asc" ]]
   });
 });
</script>
@endpush
