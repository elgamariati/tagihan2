<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">PENGATURAN</span><span style="font-size : 11pt ;"> >> Periode Bayar</span></h3>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-9">
            <button class="btn btn-primary pull-right" type="button" onclick="dialog_tambah();"> Tambah Periode</button>
        </div>
        <div class="row">
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <input type="text" id="field-cari" class="form-control" name="field-cari">
                    <span class="input-group-btn">
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
                        <h4>Periode</h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <table class="table table-hover table-condensed" id="table-datatable" style="font-size:8px" width='100%'>
                            <thead>
                                <tr>
                                    <th width="10%">NO</th>
                                    <th width="20%">KODE PERIODE </th>
                                    <th width="30%">NAMA PERIODE </th>
                                    <th width="25%">STATUS AKTIF </th>
                                    <th width="15%">AKSI</th>
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
            <div class="modal-header">Hapus Periode</div>
            <div class="modal-body">
                <div class="form-group col-lg-12 col-md-12 message_box_hapus">
                    Apakah anda yakin ingin menghapus periode ?
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="btn-hapus" class="btn btn-primary" onclick="hapus();">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-trash" aria-hidden="true"></i> Hapus
                </button>
                <button id="btn-hapus-batal" data-dismiss="modal" class="btn">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-tambah" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Tambah Periode</div>
            <div class="modal-body">
                <div class="message_box"></div>
                <?php echo form_open(current_url(), array('id' => "form-simpan", 'class' => 'form-horizontal col-md-12 col-sm-12 col-xs-12')); ?>
                <div class="form-group col-lg-12 col-md-12 ">
                    <label class="form-label">Periode Tahun</label>
                    <div class="controls">
                        <?php
                        echo form_input('tahun', '', array('id' => 'tahun', 'class' => 'form-control', 'placeholder' => 'Tahun periode'));

                        $data = array(
                            'type'  => 'hidden',
                            'name'  => 'mode',
                            'id'    => 'mode',
                            'value' => ''
                        );
                        echo form_input($data);
                        ?>
                    </div>
                    <br>
                    <label class="form-label">Periode Semester</label>
                    <div class="controls">
                        <?php
                        $ref_semester = array("1" => "Ganjil", "2" => "Genap", "3" => "Antara");
                        echo form_dropdown('semester', $ref_semester, '', array('id' => 'semester', 'class' => 'form-control'));
                        ?>
                    </div>
                    <br>
                    <label class="form-label">Status Aktif</label>
                    <div class="controls">
                        <?php
                        $ref_aktif = array("Ya" => "Ya", "Tidak" => "Tidak");
                        echo form_dropdown('is_aktif', $ref_aktif, '', array('id' => 'is_aktif', 'class' => 'form-control'));
                        ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="btn-tambah" class="btn btn-primary" onclick="do_tambah();">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-save" aria-hidden="true"></i> Simpan
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
    $('#mode').val('');

    function reload_data() {
        var url = '<?php echo base_url('periode/ajax_list'); ?>';
        oTable.api().ajax.url(url).load();
    };

    function hapus_dialog(id) {
        //alert("asd");
        $(".message_box_hapus").html("Anda yakin akan menghapus periode <b>" + id + "</b>");
        $("#btn-tambah-batal").html("Batal");
        $('#myHapus').modal("show");
        id_delete = id;
    }

    function hapus() {
        $.ajax({
            url: "<?php echo base_url('periode/periode_delete'); ?>",
            data: {
                'kode_periode': id_delete
            },
            type: "POST",
            dataType: 'JSON',
            beforeSend: function() {
                $(".fa-spinner").show();
                $("#btn-hapus").attr("disabled", true);
                $("#btn-hapus-batal").attr("disabled", true);
            },
            success: function(data) {
                $(".fa-spinner").hide();
                $("#btn-hapus").removeAttr("disabled");
                $("#btn-hapus-batal").removeAttr("disabled");
                if (data.status) {
                    $("#btn-hapus").hide();
                    $("#btn-hapus-batal").html("Tutup");
                    $(".message_box_hapus").html("<div class='alert alert-success'><i class='fa fa-check' aria-hidden='true'></i> " + data.message + "</div>");
                    reload_data();
                } else {
                    $(".message_box_hapus").html("<div class='alert alert-error'><i class='fa fa-close' aria-hidden='true'></i> " + data.message + "</div>");
                }
            },
        });
    }

    function edit_dialog(id) {
        $('#mode').val('edit');
        $.ajax({
            url: "<?php echo base_url('periode/periode_edit'); ?>",
            data: {
                'id': id
            },
            type: "POST",
            dataType: 'JSON',
            beforeSend: function() {
                $(".fa-spinner").show();
            },
            success: function(data) {
                $(".fa-spinner").hide();
                $("#btn-tambah").removeAttr("disabled");
                $("#btn-tambah-batal").removeAttr("disabled");
                if (data.status) {
                    $("#btn-tambah").show();
                    //$("#btn-tambah-batal").html("Tutup");
                    $('#tahun').val(data.data.kode_periode);
                    $('#semester').val(data.data.semester);
                    $('#is_aktif').val(data.data.is_aktif);
                } else {
                    $(".message_box").html("<div class='alert alert-error'><i class='fa fa-close' aria-hidden='true'></i> " + data.message + "</div>");
                }
            },
        });
        $("#modal-tambah").modal("show");
    }

    function dialog_tambah() {
        $('#mode').val('');
        $(".message_box").html("");
        $("#btn-tambah-batal").html("Batal");
        $("#btn-tambah").show();
        $("#modal-tambah").modal("show");
    }

    function do_tambah() {
        var data = $("#form-simpan").serialize();
        $.ajax({
            url: "<?php echo base_url('periode/periode_add'); ?>",
            data: data,
            type: "POST",
            dataType: 'JSON',
            beforeSend: function() {
                $(".fa-spinner").show();
                $("#btn-tambah").attr("disabled", true);
                $("#btn-tambah-batal").attr("disabled", true);
            },
            success: function(data) {
                $(".fa-spinner").hide();
                $("#btn-tambah").removeAttr("disabled");
                $("#btn-tambah-batal").removeAttr("disabled");
                if (data.status) {
                    $("#btn-tambah").hide();
                    $("#btn-tambah-batal").html("Tutup");
                    $(".message_box").html("<div class='alert alert-success'><i class='fa fa-check' aria-hidden='true'></i> " + data.message + "</div>");
                    reload_data();
                    location.reload();
                } else {
                    $(".message_box").html("<div class='alert alert-error'><i class='fa fa-close' aria-hidden='true'></i> " + data.message + "</div>");
                }
            },
        });

    }

    $(document).ready(function() {
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

                "url": "<?php echo site_url('periode/ajax_list') ?>",
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
                oTable.fnFilter($("#field-cari").val().trim());
            }
        });
        $("#btn-cari").click(function() {
            oTable.fnFilter($("#field-cari").val().trim());
        });

        $("#download_template_mhs_aktif").click(function() {
            var angkatan = $('#cbdownload_template_angkatan').val();
            var fakultas = $('#cbdownload_template_fakultas').val();
            window.location = "<?php echo base_url(); ?>spp/download_template_mhs_aktif/" + angkatan + "/" + fakultas;
        });
        $("#btn-filter-batal").click(function() {
            var NTypSource = '<?php echo base_url('pendaftar'); ?>';
            oTable.api().ajax.url(NTypSource).load();
        });

    });
</script>