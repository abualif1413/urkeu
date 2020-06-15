@extends('layout.app_pop')

@section('content')
<script type="text/javascript">
    function getAllJabatan(id_pegawai, nama_pegawai) {
        $("#list_jabatan").html("Loading...");
        $.get("{{ url('/RiwayatPegawai/GetRiwayat') }}/" + id_pegawai, function() {})
            .done(function(r) {
                var lj = "";
                $.each(r.data, function(i, v) {
                    lj += "<a href='javascript:void(0)'' class='list-group-item' ondblclick='memilih(" + id_pegawai + ", " + v.id_riwayat_pegawai + ", \"" + nama_pegawai + "\", \"" + v.jabatan + "\");'>" +
                            "<div style='margin-bottom: 10px;'><strong>Jabatan</strong><br />" + v.jabatan + "</div>" +
                            "<div style='margin-bottom: 10px;'><strong>Pangkat</strong><br />" + v.pangkat + "</div>" +
                            "<div style='margin-bottom: 10px;'><strong>Golongan</strong><br />" + v.golongan + "</div>" +
                            "</a>";
                });
                $("#list_jabatan").html(lj);
            })
            .fail(function() {
                alert("Error di load data jabatan");
            });
    }

    function memilih(id_pegawai, id_riwayat_pegawai, nama_pegawai, jabatan) {
        var nilai = id_pegawai + "-" + id_riwayat_pegawai;
        var tampil = nama_pegawai + " - " + jabatan;
        window.opener.window.document.querySelector("#{{ $id_value }}").value = nilai;
        window.opener.window.document.querySelector("#{{ $id_show }}").value = tampil;
        window.close();
    }
</script>
<form action="" method="get">
    <div class="input-group">
        <input type="hidden" name="id_value" value="{{ $id_value }}">
        <input type="hidden" name="id_show" value="{{ $id_show }}">
        <input type="text" class="form-control" placeholder="Search" name="cari" id="cari" value="{{ $cari }}" />
        <div class="input-group-btn">
            <button class="btn btn-primary" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
</form>
<br />
<div class="row">
    <div class="col-sm-8" style="height: 400px; overflow: scroll;">
        <div class="list-group">
        @foreach ($pegawai as $peg)
            <a href="javascript:void(0)" class="list-group-item" ondblclick="getAllJabatan({{ $peg->id }}, '{{ $peg->nama_pegawai }}');">{{ $peg->nama_pegawai }}</a>
        @endforeach
        </div> 
    </div>
    <div class="col-sm-4" style="height: 400px; overflow: scroll;">
        <h4>Daftar Jabatan Pegawai</h4>
        <div class="list-group" id="list_jabatan"></div>
    </div>
</div>
<strong>*) Double klik untuk memilih</strong>
@endsection