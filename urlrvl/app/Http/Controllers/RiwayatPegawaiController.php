<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\JenisPegawai;
use App\Golongan;
use App\Pangkat;
use App\Agama;
use App\RiwayatPegawai;

class RiwayatPegawaiController extends Controller
{
    private $sql_riwayat = "";
    private $sql_riwayat_raw = "";


    public function __construct() {
        $this->sql_riwayat = "
            SELECT
                peg.id, his.id_riwayat_pegawai, peg.nama_pegawai, his.nik, his.gapok,
                jen.jenis_pegawai,
                his.no_sk, his.per_tanggal,
                gol.golongan, pang.pangkat,
                his.no_rekening, his.nama_rekening, his.npwp, his.jabatan,
            CASE WHEN his.jenis_kelamin = 'l' THEN 'Laki-laki' ELSE 'Perempuan' END AS jenis_kelamin,
                his.tempat_lahir, his.tgl_lahir, ag.agama, his.pendidikan,
                his.alamat, his.kode_pos
            FROM
                t_pegawai peg
                LEFT JOIN itbl_apps_riwayat_pegawai his ON peg.id = his.id_pegawai
                LEFT JOIN m_jenis_pegawai jen ON his.id_jenis_pegawai = jen.id
                LEFT JOIN m_golongan gol ON his.id_golongan = gol.id
                LEFT JOIN m_pangkat_pegawai pang ON his.id_pangkat = pang.id
                LEFT JOIN m_agama ag ON his.id_agama = ag.id
            WHERE
                peg.id = ?
            ORDER BY
                his.per_tanggal DESC
        ";

        $this->sql_riwayat_raw = "SELECT * FROM itbl_apps_riwayat_pegawai WHERE id_pegawai = ? ORDER BY per_tanggal DESC";
    }

    public function index(Request $request) {
        $riwayat_terakhir = DB::select($this->sql_riwayat . " LIMIT 0,1 ", [$request->id_pegawai]);
        $riwayat = DB::select($this->sql_riwayat, [$request->id_pegawai]);
        $jenis_pegawai = JenisPegawai::orderBy("jenis_pegawai", "asc")->get();
        $agama = Agama::orderBy("id", "asc")->get();

        $jenis_kelamin = array();
        array_push($jenis_kelamin, array("kode" => "L", "jenkel" => "Laki-Laki"));
        array_push($jenis_kelamin, array("kode" => "P", "jenkel" => "Perempuan"));

        return view('riwayat-pegawai.index', [
            'riwayat_terakhir'      => $riwayat_terakhir[0],
            'riwayat'               => $riwayat,
            'jenis_pegawai'         => $jenis_pegawai,
            'jenis_kelamin'         => $jenis_kelamin,
            'agama'                 => $agama
        ]);
    }

    public function add(Request $request) {
        $riwayatPegawai = new RiwayatPegawai;
        $riwayatPegawai->id_pegawai = $request->id_pegawai;
        $riwayatPegawai->per_tanggal = $request->per_tanggal;
        $riwayatPegawai->no_sk = $request->no_sk;
        $riwayatPegawai->id_jenis_pegawai = $request->id_jenis_pegawai;
        $riwayatPegawai->id_pangkat = $request->id_pangkat;
        $riwayatPegawai->nik = $request->nik;
        $riwayatPegawai->id_golongan = $request->id_golongan;
        $riwayatPegawai->no_rekening = $request->no_rekening;
        $riwayatPegawai->nama_rekening = $request->nama_rekening;
        $riwayatPegawai->npwp = $request->npwp;
        $riwayatPegawai->jabatan = $request->jabatan;
        $riwayatPegawai->jenis_kelamin = $request->jenis_kelamin;
        $riwayatPegawai->tempat_lahir = $request->tempat_lahir;
        $riwayatPegawai->tgl_lahir = $request->tgl_lahir;
        $riwayatPegawai->id_agama = $request->id_agama;
        $riwayatPegawai->alamat = $request->alamat;
        $riwayatPegawai->kode_pos = $request->kode_pos;
        $riwayatPegawai->pendidikan = $request->pendidikan;
        $riwayatPegawai->gapok = $request->gapok;
        $riwayatPegawai->save();

        return redirect("/RiwayatPegawai?id_pegawai=" . $request->id_pegawai);
    }

    public function hapus(Request $request) {
        $riwayatPegawai = RiwayatPegawai::find($request->id_riwayat_pegawai);
        $riwayatPegawai->delete();

        return redirect("/RiwayatPegawai?id_pegawai=" . $request->id_pegawai);
    }

    public function isiFormDataTerakhir(Request $request) {
        $riwayat_terakhir = DB::select($this->sql_riwayat_raw . " LIMIT 0,1 ", [$request->id_pegawai]);

        return response(json_encode($riwayat_terakhir[0]))
                ->header('Content-Type', 'application/json');
    }

    public function getRiwayat($id_pegawai) {
        $riwayat = DB::select($this->sql_riwayat, [$id_pegawai]);

        return response()->json(["data" => $riwayat]);
    }

    public function loadGolongan(Request $request) {
        $golongan = Golongan::where('id_jenis_pegawai', $request->id_jenis_pegawai)
                                ->orderBy('golongan', 'asc')
                                ->get();

        return response(json_encode($golongan))
                ->header('Content-Type', 'application/json');
    }

    public function loadPangkat(Request $request) {
        $pangkat = Pangkat::where('id_jenis_pegawai', $request->id_jenis_pegawai)
                                ->orderBy('pangkat', 'asc')
                                ->get();
        
        return response(json_encode($pangkat))
                ->header('Content-Type', 'application/json');
    }
}
