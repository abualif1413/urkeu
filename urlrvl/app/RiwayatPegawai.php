<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPegawai extends Model
{
    protected $table = "itbl_apps_riwayat_pegawai";
    protected $primaryKey = "id_riwayat_pegawai";
    public $timestamps = false;
    const CREATED_AT = 'insert_time';
    const UPDATED_AT = 'update_time';
}
