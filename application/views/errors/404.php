<?php
if (isset($_SERVER['HTTP_HOST']) && preg_match('/^((\[[0-9a-f:]+\])|(\d{1,3}(\.\d{1,3}){3})|[a-z0-9\-\.]+)(:\d+)?$/i', $_SERVER['HTTP_HOST']))
{
	$base_url = (is_https() ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST']
		.substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
}
else
{
	$base_url = 'http://localhost/';
}
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>  
  <meta charset="utf-8" />
  <title>SIA ULM</title>
  <link rel="shortcut icon" href="<?php echo $base_url; ?>assets/data/images/logo-unlam2.png" />
  <meta name="description" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet"  href="<?php echo $base_url; ?>assets/data/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet"  href="<?php echo $base_url; ?>assets/data/css/animate.css" type="text/css" />
  <link rel="stylesheet"  href="<?php echo $base_url; ?>assets/data/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet"  href="<?php echo $base_url; ?>assets/data/css/icon.css" type="text/css" />
  <link rel="stylesheet"  href="<?php echo $base_url; ?>assets/data/css/font.css" type="text/css" />
  <link rel="stylesheet"  href="<?php echo $base_url; ?>assets/data/css/app.css" type="text/css" />  
    <!--[if lt IE 9]>
    <script src="data/js/ie/html5shiv.js"></script>
    <script src="data/js/ie/respond.min.js"></script>
    <script src="data/js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<style>
    body{
        background-color: #16333f;
    }
    .bg-dark{
       background-color: #16333f; 
    }
    
    .svg{
        width: 45%;
        height: 45%;
        margin-top: 0%
    }
    .beranda{
        position: relative; left: 41.4%;margin-bottom: 1%;margin-top: -8%
    }
    footer{
        margin-top : -48%
    }
    @media screen and (max-width: 767px) {
        .svg{
            width: 100%;
            height: 100%;
            margin-top: 30%
        }
        .beranda{
            position: relative; left: 0%;margin-bottom: 1%;margin-top: -10%
        }
        footer{
            margin-top : 0%;
        }
    }
    .cp {
        position: absolute ;
        top : 92% ;
        text-align: center ;
        color : #D7D7D7 ;
    }
</style>
<body>
    <section id="content">
    <div class="row m-n">
      <div class="col-sm-12 ">
        <div class="text-center m-b-lg">
            <img  src="<?php echo $base_url; ?>assets/data/images/error.jpg" class="svg"><br/>
        </div>
      </div>
<!--        <div style=" "class="col-sm-2 beranda">
          <a href="<?php echo base_url('welcome'); ?>" class="list-group-item">
            <i class="fa fa-chevron-right icon-muted"></i>
            <i class="fa fa-fw fa-home icon-muted"></i> Halaman Utama
          </a>
        </div>-->
    </div>
        <div class="col-xs-12 cp">
        <small style="color :  white">UPT PTIK ULM &copy; 2016</small>
    </div>
  </section>
  <script src="<?php echo $base_url; ?>assets/data/js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="<?php echo $base_url; ?>assets/data/js/bootstrap.js"></script>
  <!-- App -->
  <script src="<?php echo $base_url; ?>assets/data/js/app.js"></script>  
  <script src="<?php echo $base_url; ?>assets/data/js/slimscroll/jquery.slimscroll.min.js"></script>
  <script src="<?php echo $base_url; ?>assets/data/js/app.plugin.js"></script>
</body>
</html>

