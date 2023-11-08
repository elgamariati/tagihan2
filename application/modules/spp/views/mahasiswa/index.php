<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Tagihan Uang Kuliah</span><span style="font-size : 11pt ;"> >> Pengelolaan Data per Mahasiswa</span></h3>
        </div>

        <div class="page-title col-lg-8 col-md-8 hidden-md hidden-xs hidden-sm">
            <i class="fa fa-info-circlee"></i>
        </div>

        <div class="row-fluid">
            <div class="col-sm-12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Data Penerima Tagihan<?php echo $label_jenjang; ?></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body">
                        <div class="col-sm-5 row">
                            <div class="cari_nim">
                                <?php echo form_open(current_url(), array('class' => 'myform form-horizontal col-md-12 col-sm-12 col-xs-12')); ?>
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="nomor_induk">NIM</label>
                                    <div class="col-sm-9">
                                        <?php echo form_input('nomor_induk', '', array('class' => 'form-control nomor_induk', 'placeholder' => 'Isikan nomor induk')); ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-9">
                                    <a href="#" class="btn btn-success proses">Cari Tagihan <span class="fa fa-spinner fa-spin"></span></a>
                                </div>
                                </form>
                            </div>
                            <div class="hasil_nim" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="nomor_induk">NIM</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_nim">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="nama">Nama</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_nama">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="angkatan">Angkatan</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_angkatan">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="prodi">Prodi</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_prodi">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="fakultas">Fakultas</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_fakultas">
                                    </label>
                                </div>
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-9">
                                    <a href="#" class="btn btn-success reset">Cari Lagi</a>
                                    <a href="#" class="btn btn-success" onclick="add_modal();">Tambah Tagihan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <table class="table table-hover table-condensed" id="table-datatable" style="font-size:8px" width='100%'>
                                <thead>
                                    <tr>
                                        <th width="35%">ID</th>
                                        <th width="35%">No. Tagihan </th>
                                        <th width="25%">Semester </th>
                                        <th width="15%">Jumlah</th>
                                        <th width="25%">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="col-sm-1" style="background-color:#f8cdcd">&nbsp;</div>
                            <div class="col-sm-11">Tagihan "Tidak Aktif"</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<div id="myAdd" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Tambah Tagihan</h5>
            </div>
            <div class="modal-body">
                <div class="simpan-msg"></div>
                <div class="form-group">
                    <div class="col-sm-12">
                        Periode
                        <div>
                            <?php echo form_dropdown('periode', $list_periode, '', array('class' => 'form-control periode', 'id' => 'periode')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        Nilai Tagihan
                        <div>
                            <?php echo form_input('total_nilai_tagihan', '', array('class' => 'form-control total_nilai_tagihan', 'id' => 'total_nilai_tagihan', 'placeholder' => 'Isikan rupiah tagihan')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        Status Tagihan
                        <div>
                            <?php
                            echo form_dropdown('is_tagihan_aktif', $ref_aktif, '', array('class' => 'form-control is_tagihan_aktif', 'id' => 'is_tagihan_aktif'));
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        Status Mahasiswa
                        <div>
                            <?php
                            echo form_dropdown('jnsKode', $ref_jnsKode, '', array('class' => 'form-control jnsKode', 'id' => 'jnsKode'));
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        Waktu Tagihan Berlaku
                        <div class='input-group date'>
                            <?php echo form_input('waktu_berlaku', date("d-m-Y") . " 00:00:00", array('class' => 'form-control waktu_berlaku', 'id' => 'waktu_berlaku', 'placeholder' => 'Isikan rupiah tagihan')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        Waktu Tagihan Berakhir
                        <div class='input-group'>
                            <?php echo form_input('waktu_berakhir', date("d-m-Y") . " 23:59:00", array('class' => 'form-control waktu_berakhir', 'id' => 'waktu_berakhir', 'placeholder' => 'Isikan rupiah tagihan')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-simpan" class="btn btn-primary modalbtn">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-save" aria-hidden="true"></i> Simpan
                </button>
                <button id="btn-simpan-batal" data-dismiss="modal" class="btn modalbtn">Tutup</button>
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

<div id="myError" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title" class="box_error_header"></h5>
            </div>
            <div class="modal-body box_error_konten"></div>
            <div class="modal-footer">
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Tutup</button>
            </div>
        </div>
    </div>
</div>
<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script>
    var oTable;
    var id_delete = "";
    var is_allow_add = false;
    var nomor_induk = "";
    var id_record_tagihan = "";

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

    function add_modal() {
        $("#btn-simpan").show();
        $(".simpan-msg").html("");
        id_record_tagihan = "";
        if (is_allow_add) {
            $('#myAdd').modal("show");
            $("#periode").attr("disabled", false);
        } else {
            $(".box_error_header").html("Operasi Gagal");
            $(".box_error_konten").html("Silakan cari NIM yang tagihannya ingin ditambahkan");
            $('#myError').modal("show");
        }

    }

    function edit_modal(id) {
        $("#btn-simpan").show();
        $(".simpan-msg").html("");
        if (is_allow_add) {
            $('#myAdd').modal("show");
            $.ajax({
                url: "<?php echo base_url('spp/mahasiswa/getTagihan'); ?>",
                data: {
                    'id_record_tagihan': id
                },
                type: "POST",
                dataType: 'JSON',
                beforeSend: function() {},
                success: function(data) {
                    id_record_tagihan = id;
                    $("#is_tagihan_aktif option[value='" + data.is_tagihan_aktif + "']").prop("selected", true);
                    $("#periode option[value='" + data.kode_periode + "']").prop("selected", true);
                    $("#periode").attr("disabled", true);
                    $("#jnsKode option[value='" + data.jnsKode + "']").prop("selected", true);
                    $('#total_nilai_tagihan').val(data.total_nilai_tagihan);
                    $('#waktu_berlaku').val(data.waktu_berlaku);
                    $('#waktu_berakhir').val(data.waktu_berakhir);
                },
            });
        } else {
            $(".box_error_header").html("Operasi Gagal");
            $(".box_error_konten").html("Menu edit tagihan tidak berfungsi sebelum anda mensubmit nomor induk.");
            $('#myError').modal("show");
        }

    }

    $(document).ready(function() {
        $('#waktu_berlaku,#waktu_berlaku_upload').datetimepicker({
            autoclose: true,
            format: 'dd-mm-yyyy hh:ii:ss',
        }).on('keypress paste', function(e) {
            e.preventDefault();
            return false;
        });
        $('#waktu_berakhir,#waktu_berakhir_upload').datetimepicker({
            autoclose: true,
            format: 'dd-mm-yyyy hh:ii:ss',
        }).on('keypress paste', function(e) {
            e.preventDefault();
            return false;
        });
        $('.total_nilai_tagihan').maskMoney({
            allowNegative: true,
            thousands: '.',
            precision: 0
        });
        $(".hasil_nim").hide();
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
            "language": {
                processing: '<div class="alert alert-danger">Memroses data <span class="fa fa-spinner fa-spin"></span></div>'
            },
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [
                [2, "desc"]
            ],
            columns: [{
                    data: '6'
                },
                {
                    data: '1'
                },
                {
                    data: '2'
                },
                {
                    data: '5'
                },
                {
                    data: '7'
                }
            ],
            "bPaginate": false,
            "bInfo": false,

            "rowCallback": function(row, data, index) {
                if (data[8] == 0)
                    $('td', row).css('background-color', '#f8cdcd');
            },

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('spp/mahasiswa/ajax_list') ?>",
                "type": "POST",
                "data": function(d) {
                    return $.extend({}, d, {
                        "nomor_induk": $('.nomor_induk').val()
                    });
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            }, ],
        });

        $(".dataTables_filter, .dataTables_length").hide();

        $(document).on('click', '.proses', function(form) {
            $.ajax({
                url: "<?php echo base_url('spp/mahasiswa/get_mahasiswa'); ?>",
                data: {
                    'nomor_induk': $(".nomor_induk").val()
                },
                type: "POST",
                dataType: 'JSON',
                beforeSend: function() {
                    $(".fa-spinner").show();
                },
                success: function(data) {
                    $(".fa-spinner").hide();
                    if (data.status) {
                        nomor_induk = $(".nomor_induk").val();
                        is_allow_add = true;
                        $(".cari_nim").hide();
                        $(".hasil_nim").show();
                        $(".hasil_cari_nim").html($(".nomor_induk").val());
                        $(".hasil_cari_nama").html(data.data.mhsNama);
                        $(".hasil_cari_angkatan").html(data.data.mhsAngkatan);
                        $(".hasil_cari_prodi").html(data.data.prodiNamaResmi);
                        $(".hasil_cari_fakultas").html(data.data.fakNamaResmi);
                        oTable.api().ajax.reload();
                    } else {
                        $(".box_error_header").html("Pencarian Gagal");
                        $(".box_error_konten").html(data.msg);
                        $('#myError').modal("show");
                    }
                },
            });
        });

        $(document).on('click', '.reset', function(form) {
            is_allow_add = false;
            $(".nomor_induk").val("");
            oTable.api().ajax.reload();
            $(".cari_nim").show();
            $(".hasil_nim").hide();

        });

        $(document).on('click', '#btn-simpan', function(form) {
            $.ajax({
                url: "<?php echo base_url('spp/mahasiswa/simpan_tagihan'); ?>",
                data: {
                    'id_record_tagihan': id_record_tagihan,
                    'nomor_induk': nomor_induk,
                    'periode': $("#periode").val(),
                    'is_tagihan_aktif': $("#is_tagihan_aktif").val(),
                    'jnsKode': $("#jnsKode").val(),
                    'prioritas': 2345,
                    'total_nilai_tagihan': $('#total_nilai_tagihan').val(),
                    'waktu_berlaku': $('#waktu_berlaku').val(),
                    'waktu_berakhir': $('#waktu_berakhir').val(),
                    'keterangan': 'UKT'
                },
                type: "POST",
                dataType: 'JSON',
                beforeSend: function() {
                    $(".fa-spinner").show();
                    $("#btn-simpan").attr("disabled", true);
                    $("#btn-simpan-batal").attr("disabled", true);
                },
                success: function(data) {
                    $(".fa-spinner").hide();
                    $("#btn-simpan").removeAttr("disabled");
                    $("#btn-simpan-batal").removeAttr("disabled");
                    $(".simpan-msg").html(data.msg);
                    if (data.status) {
                        $("#btn-simpan").hide();
                        oTable.api().ajax.reload();
                    }

                },
            });
        });

    });
</script>