<?php

// echo "<pre>";
// print_r($aktif['kode_periode']);
// echo "</pre>";
// exit()

?>

<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-cogs"></i>
            <h3><span class="semi-bold">ATUR RENTANG</span><span style="font-size : 11pt ;"></span></h3>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right">

        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Atur Rentang</h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form autocomplete="off" id="form" class="" action="javascript:void(0);">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Periode Aktif</label>
                                    <p style=><b><?php echo $periode ?></b></p>
                                    <div id="jenis_tagihans" name="inVal"></div><br>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Jenis Tagihan</label>
                                    <select name="jenis_tagihan" class="chosen-select form-select drop_select " style="width:100%" readonly="true">
                                        <option value="UKT">UKT</option>
                                        <option value="Tes Psikologi">Tes Psikologi</option>
                                        <option value="Tes Kesehatan">Tes Kesehatan</option>
                                        <option value="Tes Bakat">Tes Bakat</option>
                                    </select>
                                    <div id="jenis_tagihans" name="inVal"></div><br>
                                </div><br>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Waktu Tagihan Berlaku</label>
                                    <input type="text" class="form-control tanggal" name="waktu_berlaku" placeholder="Tanggal & Waktu" />
                                    <div id="waktu_berlakus" name="inVal"></div><br>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Waktu Tagihan Berakhir</label>
                                    <input type="text" class="form-control tanggal" name="waktu_berakhir" placeholder="Tanggal & Waktu" />
                                    <div id="waktu_berakhirs" name="inVal"></div><br>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Jenjang</label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php foreach (explode(",", str_replace(" ", "", $sess['jenjang_text'])) as $s) { ?>
                                        <div><input type="checkbox" id="jenjang[]" name="jenjang[]" value="<?php echo $s ?>">&nbsp;<?php echo $s ?></div><br>
                                    <?php } ?>
                                    <div id="jenjangs" name="inVal"></div><br>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right">
                                    <button id="btn-simpan" class="btn btn-primary pull-right" onclick="atur_rentang()">
                                        <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-save" aria-hidden="true"></i> Atur Rentang
                                    </button>
                                </div>
                                <!-- <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    
                                </div> -->
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
    $(document).ready(function() {
        $('[class="form-control tanggal"]').datetimepicker({
            autoclose: true,
            format: 'dd-mm-yyyy hh:ii:ss',
        }).on('keypress paste', function(e) {
            e.preventDefault();
            return false;
        });
    });

    function atur_rentang() {
        $('#btn-lanjutkan-copy').html('Menyalin data...');
        $('#btn-lanjutkan-copy').prop('disabled', true);

        // $('.btn-lanjutkan-copy').append('&nbsp; <i class="fa fa-spin fa-spinner"></i>')

        $.ajax({
            url: "<?php echo base_url('atur_rentang'); ?>",
            type: "POST",
            data: $('#form').serialize(),
            mimeType: "multipart/form-data",
            processData: false,
            dataType: 'JSON',
            beforeSend: function() {
                $('[name=inVal]').hide('fast');
                $(".fa-spinner").show();
                $("#btn-simpan").attr("disabled", true);
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
                    toastr.error(data.keterangan, 'Gagal!')

                } else {
                    toastr.success(data.keterangan, 'Berhasil!')
                    console.log(data)
                }
                $(".fa-spinner").hide();
                $("#btn-simpan").attr("disabled", false);
            },
        });
    }
</script>