<!DOCTYPE html>
<html>
<?php $this->load->view("template/adm/metadata"); ?>

<?php
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<body class="hold-transition skin-blue fixed sidebar-mini">
	<div class="wrapper">
		<?php $this->load->view("template/adm/header"); ?>

		<?php $this->load->view("template/adm/aside"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
		    <section class="content-header">
		      <h1>
		        <?php echo $data['title']; ?>
		      </h1>
		      <ol class="breadcrumb">
		        <li><a href="<?= base_url();?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		      	<?php
		      	  $dx = count($data['subtitle']);
		      	  $x = 1;
			      foreach ($data['subtitle'] as $key) {
			       ?>
			        <li <?php if($x==$dx){ echo "class='active'"; } ?>><a href="<?= base_url().''.$key[1];?>"><?php echo $key[0]; ?></a></li><?php			      
			      	$x++;
			      } ?>
		      </ol>
		    </section>
		    <section class="content">
				<?php
				echo $output;
				?>
			</section>
		</div>
	</div>

	<?php $this->load->view("template/adm/footer"); ?>

	<?php $this->load->view("template/adm/rightbar"); ?>


</body>
<?php foreach($js_files as $file): ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<?php $this->load->view("template/adm/js"); ?>
<!-- custom button input -->
<?php
$mod = $this->uri->segment(1);
$dua = $this->uri->segment(2);
$tiga = $this->uri->segment(3);
?>
<?php
if ($dua == "client") {
 ?>
<script type="text/javascript">
$(document).ready(function(){
  $('.tDiv3').append('<a id="my_button" href="<?php echo base_url().$mod; ?>/importclient"><i class="fa fa-upload"></i>IMPORT</a>');
});
</script>
<?PHP } elseif ($dua == "supplier") { ?>
<script type="text/javascript">
$(document).ready(function(){
  $('.tDiv3').append('<a id="my_button" href="<?php echo base_url().$mod; ?>/importsup"><i class="fa fa-upload"></i>IMPORT</a>');
});
</script>
<?php } elseif ($dua == "produk") { ?>
<script type="text/javascript">
$(document).ready(function(){
  $('.tDiv3').append('<a id="my_button" href="<?php echo base_url().$mod; ?>/importprd"><i class="fa fa-upload"></i>IMPORT</a>');
});
</script>
<?php } elseif ($dua == "jerigen") { ?>
<script type="text/javascript">
$(document).ready(function(){
  $('.tDiv3').append('<a id="my_button" href="<?php echo base_url().$mod; ?>/importjer"><i class="fa fa-upload"></i>IMPORT</a>');
  $('.tDiv3').append(' | <a id="my_button" href="<?php echo base_url().$mod; ?>/jerigenlain"><i class="fa fa-database"></i>Data Jerigen Lain</a>');
});
</script>
<?php } elseif ($dua == "detail_jer") { ?>
<script type="text/javascript">
$(document).ready(function(){
  $('.tDiv3').append('<a id="my_button" href="<?php echo base_url().$mod; ?>/jerigen"><i class="fa fa-chevron-left"></i>BACK</a>');
});
</script>
<?php } elseif ($dua == "jerigenlain") { ?>
<script type="text/javascript">
$(document).ready(function(){
  $('.tDiv3').append(' | <a id="my_button" href="<?php echo base_url().$mod; ?>/jerigen"><i class="fa fa-database"></i>Data Jerigen Tersedia</a>');
});
</script>
<?php } ?>
</html>
