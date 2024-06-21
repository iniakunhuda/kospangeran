<?php

namespace App\Http\Controllers;

use App\Http\Services\AreaService;
use App\Models\AreaKos;
use App\Models\Kamar;
use App\Models\Penyewa;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['total_area'] = AreaKos::count();
        $data['total_kamar'] = Kamar::count();
        $data['total_penyewa'] = Penyewa::count();

        $data['dataTable_bulanan'] = AreaService::statistikPendapatan('Bulanan');
        $data['dataTable_tahunan'] = AreaService::statistikPendapatan('Tahunan');
        return view('admin.dashboard', $data);
    }
}
