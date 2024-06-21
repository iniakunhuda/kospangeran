<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Fasilitas Area</h4>
                <a href="{{ route('fasilitas_area.create', $area->id) }}" class="btn btn-sm btn-primary mb-2">
                    <i class="fa fa-plus mr-2"></i>
                    Tambah
                </a>
            </div>
            <div class="card-body">
                <table id="tbl_fasilitas_area" class="dataTable table" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fasilitas</th>
                            <th>Catatan</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataTable_fasilitas as $item)
                            <tr>
                                <td>{{ $item['jumlah'] }} {{ $item['nama'] }}</td>
                                <td>{{ $item['catatan'] }}</td>
                                <td>
                                    <a href="{{ route('fasilitas_area.edit', [
                                        'area_id' => $area->id,
                                        'fasilitas_id' => $item['id']
                                    ]) }}" class="btn btn-sm btn-light">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('fasilitas_area.destroy', [
                                        'area_id' => $area->id,
                                        'fasilitas_id' => $item['id']
                                    ]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
   $('#tbl_fasilitas_area').DataTable({
        "searching": false,
        "paging":   false,
        "ordering": false,
        "info":     false
   });
 });
</script>
@endpush
