<div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header mb-4">
                        <h4>Riwayat Penyewa</h4>
                    </div>
                    <div class="card-body pt-0">

                        @include('layouts.components.alert')
                        <div class="table-responsive">
                            <table id="tbl_riwayat" class="dataTable table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Kamar</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataTable_riwayat as $riwayat)
                                        <tr>
                                            <td class="d-md">
                                                {{ $riwayat->kamar->nama }}
                                                <br>
                                                {{ $riwayat->area->judul }}
                                            </td>
                                            <td>
                                                {{ $riwayat->deskripsi }}
                                                @if($riwayat->kategori == "Berhenti Sewa")
                                                <br><span class="opacity-50">Tanggal Berhenti: {{ $riwayat->tanggal_berhenti->format('d M Y') }}</span>
                                                <br><span class="text-danger">Catatan: {{ $riwayat->catatan_berhenti }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $riwayat->tanggal->format('d M Y') }}
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
