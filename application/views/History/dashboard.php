<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
$menu = $this->uri->segment(1);
$fungsi = $this->uri->segment(2);

?>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="col-sm-12">
						<div class="pull-left">
							<form method='get' action=''>
							<div class="form-inline">
								<div class="form-group">
									<div class='col-sm-4'>
										<label>Bulan</label><br>
										<input type='text' class='datepicker form-control' name='tanggal' value='<?= $this->input->get('tanggal');?>'>
									</div>
								</div>
								<div class="form-group">
									<br>
									<button class="btn btn-sm btn-primary" id="cari_filter">Cari</button>
								</div>
								</form>
							</div>
						</div>

					</div>
					<div class="col-sm-12 table-responsive">
						<?php 
						$bulan = explode('-',$tanggal);
						?>
						<h3>Logs <?= fbulan($bulan[1]).' '.$bulan[0]?></h3>
						<table class="table table-bordered" cellspacing="0">
							<thead>
								<tr>
									<th style="text-align: center;" rowspan='2'>NO</th>
									<th style="text-align: center;" rowspan='2'>USER</th>
									<th style="text-align: center;" colspan='<?=count($datamenu)+1?>'>MENU</th>
								</tr>
								<tr>
									<?php
									$totalbottom = array();
									$ind = 0;
									foreach ($datamenu as $data) {
										$totalbottom[$ind] = 0;
										?><th style="text-align: center;"><?=$data['menu_logs']?></th><?php
										$ind++;
									}
									?>
									
									<th style="text-align: center;">Total</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no =1;$l=0;
									foreach ($datauser as $data) {
								 ?>
								<tr>
								 	<td><?=$no++;?></td>
									 <?php
									 $totalside = 0;
									 $ind = 0;
									?><td style="text-align: center;"><?=$datasorting[$l]['user']?>(<?=$datasorting[$l]['userid']?>)</td><?php
										$k=0;
									foreach ($datamenu as $menu) {
										?><td style="text-align: center;">
										<a target='_blank' href='<?php echo base_url('systemlog?bulan='.$tanggal.'&user='.$datasorting[$l]['userid'].'&menu='.$menu['menu_logs']) ?>'>
										<?=$datasorting[$l][$k]?></a>
										</td><?php
										$totalbottom[$ind] += $datasorting[$l][$k];
										$ind++;
										$k++;
									 }
									 ?><td style="text-align: center;"><?=$datasorting[$l]['total']?></td><?php
									 $l++;
									?>
								 </tr>
								<?php } ?>
								<tr>
								 	<th style="text-align: center;" colspan=2>Total</th>
									<?php
									$all = 0;
									foreach ($totalbottom as $total) {
										$all+=$total;
									?><th style="text-align: center;"><?=$total?></th><?php
									}
									?>
									<th style="text-align: center;"><?=$all?></th>
								 </tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
    $(function() {
      $('.datepicker').datepicker({
      });
    });
</script>
