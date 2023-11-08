<?php

// echo "<pre>";
// print_r($sess);
// echo "</pre>";
// exit()
?>
<style>
    .cb_az {
        padding: 10px 10px 10px 10px;
    }
</style>
<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-clone"></i>
            <h3><span class="semi-bold">SALIN TAGIHAN</span><span style="font-size : 11pt ;"></span></h3>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right">

        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Salin Tagihan</h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form autocomplete="off" id="form" class="" action="javascript:void(0);">
                                <!-- Input Periode Sumber -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Periode Sumber</label>
                                    <?php echo form_dropdown('sumber', $listPeriode_min, '', array('id' => 'sumber', 'class' => 'form-control', 'placeholder' => 'Tahun periode')); ?>
                                    <div id="sumbers" name="inVal"></div><br>
                                </div>

                                <!-- Input Periode Target -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Periode Target</label>
                                    <?php echo form_dropdown('data', $listPeriode_min, '', array('id' => 'data', 'class' => 'form-control', 'placeholder' => 'Tahun periode')); ?>
                                    <div id="datas" name="inVal"></div><br>
                                </div>

                                <!-- Keterangan -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Salin data tagihan UKT Mahasiswa Aktif periode <text id="data_text"></text> yang melakukan pembayaran pada periode <text id="sumber_text"></text>.</label>
                                    <br>
                                </div>

                                <!-- Input Mulai Pembayaran -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Mulai Pembayaran</label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control tanggalwaktu" name="waktu_berlaku" placeholder="Tanggal & Waktu" />
                                    <div id="waktu_berlakus" name="inVal"></div><br>
                                </div>

                                <!-- Input Akhir Pembayaran -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Akhir Pembayaran</label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control tanggalwaktu" name="waktu_berakhir" placeholder="Tanggal & Waktu" />
                                    <div id="waktu_berakhirs" name="inVal"></div><br>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Opsi Tambahan</label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div><input type="checkbox" checked="true" disabled="disabled">&nbspMahasiswa Aktif</div><br>
                                    <div><input type="checkbox" id="mhs_cuti" name="mhs_cuti" value="1">&nbspMahasiswa Cuti</div><br>
                                    <div><input type="checkbox" id="mhs_keringanan" name="mhs_keringanan" class="cb_az" value="1">&nbspMahasiswa Penerima Keringanan UKT</div><br>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right">
                                    <button id="btn-simpan" class="btn btn-primary pull-right" onclick="do_copy()">
                                        <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-clone" aria-hidden="true"></i> Salin Tagihan
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
        $('[class="form-control tanggalwaktu"]').datetimepicker({
            autoclose: true,
            format: 'dd-mm-yyyy hh:ii:ss',

        }).on('keypress paste', function(e) {
            e.preventDefault();
            return false;
        });

        $('#data_text').text($('#data option:selected').text());
        $('#sumber_text').text($('#sumber option:selected').text());

        $("#sumber").change(function() {
            $('#sumber_text').text($('#sumber option:selected').text());
        });
        $("#data").change(function() {
            $('#data_text').text($('#data option:selected').text());
        });
    });

    function do_copy(sumber, target) {
        $('#btn-lanjutkan-copy').html('Menyalin data...');
        $('#btn-lanjutkan-copy').prop('disabled', true);

        // $('.btn-lanjutkan-copy').append('&nbsp; <i class="fa fa-spin fa-spinner"></i>')

        $.ajax({
            url: "<?php echo base_url('spp/doCopyData'); ?>",
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
                    $("#btn-simpan").removeAttr("disabled");
                    $(".fa-spinner").hide();
                    toastr.success(data.keterangan, 'Berhasil!')
                    console.log(data)
                }
            },
        });
    }
</script>