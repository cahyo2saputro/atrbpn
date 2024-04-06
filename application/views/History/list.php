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
										<label>Tanggal</label><br>
										<input type='text' class='datepicker form-control' name='tanggal' value='<?= $tanggal;?>'>
									</div>
								</div>
								<div class="form-group">
									<div class='col-sm-4'>
										<label>User</label><br>
										<select class='form-control' name='user'>
										<option value='' >Semua</option>
											<?php
											foreach($datauser as $dd){
												?><option value='<?=$dd['id_user_logs'];?>' <?php if($dd['id_user_logs']==$this->input->get('user')){echo 'selected';}?>><?=$dd['name_usr']?></option><?php 
											} 
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class='col-sm-4'>
										<label>Menu</label><br>
										<select class='form-control' name='menu'>
										<option value='' >Semua</option>
											<?php
											foreach($datamenu as $dd){
												?><option value='<?=$dd['menu_logs'];?>' <?php if($dd['menu_logs']==$this->input->get('menu')){echo 'selected';}?>><?=$dd['menu_logs']?></option><?php 
											} 
											?>
										</select>
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
					<div class="col-sm-12">
						<hr>
						<div class="col-sm-3 col-xs-6">
							<div class="description-block border-right">
								<h5 class="description-header"><?=count($history)?></h5>
								<span class="description-text">Total Aktivitas</span>
							</div>
						</div>

						<div class="col-sm-3 col-xs-6">
							<div class="description-block border-right">
								<h5 class="description-header"><?=$add['jumlah']?></h5>
								<span class="description-text">Menambahkan Data baru</span>
							</div>
						</div>

						<div class="col-sm-3 col-xs-6">
							<div class="description-block border-right">
								<h5 class="description-header"><?=$edit['jumlah']?></h5>
								<span class="description-text">Mengedit Data</span>
							</div>
						</div>

						<div class="col-sm-3 col-xs-6">
							<div class="description-block">
								<h5 class="description-header"><?=count($history)-$add['jumlah']-$edit['jumlah']?></h5>
								<span class="description-text">Lain-lain</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table class="table table-bordered" cellspacing="0">
							<thead>
								<tr>
									<th style="text-align: center;">NO</th>
									<th style="text-align: center;">USER</th>
									<th style="text-align: center;">IP</th>
									<th style="text-align: center;">BROWSER</th>
									<th style="text-align: center;">OS</th>
									<th style="text-align: center;">TANGGAL</th>
									<th style="text-align: center;">DATABASE</th>
									<th style="text-align: center;">REFERENSI</th>
									<th style="text-align: center;">AKTIVITAS</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = count($history);

									foreach ($history as $data) {
								 ?>
								<tr>
								 	<td><?=$no--;?></td>
								 	<td> <?=$data['name_usr'];?> </td>
								 	<td> <?=$data['ip_logs'];?> </td>
								 	<td> <?=$data['browser_logs'];?></td>
								 	<td><?=$data['os_logs'];?></td>
								 	<td> <?=$data['date_logs']?> </td>
								 	<td> <?=$data['dbase_logs']?></td>
								 	<td><?= 'ID = '.$data['refer_logs'].'<br>Menu = <b>'.$data['menu_logs'].'</b><br>Lokasi = <b>'.$data['detail_logs'].'</b>';?></td>
								 	<td><?= $data['aktivitas_logs']?></td>
								 </tr>
								<?php } ?>
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
