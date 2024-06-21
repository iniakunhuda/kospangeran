
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Semua Kamar</h4>
                <a href="{{ route('kamar.create') }}" class="btn btn-sm btn-primary mb-2">
                    <i class="fa fa-plus mr-2"></i>
                    Tambah
                </a>
            </div>
            <div class="card-body">
                @if(isset($show_alert) && $show_alert)
                    @include('layouts.components.alert')
                @endif

                <div class="table-responsive">
                    <table id="tbl_kamar" class="dataTable table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Area</th>
                                <th>Tipe Kamar</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataTable_kamar as $kamar)
                                <tr>
                                    <td class="d-md-flex">
                                        <img src="{{ count($kamar->foto_url) > 0 ? $kamar->foto_url[0] : 'https://via.placeholder.com/200x200?text=Kamar' }}" alt="" class="img-fluid mr-3" style="width: 100px;">
                                        <div class="d-md-flex justify-content-center align-items-center">
                                            {{ $kamar->nama }}
                                            <br>
                                            LT {{ $kamar->lantai }}
                                            NO {{ $kamar->nomor }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ $kamar->area->judul }}
                                    </td>
                                    <td>
                                        {{ $kamar->tipe_kamar->nama }}
                                    </td>
                                    <td class="text-right">@currency($kamar->harga)</td>
                                    <td class="text-center">
                                        @if($kamar->status == \App\Models\Kamar::STATUS_KOSONG)
                                            <span class="text-danger">Kosong</span>
                                        @else
                                            <span class="text-success">Terisi</span>
                                        @endif
                                    </td>
                                    <td style="width:15%" class="text-right">
                                        <a href="{{ route('kamar.show', $kamar->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('kamar.edit', $kamar->id) }}" class="btn btn-sm btn-light">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if($kamar->is_allow_delete)
                                        <form action="{{ route('kamar.destroy', $kamar->id) }}" method="POST" class="d-inline">
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
   $('#tbl_kamar').DataTable({
    order: [[4, 'asc']]
   });
 });
</script>
@endpush
