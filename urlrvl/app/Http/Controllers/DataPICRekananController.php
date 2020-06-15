<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\AppCore\Common;
use App\DataRekanan;
use App\DataRekananPIC;
use App\DataRekananPICFileBerkas;

class DataPICRekananController extends Controller
{
    public function index(Request $request) {
        $data_rekanan = DataRekanan::find($request->id_data_rekanan);

        return view('data-pic-rekanan.index', [
            "data_rekanan"      => $data_rekanan,
            "ragam_pajak"       => Common::enumRagamPajakPIC()
        ]);
    }

    public function submit(Request $request) {
        $response = [];
        try {
            if($request->submit_type == "add") {
                $data_rekanan_pic = new DataRekananPIC;
                $data_rekanan_pic->id_data_rekanan = $request->id_data_rekanan;
                $data_rekanan_pic->nama = $request->nama;
                $data_rekanan_pic->no_kontak = $request->no_kontak;
                $data_rekanan_pic->no_surat_kuasa = $request->no_surat_kuasa;
                $data_rekanan_pic->tgl_surat_kuasa = $request->tgl_surat_kuasa;
                $data_rekanan_pic->kena_ppn = $request->kena_ppn;
                $data_rekanan_pic->kena_pph = $request->kena_pph;
                $data_rekanan_pic->save();
            } elseif($request->submit_type == "update") {
                $data_rekanan_pic = DataRekananPIC::find($request->id_data_rekanan_pic);
                $data_rekanan_pic->id_data_rekanan = $request->id_data_rekanan;
                $data_rekanan_pic->nama = $request->nama;
                $data_rekanan_pic->no_kontak = $request->no_kontak;
                $data_rekanan_pic->no_surat_kuasa = $request->no_surat_kuasa;
                $data_rekanan_pic->tgl_surat_kuasa = $request->tgl_surat_kuasa;
                $data_rekanan_pic->kena_ppn = $request->kena_ppn;
                $data_rekanan_pic->kena_pph = $request->kena_pph;
                $data_rekanan_pic->save();
            }
            $response = [
                "status" => 1,
                "message" => "success"
            ];
        } catch (Throwable $e) {
            $response = [
                "status" => 0,
                "message" => $e
            ];
        }

        return response(json_encode($response))
                ->header('Content-Type', 'application/json');
    }

    public function showPicRekanan($idDataRekanan) {
        $dataRekananPIC = DataRekananPIC::where('id_data_rekanan', $idDataRekanan)
                            ->orderBy('nama', 'asc')
                            ->get()->toArray();

        foreach($dataRekananPIC as &$pic) {
            $pic["kena_pph_tostring"] = Common::pengenaanPajakToString($pic["kena_pph"]);
            $pic["kena_ppn_tostring"] = Common::pengenaanPajakToString($pic["kena_ppn"]);
        }
        
        return response()->json($dataRekananPIC);
    }

    public function hapusPICRekanan($idDataRekananPIC) {
        $dataRekananPIC = DataRekananPIC::find($idDataRekananPIC);
        $dataRekananPIC->delete();

        return response()->json(["status" => 1, "pesan" => "Telah berhasil menghapus"]);
    }

    public function goEditPicRekanan($idDataRekananPIC) {
        $dataRekananPIC = DataRekananPIC::find($idDataRekananPIC);
        
        return response()->json($dataRekananPIC);
    }

    public function uploadBerkas(Request $request) {
        $arr = [];

        if($request->hasFile('berkas')) {

            /*
            | -----------------------------------------------------------------------------------------------
            | Proses penguploadan berkas.
            | -----------------------------------------------------------------------------------------------
            */
            $file = $request->file('berkas');
            $ekstensi = \File::extension($file->getClientOriginalName());
            $namaFileAsli = $file->getClientOriginalName();
            $namaFile = $request->id_data_rekanan_pic . "_" . Str::random(32) . "." . $ekstensi;
            $file->move(Common::folderFilePICRekanan(), $namaFile);
            /*
            | -----------------------------------------------------------------------------------------------
            */

            /*
            | -----------------------------------------------------------------------------------------------
            | Proses penyimpanan ke database.
            | -----------------------------------------------------------------------------------------------
            */
            $dataRekananPICFileBerkas = new DataRekananPICFileBerkas;
            $dataRekananPICFileBerkas->id_data_rekanan_pic = $request->id_data_rekanan_pic;
            $dataRekananPICFileBerkas->nama_file = $namaFile;
            $dataRekananPICFileBerkas->nama_file_asli = $namaFileAsli;
            $dataRekananPICFileBerkas->save();
            /*
            | -----------------------------------------------------------------------------------------------
            */

            $arr = [
                "status" => 1,
                "pesan" => "Berkas telah selesai diupload"
             ];
        } else {
            $arr = [
               "status" => 0,
               "pesan" => "Berkas tidak diupload"
            ];
        }

        return response()->json($arr);
    }

    public function loadFileBerkas($idDataRekananPIC) {
        $fileBerkas = DataRekananPICFileBerkas::where('id_data_rekanan_pic', $idDataRekananPIC)
                        ->orderBy('id_data_rekanan_pic_file_berkas', 'asc')
                        ->get();

        return response()->json($fileBerkas);
    }

    public function hapusFileBerkas($idDataRekananPicFileBerkas) {
        $fileBerkas = DataRekananPICFileBerkas::find($idDataRekananPicFileBerkas);
        $fileBerkas->delete();

        return response()->json(["status" => 1, "pesan" => "Telah berhasil menghapus"]);
    }

    public function testing(Request $request) {
        $uji = [
            $request->id_data_rekanan, $request->nama, $request->no_kontak
        ];

        return response(json_encode($uji))
                ->header('Content-Type', 'application/json');
    }
}
