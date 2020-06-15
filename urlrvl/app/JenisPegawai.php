<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisPegawai extends Model
{
    protected $table = "m_jenis_pegawai";
    protected $primaryKey = "id";
    public $timestamps = false;
}
