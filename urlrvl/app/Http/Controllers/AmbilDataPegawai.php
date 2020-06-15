<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\RiwayatPegawai;

class AmbilDataPegawai extends Controller
{
    public function Index(Request $request) {
        $pegawai = DB::select("
            SELECT
                id, nama_pegawai
            FROM
                t_pegawai
            WHERE
                hapus = 'N' AND nama_pegawai LIKE ?
            ORDER BY
                nama_pegawai ASC
        ", ["%" . $request->cari . "%"]);

        return view('riwayat-pegawai.ambil_data_pegawai', [
            'cari' => $request->cari,
            'pegawai' => $pegawai,
            'id_value' => $request->id_value,
            'id_show' => $request->id_show
        ]);
    }

    public function getAllJabatan($id_pegawai) {
        $jabatan = RiwayatPegawai::where('id_pegawai', $id_pegawai)
                    ->orderBy('per_tanggal', 'desc', 'id_riwayat_pegawai', 'desc')
                    ->get();

        return response()->json(["data" => $jabatan]);
    }
}
