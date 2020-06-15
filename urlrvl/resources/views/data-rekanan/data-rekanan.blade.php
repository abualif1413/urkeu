@extends('layout.app')

@section('content')
<script type="text/javascript">
    $(function() {
        goReset();

        $("#frm_data_rekanan").validate({
            rules: {
                nama_perusahaan : {
                    required : true
                },
                alamat : {
                    required : true
                },
                email : {
                    required : true
                },
                no_kontak : {
                    required : true
                }
            },
            messages : {
                nama_perusahaan : {
                    required : "Nama perusahaan harus diisi"
                },
                alamat : {
                    required : "Alamat harus diisi"
                },
                email : {
                    required : "Email harus diisi"
                },
                no_kontak : {
                    required : "No. telepon harus diisi"
                }
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

    function goEdit(id_data_rekanan) {
        $.get("{{ url('DataRekanan/GoEdit') }}", {id_data_rekanan : id_data_rekanan}, function() {})
            .done(function(r) {
                $("#id_data_rekanan").val(r.id_data_rekanan);
                $("#nama_perusahaan").val(r.nama_perusahaan);
                $("#alamat").val(r.alamat);
                $("#email").val(r.email);
                $("#no_kontak").val(r.no_kontak);
                $("#submit_type").val("update");
                $("#btn_reset").show();
            })
            .fail(function() {
                alert("Sepertinya terjadi error");
            });
    }

    function goDelete(id_data_rekanan) {
        if(confirm("Anda yakin akan menghapus data ini?")) {
            document.location.href = "{{ url('/DataRekanan/GoDelete') }}/" + id_data_rekanan;
        }
    }

    function goPic(id_data_rekanan) {
        document.location.href = "{{ url('/DataPICRekanan') }}?id_data_rekanan=" + id_data_rekanan;
    }

    function goReset() {
        $("#id_data_rekanan").val("");
        $("#submit_type").val("add");
        $("#btn_reset").hide();
    }
</script>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">DataRekanan</li>
    </ol>
</div><!--/.row-->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Data Rekanan</h1>
    </div>
</div><!--/.row-->
<div class="panel panel-primary">
    <div class="panel-heading">Form Data Rekanan</div>
    <div class="panel-body">
        <form method="post" action="{{ url('/DataRekanan/Submit') }}" id="frm_data_rekanan" onreset="goReset();">
            @csrf()
            <input type="hidden" name="id_data_rekanan" id="id_data_rekanan" value="" />
            <input type="hidden" name="submit_type" id="submit_type" value="add" />
            <div class="row">
                <div class="form-group col-lg-6">
                    <label class="control-label">Nama Perusahaan</label>
                    <input class="form-control" id="nama_perusahaan" name="nama_perusahaan" type="text" value="">
                </div>
                <div class="form-group col-lg-6">
                    <label class="control-label">Alamat</label>
                    <input class="form-control" id="alamat" name="alamat" type="text" value="">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label class="control-label">Email</label>
                    <input class="form-control" id="email" name="email" type="text" value="">
                </div>
                <div class="form-group col-lg-6">
                    <label class="control-label">No. Telp</label>
                    <input class="form-control" id="no_kontak" name="no_kontak" type="text" value="">
                </div>
            </div>
            <hr />
            <button class="btn btn-primary" id="btn_simpan" name="btn_simpan" type="submit">Simpan</button>
            <button class="btn btn-warning" id="btn_reset" name="btn_reset" type="reset">Reset</button>
        </form>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">Data Rekanan</div>
    <div class="panel-body">
        <table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0" style="font-size: 90%;">
            <thead>
                <tr class="bg-primary">
                    <th width="50px"></th>
                    <th width="50px"></th>
                    <th width="50px"></th>
                    <th width="30px">No.</th>
                    <th width="">Nama Perusahaan</th>
                    <th width="">Alamat</th>
                    <th width="">No. Telp</th>
                    <th width="">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekanan as $no => $rekanan)
                <tr>
                    <td><button class="btn btn-warning btn-xs" onclick="goDelete({{ $rekanan->id_data_rekanan }});">Hapus</button></td>
                    <td><button class="btn btn-success btn-xs" onclick="goEdit({{ $rekanan->id_data_rekanan }});">Ubah</button></td>
                    <td><button class="btn btn-primary btn-xs" onclick="goPic({{ $rekanan->id_data_rekanan }});">P.I.C</button></td>
                    <td>{{ ($no + 1) }}</td>
                    <td>{{ $rekanan->nama_perusahaan }}</td>
                    <td>{{ $rekanan->alamat }}</td>
                    <td>{{ $rekanan->no_kontak }}</td>
                    <td>{{ $rekanan->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection