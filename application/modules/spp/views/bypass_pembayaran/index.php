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

    .nopad {
        padding: 0 0 0 0;
    }
</style>
<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Daftar Penihilan</span><span style="font-size : 11pt ;"></span></h3>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div style="float : right ; margin-right : -14px">
                <a class="btn btn-primary" type="button" href="<?php echo base_url('spp/bypasspembayaran/download_penihilan') ?>" target="_blank"><span class="fa fa-download"></span> Unduh XLS</a>
            </div>
        </div>
        <div class="nopad col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="nopad col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="control-label" style="margin-top:8px">Filter Range Waktu</label>
            </div>
            <div class="nopad col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <input id="awal" type="text" class="form-control tanggalwaktu" placeholder="Awal" />
            </div>
            <div class="nopad col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <input id="akhir" type="text" class="form-control tanggalwaktu" placeholder="Akhir" />
            </div>
            <div class="nopad col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <button class="btn btn-primary" onclick="filter()">Terapkan</button>
            </div>
        </div>
        <div class="nopad col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-right">
            <div class="input-group">
                <input type="text" id="field-cari" class="form-control" name="field-cari">
                <span class="input-group-btn">
                    <a class="btn btn-primary" id="btn-cari" href="#" value="Cari"><i class="fa fa-search"></i></a>
                </span>
            </div>
            <br>
        </div>
        <div class="nopad col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="nopad col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="control-label" style="margin-top:8px">Pembatalan Penihilan Massal :</label>
            </div>
            <div class="nopad col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <a class="btn btn-primary" type="button" href="<?php echo base_url('assets/template_download_penihilan.xls') ?>" target="_blank"><span class="fa fa-cloud-download"></span> Unduh Template</a>
                <button class="btn btn-primary" type="button" onclick="upload_modal()"><span class="fa fa-cloud-upload"></span> Unggah Template</button>
            </div>

        </div>
        <div class="nopad col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding-top:10px">
            <button onclick="batalkan()" class="btn btn-primary pull-right">Batalkan Penihilan</button>
        </div>
        <div class="nopad col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <br>
        </div>


        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Daftar Penihilan <?php echo $label_nama_periode . $label_jenjang; ?> <br><text id="filter"></text></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <table class="table table-hover table-condensed" id="table-datatable" style="font-size:8px" width='100%'>
                            <thead>
                                <tr>
                                    <th width="1%"><input type="checkbox" id="check_all" /></th>
                                    <th width="14%">NIM</th>
                                    <th width="22%">NAMA</th>
                                    <th width="12%">JUMLAH</th>
                                    <th width="10%">WAKTU</th>
                                    <th width="15%">ALASAN</th>

                                    <th width="15%">KANAL</th>
                                    <th width="13%">OPERATOR</th>
                                    <th width="13%">AKSI</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
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

<div id="myUpload" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Unggah Template Pembatalan Penihilan Massal</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
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
    let url = "<?php echo site_url('spp/bypasspembayaran/ajax_list/') ?>";
    let awal;
    let akhir;

    function upload_modal() {
        $('.progress').hide();
        $('#myUpload').modal("show");
    }

    function filter() {
        if ($('#awal').val() && $('#akhir').val()) {
            awal = $('#awal').val();
            akhir = $('#akhir').val();

            $.ajax({
                url: "<?php echo base_url('spp/bypasspembayaran/ajax_list');
                        ?>",
                data: {
                    "date_awal": awal,
                    "date_akhir": akhir,
                },
                type: "POST",
                processData: true,
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == true) {

                        $('#table-datatable').DataTable().ajax.url(url + "<?php echo $kode_periode ?>").load();
                        $('#filter').html('Range Waktu <b>' + awal + "</b> s.d <b>" + akhir + "</b>");
                    } else {
                        toastr.error("Tanggal tidak valid", 'Gagal!')
                        $('#filter').html('');
                    }
                },
                complete: function(data) {
                    awal = "";
                    akhir = "";
                    $('#awal').val('');
                    $('#akhir').val('');
                },
            });

        } else {
            $('#filter').html('');
            toastr.error("Filter belum diisi", 'Gagal!')
            $('#table-datatable').DataTable().ajax.url(url + "<?php echo $kode_periode ?>").load();
        }
    }

    function hapus_dialog(id) {
        id_delete = id;

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Setelah anda menekan tombol ya maka data akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0AA699',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',

        }).then((result) => {
            if (result.value) {
                hapus();
            }
        })
    }

    function batalkan() {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Setelah anda menekan tombol ya maka data akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0AA699',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',

        }).then((result) => {
            if (result.value) {
                batalkan_req();
            }
        })
    }

    function batalkan_req() {

        // Show all records
        before = oSettings[0]._iDisplayLength;
        oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
        oTable.draw();

        // Checkbox as array
        var array = $("input[name='check[]']:checked").map(function() {
            return this.value;
        }).get()

        // Hide all records
        oSettings[0]._iDisplayLength = before;
        oTable.draw(); //again draw the table
        console.log(array)

        // Request to server
        $.ajax({
            url: "<?php echo base_url('spp/bypasspembayaran/batal_banyak');
                    //             
                    ?>",
            data: "checked=" + array,
            type: "POST",
            processData: true,
            dataType: 'JSON',
            success: function(data) {
                if (data.status == true) {
                    // datatable reload
                    $('#table-datatable').DataTable().ajax.reload();
                    toastr.success(data.keterangan, 'Berhasil!')
                } else {
                    toastr.error(data.keterangan, 'Gagal!')
                }
            },
        });
    }

    function hapus() {
        var id = id_delete;
        $.ajax({
            url: "<?php echo base_url('spp/bypasspembayaran/batal_bypass'); ?>",
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
                $('#table-datatable').DataTable().ajax.reload();
                toastr.success('Berhasil menghapus', 'Berhasil!')

            },
        });
    }

    function detail(id) {
        $('#myDetail').modal("show");
        $.ajax({
            url: "<?php echo base_url('spp/bypasspembayaran/detail/'); ?>" + id,
            type: "GET",
            success: function(data) {
                $(".detail").html(data);
            }
        });
    }


    $(document).ready(function() {
        $('[class="form-control tanggalwaktu"]').datetimepicker({
            autoclose: true,
            format: 'dd-mm-yyyy hh:ii:ss',
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

        oTable = $('#table-datatable').DataTable({
            "ajax": {
                <?php if ($kode_periode != null) { ?> "url": url + "<?php echo $kode_periode ?>",
                <?php } else { ?> "url": url,
                <?php } ?> "type": "POST",
                "data": function(d) {
                    d.awal = awal;
                    d.akhir = akhir;
                }
            },
            processing: true,
            scrollX: false,
            // paging: false,
            bFilter: true,
            bLengthChange: false,
            dom: '<"top">lrt<"bottom"ip>',
            columnDefs: [{
                "className": "dt",
                "targets": [3]
            }],
        });
        oSettings = oTable.settings(); //store its settings in oSettings


        $("#field-cari").on("keyup", function() {
            oTable.search(this.value).draw();
        });
        $("#btn-cari").click(function() {
            oTable.search($("#field-cari").val()).draw();
        });

        $("#download_template_mhs_aktif").click(function() {
            var angkatan = $('#cbdownload_template_angkatan').val();
            var fakultas = $('#cbdownload_template_fakultas').val();
            window.location = "<?php echo base_url(); ?>spp/download_template_mhs_aktif/" + angkatan + "/" + fakultas;
        });

        $("#btn-filter-batal").click(function() {
            var NTypSource = '<?php echo base_url('pendaftar'); ?>';
            oTable.ajax.url(NTypSource).load();
        });

        $("#check_all").click(function() {
            // $('input:checkbox').not(this).prop('checked', this.checked);
            var cells = oTable.column(0).nodes(), // Cells from 1st column
                state = this.checked;

            for (var i = 0; i < cells.length; i += 1) {
                cells[i].querySelector("input[type='checkbox']").checked = state;
            }
        });

        $("#btn-upload").click(function() {
            $('#btn-upload').html('<i class="fa fa-upload" aria-hidden="true"></i> Unggah');
            $('#btn-upload').prop('disabled', true);
            $('#upload-msg').html('<span class="fa fa-spinner fa-spin"></span> Proses');
            var form_data = new FormData();
            form_data.append("file", $('#file')[0].files[0]);

            $.ajax({
                url: "<?php echo site_url('spp/bypasspembayaran/do_upload_penihilan') ?>",
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,
                dataType: 'JSON',

                success: function(data) {
                    console.log(data);
                    if (data.status == true) {
                        $('#table-datatable').DataTable().ajax.reload();
                        toastr.success(data.msg, 'Berhasil!')
                        $('#myUpload').modal("hide");

                    } else {
                        toastr.error(data.msg, 'Gagal!')
                    }
                    $('#upload-msg').html('');
                    $('#btn-upload').prop('disabled', false);
                    $('#btn-upload').html('<i class="fa fa-upload" aria-hidden="true"></i> Unggah');
                }
            });
        });
    });
</script>