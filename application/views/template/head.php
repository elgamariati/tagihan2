<?php

?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>SI TAGIHAN</title>
    <link rel="icon" href="<?php echo base_url(); ?>assets/img/logo_unlam_small.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="Sistem Informasi Pembayaran" name="description" />
    <meta content="UPT PTIK UNLAM" name="author" />
    <meta name="google" value="notranslate">

    <!-- toastr css -->
    <link href="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" media="screen" />
    <!-- swal -->
    <link href="<?php echo base_url(); ?>assets/plugins/toastr/swal.min.css" rel="stylesheet" type="text/css" media="screen" />

    <!-- BEGIN PLUGIN CSS -->
    <link href="<?php echo base_url(); ?>assets/plugins/chosen/docsupport/style.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo base_url(); ?>assets/plugins/chosen/docsupport/prism.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo base_url(); ?>assets/plugins/chosen/chosen.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo base_url(); ?>assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/dropzone/css/dropzone.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/ios-switch/ios7-switch.css" rel="stylesheet" type="text/css" media="screen">
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo base_url(); ?>assets/plugins/boostrap-clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
    <!--      <link href="<?php echo base_url(); ?>assets/plugins/jquery-datatable/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
      <link href="<?php echo base_url(); ?>assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />-->
    <link href="<?php echo base_url(); ?>assets/css/datatables bootstrap 3/datatables.bootstrap.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo base_url(); ?>assets/css/datatables bootstrap 3/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen" />
    <!-- END PLUGIN CSS -->
    <!-- BEGIN CORE CSS FRAMEWORK -->
    <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/font-awesome/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/animate.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" />
    <!-- END CORE CSS FRAMEWORK -->
    <!-- BEGIN CSS TEMPLATE -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/custom-icon-set.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/mystyle.css" rel="stylesheet" type="text/css" />
    <!-- END CSS TEMPLATE -->
    <link href="<?php echo base_url(); ?>assets/plugins/boostrap-slider/css/slider.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
    <!--<script src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap.min.js" type="text/javascript"></script>-->
    <script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <!--  <script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/js/jquery.dataTables.min.js" type="text/javascript"></script>
      <script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/extra/js/dataTables.tableTools.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/lodash.min.js"></script>-->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.maskMoney.js" type="text/javascript"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker();
            $("#datepicker2").datepicker();
            $("#daftarMulai").datepicker();

        });

        $(document).ready(function() {
            $('#table_id').dataTable();
            $('.fa-spin').hide();
        });

        $('#table_id').DataTable({
            "pageLength": 2,
            "searching": false
        });

        $('#table_id_length').select2();

        function ubahPassword() {
            //form.preventDefault();
            $('.fa-spin').show();
            $('.fa-spin').prop('disabled', true);
            $.post('<?php echo base_url('/login/ubah_pass'); ?>', $('form.myform').serialize(), function(response) {
                $('.fa-spin').hide();
                $('.fa-spin').prop('disabled', false);
                tabel = jQuery.parseJSON(response);
                var validasi = tabel.data.validasi;
                if (validasi == 1) {
                    var pesan = tabel.data.pesan;
                    $('#pesan_pass').html(pesan);
                    $('#error_lama').html('');
                    $('#error_baru').html('');
                    $('#error_retype').html('');
                    $('#lama').val('');
                    $('#baru').val('');
                    $('#retype').val('');
                } else {
                    var lama = tabel.data.lama;
                    var baru = tabel.data.baru;
                    var retype = tabel.data.retype;
                    $('#error_lama').html(lama);
                    $('#error_baru').html(baru);
                    $('#error_retype').html(retype);
                }
                $('#Submit').html('Simpan');

            });

        };
    </script>

    <style>
        /*UNIVERSAL CSS*/
        .modal-body {
            background-color: #ffffff;
        }

        #modal-title {
            font-weight: bold !important;
            font-size: 14px;
        }

        .top-modal-space {
            margin-top: -20px;
        }

        .modal-header {
            font-weight: bold !important;
            font-size: 14px;
        }

        /*eksperiment*/
        /*        ::-webkit-scrollbar {
            width: 5px;
        }
         Track 
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
            -webkit-border-radius: 10px;
            border-radius: 10px;
        }
         Handle 
        ::-webkit-scrollbar-thumb {
            -webkit-border-radius: 10px;
            border-radius: 10px;
            background: rgba(10,166,153,0.8); 
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.8); 
        }
        ::-webkit-scrollbar-thumb:window-inactive {
                background: rgba(10,166,153,0.4); 
        }*/
    </style>
</head>
<div id="modal-pass" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Ubah Password</div>
            <div class="modal-body">
                <?php echo form_open('login/ubah_pass', array('class' => 'myform')); ?>
                <div id="pesan_pass"></div>
                <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password" class="form-control" placeholderr="Password lama" id="lama" name="lama" value="<?php echo set_value('lama', '') ?>">
                    <div id="error_lama"></div>
                </div>
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" class="form-control" placeholderr="Password baru" id="baru" name="baru" value="<?php echo set_value('baru', '') ?>">
                    <div id="error_baru"></div>
                </div>
                <div class="form-group">
                    <label>Re-type Password Baru</label>
                    <input type="password" class="form-control" placeholderr="Re-type Password baru" id="retype" name="retype" value="<?php echo set_value('retype', '') ?>">
                    <div id="error_retype"></div>
                </div>

                <?php echo form_close(); ?>

            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="btn-hapus" class="btn btn-primary" onclick="ubahPassword();">
                    <span class="fa fa-spinner fa-spin"></span> <span class="fa fa-floppy-o"></span> Ubah
                </button>
                <button id="btn-batal" data-dismiss="modal" class="btn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<body>