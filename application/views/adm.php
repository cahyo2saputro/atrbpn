<!DOCTYPE html>
<html>
<?php $this->load->view("template/adm/metadata"); ?>

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
		        <li><a href="<?= base_url()?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		      	<?php
		      	  $dx = count($data['subtitle']);
		      	  $x = 1;
			      foreach ($data['subtitle'] as $key) {
							if (strpos($key[1], 'javascript')!== false) {
							    $linkkey=$key[1];
							}else{
								$linkkey=base_url().''.$key[1];
							}
			       ?>
			        <li <?php if($x==$dx){ echo "class='active'"; } ?>><a href="<?= $linkkey;?>"><?php echo $key[0]; ?></a></li><?php
			      	$x++;
			      } ?>
		      </ol>
		    </section>
		    <section class="content">
				<?php
				if($load){
					foreach ($load as $val) {
						$this->load->view($val);
					}
				}

			?>
			</section>
		</div>
	</div>
	<?php $this->load->view("template/adm/footer"); ?>

</body>

<?php $this->load->view("template/adm/js"); ?>
</html>
