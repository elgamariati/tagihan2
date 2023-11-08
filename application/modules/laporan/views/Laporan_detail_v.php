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

    /* th {
        font-size: 30px;
    }

    td {
        font-size: 30px;
    } */
</style>
<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-7 col-md-7 col-sm-7 col-xs-7">
            <i class="fa fa-file"></i>
            <h3><span class="semi-bold">Pembayaran</span><span style="font-size : 11pt ;"> >> Laporan Data per Semester</span></h3>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <div class="dropdown" style="float : right ; margin-right : -14px">
                <a class="btn btn-primary" onclick="dialog_tambah()" href="#"> <span class="fa fa-download"></span> Unduh XLS</a>
            </div>
        </div>
        <div class="page-title col-lg-8 col-md-8 hidden-md hidden-xs hidden-sm">
            <i class="fa fa-info-circlee"></i>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Filter </h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <div class="row">
                            <form onsubmit="return false;" autocomplete="off">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Filter Keterangan -->
                                    <div class="nopad col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="control-label" style="margin-top:10px">Keterangan</label>
                                    </div>
                                    <div class="nopad col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <?php
                                        if ($this->session->userdata('user')['role'] !== "keuangan_pasca") {
                                            $ref_jnsKode = array("semua" => "Semua", "UKT" => "UKT", "Tes Psikologi" => "Tes Psikologi", "Tes Kesehatan" => "Tes Kesehatan", "Tes Bakat" => "Tes Bakat");
                                        } else {
                                            $ref_jnsKode = array("UKT" => "UKT");
                                        }
                                        echo form_dropdown('keterangan_db', $ref_jnsKode, set_value('keterangan_db'), array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
                                    </div>
                                    <!-- Filter Semester -->
                                    <div class="nopad col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="control-label" style="margin-top:10px">Semester</label>
                                    </div>
                                    <div class="nopad col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <?php
                                        $ref_periode = array();
                                        $i = 0;
                                        foreach ($periode as $p) {
                                            $ref_periode[$p->kode_periode] = $p->nama_periode;

                                            $i++;
                                        }
                                        echo form_dropdown('periode_db', $ref_periode, $kode_periode, array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Filter Fakultas -->
                                    <div class="nopad col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="control-label" style="margin-top:10px">Fakultas</label>
                                    </div>
                                    <div class="nopad col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <?php
                                        $ref_fakultas = array("semua" => "Semua Fakultas") + $fakultas;
                                        echo form_dropdown('fakultas_db', $ref_fakultas, set_value('semua'), array('class' => 'chosen-select form-select drop_select fakultas_db', 'style' => 'width:100%', 'readonly')); ?>
                                    </div>
                                    <!-- Filter Prodi -->
                                    <div class="nopad col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="control-label" style="margin-top:10px">Program Studi</label>
                                    </div>
                                    <div class="nopad col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <?php
                                        $ref_prodi = array("semua" => "Semua Program Studi");
                                        echo form_dropdown('prodi_db', $ref_prodi, 0, array('class' => 'chosen-select form-select drop_select prodi_db', 'style' => 'width:100%', 'readonly')); ?>
                                    </div>

                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Filter Tanggal Mulai -->
                                    <div class="nopad col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="control-label" style="margin-top:10px">Tanggal Mulai</label>
                                    </div>
                                    <div class="nopad col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class='input-group date'>
                                            <?php
                                            echo form_input('tanggal_mulai', set_value('tanggal_mulai', ''), array('class' => 'form-control tanggalwaktu', 'id' => 'tanggal_mulai', 'placeholder' => 'Tanggal mulai')); ?>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Filter Tanggal Akhir -->
                                    <div class="nopad col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="control-label" style="margin-top:10px">Tanggal Akhir</label>
                                    </div>
                                    <div class="nopad col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class='input-group date'>
                                            <?php
                                            echo form_input('tanggal_akhir', set_value('tanggal_akhir', ''), array('class' => 'form-control tanggalwaktu', 'id' => 'tanggal_akhir', 'placeholder' => 'Tanggal akhir')); ?>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Button Terapkan Filter -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="dropdown" style="float : right ;">
                                        <a class="btn btn-primary" onclick="dtb_reload()" href="#"> <span class="fa fa-filter"></span> Terapkan Filter</a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <hr>
                                </div>
                                <div class="col-md-12 pull-right">
                                    <div class="input-group">
                                        <input type="text" id="field-cari" class="form-control" name="field-cari" placeholder="Pencarian">
                                        <span class="input-group-btn">
                                            <a class="btn btn-primary" id="btn-cari" href="#" value="Cari"><i class="fa fa-search">&nbspCari</i></a>
                                        </span>
                                    </div>
                                    <br>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Laporan Pembayaran</h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <table class="table table-hover table-condensed" id="table-datatable" style="font-size:8px" width='100%'>
                            <thead>
                                <tr>
                                    <th width="1%">NO</th>
                                    <th width="14%">NIM</th>
                                    <th width="14%">NAMA</th>
                                    <th width="9%">JUMLAH TAGIHAN</th>
                                    <th width="9%">WAKTU</th>
                                    <th width="7%">KETERANGAN</th>
                                    <th width="7%">KANAL</th>
                                    <th width="6%">BANK</th>
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
<div id="modal-download" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Download XLS</div>
            <div class="modal-body">
                <div class="message_box"></div>
                <div class="form-group col-lg-12 col-md-12 ">
                    <label class="form-label">Periode Aktif</label>
                    <div class="controls">
                        <?php
                        $ref_periode = array();
                        $i = 0;
                        foreach ($periode as $p) {
                            $ref_periode[$p->kode_periode] = $p->nama_periode;

                            $i++;
                        }
                        echo form_dropdown('periode_dw', $ref_periode, $kode_periode, array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
                    </div>
                    <br>
                    <label class="form-label">Jenjang</label>
                    <select name="jenjang" class="chosen-select form-select drop_select" style="width:100%" readonly="true">
                        <?php
                        if ($sess['role'] == "superadmin") {
                            echo '
                            <option value="1">Semua</option>
                            <option value="2">D3, S1, Profesi</option>
                            <option value="3">S2, S3</option>
                            ';
                        } else if ($sess['role'] == 'keuangan_rektorat') {
                            echo '
                            <option value="2">D3, S1, Profesi</option>
                            <option value="3">S2, S3</option>
                            ';
                        } else if ($sess['role'] == 'keuangan_pasca') {
                            echo '
                            <option value="3">S2, S3</option>
                            ';
                        }
                        ?>
                    </select>
                    <br><br>
                    <label class="form-label">Keterangan</label>
                    <div class="controls">
                        <?php
                        $ref_jnsKode = array("" => "Semua", "UKT" => "UKT", "Tes Psikologi" => "Tes Psikologi", "Tes Kesehatan" => "Tes Kesehatan", "Tes Bakat" => "Tes Bakat");
                        echo form_dropdown('keterangan_dw', $ref_jnsKode, set_value('keterangan_db'), array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
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
                    <br>
                    <label class="form-label">Tanggal Mulai</label>
                    <div class="controls">
                        <div class='input-group date'>
                            <?php
                            echo form_input('tanggal_mulai_dw', set_value('tanggal_mulai_dw', ''), array('class' => 'form-control tanggalwaktu', 'id' => 'tanggal_mulai_dw', 'placeholder' => 'Tanggal mulai')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <br>
                    <label class="form-label">Tanggal Mulai</label>
                    <div class="controls">
                        <div class='input-group date'>
                            <?php
                            echo form_input('tanggal_akhir_dw', set_value('tanggal_akhir_dw', ''), array('class' => 'form-control tanggalwaktu', 'id' => 'tanggal_akhir_dw', 'placeholder' => 'Tanggal akhir')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
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
</section>

<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script>
    var oTable;
    var id_delete = "";

    function do_download() {
        let periode = $('[name=periode_dw]').val();
        let jenjang = $('[name=jenjang]').val();
        let keterangan = $('[name=keterangan_dw]').val();
        let kode_fakultas = $('[name=kode_fakultas]').val();
        let kode_prodi = $('[name=kode_prodi]').val();

        if (!jenjang) {
            jenjang = 'semua';
        }
        if (!keterangan) {
            keterangan = 'semua';
        }
        if (!kode_fakultas) {
            kode_fakultas = 'semua';
        }
        if (!kode_prodi) {
            kode_prodi = 'semua';
        }
        let tanggal_mulai_dw = $('[name=tanggal_mulai_dw]').val()
        if (!tanggal_mulai_dw) {
            tanggal_mulai_dw = 'semua'
        }
        let tanggal_akhir_dw = $('[name=tanggal_akhir_dw]').val()
        if (!tanggal_akhir_dw) {
            tanggal_akhir_dw = 'semua'
        }

        periode = $('[name=periode_dw] option:selected').val();

        window.open("<?php echo base_url('laporan/download_xls/'); ?>" +
            jenjang + "/" +
            keterangan + "/" +
            kode_fakultas + "/" +
            kode_prodi + "/" +
            periode + "/" +
            tanggal_mulai_dw + "/" +
            tanggal_akhir_dw
        );
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

    function get_prodi_filter(sourceClassName, targetClassName, reloadDtb = 0) {
        $(`.${targetClassName}`).attr('disabled', 'disabled');
        $.ajax({
            url: "<?php echo base_url(); ?>laporan/get_prodi_new",
            type: 'POST',
            data: {
                'fakultas': $(`.${sourceClassName}`).val()
            },
            dataType: 'json',
            success: function(data) {
                console.log(data);
                $(`.${targetClassName}`).removeAttr('disabled')
                $(`.${targetClassName}`).find('option').remove().end()
                $.each(data, function(dt_id, dt_val) {
                    $(`.${targetClassName}`).append(`<option value="${dt_val.key}">${dt_val.value}</option>`)
                });
                if (reloadDtb == 1) {
                    dtb_reload()
                }
            }
        });
    }

    let base_url = '<?php echo site_url('laporan/detail_pembayaran/') ?>';

    function update_dtb_url() {
        let tanggal_mulai = $('#tanggal_mulai').val() !== '' ? $('#tanggal_mulai').val() : 'semua'
        let tanggal_akhir = $('#tanggal_akhir').val() !== '' ? $('#tanggal_akhir').val() : 'semua'
        let url = base_url + $('[name=periode_db] option:selected').val() +
            "/" + $('[name=keterangan_db] option:selected').val() +
            "/" + $('[name=fakultas_db] option:selected').val() +
            "/" + $('[name=prodi_db] option:selected').val() +
            "/" + tanggal_mulai +
            "/" + tanggal_akhir
        return url;
    }

    // Reloading DTB using updated url (filter)
    function dtb_reload() {
        oTable.api().ajax.url(update_dtb_url()).load();
    }

    $(document).ready(function() {

        // Filter Fakultas onchange
        $('.fakultas').change(function(event, a) {
            get_prodi_filter('fakultas', 'prodi')
        });

        // docs : https://www.malot.fr/bootstrap-datetimepicker/
        $('[class="form-control tanggalwaktu"]').datetimepicker({
            autoclose: true,
            minView: 0,
            format: 'dd-mm-yyyy hh:ii',
            language: 'id',
            pickerPosition: 'top-left',
        }).on('keypress paste', function(e) {
            e.preventDefault();
            return false;
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

        //Filter DTB
        $('.fakultas_db').change(function(event, a) {
            get_prodi_filter('fakultas_db', 'prodi_db', 0)
        });


        oTable = $('#table-datatable').dataTable({
            "ajax": {
                "url": update_dtb_url(),
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