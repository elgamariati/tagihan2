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
        <div class="page-title col-lg-7 col-md-7 col-sm-7 col-xs-7">
            <i class="fa fa-file"></i>
            <h3><span class="semi-bold">Laporan Mahasiswa Aktif</span><span style="font-size : 11pt ;"> >> Laporan Data per Semester</span></h3>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <div class="dropdown" style="float : right ; margin-right : -14px">
                <a class="btn btn-primary" onclick="dialog_tambah()" href="#"> <span class="fa fa-download"></span> Unduh XLS</a>
            </div>
        </div>
        <div class="page-title col-lg-8 col-md-8 hidden-md hidden-xs hidden-sm">
            <i class="fa fa-info-circlee"></i>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <!-- <div class="nopad col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label class="control-label" style="margin-top:10px">Keterangan</label>
                </div>
                 <div class="nopad col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <?php
                    //$ref_jnsKode = array("UKT" => "UKT");
                    //echo form_dropdown('keterangan_db', $ref_jnsKode, set_value('keterangan_db'), array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); 
                    ?>
                </div> -->
                <!-- <div class="nopad col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <button class="btn btn-primary">Terapkan</button>
                </div> -->
            </div>
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <input type="text" id="field-cari" class="form-control" name="field-cari" placeholder="Pencarian">
                    <span class="input-group-btn">
                        <a class="btn btn-primary" id="btn-cari" href="#" value="Cari"><i class="fa fa-search">&nbspCari</i></a>
                    </span>
                </div>
                <br>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Laporan Mahasiswa Aktif <?php echo $label_nama_periode . $label_jenjang; ?></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <table class="table table-hover table-condensed" id="table-datatable" style="font-size:8px" width='100%'>
                            <thead>
                                <tr>
                                    <th width="1%">NO</th>
                                    <th width="15%">NIM</th>
                                    <th width="17%">NAMA</th>
                                    <th width="6%">ANGKATAN</th>
                                    <th width="6%">STRATA</th>
                                    <th width="15%">PRODI</th>
                                    <th width="15%">FAKULTAS</th>

                                    <!-- <th width="20%">AKSI</th> -->
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
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="myWaktuBerlaku" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Setting Masa Tagihan <br /><br /><span style="color:blue">Periode <?php echo $kode_periode . $label_jenjang ?></span></h5>
            </div>
            <div class="modal-body">
                <div class="waktuberlaku-msg"></div>
                <div class="form-group">
                    <div class="col-sm-6">
                        Waktu Tagihan Berlaku
                        <div class='input-group date'>
                            <?php echo form_input('waktu_berlaku', date("Y-m-d") . " 00:00:00", array('class' => 'form-control waktu_berlaku', 'id' => 'waktu_berlaku', 'placeholder' => 'Isikan rupiah tagihan')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        Waktu Tagihan Berakhir
                        <div class='input-group'>
                            <?php echo form_input('waktu_berakhir', date("Y-m-d") . " 23:59:00", array('class' => 'form-control waktu_berakhir', 'id' => 'waktu_berakhir', 'placeholder' => 'Isikan rupiah tagihan')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-waktuberlaku" class="btn btn-primary modalbtn">Ubah Waktu Tagihan</button>
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="myDownload" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Unduh Template</h5>
            </div>
            <div class="modal-body">
                <div class="col-sm-6">
                    <h4>Template Kosong</h4>
                    <hr>
                    <br>
                    <a href="<?php echo base_url(); ?>assets/update_tagihan_massal.xls" id="download_template_kosong" class=" col-sm-12 btn btn-primary download_template_kosong">
                        <i class="fa fa-download" aria-hidden="true"></i> Download
                    </a>
                </div>
                <div class="col-sm-6">
                    <h4>Template Mahasiswa Aktif</h4>
                    <hr> Angkatan
                    <?php
                    $arr['all'] = 'Semua Angkatan';
                    for ($a = date('Y'); $a >= date('Y') - 10; $a--)
                        $arr[$a] = $a;
                    echo form_dropdown('cbdownload_template_angkatan', $arr, '', array('class' => 'form-control waktu_berlaku', 'id' => 'cbdownload_template_angkatan', 'placeholder' => 'Isikan rupiah tagihan'));
                    ?>
                    Fakultas
                    <?php
                    $download_template_fakultas = array("nonukt" => "Semua Fakultas") + $fakultas;
                    echo form_dropdown('cbdownload_template_fakultas', $download_template_fakultas, '', array('class' => 'form-control waktu_berlaku', 'id' => 'cbdownload_template_fakultas', 'placeholder' => 'Isikan rupiah tagihan'));
                    ?>
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

<div id="modal-download" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Download XLS</div>
            <div class="modal-body">
                <div class="message_box"></div>
                <div class="form-group col-lg-12 col-md-12 ">
                    <label class="form-label">Periode Aktif</label>
                    <div class="controls">
                        <p><b><?php echo $this->session->userdata('user')['periode_text'] ?></b></p>
                    </div>

                    <br>
                    <label class="form-label">Fakultas</label>
                    <div class="controls">
                        <?php
                        $ref_fakultas = array("" => "Semua Fakultas") + $fakultas;
                        echo form_dropdown('kode_fakultas', $ref_fakultas, set_value('kode_fakultas'), array('class' => 'chosen-select form-select drop_select fakultas', 'style' => 'width:100%', 'readonly'));
                        ?>
                    </div>
                    <br>
                    <label class="form-label">Program Studi</label>
                    <div class="controls">
                        <?php
                        $ref_prodi = array("" => "Semua Program Studi");
                        echo form_dropdown('kode_prodi', $ref_prodi, 0, array('class' => 'chosen-select form-select drop_select prodi', 'style' => 'width:100%', 'readonly')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="btn-tambah" class="btn btn-primary" onclick="do_download();">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-save" aria-hidden="true"></i> Download
                </button>
                <button id="btn-tambah-batal" data-dismiss="modal" class="btn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="myUpload" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Unggah Template Tagihan</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-6">
                        Waktu Tagihan Berlaku
                        <div class='input-group date'>
                            <?php echo form_input('waktu_berlaku_upload', date("Y-m-d") . " 00:00:00", array('class' => 'form-control waktu_berlaku', 'id' => 'waktu_berlaku_upload', 'placeholder' => 'Isikan tanggal mulai tagihan')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        Waktu Tagihan Berakhir
                        <div class='input-group'>
                            <?php echo form_input('waktu_berakhir_upload', date("Y-m-d") . " 23:59:00", array('class' => 'form-control waktu_berakhir_upload', 'id' => 'waktu_berakhir_upload', 'placeholder' => 'Isikan tanggal berakhir tagihan')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br> File Excel
                        <input type="file" name="file" id="file" style="width: 100%" />
                        <br>
                        <div id="upload-msg"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-upload" class="btn btn-primary"><i class="fa fa-upload" aria-hidden="true"></i> Unggah</button>
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Keluar</button>
            </div>
        </div>
    </div>
</div>
</section>

<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script>
    var oTable;
    var id_delete = "";

    function do_download() {
        let kode_fakultas = $('[name=kode_fakultas]').val();
        let kode_prodi = $('[name=kode_prodi]').val();

        if (!kode_fakultas) {
            kode_fakultas = 0;
        }
        if (!kode_prodi) {
            kode_prodi = 0;
        }
        window.open("<?php echo base_url('laporan/mhs_aktif_download/'); ?>" + kode_fakultas + "/" + kode_prodi);
    }

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


    function dialog_tambah() {
        $("#modal-download").modal("show");
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
                }
                oTable.api().ajax.reload();
                $("#myHapus .modal-body").html(data.msg);
            },
        });
    }

    function detail(id) {
        $('#myDetail').modal("show");
        $.ajax({
            url: "<?php echo base_url('spp/detail/'); ?>" + id,
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
        $(".waktuberlaku-msg").html('');
        $('#myWaktuBerlaku').modal("show");
    }

    function upload_modal() {
        $('.progress').hide();
        $('#myUpload').modal("show");
    }

    function download_modal() {
        $('#myDownload').modal("show");
    }

    let url = '<?php echo site_url('laporan/mhs_aktif/') ?>' + '/';

    $(document).ready(function() {
        $('#waktu_berlaku, #waktu_berlaku_upload').datetimepicker({
            language: 'id',
            format: "yyyy-mm-dd hh:ii:ss"
        });
        $('#waktu_berakhir, #waktu_berakhir_upload').datetimepicker({
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

        $('[name=keterangan_db]').change(function(event, a) {
            keterangan = $('[name=keterangan_db] option:selected').val();
            oTable.api().ajax.url(url + keterangan).load();
        });

        oTable = $('#table-datatable').dataTable({
            "ajax": {
                "url": url + $('[name=keterangan_db] option:selected').val(),
                "type": "POST",
            },
            processing: true,
            "sDom": "<'row'<'col-sm-6'><'col-sm-6'>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            }, ],
        });

        $("#field-cari").on('keyup', function(e) {
            var code = e.which;
            if (code == 13) e.preventDefault();
            if (code == 32 || code == 13 || code == 188 || code == 186) {
                oTable.fnFilter($("#field-cari").val().trim());
            }
        });
        $("#btn-cari").click(function() {
            oTable.fnFilter($("#field-cari").val().trim());
        });

        // keterangan = $('[name=keterangan_db] option:selected').text();
        // oTable.api().ajax.url(url + keterangan).load();

        $("#download_template_mhs_aktif").click(function() {
            var angkatan = $('#cbdownload_template_angkatan').val();
            var fakultas = $('#cbdownload_template_fakultas').val();
            window.location = "<?php echo base_url(); ?>spp/download_template_mhs_aktif/" + angkatan + "/" + fakultas;
        });

        $("#btn-filter-batal").click(function() {

            var NTypSource = '<?php echo base_url('pendaftar'); ?>';
            oTable.api().ajax.url(NTypSource).load();
        });


        $("#btn-waktuberlaku").click(function() {
            $('#btn-waktuberlaku').html('Menyimpan...');
            $('#btn-waktuberlaku').prop('disabled', true);

            var form_data = new FormData();
            form_data.append("waktu_berlaku", $('#waktu_berlaku').val());
            form_data.append("waktu_berakhir", $('#waktu_berakhir').val());
            $.ajax({
                url: "<?php echo site_url('spp/set_waktuberlaku/' . $kode_periode) ?>",
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,
                dataType: 'JSON',
                success: function(data) {
                    $(".waktuberlaku-msg").html(data.msg);
                    $('#btn-waktuberlaku').prop('disabled', false);
                    $('#btn-waktuberlaku').html('Ubah Waktu Tagihan');
                    oTable.api().ajax.reload();
                }
            });
        });

        $('.fakultas').change(function(event, a) {
            //alert($(this).val());
            $('.prodi').html("<option>Loading...</option>");
            $.ajax({
                url: "<?php echo base_url(); ?>laporan/get_prodi",
                type: 'POST',
                data: {
                    'fakultas': $(this).val()
                },
                success: function(data) {
                    $('.prodi').html(data);
                    if (a != null)
                        $('.prodi option[value=' + a + ']').prop('selected', true);
                    else
                        $('.prodi option[value=<?php echo set_value('kode_prodi', 0) ?>]').prop('selected', true);
                }
            });
        });

        $("#btn-upload").click(function() {
            $('#btn-upload').html('<i class="fa fa-upload" aria-hidden="true"></i> Unggah');
            $('#btn-upload').prop('disabled', true);
            $('#upload-msg').html('<span class="fa fa-spinner fa-spin"></span> Proses');
            var form_data = new FormData();
            form_data.append("file", $('#file')[0].files[0]);
            form_data.append("awal", $('#waktu_berlaku_upload').val());
            form_data.append("akhir", $('#waktu_berakhir_upload').val());
            form_data.append("kode_periode", "<?php echo $kode_periode; ?>");

            $.ajax({
                url: "<?php echo site_url('spp/do_upload') ?>",
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,

                success: function(data) {
                    $("#upload-msg").html(data);
                    $('#btn-upload').prop('disabled', false);
                    $('#btn-upload').html('<i class="fa fa-upload" aria-hidden="true"></i> Unggah');
                    oTable.api().ajax.reload();
                }
            });
        });
    });
</script>