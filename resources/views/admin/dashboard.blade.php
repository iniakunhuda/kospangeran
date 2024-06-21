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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Dashboard</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-map text-success border-success"></i>
                            </div>
                            <div class="stat-content d-inline-block">
                                <div class="stat-text">Total Area</div>
                                <div class="stat-digit">{{ $total_area }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-layout-grid2 text-pink border-pink"></i>
                            </div>
                            <div class="stat-content d-inline-block">
                                <div class="stat-text">Total Kamar</div>
                                <div class="stat-digit">{{ $total_kamar }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-user text-primary border-primary"></i>
                            </div>
                            <div class="stat-content d-inline-block">
                                <div class="stat-text">Total Penyewa</div>
                                <div class="stat-digit">{{ $total_penyewa }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $total_pendapatan_bulanan = 0;
                $total_pendapatan_tahunan = 0;
                foreach ($dataTable_bulanan as $item) {
                    if($item['jumlah'] == 0) continue;
                    $total_pendapatan_bulanan += $item['pendapatan'];
                }
                foreach ($dataTable_tahunan as $item) {
                    if($item['jumlah'] == 0) continue;
                    $total_pendapatan_tahunan += $item['pendapatan'];
                }
            @endphp

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Estimasi Pendapatan / bulan</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <div class="table-responsive">
                                <table id="tbl_list" class="dataTable table" cellspacing="0" width="100%">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Area</th>
                                            <th>Tipe Kamar</th>
                                            <th class="text-center">Jumlah Kamar</th>
                                            <th class="text-center">Kosong</th>
                                            <th class="text-center">Terisi</th>
                                            <th class="text-right">Pendapatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-right"><strong>Total Pendapatan</strong></td>
                                            <td class="text-right">
                                                <h3 class="text-primary">@currency($total_pendapatan_bulanan)</h3>
                                            </td>
                                        </tr>
                                        @foreach ($dataTable_bulanan as $item)
                                            @if($item['jumlah'] == 0) @continue @endif
                                            <tr>
                                                <td>
                                                    <a class="text-primary" href="{{ route('kamar.index') }}?area_id={{ $item['area_id'] }}">
                                                        {{ $item['area'] }}
                                                    </a>
                                                </td>
                                                <td>{{ $item['tipe'] }}</td>
                                                <td class="text-center">{{ $item['jumlah'] }}</td>
                                                <td class="text-center">{{ $item['kosong'] }}</td>
                                                <td class="text-center">{{ $item['terisi'] }}</td>
                                                <td class="text-right">@currency($item['pendapatan'])</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


             <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Estimasi Pendapatan / tahun</h4>
                        </div>
                        <div class="card-body">
                            @include('layouts.components.alert')

                            <div class="table-responsive">
                                <table id="tbl_list" class="dataTable table" cellspacing="0" width="100%">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Area</th>
                                            <th>Tipe Kamar</th>
                                            <th class="text-center">Jumlah Kamar</th>
                                            <th class="text-center">Kosong</th>
                                            <th class="text-center">Terisi</th>
                                            <th class="text-right">Pendapatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-right"><strong>Total Pendapatan</strong></td>
                                            <td class="text-right">
                                                <h3 class="text-primary">@currency($total_pendapatan_tahunan)</h3>
                                            </td>
                                        </tr>
                                        @foreach ($dataTable_tahunan as $item)
                                            @if($item['jumlah'] == 0) @continue @endif
                                            <tr>
                                                <td>
                                                    <a class="text-primary" href="{{ route('kamar.index') }}?area_id={{ $item['area_id'] }}">
                                                        {{ $item['area'] }}
                                                    </a>
                                                </td>
                                                <td>{{ $item['tipe'] }}</td>
                                                <td class="text-center">{{ $item['jumlah'] }}</td>
                                                <td class="text-center">{{ $item['kosong'] }}</td>
                                                <td class="text-center">{{ $item['terisi'] }}</td>
                                                <td class="text-right">@currency($item['pendapatan'])</td>
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
