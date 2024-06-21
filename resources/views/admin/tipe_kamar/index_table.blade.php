
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Tipe Kamar</h4>
                <a href="{{ route('tipe_kamar.create') }}" class="btn btn-sm btn-primary mb-2">
                    <i class="fa fa-plus mr-2"></i>
                    Tambah
                </a>
            </div>
            <div class="card-body">
                @if(isset($show_alert) && $show_alert)
                    @include('layouts.components.alert')
                @endif

                <div class="table-responsive">
                    <table id="tbl_tipe_kamar" class="dataTable table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Fasilitas</th>
                                <th>Total Kamar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataTable_tipe_kamar as $tipe)
                                <tr>
                                    <td>{{ $tipe->nama }} <br> {{ $tipe->kode }}</td>
                                    <td class="text-right">@currency($tipe->harga)</td>
                                    <td>{{ implode(', ', $tipe->fasilitas) }}</td>
                                    <td>{{ $tipe->kamar->count() }}</td>
                                    <td>
                                        <!-- TODO: Show tipe kamar -->
                                        <a href="{{ route('tipe_kamar.show', $tipe->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tipe_kamar.edit', $tipe->id) }}" class="btn btn-sm btn-light">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if($tipe->is_allow_delete)
                                        <form action="{{ route('tipe_kamar.destroy', $tipe->id) }}" method="POST" class="d-inline">
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

@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
   $('#tbl_tipe_kamar').DataTable({});
 });
</script>
@endpush
