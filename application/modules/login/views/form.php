<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>SI TAGIHAN ULM</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/logo upr.png" />
    <meta name="description" content="Sistem Informasi Mahasiswa Baru Universitas Lambung Mangkurat" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- BEGIN CORE CSS FRAMEWORK -->
    <link href="<?php echo base_url(); ?>assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/animate.min.css" rel="stylesheet" type="text/css" />
    <!-- END CORE CSS FRAMEWORK -->
    <!-- BEGIN CSS TEMPLATE -->
    <link href="<?php echo base_url(); ?>assets/css/mystyle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/custom-icon-set.css" rel="stylesheet" type="text/css" />
    <!-- END CSS TEMPLATE -->
    <style>
        body {
            background-image: url(assets/img/bg_login_gerbang.jpeg);
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>

<body class="error-body no-top">
    <div class="container">
        <div class="row login-container column-seperation">

            <div class="col-md-5 login-form">
                <?php echo form_open(base_url(uri_string())); ?>
                <?php //echo validation_errors();
                ?>
                <!--                    <form id="login-form" class="login-form" action="index.html" method="post"> -->
                <div class="row">
                    <div class="form-group col-md-10">
                        <div style="text-align:center">
                            <img src="<?php echo base_url() . "assets/img/logo upr.png"; ?>" width="100px">
                            <div style="font-size:20px;"><b style="font-weight:900">SI TAGIHAN</b> <b style="font-weight:100">U P R</b></div>
                            SISTEM INFORMASI TAGIHAN
                        </div>
                    </div>
                    <div class="form-group col-md-10" style="margin:0px">

                        <div class="controls">
                            <div class="input-with-icon  right">
                                <i class=""></i>
                                <!--<input type="text" name="txtusername" id="txtusername" class="form-control">-->
                                <?php echo form_input('username', $model['username'], 'placeholder="Username" class="form-control"'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">

                        <span class="help"></span>
                        <div class="controls">
                            <div class="input-with-icon  right">
                                <i class=""></i>
                                <!--<input type="password" name="txtpassword" id="txtpassword" class="form-control">-->
                                <?php echo form_password('password', '', 'placeholder="Password" class="form-control"'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10" style="text-align:center;margin-bottom: 0px;">

                        <span class="help"></span>
                        <div class="controls">
                            <small style="color : red ;"><?php echo $status; ?></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-10 form-group">
                        <?php echo form_submit('Submit', 'Masuk', 'class="btn btn-primary btn-cons pull-right"'); ?>
                        <!--<button class="btn btn-primary btn-cons pull-right" type="submit">Login</button>-->
                    </div>
                </div <!--</form>--> <?php  ?> <?php echo form_close(); ?> </div> </div> </div> <!-- BEGIN CORE JS FRAMEWORK-->
                <script src="<?php echo base_url(); ?>assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url(); ?>assets/plugins/pace/pace.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url(); ?>assets/js/login.js" type="text/javascript"></script>
                <!-- END CORE TEMPLATE JS -->
</body>

</html>