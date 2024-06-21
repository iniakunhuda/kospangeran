<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CarbonDateHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\RiwayatKamarResource;
use App\Models\RiwayatKamar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RiwayatKamarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $riwayat = RiwayatKamar::orderBy('created_at', 'desc');

        if ($request->has('area_id')) {
            $riwayat->where('area_id', $request->area_id);
        }

        if ($request->has('tanggal')) {
            $filter_tanggal = Carbon::parse($request->tanggal / 1000);
            $format = $filter_tanggal->format('Y-m-d');

            $riwayat->whereBetween(
                'tanggal',
                [
                    new Carbon($format . ' ' . '00:00:00'),
                    new Carbon($format . ' ' . '23:59:59')
                ]
            );
        }


        return RiwayatKamarResource::collection($riwayat->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
