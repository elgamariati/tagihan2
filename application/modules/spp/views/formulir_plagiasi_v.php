<!-- BEGIN PAGE CONTAINER-->

<style>
    .error_msg {
        color: red
    }
</style>
<div class="page-content">
    <div class="content">
        <div class="page-title col-lg-7 col-md-7 col-sm-7 col-xs-7">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Input Tagihan Cek Plagiasi</span><span style="font-size : 11pt ;"> >> Pengelolaan Data per Periode per Mahasiswa</span></span></h3>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <div style="float : right ; margin-right : -14px">
                <button class="btn btn-primary" type="button" onclick="unduh_template()"><span class="fa fa-cloud-download"></span> Unduh Template</button>
                <button class="btn btn-primary" type="button" onclick="upload_modal()"><span class="fa fa-cloud-upload"></span> Unggah Template</button>
            </div>
        </div>
        <div class="row">
            <?php echo form_open(current_url(), array('class' => 'myform form-horizontal col-md-12 col-sm-12 col-xs-12', 'autocomplete' => 'off')); ?>
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Input Tagihan Cek Plagiasi <a href="<?php echo base_url() . 'spp/tagihan/' . $kode_periode; ?>"><?php echo $label_nama_periode . $label_jenjang; ?></a> >
                            <?php echo ($tagihan["id_record_tagihan"] != "" ? "Edit" : "Tambah") ?></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
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
                                <h4><i class="i  i-user3"></i> Data Penerima Tagihan</h4>
                                <hr>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nomor_pembayaran">NIM</label>
                                    <div class="col-sm-6">
                                        <?php echo form_input('nomor_induk', set_value('nomor_induk', $tagihan["nomor_induk"]), array('class' => 'form-control nomor_induk', 'placeholder' => 'Isikan NIM')); ?>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <span class="fa fa-spinner fa-spin"></span>
                                        <?php echo (form_error('nomor_induk') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nomor_pembayaran">No. Bayar</label>
                                    <div class="col-sm-6">
                                        <?php echo form_input('nomor_pembayaran', set_value('nomor_pembayaran', $tagihan["nomor_pembayaran"]), array('class' => 'form-control nomor_pembayaran', 'readonly' => 'true')); ?>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <?php echo (form_error('nomor_pembayaran') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama">Nama</label>
                                    <div class="col-sm-6">
                                        <?php echo form_input('nama', set_value('nama', $tagihan["nama"]), array('class' => 'form-control nama', 'readonly' => 'true')); ?>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <?php echo (form_error('nama') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama">Fakultas</label>
                                    <div class="col-sm-6">
                                        <?php
                                        $ref_fakultas = array("" => "Pilih fakultas") + $ref_fakultas;
                                        echo form_dropdown('kode_fakultas', $ref_fakultas, set_value('kode_fakultas', $tagihan["kode_fakultas"]), array('class' => 'chosen-select form-select drop_select fakultas', 'style' => 'width:100%', 'readonly' => 'true'));
                                        ?>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <?php echo (form_error('kode_fakultas') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama">Prodi</label>
                                    <div class="col-sm-6">
                                        <?php
                                        $ref_prodi = array("" => "Pilih program studi");
                                        echo form_dropdown('kode_prodi', $ref_prodi, $tagihan['kode_prodi'], array('class' => 'chosen-select form-select drop_select prodi', 'style' => 'width:100%', 'readonly' => 'true')); ?>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <?php echo (form_error('kode_prodi') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama">Angkatan</label>
                                    <div class="col-sm-6">
                                        <?php echo form_input('angkatan', set_value('angkatan', $tagihan["angkatan"]), array('class' => 'form-control angkatan', 'readonly' => 'true')); ?>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <?php echo (form_error('angkatan') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama">Jenjang</label>
                                    <div class="col-sm-6">
                                        <?php echo form_input('strata', set_value('strata', $tagihan["strata"]), array('class' => 'form-control strata', 'readonly' => 'true')); ?>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <?php echo (form_error('strata') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="span4 col-sm-6" style="display:block">
                                <h4><i class="i  i-user3"></i> Data Tagihan</h4>
                                <hr>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama">Jumlah</label>
                                    <div class="col-sm-6">
                                        <?php echo form_input('total_nilai_tagihan', str_replace(".", "", set_value('total_nilai_tagihan', $tagihan["total_nilai_tagihan"])), array('class' => 'form-control total_nilai_tagihan', 'placeholder' => 'Isikan rupiah tagihan')); ?>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <?php echo (form_error('total_nilai_tagihan') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama">Status Tagihan</label>
                                    <div class="col-sm-6">
                                        <?php
                                        $ref_aktif = array("1" => "Aktif", "0" => "Tidak Aktif");
                                        echo form_dropdown('is_tagihan_aktif', $ref_aktif, set_value('is_tagihan_aktif', $tagihan['is_tagihan_aktif']), array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
                                    </div>
                                    <div class="error_msg col-md-3">
                                        <?php echo (form_error('is_tagihan_aktif') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama">Status Mahasiswa</label>
                                    <div class="col-sm-6">
                                        <?php
                                        $ref_jnsKode = array("1" => "Aktif Kuliah", "2" => "Cuti", "3" => "Daftar Admisi");
                                        echo form_dropdown('jnsKode', $ref_jnsKode, set_value('jnsKode', $tagihan['jnsKode']), array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
                                    </div>
                                    <div class="error_msg col-md-3">
                                        <?php echo (form_error('is_tagihan_aktif') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Awal</label>
                                    <div class="col-sm-6">
                                        <div class='input-group date'>
                                            <?php echo form_input('waktu_berlaku', set_value('waktu_berlaku', $tagihan["waktu_berlaku"]), array('class' => 'form-control tanggalwaktu', 'id' => 'waktu_berlaku', 'placeholder' => 'Tanggal mulai tagihan')); ?>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <?php echo ((form_error('waktu_berlaku') != "") ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Akhir</label>
                                    <div class="col-sm-6">
                                        <div class='input-group'>
                                            <?php echo form_input('waktu_berakhir', set_value('waktu_berakhir', $tagihan["waktu_berakhir"]), array('class' => 'form-control tanggalwaktu', 'id' => 'waktu_berakhir', 'placeholder' => 'Tanggal akhir tagihan')); ?>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="error_msg col-sm-3">
                                        <?php echo ((form_error('waktu_berakhir') != "") ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama">Jenis</label>
                                    <div class="col-sm-6">
                                        <?php
                                        $ref_jenis = array("PEMBAYARAN" => "PEMBAYARAN", "VOUCHER" => "VOUCHER");
                                        $ref_jenis = array("PEMBAYARAN" => "PEMBAYARAN");
                                        echo form_dropdown('pembayaran_atau_voucher', $ref_jenis, $tagihan['pembayaran_atau_voucher'], array('class' => 'chosen-select form-select drop_select pembayaran_atau_voucher', 'style' => 'width:100%')); ?>
                                    </div>
                                    <div class="error_msg  col-sm-3">
                                        <?php echo (form_error('pembayaran_atau_voucher') != "" ? "Wajib diisi" : ""); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3"></label>
                                    <div class="col-sm-6">
                                        <?php echo form_submit('Submit', "Simpan Tagihan", array('class' => 'btn btn-primary', 'style' => '')); ?>
                                    </div>
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
                        <input type="text" class="form-control tanggalwaktu" id="waktu_berlaku_upload" placeholder="Tanggal & Waktu" />
                    </div>
                    <div class="col-md-6">
                        Waktu Tagihan Berakhir
                        <input type="text" class="form-control tanggalwaktu" id="waktu_berakhir_upload" placeholder="Tanggal & Waktu" />
                        <div id="m_akhirs" name="m_inVal" class="nomar" hidden></div><br>
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

<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script>
    $(document).ready(function() {
        $('[class="form-control tanggalwaktu"]').datetimepicker({
            autoclose: true,
            format: 'dd-mm-yyyy hh:ii:ss',
            language: 'id',
        }).on('keypress paste', function(e) {
            e.preventDefault();
            return false;
        });

        $('.nomor_induk, .nomor_pembayaran, .nama, .fakultas, .angkatan, .strata, .kode_prodi').prop('readonly', true);

        $('.total_nilai_tagihan').maskMoney({
            allowNegative: true,
            thousands: '.',
            precision: 0
        });

        <?php if ($mode_input == "add") { ?>
            $('.nomor_induk').prop('readonly', false);
            $('.nomor_induk').focusout(function() {
                if ($(this).val() != "") {
                    $('.fa-spinner').show();
                    $.ajax({
                        url: "<?php echo base_url(); ?>spp/cek_tagihan_plagiasi",
                        type: 'POST',
                        data: {
                            'nomor_induk': $(this).val(),
                            'kode_periode': '<?php echo $kode_periode; ?>'
                        },
                        success: function(res) {
                            data = jQuery.parseJSON(res);
                            if (data.status) {
                                if (data.data.nomor_pembayaran == 'AFK') {
                                    $('.nomor_pembayaran, .nama, .fakultas, .angkatan, .strata, .kode_prodi').val('');
                                    $('.nomor_pembayaran, .nama, .fakultas, .angkatan, .strata, .kode_prodi').prop('readonly', false);
                                } else {
                                    $('.nomor_pembayaran, .nama, .fakultas, .angkatan, .strata, .kode_prodi').prop('readonly', true);
                                    $('.nomor_pembayaran').val(data.data.nomor_pembayaran);
                                    $('.nama').val(data.data.mhsNama);
                                    $('.fakultas').val(data.data.fakKode);
                                    $('.fakultas').trigger("change", [data.data.prodiKode]);
                                    $('.angkatan').val(data.data.mhsAngkatan);
                                    $('.strata').val(data.data.prodiJjarKode);
                                }
                            } else {
                                $("#myExist").modal({
                                    backdrop: 'static',
                                    keyboard: true,
                                    show: true
                                });
                                $('#msg-err').html(data.msg);
                                //$('input, select').prop("disabled",true);
                            }
                            $('.fa-spinner').hide();
                            //$('.prodi').val(data.data.prodiKode);
                        }
                    });
                }
            });
        <?php } ?>
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
                        $('.prodi option[value=<?php echo set_value('kode_prodi', $tagihan['kode_prodi']) ?>]').prop('selected', true);
                }
            });
        });

        $('.fakultas').trigger("change");

        $("#btn-upload").click(function() {
            $('#btn-upload').html('<i class="fa fa-upload" aria-hidden="true"></i> Unggah');
            $('#btn-upload').prop('disabled', true);
            $('#upload-msg').html('<span class="fa fa-spinner fa-spin"></span> Proses');
            var form_data = new FormData();
            form_data.append("file", $('#file')[0].files[0]);
            form_data.append("awal", $('#waktu_berlaku_upload').val());
            form_data.append("akhir", $('#waktu_berakhir_upload').val());
            form_data.append("keterangan", 'Cek Plagiasi');
            form_data.append("kode_periode", "<?php echo $kode_periode; ?>");

            $.ajax({
                url: "<?php echo site_url('spp/do_upload_plagiasi') ?>",
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

    function upload_modal() {
        $('.progress').hide();
        $('#myUpload').modal("show");
    }

    function unduh_template() {
        window.open('<?php echo base_url(); ?>assets/update_tagihan_massal.xls')
    }
</script>
</div>