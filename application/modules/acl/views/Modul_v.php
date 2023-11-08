<style>
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
            <h3><span class="semi-bold"><?php echo $nama_modul ?></span><span style="font-size : 11pt ;"> >> Master</span></h3>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 nopad">
            <button class="btn btn-primary pull-right" type="button" id="btn-tambah" onclick="tambah()"> Tambah <?php echo $nama_modul ?></button>
        </div>
        <div class=" row">
            &nbsp
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
                        <h4><?php echo $nama_modul ?></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <table class="table table-hover table-condensed" id="table" style="font-size:8px" width='100%'>
                            <thead>
                                <tr>
                                    <th width="10%">NO</th>
                                    <th width="70%">NAMA <?php echo strtoupper($nama_modul) ?> </th>
                                    <th width="20%">OPSI </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</section>
<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script>
    function hapus(key) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: "Data ini akan dihapus dan tidak bisa dikembalikan lagi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            preConfirm: function() {
                request = hapus_req(key);
                // request = false;
                if (request == false) {
                    return false;
                } else {
                    // Lanjut
                    oTable.api().ajax.reload();
                }
            }
        }).then((result) => {
            if (result.value) {
                swal.close();
            } else {}
        })
    }

    function hapus_req(key = "") {
        var status = '';
        $('.swal2-actions').hide();

        var form_data = new FormData();
        form_data.append("key", key);

        $.ajax({
            url: "<?php echo base_url($modul . '/hapus/') ?>",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            async: false,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.status == "validasi") {
                    $('.swal2-actions').show();
                    toastr.error(data.keterangan);
                    status = false;
                } else {
                    toastr.success(data.keterangan);
                    status = true;
                }
            },
            error: function() {
                $('.swal2-actions').show();
            }
        })
        return status;
    }

    function tambah(key = "") {
        Swal.fire({
            title: 'Tambah <?php echo $nama_modul ?>',
            text: 'Modal with a custom image.',
            allowOutsideClick: false,
            html: `
                Nama
                <input class="form-control" type="text" id="<?php echo $column ?>" value="` + key + `"/>
                <p id="<?php echo $column ?>s" name="inVal" style="padding:10px;margin-bottom:0px;" hidden></p><br>
                `,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Simpan",
            cancelButtonText: "Batal",
            onOpen: function() {
                $('#modul').focus();
            },
            preConfirm: function() {
                request = tambah_req(key);
                // request = false;
                if (request == false) {
                    return false;
                } else {
                    // Lanjut
                    oTable.api().ajax.reload();
                }
            }
        }).then((result) => {
            if (result.value) {
                swal.close();
            } else {}
        })
    }

    function tambah_req(key = null) {
        var status = '';
        $('.swal2-actions').hide();

        var form_data = new FormData();
        form_data.append("<?php echo $column ?>", $('#<?php echo $column ?>').val());
        if (key) {
            form_data.append("key", key);
        }

        $('.swal2-confirm').attr('disabled');
        $('[name=inVal]').removeClass().html('');
        $('[name=inVal]').hide();

        $.ajax({
            url: "<?php echo base_url($modul . '/action/') ?>",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            async: false,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.status == "validasi") {
                    $.each(data, function(key, value) {
                        console.log(key);
                        if (value == "") {
                            $('#' + key + 's').removeClass().html('');
                            $('#' + key + 's').hide('fast');
                        } else {
                            $('#' + key + 's').addClass('alert alert-danger').html(value);
                            $('#' + key + 's').show('fast');
                        }
                    });
                    $('.swal2-actions').show();
                    toastr.error(data.keterangan);
                    status = false;
                } else {
                    toastr.success(data.keterangan);
                    status = true;
                }
            },
            error: function() {
                $('.swal2-actions').show();
            }
        })
        return status;
    }

    $(document).ready(function() {
        //datatables
        oTable = $('#table').dataTable({
            processing: true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url($modul . '/ajax_list') ?>",
                "type": "POST"
            },
            "sDom": "lrtip",
            // "searching": false,
            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            }, ],
            "fnDrawCallback": function(oSettings) {
                console.log("drawed");
                // $('#table-datatable').show();
            },
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
    });
</script>