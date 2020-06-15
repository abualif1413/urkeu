<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\DataRekananPICResource;

use App\PermohonanDana;
use App\DetailPermohonanDana;
use App\DetailPermohonanDanaNormatif;
use App\DataRekananPIC;

class PengeluaranDanaController extends Controller
{
    public function tambahDetail(Request $request) {
        $ppn = 0;
		$pph = 0;
		if(isset($request->ppn)) {
			$ppn = 1;
		}
		if(isset($request->pph)) {
			$pph = 1;
        }
        
        if(!$request->id_detail) {
            $detailPermohonanDana = new DetailPermohonanDana;
            $detailPermohonanDana->id_permohonan_dana = $request->id_permohonan_dana;
            $detailPermohonanDana->id_jenis_pajak = 0;
            $detailPermohonanDana->penerima = $request->penerima;
            $detailPermohonanDana->qty = $request->qty;
            $detailPermohonanDana->satuan = $request->satuan;
            $detailPermohonanDana->harga_satuan = $request->harga_satuan;
            $detailPermohonanDana->jumlah = 0;
            $detailPermohonanDana->uraian = $request->uraian;
            $detailPermohonanDana->no_faktur = (isset($request->no_faktur) ? $request->no_faktur : "");
            $detailPermohonanDana->tgl_faktur = (isset($request->tgl_faktur) ? $request->tgl_faktur : "0001-01-01");
            $detailPermohonanDana->ppn = $ppn;
            $detailPermohonanDana->pph = $pph;
            $detailPermohonanDana->user_insert = $request->user_id;
            $detailPermohonanDana->save();
        } else {
            $detailPermohonanDana = DetailPermohonanDana::find($request->id_detail);
            $detailPermohonanDana->id_permohonan_dana = $request->id_permohonan_dana;
            $detailPermohonanDana->id_jenis_pajak = 0;
            $detailPermohonanDana->penerima = $request->penerima;
            $detailPermohonanDana->qty = $request->qty;
            $detailPermohonanDana->satuan = $request->satuan;
            $detailPermohonanDana->harga_satuan = $request->harga_satuan;
            $detailPermohonanDana->jumlah = 0;
            $detailPermohonanDana->uraian = $request->uraian;
            $detailPermohonanDana->no_faktur = (isset($request->no_faktur) ? $request->no_faktur : "");
            $detailPermohonanDana->tgl_faktur = (isset($request->tgl_faktur) ? $request->tgl_faktur : "0001-01-01");
            $detailPermohonanDana->ppn = $ppn;
            $detailPermohonanDana->pph = $pph;
            $detailPermohonanDana->user_insert = $request->user_id;
            $detailPermohonanDana->save();
        }
        
        return response()->json(["status" => 1, "message" => "berhasil"]);
    }

    public function tambahDetailNormatif(Request $request) {
        if(!$request->normatif_id_detail) {
            $normatif = new DetailPermohonanDanaNormatif;
            $normatif->id_belanja_honor = $request->normatif_id_belanja_honor;
            $normatif->id_pegawai = $request->normatif_id_pegawai;
            $normatif->jabatan_pengelola = $request->normatif_jabatan_pengelola;
            $normatif->qty = $request->normatif_qty;
            $normatif->sbu_honor = $request->normatif_sbu_honor;
            $normatif->user_insert = $request->user_id;
            $normatif->save();
        } else {
            $normatif = DetailPermohonanDanaNormatif::find($request->normatif_id_detail);
            $normatif->id_belanja_honor = $request->normatif_id_belanja_honor;
            $normatif->id_pegawai = $request->normatif_id_pegawai;
            $normatif->jabatan_pengelola = $request->normatif_jabatan_pengelola;
            $normatif->qty = $request->normatif_qty;
            $normatif->sbu_honor = $request->normatif_sbu_honor;
            $normatif->user_insert = $request->user_id;
            $normatif->save();
        }
        
        return response()->json(["status" => 1, "message" => "berhasil"]);
    }

    public function hapusDetail($id_detail) {
        $detailPermohonanDana = DetailPermohonanDana::find($id_detail);
        $detailPermohonanDana->delete();

        return response()->json(["status" => 1, "message" => "berhasil"]);
    }

    public function hapusDetailNormatif($id_detail_normatif) {
        $normatif = DetailPermohonanDanaNormatif::find($id_detail_normatif);
        $normatif->delete();

        return response()->json(["status" => 1, "message" => "berhasil"]);
    }

    public function getDetailTemp($userID) {
        $data = DB::select(
            "
                SELECT
                    datanya.id, MAX(datanya.qty) AS qty, MAX(datanya.satuan) AS satuan,
                    MAX(datanya.harga_satuan) AS harga_satuan, MAX(datanya.id_jenis_pajak) AS id_jenis_pajak, MAX(datanya.penerima) AS penerima,
                    MAX(datanya.qty * datanya.harga_satuan) AS jumlah, MAX(datanya.uraian) AS uraian, MAX(datanya.user_insert) AS user_insert,
                    MAX(datanya.jenis_pajak) AS jenis_pajak, SUM(datanya.besar_ppn) AS ppn, SUM(datanya.besar_pph) AS pph
                FROM
                    (
                        SELECT
                            a.*, b.keterangan AS jenis_pajak,
                            COALESCE(ppn.besar, 0) AS persen_ppn, COALESCE(pph.besar, 0) AS persen_pph,
                            @ppn := CASE
                                WHEN (a.qty * a.harga_satuan) >= 2000000 THEN CEIL((a.qty * a.harga_satuan) / 11)
                                ELSE 0
                            END AS besar_ppn, 
                            CASE
                                WHEN (a.qty * a.harga_satuan) >= 1000000 THEN CEIL((((a.qty * a.harga_satuan) - @ppn) * 1.5 / 100))
                                ELSE 0
                            END AS besar_pph
                        FROM
                            t_detail_permohonan_dana a	
                            LEFT JOIN m_jenis_pajak b ON a.id_jenis_pajak = b.id
                            LEFT JOIN m_memiliki_pajak c ON b.id = c.id_jenis_pajak
                            LEFT JOIN m_pajak ppn ON c.id_pajak = ppn.id AND ppn.tipe = 'PPN'
                            LEFT JOIN m_pajak pph ON c.id_pajak = pph.id AND pph.tipe = 'PPh'
                            CROSS JOIN (SELECT @ppn := 0) ppn
                        WHERE
                            (a.id_permohonan_dana IS NULL OR a.id_permohonan_dana = 0) AND a.user_insert = ?
                    ) datanya
                GROUP BY
                    datanya.id
            ",
            [$userID]
        );

        return response()->json(["data" => $data]);
    }

    public function getDetailNormatifTemp($userID) {
        $data = DB::select(
            "
                SELECT
                    a.id, b.nama_pegawai, a.qty, a.sbu_honor
                FROM
                    t_detail_permohonan_dana_normatif a
                    LEFT JOIN t_pegawai b ON a.id_pegawai = b.id
                WHERE
                    (a.id_belanja_honor = '0' OR a.id_belanja_honor IS NULL) AND a.user_insert = ?
                ORDER BY
                    a.id ASC
            "
            , [$userID]);
        
        return response()->json(["data" => $data]);
    }

    public function getRekananPIC($id_data_rekanan_pic) {
        $dataRekananPIC = DataRekananPIC::findOrFail($id_data_rekanan_pic);
        $resource = new DataRekananPICResource($dataRekananPIC);

        return response()->json(["data" => $resource]);
    }
}
