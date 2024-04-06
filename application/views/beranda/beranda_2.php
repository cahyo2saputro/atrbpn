<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	h3{
		font-size:27px !important;
	}
</style>
<div class="box box-primary col-md-8">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<section class='content'>
					<div class='row'>

						<?php if (in_array(13, $_SESSION['menu']) || $user['level_usr']==1) { ?>
						<div class="col-md-3">
							<div class="small-box bg-yellow">
		            <div class="inner">
		              <h3>E-Data</h3>

		              <p>E-Data</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-database" aria-hidden="true"></i>
		            </div>
		            <a href="<?php echo base_url('Studio2') ?>" class="small-box-footer">
		              Detail <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
						</div>
						<?php } ?>

						<?php if (in_array(1, $_SESSION['menu']) || $user['level_usr']==1) { ?>
						<div class="col-md-3">
							<div class="small-box bg-red">
		            <div class="inner">
		              <h3>E-Digitalisasi</h3>

		              <p>E-Digitalisasi</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-cloud-upload" aria-hidden="true"></i>
		            </div>
		            <a href="<?php echo base_url('Studio1') ?>" class="small-box-footer">
		              Detail <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
						</div>
						<?php } ?>
						<?php if (in_array(38, $_SESSION['menu']) || $user['level_usr']==1) { ?>
						<div class="col-md-3">
							<div class="small-box bg-green">
		            <div class="inner">
		              <h3>E-Validasi</h3>

		              <p>E-Validasi</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-check-square-o" aria-hidden="true"></i>
		            </div>
		            <a href="<?php echo base_url('Studio1/validation') ?>" class="small-box-footer">
		              Detail <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
						</div>
						<?php } ?>

						<?php if (in_array(25, $_SESSION['menu']) || $user['level_usr']==1) { ?>
						<div class="col-md-3">
							<div class="small-box bg-yellow">
		            <div class="inner">
		              <h3>E-Desa</h3>

		              <p>E-Desa</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-file" aria-hidden="true"></i>
		            </div>
		            <a href="<?php echo base_url('Studio3') ?>" class="small-box-footer">
		              Detail <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
						</div>
						<?php } ?>

						<?php if (in_array(42, $_SESSION['menu']) || $user['level_usr']==1) { ?>
						<div class="col-md-3">
							<div class="small-box bg-blue">
		            <div class="inner">
		              <h3>E-Fisik</h3>

		              <p>E-Fisik</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-map-marker" aria-hidden="true"></i>
		            </div>
		            <a href="<?php echo base_url('Studio5') ?>" class="small-box-footer">
		              Detail <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
						</div>
						<?php } ?>

						<?php if (in_array(40, $_SESSION['menu']) || $user['level_usr']==1) { ?>
						<div class="col-md-3">
							<div class="small-box bg-green">
		            <div class="inner">
		              <h3>E-TU</h3>

		              <p>E-TU</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-envelope" aria-hidden="true"></i>
		            </div>
		            <a href="<?php echo base_url('Studio4') ?>" class="small-box-footer">
		              Detail <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
						</div>
						<?php } ?>

						<?php if (in_array(56, $_SESSION['menu']) || $user['level_usr']==1) { ?>
						<div class="col-md-3">
							<div class="small-box bg-red">
		            <div class="inner">
		              <h3>E-PPAT</h3>

		              <p>E-PPAT</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-user-o" aria-hidden="true"></i>
		            </div>
		            <a href="<?php echo base_url('Studioppat') ?>" class="small-box-footer">
		              Detail <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
						</div>
						<?php } ?>

						<?php if (in_array(42, $_SESSION['menu']) || $user['level_usr']==1) { ?>
						<div class="col-md-3">
							<div class="small-box bg-blue">
		            <div class="inner">
		              <h3>E-Tataruang</h3>

		              <p>E-Tataruang</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-area-chart" aria-hidden="true"></i>
		            </div>
		            <a href="<?php echo base_url('Studiotataruang') ?>" class="small-box-footer">
		              Detail <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
						</div>
						<?php } ?>

					</div>
				</section>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.sidebar-toggle').click();
	})
</script>
