<?php

namespace App\AppCore;

use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    public static function enumRagamPajakPIC() {
        $ragamPajak = [
            ["kode" => "YES_NO", "nilai" => "Tidak ditentukan"],
            ["kode" => "YES", "nilai" => "Dikenakan"],
            ["kode" => "NO", "nilai" => "Tidak dikenakan"]
        ];

        return $ragamPajak;
    }

    public static function pengenaanPajakToString($pengenaanPajak) {
        $ragamPajak = Common::enumRagamPajakPIC();
        foreach($ragamPajak as $rp) {
            if(strtoupper($rp["kode"]) == strtoupper($pengenaanPajak)) {
                return $rp["nilai"];
            }
        }

        return "undefined";
    }

    public static function folderFilePICRekanan() {
        return public_path() . "/berkas_pic_rekanan";
    }
}
