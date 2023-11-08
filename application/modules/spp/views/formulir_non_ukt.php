<!-- BEGIN PAGE CONTAINER-->

<style>
    .error_msg {
        color: red
    }

    .nomar {
        margin: 0px;
    }
</style>
<div class="page-content">
    <div class="content">
        <div class="page-title col-lg-7 col-md-7 col-sm-7 col-xs-7">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Input Tagihan Non-UKT</span><span style="font-size : 11pt ;"> >> Input Tagihan Non-UKT Calon Mahasiswa</span></span></h3>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <div style="float : right ; margin-right : -14px">
                <a class="btn btn-primary" type="button" href="<?php echo base_url('spp/download_template_non_ukt/') ?>" target="_blank"><span class="fa fa-cloud-download"></span> Unduh Template</a>
                <button class="btn btn-primary" type="button" onclick="upload_modal()"><span class="fa fa-cloud-upload"></span> Unggah Template</button>
            </div>
        </div>
        <div class="row">
            <form autocomplete="off" id="form" class="myform form-horizontal col-md-12 col-sm-12 col-xs-12" action="javascript:void(0);">
                <div class="col-md-12">
                    <div class="grid simple">
                        <div class="grid-body no-border">
                            <div class="row-fluid">
                                <?php if ($msg != "") { ?>
                                    <div class="alert alert-<?php echo ($msg_status ? "success" : "alert"); ?>">
                                        <button type="button" class="close" data-dismiss="alert"></button>
                                        <?php echo $msg . " | "; ?>
                                        <a href="<?php echo base_url() . 'spp/tagihan/' . $kode_periode; ?>">kembali ke menu tagihan semester aktif</a>
                                    </div>
                                <?php } ?>
                                <div class="span3 col-sm-6">
                                    <h4><i class="i  i-user3"></i> Data Mahasiswa</h4>
                                    <hr>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nomor_pembayaran">No.Ujian</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="nomor_induk" placeholder="Nomor Ujian" />
                                            <div id="nomor_induks" name="inVal" class="nomar" hidden></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nomor_pembayaran">Nama</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="nama" placeholder="Nama" />
                                            <div id="namas" name="inVal" class="nomar" hidden></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nama">Fakultas</label>
                                        <div class="col-sm-9">
                                            <?php
                                            $ref_fakultas = array("" => "Pilih fakultas") + $ref_fakultas;
                                            echo form_dropdown('kode_fakultas', $ref_fakultas, set_value('kode_fakultas', $tagihan["kode_fakultas"] ?? ""), array('class' => 'chosen-select form-select drop_select fakultas', 'style' => 'width:100%', 'readonly'));
                                            ?>
                                            <div id="kode_fakultass" name="inVal" class="nomar" hidden></div>
                                            <div id="extramargin"></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nama">Prodi</label>
                                        <div class="col-sm-9">
                                            <?php
                                            $ref_prodi = array("" => "Pilih program studi");
                                            echo form_dropdown('kode_prodi', $ref_prodi, $tagihan['kode_prodi'] ?? "", array('class' => 'chosen-select form-select drop_select prodi', 'style' => 'width:100%', 'readonly')); ?>
                                            <div id="kode_prodis" name="inVal" class="nomar" hidden></div>
                                            <div id="extramargin"></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nomor_pembayaran">Angkatan</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="angkatan" placeholder="Angkatan" />
                                            <div id="angkatans" name="inVal" class="nomar" hidden></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nama">Jenjang</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="strata" placeholder="Jenjang" disabled="disabled" />
                                            <div id="stratas" name="inVal" class="nomar" hidden></div><br>
                                        </div>
                                    </div>
                                </div>

                                <div class="span4 col-sm-6" style="display:block">
                                    <h4><i class="i  i-user3"></i> Data Tagihan</h4>
                                    <hr>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nama">Jumlah</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="total_nilai_tagihan" placeholder="Isikan rupiah tagihan" />
                                            <div id="total_nilai_tagihans" name="inVal" class="nomar" hidden></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nama">Status Tagihan</label>
                                        <div class="col-sm-9">
                                            <?php
                                            $ref_aktif = array("1" => "Aktif", "0" => "Tidak Aktif");
                                            echo form_dropdown('is_tagihan_aktif', $ref_aktif, set_value('is_tagihan_aktif', $tagihan['is_tagihan_aktif'] ?? ""), array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
                                            <div id="is_tagihan_aktifs" name="inVal" class="nomar" hidden></div>
                                            <div id="extramargin"></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nama">Keterangan Tagihan</label>
                                        <div class="col-sm-9">
                                            <?php
                                            $ref_jnsKode = array("Tes Psikologi" => "Tes Psikologi", "Tes Kesehatan" => "Tes Kesehatan", "Tes Bakat" => "Tes Bakat");
                                            echo form_dropdown('keterangan_db', $ref_jnsKode, set_value('keterangan_db', $tagihan['keterangan_db'] ?? ""), array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
                                            <div id="keterangan_dbs" name="inVal" class="nomar" hidden></div>
                                            <div id="extramargin"></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left">Awal</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control tanggalwaktu" name="waktu_berlaku" placeholder="Tanggal & Waktu" />
                                            <div id="waktu_berlakus" name="inVal" class="nomar" hidden></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left">Akhir</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control tanggalwaktu" name="waktu_berakhir" placeholder="Tanggal & Waktu" />
                                            <div id="waktu_berakhirs" name="inVal" class="nomar" hidden></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left" for="nama">Cicilan</label>
                                        <div class="col-sm-9">
                                            <?php
                                            $ref_cicilan = array("Cicilan 2 Kali" => "Cicilan 2 Kali", "Cicilan 3 Kali" => "Cicilan 3 Kali");
                                            echo form_dropdown('keterangan_db', $ref_cicilan, set_value('keterangan_db', $tagihan['keterangan_db'] ?? ""), array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
                                            <div id="keterangan_dbs" name="inVal" class="nomar" hidden></div>
                                            <div id="extramargin"></div><br>
                                        </div>
                                    </div>
                                    <div class="form-group nomar">
                                        <label class="control-label col-sm-3" style="text-align:left"></label>
                                        <button id="btn-simpan" class="col-sm-6 btn btn-primary pull" onclick="simpan()">
                                            <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-clone" aria-hidden="true"></i> Simpan Tagihan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                <div class="form-group nomar">
                    <div class="col-md-12" style="margin-bottom:15px">
                        Jenis Jalur
                        <select id="m_daftarId" style="width:100% !important;" class="chosen-select form-select drop_select">
                            <?php
                            foreach ($reg_pendaftaran as $r) {
                                echo "<option value='" . $r->daftarId . "'>" . $r->daftarJalur . " - " . $r->daftarTahun . "</option>";
                            }
                            ?>
                        </select>
                        <div id="m_daftarIds" name="m_inVal" class="nomar" hidden></div><br>
                    </div>
                    <div class="col-md-6">
                        Waktu Tagihan Berlaku
                        <input type="text" class="form-control tanggalwaktu" id="m_awal" placeholder="Tanggal & Waktu" />
                        <div id="m_awals" name="m_inVal" class="nomar" hidden></div><br>
                    </div>
                    <div class="col-md-6">
                        Waktu Tagihan Berakhir
                        <input type="text" class="form-control tanggalwaktu" id="m_akhir" placeholder="Tanggal & Waktu" />
                        <div id="m_akhirs" name="m_inVal" class="nomar" hidden></div><br>
                    </div>
                    <div class="col-md-12">
                        File Excel
                        <input type="file" name="m_file" id="m_file" style="width: 100%" />
                        <div id="m_files" name="m_inVal" class="nomar" hidden></div><br>
                    </div>
                    <div class="col-md-12">
                        <div id="upload-msg" name="m_inVal" class="alert alert-warning" hidden></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-loading" class="btn btn-default"><i class="fa fa-clock-o" aria-hidden="true"></i> Mohon menunggu..</button>
                <button id="btn-upload" class="btn btn-primary"><i class="fa fa-upload" aria-hidden="true"></i> Unggah</button>
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Keluar</button>
            </div>
        </div>
    </div>
</div>

<div id="myExist" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Pesan Kesalahan</h5>
            </div>
            <div class="modal-body">
                <div id="msg-err"></div>
            </div>
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
    function simpan() {
        let id_record_tagihan = "";
        <?php if ($edit) {  ?>
            id_record_tagihan = "&id_record_tagihan=<?php echo $edit->id_record_tagihan ?>";
        <?php }  ?>

        $('#btn-lanjutkan-copy').html('Menyalin data...');
        $('#btn-lanjutkan-copy').prop('disabled', true);

        // $('.btn-lanjutkan-copy').append('&nbsp; <i class="fa fa-spin fa-spinner"></i>')

        $.ajax({
            url: "<?php echo base_url('spp/input_non_ukt/1'); ?>",
            type: "POST",
            data: $('#form').serialize() + id_record_tagihan,
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
                    location.href = "<?php echo base_url('spp/tagihan_non_ukt/'); ?> ";
                }
            },
        });
    }

    $(document).ready(function() {
        $("#btn-loading").hide();

        $('[class="form-control tanggalwaktu"]').datetimepicker({
            autoclose: true,
            format: 'dd-mm-yyyy hh:ii:ss',
        }).on('keypress paste', function(e) {
            e.preventDefault();
            return false;
        });

        $('.fakultas').change(function(event, a) {
            //alert($(this).val());
            $('.prodi').html("<option>Loading...</option>");
            $.ajax({
                url: "<?php echo base_url(); ?>spp/get_prodi",
                type: 'POST',
                data: {
                    'fakultas': $(this).val()
                },
                success: function(data) {
                    $('.prodi').html(data);
                    if (a != null)
                        $('.prodi option[value=' + a + ']').prop('selected', true);
                    else
                        $('.prodi option[value=<?php echo set_value('kode_prodi', $tagihan['kode_prodi'] ?? "") ?>]').prop('selected', true);
                }
            });
        });

        $('.fakultas').trigger("change");

        $('.prodi').change(function(event, a) {
            //alert($(this).val());
            if ($('[name="kode_prodi"] option:selected').val() !== "") {
                $.ajax({
                    url: "<?php echo base_url(); ?>spp/get_strata",
                    type: 'POST',
                    data: {
                        'prodi': $(this).val()
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        console.log(data.strata);
                        console.log(data);

                        $('[id=strata]').val(data.strata);

                    }
                });
            }

        });


        $("#btn-upload").click(function() {
            // $('#btn-upload').html('<i class="fa fa-upload" aria-hidden="true"></i> Unggah');
            // $('#btn-upload').prop('disabled', true);
            // $('#upload-msg').html('<span class="fa fa-spinner fa-spin"></span> Proses');
            var form_data = new FormData();
            form_data.append("m_daftarId", $('#m_daftarId option:selected').val());
            form_data.append("m_awal", $('#m_awal').val());
            form_data.append("m_akhir", $('#m_akhir').val());
            form_data.append("m_file", $('#m_file')[0].files[0]);
            form_data.append("m_kode_periode", "<?php echo $kode_periode; ?>");

            $.ajax({
                url: "<?php echo site_url('spp/do_upload_non_ukt') ?>",
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,
                dataType: 'JSON',
                beforeSend: function() {
                    $('#upload-msg').html('');
                    $('#upload-msg').hide();

                    $('[name=m_inVal]').hide('fast');
                    $("#btn-upload").attr("disabled", true);
                    $("#btn-upload").hide();
                    $("#btn-loading").show();

                },
                success: function(data) {
                    if (data.status == 0) {
                        $("#btn-upload").removeAttr("disabled");
                        $("#btn-upload").show();
                        $("#btn-loading").hide();
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
                        $("#btn-upload").show();
                        $("#btn-loading").hide();
                        $("#btn-upload").removeAttr("disabled");
                        if (data.fail !== '') {
                            $('#upload-msg').show();
                            $('#upload-msg').html(data.fail);
                        }
                        toastr.success(data.keterangan, 'Berhasil!')
                        console.log(data)
                    }
                },
            });
        });

        // Start of edit mode
        <?php if ($edit) {  ?>
            $('[name="nomor_induk"]').val('<?php echo $edit->nomor_induk; ?>');
            $('[name="nama"]').val("<?php echo $edit->nama; ?>");
            $('[name="kode_fakultas"]').val('<?php echo $edit->kode_fakultas; ?>');
            $('[name="kode_fakultas"]').trigger("change");
            setTimeout(() => {
                $('[name="kode_prodi"]').val('<?php echo $edit->kode_prodi; ?>');
                $('[name="kode_prodi"]').trigger("change");
            }, 1000);
            $('[name="is_tagihan_aktif"]').val('<?php echo $edit->is_tagihan_aktif; ?>');
            $('[name="is_tagihan_aktif"]').trigger("change");
            $('[name="keterangan_db"]').val('<?php echo $edit->keterangan; ?>');
            $('[name="keterangan_db"]').trigger("change");
            $('[name="angkatan"]').val('<?php echo $edit->angkatan; ?>');
            $('[name="strata"]').val('<?php echo $edit->strata; ?>');
            $('[name="total_nilai_tagihan"]').val('<?php echo $edit->total_nilai_tagihan; ?>');
            $('[name="waktu_berlaku"]').val('<?php echo $edit->waktu_berlaku; ?>');
            $('[name="waktu_berakhir"]').val('<?php echo $edit->waktu_berakhir; ?>');
        <?php }  ?>
        // end of edit mode
    });

    function upload_modal() {
        $('.progress').hide();
        $('#myUpload').modal("show");
    }

    // function unduh_template() {
    //     window.open('<?php //echo base_url(); 
                        ?>spp/download_template_non_ukt')
    // }
</script>
</div>