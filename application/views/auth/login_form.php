<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (isset($this->session->smt_member)) {
	header("location:".base_url()."home");
}
?>
<style>
.login-logo, .register-logo{
	font-size: 30px !important;
	background: #FFD700;
	margin-bottom:0px !important;
	padding:15px;
}
</style>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php if(isset($title_page)) echo $title_page." - ";?> e-ATRBPN Kab.Semarang</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <meta name="description" content="e-ATRBPN Kab.Semarang" />
  <meta name="keywords" content="e-ATRBPN Kab.Semarang" />
  <meta name="author" content="e-ATRBPN Kab.Semarang" />
  <meta name="copyright" content="e-ATRBPN Kab.Semarang" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
 <link rel="manifest" type="text/css" href="<?=base_url()?>assets/manifest.json">
  <script src="<?=base_url()?>assets/sw.js" ></script>
  <link rel="stylesheet" href="<?php echo base_url("assets/bootstrap/dist/css/bootstrap.min.css"); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url("assets/font-awesome/css/font-awesome.min.css"); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url("assets/Ionicons/css/ionicons.min.css"); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url("assets/css/AdminLTE.min.css"); ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url("assets/css/skins/_all-skins.min.css"); ?>">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- jQuery 3 -->
<?php if ($this->uri->segment(2) != "master") { ?>
  <script src="<?php echo base_url("assets/js/jquery.min.js"); ?>"></script>
<?php } ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
</head>
<style>
body{
	background: url('https://kreasiundangan.com/wp-content/uploads/2018/09/golden-european-pattern.jpg') !important;
}
</style>
<body class="hold-transition login-page" onload="createCaptcha()">
<div class="login-box">
  <div class="login-logo">
    <a href="<?php echo base_url(); ?>"><b>EATRBPN</b> KAB.SEMARANG</a>
  </div>
  <!-- /.login-logo -->
    <?php if ($this->session->message): ?>
        <div class="col-xs-12">
            <div class="box-body">
                <div class="alert alert-error alert-dismissible" style="width: 100%">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->message; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
    <?php echo validation_errors();?>
    <form id='formlogin' action="<?php echo base_url("auth/process"); ?>" method="post" onsubmit="validateCaptcha()">
      <div class="form-group has-feedback">
        <input type="username" name="username" id="username" class="form-control" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
			<div class="form-group has-feedback">
				<input type='radio' name='source' value='1' checked> BPN
				<input type='radio' name='source' value='2'> Android
      </div>
      <div class="form-group has-feedback">
				<div id="captcha">
				</div>
				<input type="text" class='form-control' placeholder="Captcha" id="cpatchaTextBox"/>
          <?= $capca; ?>
					<!-- <h4>Capcha</h4> -->
          <input style='text-align:center;font-size:20px;' type="hidden" readonly name="capca_valid" style="width:100%;" value="<?= $text_capca; ?>">
      </div>
      <div class="form-group text-center">
          <input type="text" name="capca" value="" class="input-field form-control" placeholder="masukan capcha..">
      </div>
      <div class="row">
        <div class="col-xs-8"></div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>

      </div>
    </form>

    <center>
    <a href="#">&copy; copyright 2021 - e-ATRBPN Kab.Semarang</a><br>
    </center>

  </div>
</div>
<script src="<?php echo base_url("assets/jquery/dist/jquery.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/jquery/dist/bootstrap.min.js"); ?>"></script>
</body>
</html>
