<style>
    ul li {
        list-style: none;
        margin-left: 0px;
        margin-bottom: 0px;
    }
    
    .dropdown-menu li {
        padding-left: 10px;
    }
    
    #status {
        font-size: 14px
    }
    
    #message {
        font-size: 12px
    }
    
    #box_sukses {
        border: 1px solid #099a8c;
        padding: 10px
    }
    
    #box_gagal {
        border: 1px solid #f14d4d;
        padding: 10px
    }
    
    th {
        font-size: 8px;
    }
    
    td {
        font-size: 10px;
    }
</style>
<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">TAGIHAN SPP/UKT</span><span style="font-size : 11pt ;"> Manajemen data tagihan untuk SPP/UKT.</span></h3>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="dropdown" style="float : right ; margin-right : -14px">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"></i> Menu Operasi
                    <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right">
                    <li class="dropdown-header">Input Manual</li>
                    <li><a href="<?php echo base_url('spp/formulir/'.$kode_periode)?>">Form Kosong</a></li>
                    
                    <li role="presentation" class="divider"></li>
                    <li class="dropdown-header">Input Data Excel</li>
                    <li><a href="#" onclick="download_modal();">Download Template</a></li>
                    <li><a href="#" onclick="upload_modal();">Upload Template</a></li>
                    <li role="presentation" class="divider"></li>
                    <li class="dropdown-header">Update Group Data</li>
                    <li><a href="#" onclick="waktu_modal();">Set Waktu Berlaku</a></li>
                </ul>
            </div>
        </div>
        <div class="page-title col-lg-8 col-md-8 hidden-md hidden-xs hidden-sm">
            <i class="fa fa-info-circlee"></i>
        </div>
        <!--       <div class="col-lg-4 col-md-4">
         <a href="#" data-toggle="modal" data-backdrop="static" data-target="#modal-form" class="btn btn-primary btn-cons" style="float : right ; margin-right : -14px"><i class="fa fa-plus"></i></i><span>Tambah Data</span></a>
         </div>-->
        <div class="row">
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <input type="text" id="field-cari" class="form-control" name="field-cari">
                    <span class="input-group-btn">
                  <!--<input type="button" id="btn-cari" class="btn btn-default" value="Cari" />-->
                  <a class="btn btn-primary" id="btn-cari" href="#" value="Cari"><i class="fa fa-search"></i></a>
               </span>
                </div>
                <br>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Tagihan <?php echo $label_nama_periode;?></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <table class="table table-hover table-condensed" id="table-datatable" style="font-size:8px" width='100%'>
                            <thead>
                                <tr>
                                    <th width="1%">NO</th>
                                    <th width="15%">NO. PEMBAYARAN </th>
                                    <th width="15%">SEMESTER </th>
                                    <th width="30%">NAMA</th>
                                    <th width="10%">PRODI</th>
                                    <th width="15%">TAGIHAN</th>
                                    <th width="15%">PEMBAYARAN</th>
                                    <th width="10%">AKSI</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myHapus" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">Hapus Tagihan</div>
            <div class="modal-body">
                <div class="form-group col-lg-12 col-md-12">
                    Apakah anda yakin ingin menghapus tagihan?
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="btn-hapus" class="btn btn-primary" onclick="hapus();">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-trash" aria-hidden="true"></i> Hapus
                </button>
                <button id="btn-batal" data-dismiss="modal" class="btn">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div id="myDownloadTolak" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Download/Upload Tempate Ditolak</h5>
            </div>
            <div class="modal-body">
                Sebelum anda mendownload atau meupload template, harap setting JALUR MASUK dimenu Filter. Satu buah file template hanya digunakan untuk satu list data jalur masuk.
            </div>
            <div class="modal-footer">
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Ok</button>
            </div>
        </div>
    </div>
</div>
<div id="myDetail" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Detail Tagihan</h5>
            </div>
            <div class="modal-body detail">
            </div>
            <div class="modal-footer">
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Ok</button>
            </div>
        </div>
    </div>
</div>
<div id="myWaktuBerlaku" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Setting Masa Berlaku</h5>
            </div>
            <div class="modal-body">
                <div class="waktuberlaku-msg"></div>
                <div class="form-group">
                    <div class="col-sm-6">
                        Waktu Tagihan Berlaku
                        <div class='input-group date'>
                            <?php echo form_input('waktu_berlaku', date("Y-m-d"), array('class' => 'form-control waktu_berlaku','id'=>'waktu_berlaku','placeholder' => 'Isikan rupiah tagihan')); ?>
                                <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        Waktu Tagihan Berakhir
                        <div class='input-group'>
                            <?php echo form_input('waktu_berakhir', date("Y-m-d"), array('class' => 'form-control waktu_berakhir','id'=>'waktu_berakhir','placeholder' => 'Isikan rupiah tagihan')); ?>
                                <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-waktuberlaku" class="btn btn-primary modalbtn">
                    <i class="fa fa-save" aria-hidden="true"></i> Submit
                </button>
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Ok</button>
            </div>
        </div>
    </div>
</div>
<div id="myDownload" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Download template</h5>
            </div>
            <div class="modal-body">
                <div class="col-sm-6">
                    <h4>Template kosong</h4>
                    <hr>
                    <br>
                    <a href="<?php echo base_url();?>assets/list_pembayaran.xls" id="download_template_kosong" class=" col-sm-12 btn btn-primary download_template_kosong">
                        <i class="fa fa-download" aria-hidden="true"></i> Download
                    </a>
                </div>
                <div class="col-sm-6">
                    <h4>Template Mahasiswa Aktif</h4>
                    <hr> Angkatan
                    <?php 
                  $arr['all']='Semua Angkatan';
                  for ($a=date('Y');$a>=date('Y')-10;$a--)
                          $arr[$a]=$a;
                  echo form_dropdown('cbdownload_template_angkatan', $arr,'', array('class' => 'form-control waktu_berlaku','id'=>'cbdownload_template_angkatan','placeholder' => 'Isikan rupiah tagihan')); ?>
                        Fakultas
                        <?php 
                  $download_template_fakultas=array("all"=>"Semua Fakultas")+$fakultas;
                  echo form_dropdown('cbdownload_template_fakultas', $download_template_fakultas,'', array('class' => 'form-control waktu_berlaku','id'=>'cbdownload_template_fakultas','placeholder' => 'Isikan rupiah tagihan')); ?>
                            <button id="download_template_mhs_aktif" class=" col-sm-12 btn btn-primary download_template_mhs_aktif">
                                <i class="fa fa-download" aria-hidden="true"></i> Download
                            </button>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Keluar</button>
            </div>
        </div>
    </div>
</div>
<div id="myUpload" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Upload template</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-6">
                        Waktu Tagihan Berlaku
                        <div class='input-group date'>
                            <?php echo form_input('waktu_berlaku_upload', date("Y-m-d")." 00:00:00", array('class' => 'form-control waktu_berlaku','id'=>'waktu_berlaku_upload','placeholder' => 'Isikan rupiah tagihan')); ?>
                                <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        Waktu Tagihan Berakhir
                        <div class='input-group'>
                            <?php echo form_input('waktu_berakhir_upload', date("Y-m-d")." 00:00:00", array('class' => 'form-control waktu_berakhir_upload','id'=>'waktu_berakhir_upload','placeholder' => 'Isikan rupiah tagihan')); ?>
                                <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br> File Excel
                        <input type="file" name="file" id="file" style="width: 100%" />
                        <br>
                        <button id="btn-upload" class="btn btn-upload">Upload</button>
                        
                        <div id="upload-msg"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Keluar</button>
            </div>
        </div>
    </div>
</div>
</section>
<link href="<?php echo base_url();?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script>
    var oTable;
    var id_delete = "";

    function setModalHapus(dom, row1) {
        var id = dom.data('id');
        id_delete = id;
        console.log(row1);
        $(".fa-spinner").hide();
        $("#btn-hapus").show();
        $("#btn-batal").html("Batal");
        $("#modal-hapus .modal-body").html("Anda yakin menghapus Pendaftar dengan Nama Peserta \"<span id='id-delete'></span>\"?");
        $("#id-delete").html(row1);
    }

    function hapus() {
        var id = id_delete;
        $.ajax({
            url: "<?php echo base_url('spp/tagihan_delete'); ?>",
            data: {
                'id': id
            },
            type: "GET",
            dataType: 'JSON',
            beforeSend: function() {
                $(".fa-spinner").show();
                $("#btn-hapus").attr("disabled", true);
                $("#btn-batal").attr("disabled", true);
            },
            success: function(data) {
                $(".fa-spinner").hide();
                $("#btn-hapus").removeAttr("disabled");
                $("#btn-batal").removeAttr("disabled");
                if (data.status) {
                    $("#btn-hapus").hide();
                    $("#btn-batal").html("Tutup");
                    oTable.api().ajax.reload();
                }
                $("#myHapus .modal-body").html(data.msg);
            },
        });
    }

    function detail(id) {
        $('#myDetail').modal("show");
        $.ajax({
            url: "<?php echo base_url('spp/detail/');?>" + id,

            type: "GET",
            success: function(data) {

                $(".detail").html(data);

            }
        });
    }

    function hapus_dialog(id) {
        //alert("asd");
        $('#myHapus').modal("show");
        id_delete = id;

    }

    function waktu_modal() {
        $('#myWaktuBerlaku').modal("show");

    }

    function upload_modal() {
        $('.progress').hide();
        $('#myUpload').modal("show");

    }

    function download_modal() {
        $('#myDownload').modal("show");

    }

    $(document).ready(function() {
       
        $('#waktu_berlaku,#waktu_berlaku_upload').datetimepicker({
            //pickerPosition:'top-right',
            language: 'id',
            format: "yyyy-mm-dd hh:ii:ss"
        });
        $('#waktu_berakhir,#waktu_berakhir_upload').datetimepicker({
            //pickerPosition:'top-right',
            language: 'id',
            format: "yyyy-mm-dd hh:ii:ss"
        });
        var formTitle = $("#modal-form #modal-title").html();
        var formBody = $("#modal-form .modal-body").html();
        var formFooter = $("#modal-form .modal-footer").html();
        $("#modal-form").modal({
            backdrop: "static",
            show: false
        });
        $("#modal-form").on("show.bs.modal", function() {
            $("#modal-form #modal-title").html(formTitle);
            $("#modal-form .modal-body").html(formBody);
            $("#modal-form .modal-footer").html(formFooter);
            $(".fa-spinner").hide();
            $("#pesan-error").hide();
        });
        oTable = $('#table-datatable').dataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.

            "order": [
                [4, "asc"]
            ],
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                <?php if ($kode_periode!=null){ ?>
                "url": "<?php echo site_url('spp/ajax_list/'.$kode_periode)?>",
                <?php } else { ?>
                "url": "<?php echo site_url('spp/ajax_list')?>",
                <?php } ?>
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            }, ],
        });
     $(".dataTables_filter").hide();
        $("#field-cari").on('keyup', function(e) {
            var code = e.which;
            if (code == 13) e.preventDefault();
            if (code == 32 || code == 13 || code == 188 || code == 186) {
                oTable.fnFilter($("#field-cari").val());
            }
        });
        $("#btn-cari").click(function() {
            oTable.fnFilter($("#field-cari").val());
        });

        $("#download_template_mhs_aktif").click(function() {
            var angkatan = $('#cbdownload_template_angkatan').val();
            var fakultas = $('#cbdownload_template_fakultas').val();
            window.location = "<?php echo base_url();?>spp/download_template_mhs_aktif/" + angkatan + "/" + fakultas;
        });
        $("#btn-filter-batal").click(function() {

            var NTypSource = '<?php echo base_url('pendaftar'); ?>';
            oTable.api().ajax.url(NTypSource).load();
        });
        //        $("#field-cari").on('keyup', function(e) {
        //            var code = e.which;
        //            if(code==13)e.preventDefault();
        //            if(code==32||code==13||code==188||code==186){
        //                oTable.fnFilter($("#field-cari").val());
        //            }
        //        });
        //        $("#btn-cari").click(function () {
        //            oTable.fnFilter($("#field-cari").val());
        //        });

        $("#btn-waktuberlaku").click(function() {
            $('#btn-waktuberlaku').html('Menyimpan...');
            $('#btn-waktuberlaku').prop('disabled', true);

            var form_data = new FormData();
            form_data.append("waktu_berlaku", $('#waktu_berlaku').val());
            form_data.append("waktu_berakhir", $('#waktu_berakhir').val());
            $.ajax({
                url: "<?php echo site_url('spp/set_waktuberlaku/'.$kode_periode)?>",
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,
                dataType: 'JSON',
                success: function(data) {

                    $(".waktuberlaku-msg").html(data.msg);
                    $('#btn-waktuberlaku').prop('disabled', false);
                    $('#btn-waktuberlaku').html('<i class="fa fa-save" aria-hidden="true"></i> Submit');
                    oTable.api().ajax.reload();

                }
            });
        });
        $("#btn-upload").click(function() {
           
            $('#btn-upload').html('Mengupload...');
            $('#btn-upload').prop('disabled', true);
               $('#upload-msg').html('<span class="fa fa-spinner fa-spin"></span> Sedang proses input data');
            var form_data = new FormData();
            form_data.append("file", $('#file')[0].files[0]);
            form_data.append("awal", $('#waktu_berlaku_upload').val());
            form_data.append("akhir", $('#waktu_berakhir_upload').val());
            form_data.append("kode_periode", "<?php echo $kode_periode;?>");

            $.ajax({
                url: "<?php echo site_url('spp/do_upload')?>",
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,
               
                success: function(data) {
                    $("#upload-msg").html(data);
                    $('#btn-upload').prop('disabled', false);
                    $('#btn-upload').html('<i class="fa fa-upload" aria-hidden="true"></i> Upload');
                    oTable.api().ajax.reload();

                }
            });
        });
    });
</script>