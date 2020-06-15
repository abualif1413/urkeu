@extends('layout.app')

@section('content')
<script type="text/javascript">
    $(function() {
        showPIC();

        $("#frm_pic").validate({
            rules: {
                nama : {required : true},
                no_kontak : {required : true},
                no_surat_kuasa : {required : true},
                tgl_surat_kuasa : {required : true},
                kena_ppn : {required : true},
                kena_pph : {required : true}
            },
            messages : {
                nama : {required : "Nama harap disii"},
                no_kontak : {required : "No. telepon harap diisi"},
                no_surat_kuasa : {required : "No. surat kuasa harap diisi"},
                tgl_surat_kuasa : {required : "Tgl. surat kuasa harap diisi"},
                kena_ppn : {required : "Pilih pengenaan PPN"},
                kena_pph : {required : "Pilih pengenaan PPh"}
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
                    //form.submit();
                    submitPIC();
                }
            }
        });
    });

/*
| --------------------------------------------------------------------------------------------------
| Proses load data-data PIC untuk rekanan yang bersangkutan
| --------------------------------------------------------------------------------------------------
*/
    function submitPIC() {
        var formData = new FormData(document.querySelector("#frm_pic"));

        $.ajax({
            url         : "{{ url('/DataPICRekanan/Submit') }}",
            type        : "post",
            dataType    : "json",
            data        : formData,
            processData : false,
            contentType : false,
            success:function(data, textStatus, jqXHR){
                showPIC();
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Error : " + textStatus);
            }
        });
        return false;
    }

    function showPIC() {
        document.querySelector("#frm_pic").reset();
        var id_data_rekanan = {{ $data_rekanan->id_data_rekanan }};
        $("#daftar_pic").html("<img src='{{ url('/public/images/loading.gif') }}' />");
        $.get("{{ url('/DataPICRekanan/ShowPIC') }}/" + id_data_rekanan, function() {})
            .done(function(r) {
                var list_pic = "";
                $.each(r, function(i, v) {
                    list_pic += "<a href='javascript:void(0);' class='list-group-item'>" +
                                    "<h4 class='list-group-item-heading'><strong>" + v.nama + "</strong></h4>" +
                                    "<p class='list-group-item-text'>" +
                                        "<div class='row'>" +
                                            "<div class='col-lg-2'>Telepon</div>" +
                                            "<div class='col-lg-10'>" + v.no_kontak + "</div>" +
                                        "</div>"+
                                        "<div class='row'>" +
                                            "<div class='col-lg-2'>No. Surat Kuasa</div>" +
                                            "<div class='col-lg-10'>" + v.no_surat_kuasa + "</div>" +
                                        "</div>"+
                                        "<div class='row'>" +
                                            "<div class='col-lg-2'>Tgl. Surat Kuasa</div>" +
                                            "<div class='col-lg-10'>" + v.tgl_surat_kuasa + "</div>" +
                                        "</div>"+
                                        "<div class='row'>" +
                                            "<div class='col-lg-2'>Pengenaan Pajak</div>" +
                                            "<div class='col-lg-10'>PPN : " + v.kena_ppn_tostring + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PPh : " + v.kena_pph_tostring + "</div>" +
                                        "</div>" +
                                        "<hr />" +
                                        "<button class='btn btn-sm btn-primary' onclick='goEdit(" + v.id_data_rekanan_pic + ");'>Ubah data</button> " +
                                        "<button class='btn btn-sm btn-warning' onclick='goHapus(" + v.id_data_rekanan_pic + ");'>Hapus data</button> " +
                                        "<button class='btn btn-sm btn-success' onclick='goUploadBerkasPIC(" + v.id_data_rekanan_pic + ");'>Upload file pendukung</button>" +
                                        "<hr />" +
                                        "<div id='file_berkas_listing_" + v.id_data_rekanan_pic + "'></div>" +
                                    "</p>" +
                                "</a>";
                    
                    loadFileBerkas(v.id_data_rekanan_pic);
                });
                $("#daftar_pic").html(list_pic);
            })
            .fail(function() {
                alert("Terjadi error di load pic");
            });
    }

    function goEdit(idRekananPIC) {
        $.get("{{ url('/DataPICRekanan/GoEditPicRekanan') }}/" + idRekananPIC, function() {})
            .done(function(r) {
                $("#submit_type").val("update");
                $("#id_data_rekanan_pic").val(r.id_data_rekanan_pic);
                $("#nama").val(r.nama);
                $("#no_kontak").val(r.no_kontak);
                $("#no_surat_kuasa").val(r.no_surat_kuasa);
                $("#tgl_surat_kuasa").val(r.tgl_surat_kuasa);
                $("#kena_ppn").val(r.kena_ppn);
                $("#kena_pph").val(r.kena_pph);
                $("#btn_submit").html("Ubah");
                $("#btn_reset").show();
                window.scrollTo(0, 0);
            })
            .fail(function() {

            });
    }

    function goHapus(idRekananPIC) {
        if(confirm("Anda yakin akan menghapus data PIC ini?")) {
            $.get("{{ url('/DataPICRekanan/HapusPICRekanan') }}/" + idRekananPIC, function() {})
                .done(function(r) {
                    showPIC();
                    window.scrollTo(0, 0);
                })
                .fail(function() {

                });
        }
    }
/*
| --------------------------------------------------------------------------------------------------
*/

    

    function formReset() {
        $("#submit_type").val("add");
        $("#id_data_rekanan_pic").val("");
        $("#btn_submit").html("Tambah");
        $("#btn_reset").hide();
        window.scrollTo(0, 0);
    }

/*
| --------------------------------------------------------------------------------------------------
| Proses pengupload-an berkas untuk PIC
| --------------------------------------------------------------------------------------------------
*/
    function goUploadBerkasPIC(id_data_rekanan_pic) {
        $("#id_data_rekanan_pic_upload_berkas").val(id_data_rekanan_pic);
        document.querySelector("#berkas").click();
    }

    function startUpload() {
        var formData = new FormData(document.querySelector("#frm_pic_upload_berkas"));
        var id_data_rekanan_pic = $("#id_data_rekanan_pic_upload_berkas").val();
       
        $.ajax({
            url         : "{{ url('/DataPICRekanan/UploadBerkas') }}",
            type        : "post",
            dataType    : "json",
            data        : formData,
            processData : false,
            contentType : false,
            success:function(data, textStatus, jqXHR){
                //alert(JSON.stringify(data));
                loadFileBerkas(id_data_rekanan_pic);
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Error : " + textStatus);
            }
        });
        return false;
    }

    function loadFileBerkas(id_data_rekanan_pic) {
        //$("#file_berkas_listing_" + id_data_rekanan_pic).html("<img src='{{ url('/public/images/loading.gif') }}' style='width: 100px;' />");
        $("#file_berkas_listing_" + id_data_rekanan_pic).html("Loading...");
        $.get("{{ url('/DataPICRekanan/LoadFileBerkas') }}/" + id_data_rekanan_pic, function() {})
            .done(function(r) {
                var file_listing = "";
                $.each(r, function(i, v) {
                    file_listing += "<div class='btn-group'>" + 
                        "<a target='_blank' href='{{ url('/public/berkas_pic_rekanan') }}/" + v.nama_file + "' class='btn btn-xs btn-success'>" + v.nama_file_asli + "</a>" +
                        "<a href='javascript:void(0);' onclick='hapusFileBerkas(" + v.id_data_rekanan_pic_file_berkas + ", " + id_data_rekanan_pic + ")' class='btn btn-xs btn-danger' title='Klik untuk menghapus file'><i class='fa fa-trash'></i></a>" +
                        "</div> ";
                });
                $("#file_berkas_listing_" + id_data_rekanan_pic).html(file_listing);
            })
            .fail(function(r) {
                alert("Error pada loading file");
            });
    }

    function hapusFileBerkas(id_data_rekanan_pic_file_berkas, id_data_rekanan_pic) {
        $.get("{{ url('/DataPICRekanan/HapusFileBerkas') }}/" + id_data_rekanan_pic_file_berkas, function() {})
            .done(function(r) {
                loadFileBerkas(id_data_rekanan_pic);
            })
            .fail(function(r) {
                alert("Error pada hapus file berkas");
            })
    }
/*
| --------------------------------------------------------------------------------------------------
*/
</script>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Data PIC Rekanan</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Data PIC Rekanan</h1>
    </div>
</div><!--/.row-->

<div class="panel panel-primary">
    <div class="panel-heading">Data Rekanan</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3">Nama Perusahaan</div>
            <div class="col-lg-9" style="font-weight: bold;">{{ $data_rekanan->nama_perusahaan }}</div>
        </div>
        <div class="row">
            <div class="col-lg-3">Alamat</div>
            <div class="col-lg-9" style="font-weight: bold;">{{ $data_rekanan->alamat }}</div>
        </div>
        <hr />
        <button class="btn btn-success" onclick="document.location.href='{{ url('/DataRekanan') }}';"><i class="fa fa-chevron-left">&nbsp;</i> Kembali ke Daftar Rekanan</button>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Form Data PIC Rekanan</div>
    <div class="panel-body">
        <form method="post" enctype="multipart/form-data" onsubmit="" id="frm_pic_upload_berkas" style="display:none;">
            @csrf()
            <input type="hidden" name="id_data_rekanan_pic" id="id_data_rekanan_pic_upload_berkas" />
            <input type="file" name="berkas" id="berkas" accept=".pdf,.png,.jpg" onchange="startUpload();" />
        </form>
        <form method="post" action="{{ url('/DataPICRekanan/Submit') }}" id="frm_pic" onreset="formReset();">
            @csrf()
            <input type="hidden" name="submit_type" id="submit_type" value="add" />
            <input type="hidden" name="id_data_rekanan" id="id_data_rekanan" value="{{ $data_rekanan->id_data_rekanan }}" />
            <input type="hidden" name="id_data_rekanan_pic" id="id_data_rekanan_pic" />
            <div class="row">
                <div class="col-lg-6 form-group">
                    <label class="control-label">Nama P.I.C</label>
                    <input type="textbox" name="nama" id="nama" class="form-control" />
                </div>
                <div class="col-lg-6 form-group">
                    <label class="control-label">No. Telepon</label>
                    <input type="textbox" name="no_kontak" id="no_kontak" class="form-control" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 form-group">
                    <label class="control-label">No. Surat Kuasa</label>
                    <input type="textbox" name="no_surat_kuasa" id="no_surat_kuasa" class="form-control" />
                </div>
                <div class="col-lg-2 form-group">
                    <label class="control-label">Tgl. Surat Kuasa</label>
                    <input type="date" name="tgl_surat_kuasa" id="tgl_surat_kuasa" class="form-control" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 form-group">
                    <label class="control-label">PPN</label>
                    <select name="kena_ppn" id="kena_ppn" class="form-control">
                        <option value=""></option>
                        @foreach($ragam_pajak as $rp)
                        <option value="{{ $rp['kode'] }}">{{ $rp['nilai'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 form-group">
                    <label class="control-label">PPh</label>
                    <select name="kena_pph" id="kena_pph" class="form-control">
                        <option value=""></option>
                        @foreach($ragam_pajak as $rp)
                        <option value="{{ $rp['kode'] }}">{{ $rp['nilai'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr />
            <button class="btn btn-primary" id="btn_submit" type="submit">Tambah</button>
            <button class="btn btn-warning" id="btn_reset" type="reset" style="display: none;">Reset</button>
        </form>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Daftar PIC Rekanan</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 form-group">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search">
                    <div class="input-group-btn">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="list-group" id="daftar_pic">
            
        </div> 
    </div>
</div>

@endsection