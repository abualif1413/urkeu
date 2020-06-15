<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataRekanan;

class DataRekananController extends Controller
{
    public function index() {
        $rekanan = DataRekanan::orderBy('nama_perusahaan', 'ASC')->get();

        return view('data-rekanan.data-rekanan', [
            'rekanan'       => $rekanan
        ]);
    }

    public function submit(Request $request) {
        if($request->submit_type == "add") {
            $data_rekanan = new DataRekanan;
            $data_rekanan->nama_perusahaan = $request->nama_perusahaan;
            $data_rekanan->pic = $request->pic;
            $data_rekanan->alamat = $request->alamat;
            $data_rekanan->email = $request->email;
            $data_rekanan->no_kontak = $request->no_kontak;
            $data_rekanan->save();

            return Redirect('/DataRekanan');
        } elseif($request->submit_type == "update") {
            $data_rekanan = DataRekanan::find($request->id_data_rekanan);
            $data_rekanan->nama_perusahaan = $request->nama_perusahaan;
            $data_rekanan->pic = $request->pic;
            $data_rekanan->alamat = $request->alamat;
            $data_rekanan->email = $request->email;
            $data_rekanan->no_kontak = $request->no_kontak;
            $data_rekanan->save();

            return Redirect('/DataRekanan');
        }
    }

    public function goEdit(Request $request) {
        $data_rekanan = DataRekanan::find($request->id_data_rekanan);

        return response(json_encode($data_rekanan))
                ->header('Content-Type', 'application/json');
    }

    public function goDelete($id_data_rekanan) {
        $data_rekanan = DataRekanan::find($id_data_rekanan);
        $data_rekanan->delete();

        return Redirect('/DataRekanan');
    }
}
