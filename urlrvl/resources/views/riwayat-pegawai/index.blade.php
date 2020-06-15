@extends('layout.app')

@section('content')
<script type="text/javascript">
    $(function() {
        isiFormDataTerakhir();

        $("#frm_riwayat_pegawai").validate({
            rules: {
                per_tanggal : {required : true},
                no_sk : {required : true},
                id_jenis_pegawai : {required : true},
                nik : {required : true},
                gapok : {required : true, number : true},
                no_rekening : {required : true},
                nama_rekening : {required : true},
                npwp : {required : true},
                jabatan : {required : true},
                jenis_kelamin : {required : true},
                tempat_lahir : {required : true},
                tgl_lahir : {required : true},
                id_agama : {required : true},
                alamat : {required : true}
            },
            messages : {
                per_tanggal : {required : "Harap tentukan per tgl berapa riwayat ini berlaku"},
                no_sk : {required : "Isikan no SK jika ada"},
                id_jenis_pegawai : {required : "Pilih jenis pegawai"},
                nik : {required : "Nik harap diisi"},
                gapok : {required : "harap diisi", number : "Harus angka"},
                no_rekening : {required : "No. Rekening harap diisi"},
                nama_rekening : {required : "Nama Rekening harap diisi"},
                npwp : {required : "NPWP harap diisi"},
                jabatan : {required : "Jabatan harap diisi"},
                jenis_kelamin : {required : "Harap diisi"},
                tempat_lahir : {required : "Harap diisi"},
                tgl_lahir : {required : "Harap diisi"},
                id_agama : {required : "Harap diisi"},
                alamat : {required : "Alamat harap diisi"}
            },
            errorElement : "span",
            errorClass : "text-danger",
            errorPlacement : function ( error, element ) {
            // Add the `help-block` class to the error element
                error.addClass( "help-block" );

                if ( element.prop( "type" ) === "checkbox" ) {
                    error.insertAfter( element.parent( "label" ) );
                } else {
                    error.insertAfter( element );
                }
            },
            highlight : function ( element, errorClass, validClass ) {
                //$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
                $(element).parent().addClass("has-warning").removeClass("has-success");
            },
            unhighlight : function (element, errorClass, validClass) {
                //$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
                $(element).parent().addClass("has-success").removeClass("has-warning");
            },
            submitHandler : function(form) {
                if(confirm("Anda yakin akan menyimpan data ini?")) {
                    form.submit();
                }
            }
        });
    });

    function isiFormDataTerakhir() {
        var id_pegawai = {{ $riwayat_terakhir->id }};
        $.get("{{ url('/RiwayatPegawai/IsiFormDataTerakhir') }}", {id_pegawai : id_pegawai}, function() {})
            .done(function(r) {
                $("#per_tanggal").val("");
                $("#no_sk").val();
                $("#id_jenis_pegawai").val(r.id_jenis_pegawai);
                $("#nik").val(r.nik);
                $("#gapok").val(r.gapok);
                $("#no_rekening").val(r.no_rekening);
                $("#nama_rekening").val(r.nama_rekening);
                $("#npwp").val(r.npwp);
                $("#jabatan").val(r.jabatan);
                $("#jenis_kelamin").val(r.jenis_kelamin);
                $("#tempat_lahir").val(r.tempat_lahir);
                $("#tgl_lahir").val(r.tgl_lahir);
                $("#id_agama").val(r.id_agama);
                $("#pendidikan").val(r.pendidikan);
                $("#alamat").val(r.alamat);
                $("#kode_pos").val(r.kode_pos);

                var id_golongan = r.id_golongan;
                var id_pangkat = r.id_pangkat;

                $.get("{{ url('/RiwayatPegawai/LoadGolongan') }}", {id_jenis_pegawai : r.id_jenis_pegawai}, function() {})
                    .done(function(r) {
                        var option_golongan = "<option value=''></option>";
                        $.each(r, function(r_index, r_value) {
                            option_golongan += "<option value='" + r_value.id + "'>" + r_value.golongan + "</option>";
                        });
                        $("#id_golongan").html(option_golongan);
                        $("#id_golongan").val(id_golongan);
                    })
                    .fail(function() {
                        alert("Terjadi error di load golongan");
                    });

                $.get("{{ url('/RiwayatPegawai/LoadPangkat') }}", {id_jenis_pegawai : r.id_jenis_pegawai}, function() {})
                    .done(function(r) {
                        var option_pangkat = "<option value=''></option>";
                        $.each(r, function(r_index, r_value) {
                            option_pangkat += "<option value='" + r_value.id + "'>" + r_value.pangkat + "</option>";
                        });
                        $("#id_pangkat").html(option_pangkat);
                        $("#id_pangkat").val(id_pangkat);
                    })
                    .fail(function() {
                        alert("Terjadi error di load pangkat");
                    });
            })
            .fail(function() {
                alert("Terjadi error");
            });
        //alert("Hai");
    }

    function goHapus(id_riwayat_pegawai) {
        var id_pegawai = {{ $riwayat_terakhir->id }};
        if(confirm("Anda yakin akan menghapus data ini?")) {
            document.location.href = "{{ url('/RiwayatPegawai/Hapus') }}?id_riwayat_pegawai=" + id_riwayat_pegawai + "&id_pegawai=" + id_pegawai;
        }
    }

    function loadGolonganPangkat(id_jenis_pegawai) {
        $.get("{{ url('/RiwayatPegawai/LoadGolongan') }}", {id_jenis_pegawai : id_jenis_pegawai}, function() {})
            .done(function(r) {
                var option_golongan = "<option value=''></option>";
                $.each(r, function(r_index, r_value) {
                    option_golongan += "<option value='" + r_value.id + "'>" + r_value.golongan + "</option>";
                });
                $("#id_golongan").html(option_golongan);
            })
            .fail(function() {
                alert("Terjadi error di load golongan");
            });

        $.get("{{ url('/RiwayatPegawai/LoadPangkat') }}", {id_jenis_pegawai : id_jenis_pegawai}, function() {})
            .done(function(r) {
                var option_pangkat = "<option value=''></option>";
                $.each(r, function(r_index, r_value) {
                    option_pangkat += "<option value='" + r_value.id + "'>" + r_value.pangkat + "</option>";
                });
                $("#id_pangkat").html(option_pangkat);
            })
            .fail(function() {
                alert("Terjadi error di load pangkat");
            });
    }
</script>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Data Riwayat Pegawai</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Data Riwayat Pegawai</h1>
    </div>
</div><!--/.row-->

<div class="panel panel-primary">
    <div class="panel-heading">Data Terakhir Pegawai (Sesuai Urutan Riwayat)</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3">Nama Pegawai</div>
            <div class="col-lg-9" style="font-weight: bold;">{{ $riwayat_terakhir->nama_pegawai }}</div>
        </div>
        <div class="row">
            <div class="col-lg-3">NIK / NRP / NIP</div>
            <div class="col-lg-9" style="font-weight: bold;">{{ $riwayat_terakhir->nik }}</div>
        </div>
        <div class="row">
            <div class="col-lg-3">Jenis Pegawai</div>
            <div class="col-lg-9" style="font-weight: bold;">{{ $riwayat_terakhir->jenis_pegawai }}</div>
        </div>
        <div class="row">
            <div class="col-lg-3">Golongan</div>
            <div class="col-lg-9" style="font-weight: bold;">{{ $riwayat_terakhir->golongan }}</div>
        </div>
        <div class="row">
            <div class="col-lg-3">Pangkat</div>
            <div class="col-lg-9" style="font-weight: bold;">{{ $riwayat_terakhir->pangkat }}</div>
        </div>
        <div class="row">
            <div class="col-lg-3">Jabatan</div>
            <div class="col-lg-9" style="font-weight: bold;">{{ $riwayat_terakhir->jabatan }}</div>
        </div>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Form penambahan data riwayat pegawai</div>
    <div class="panel-body">
        <form class="form-horizontal" action="{{ url('/RiwayatPegawai/Add') }}" method="post" id="frm_riwayat_pegawai">
            @csrf()
            <input type="hidden" name="id_pegawai" value="{{ $riwayat_terakhir->id }}" />

            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">Per Tanggal</label>
                <div class="col-sm-2">
                    <input id="per_tanggal" name="per_tanggal" type="date" placeholder="" class="form-control" value="">
                </div>
                <label class="col-sm-2 control-label" for="penerima">No. SK (jika ada)</label>
                <div class="col-sm-5">
                    <input id="no_sk" name="no_sk" type="text" placeholder="No. SK" class="form-control" value="">
                </div>
            </div>

            <hr />

            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">Jenis Pegawai</label>
                <div class="col-sm-4">
                    <select name="id_jenis_pegawai" id="id_jenis_pegawai" class="form-control" onchange="loadGolonganPangkat(this.value);">
                        <option value=""></option>
                        @foreach($jenis_pegawai as $jp)
                        <option value="{{ $jp->id }}">{{ $jp->jenis_pegawai }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">NIP / NRP / NIK</label>
                <div class="col-sm-6">
                    <input id="nik" name="nik" type="text" placeholder="NIP / NRP / NIK" class="form-control" value="">
                </div>
                <label class="col-sm-2 control-label" for="penerima">Gaji Pokok</label>
                <div class="col-sm-2">
                    <input id="gapok" name="gapok" type="text" placeholder="Kode Gapok" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">Golongan</label>
                <div class="col-sm-3">
                    <select id="id_golongan" name="id_golongan" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
                <label class="col-sm-2 control-label" for="penerima">Pangkat</label>
                <div class="col-sm-5">
                    <select id="id_pangkat" name="id_pangkat" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">No. Rekening</label>
                <div class="col-sm-3">
                    <input id="no_rekening" name="no_rekening" type="text" placeholder="No. Rekening" class="form-control" value="">
                </div>
                <label class="col-sm-2 control-label" for="penerima">A.N. Rekening</label>
                <div class="col-sm-5">
                    <input id="nama_rekening" name="nama_rekening" type="text" placeholder="A.N. Rekening" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">NPWP</label>
                <div class="col-sm-5">
                    <input id="npwp" name="npwp" type="text" placeholder="NPWP" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">Jabatan</label>
                <div class="col-sm-10">
                    <input id="jabatan" name="jabatan" type="text" placeholder="Jabatan" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">Jenis Kelamin</label>
                <div class="col-sm-2">
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                        <option value=""></option>
                        @foreach($jenis_kelamin as $jk)
                        <option value="{{ $jk['kode'] }}">{{ $jk['jenkel'] }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="col-sm-2 control-label" for="penerima">Tempat / Tgl. Lahir</label>
                <div class="col-sm-4">
                    <input id="tempat_lahir" name="tempat_lahir" type="text" placeholder="Tempat Lahir" class="form-control" value="">
                </div>
                <div class="col-sm-2">
                    <input id="tgl_lahir" name="tgl_lahir" type="date" placeholder="" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">Agama</label>
                <div class="col-sm-3">
                    <select name="id_agama" id="id_agama" class="form-control">
                        <option value=""></option>
                        @foreach($agama as $agama)
                        <option value="{{ $agama->id }}">{{ $agama->agama }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="col-sm-2 control-label" for="penerima">Pendidikan</label>
                <div class="col-sm-5">
                    <input id="pendidikan" name="pendidikan" type="text" placeholder="Pendidikan" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="penerima">Alamat</label>
                <div class="col-sm-5">
                    <input id="alamat" name="alamat" type="text" placeholder="Alamat" class="form-control" value="">
                </div>
                <label class="col-sm-2 control-label" for="penerima">Kode Pos</label>
                <div class="col-sm-3">
                    <input id="kode_pos" name="kode_pos" type="text" placeholder="Kode Pos" class="form-control" value="">
                </div>
            </div>
            <hr />
            <button type="submit" name="save" id="save" value="Save" class="btn btn-primary">Simpan Data Riwayat Pegawai</button>
            <button type="reset" name="reset" id="reset" value="Reset" class="btn btn-warning" onclick="isiFormDataTerakhir();">Reset Data</button>
        </form>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Riwayat Pegawai</div>
    <div class="panel-body">
        <table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0" style="font-size: 90%;">
            <thead>
                <tr class="bg-primary">
                    <th width="50px"></th>
                    <th width="30px">No.</th>
                    <th width="">Per Tanggal</th>
                    <th width="">No. SK</th>
                    <th width="">Jenis Pegawai</th>
                    <th width="">Golongan</th>
                    <th width="">Pangkat</th>
                    <th width="">Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayat as $no => $riwayat)
                <tr>
                    <td><button class="btn btn-warning btn-xs btn-block" onclick="goHapus({{ $riwayat->id_riwayat_pegawai }})">Hapus</button></td>
                    <td>{{ ($no + 1) }}</td>
                    <td>{{ $riwayat->per_tanggal }}</td>
                    <td>{{ $riwayat->no_sk }}</td>
                    <td>{{ $riwayat->jenis_pegawai }}</td>
                    <td>{{ $riwayat->golongan }}</td>
                    <td>{{ $riwayat->pangkat }}</td>
                    <td>{{ $riwayat->jabatan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection