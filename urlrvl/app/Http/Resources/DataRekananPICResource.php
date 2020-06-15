<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\DataRekanan;

class DataRekananPICResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $dataRekanan = DataRekanan::find($this->id_data_rekanan);

        return [
            "id_data_rekanan_pic" => $this->id_data_rekanan_pic,
            "id_data_rekanan" => $this->id_data_rekanan,
            "nama_pic" => $this->nama,
            "nama_rekanan" => $dataRekanan->nama_perusahaan,
            "kena_ppn" => $this->kena_ppn,
            "kena_pph" => $this->kena_pph
        ];
    }
}
