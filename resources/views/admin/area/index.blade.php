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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Manajemen Kos</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Area Bangunan</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Area Bangunan</h4>
                            <a href="{{ route('area.create') }}" class="btn btn-sm btn-primary mb-2">
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
                                            <th>Judul</th>
                                            <th>Jenis</th>
                                            <th>Deskripsi</th>
                                            <th>Alamat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataTable as $area)
                                            <tr>
                                                <td>{{ $area->judul }}</td>
                                                <td>{{ $area->jenis }}</td>
                                                <td>{{ $area->deskripsi }}</td>
                                                <td>{{ $area->alamat }}</td>
                                                <td>
                                                    <a href="{{ route('area.show', $area->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('area.edit', $area->id) }}" class="btn btn-sm btn-light">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @if($area->is_allow_delete)
                                                    <form action="{{ route('area.destroy', $area->id) }}" method="POST" class="d-inline">
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
   $('#tbl_list').DataTable({});
 });
</script>
@endpush
