<?php

// echo "<pre>";
// print_r($sess);
// echo "</pre>";
// exit()
?>

<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-cogs"></i>
            <h3><span class="semi-bold">ATUR JENJANG</span><span style="font-size : 11pt ;"></span></h3>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right">

        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Atur Jenjang</h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form id="form" class="" action="javascript:void(0);">
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                    <label>Filter Jenjang</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <select name="jenjang" class="chosen-select form-select drop_select fakultas" style="width:100%" readonly="true">
                                        <?php
                                        if ($sess['role'] == "superadmin") {
                                            echo '
                                            <option value="1">Semua</option>
                                            <option value="2">D3, S1, Profesi, Spesialis</option>
                                            <option value="3">S2, S3</option>
                                            ';
                                        } else if ($sess['role'] == 'keuangan_rektorat') {
                                            echo '
                                                <option value="2">D3, S1, Profesi, Spesialis</option>
                                                <option value="3">S2, S3</option>
                                                ';
                                        } else if ($sess['role'] == 'keuangan_pasca') {
                                            echo '
                                                <option value="3">S2, S3</option>
                                                ';
                                        }

                                        ?>
                                    </select>
                                    <div id="jenjangs" name="inVal"></div><br>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <button id="btn-simpan" class="btn btn-primary" onclick="modify_jenjang()">
                                        <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-save" aria-hidden="true"></i> Simpan
                                    </button>
                                </div>
                            </form>
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
    function modify_jenjang() {

        $.ajax({
            url: "<?php echo base_url('atur_jenjang/index'); ?>",
            type: "POST",
            data: $('#form').serialize(),
            mimeType: "multipart/form-data",
            processData: false,
            dataType: 'JSON',
            beforeSend: function() {
                $('[name=inVal]').hide();
                // $(".fa-spinner").show();
                // $("#btn-simpan").attr("disabled", true);
            },
            success: function(data) {
                if (data.status == 0) {
                    $("#btn-simpan").removeAttr("disabled");
                    $(".fa-spinner").hide();
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
                } else {
                    toastr.success('Jenjang berhasil diubah!', 'Sukses')
                    console.log(data)
                    window.location = "<?php echo base_url('periode') ?>";
                }
            },
        });
    }
</script>