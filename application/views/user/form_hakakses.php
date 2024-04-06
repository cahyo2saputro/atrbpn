<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
.blur{
  color:#555;
}
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<div class="col-md-12 box-body">
						<div class="col-sm-12 table-responsive">
							<table class='table table-striped'>
								<tr>
									<td>NIP</td>
									<td>:</td>
									<td><?= $user['nip_usr']?></td>
								</tr>
								<tr>
									<td>Nama User</td>
									<td>:</td>
									<td><?= $user['name_usr']?></td>
								</tr>
								<tr>
									<td>Gunakan Template</td>
									<td>:</td>
									<td>
										<select class='form-control' id='template'>
											<option value='0'>tidak menggunakan template</option>
										<?php
										foreach ($template as $dd) {
											?><option value='<?= $dd['id_tmp']?>'><?=$dd['name_tmp']?></option><?php
										}
										?>
										</select>
									</td>
								</tr>
							</table>
							<div class="col-md-12">
								<form action='<?= base_url()?>user/hakakses/<?= $user['idusr_usr']?>' method='post'>
									<div class="col-sm-8">
										<ul class="nav nav-tabs" style='font-weight:bold'>
											<li><a data-toggle="tab" href="#menu-sub1">E-Walidata</a></li>
											<li class='active'><a class='tablis' data-toggle="tab" href="#menu-sub2">E-BPN</a></li>
                      <li><a class='tablis blur' >E-BPKAD</a></li>
											<li><a class='tablis blur' >E-PUPR</a></li>
											<li><a class='tablis' data-toggle="tab" href="#menu-sub5" >E-Desa</a></li>
											<li><a class='tablis' data-toggle="tab" href="#menu-sub6">E-PTSL</a></li>
											<li><a class='tablis' data-toggle="tab" href="#menu6">E-PPAT</a></li>
                      <li><a class='tablis' data-toggle="tab" href="#menu12">E-GTRA</a></li>
										</ul>
										<div class="tab-content" id='firstmenu'>
											<div id='menu-sub1' class="tab-pane fade">
												<ul class="nav nav-tabs">
													<li><a class='tablis' data-toggle="tab" href="#menu2">E-Data BPN</a></li>
													<li><a class='tablis' data-toggle="tab" href="#menu10">E-Data BPPKAD</a></li>
												</ul>
											</div>
											<div id='menu-sub2' class="tab-pane fade in active">
												<ul class="nav nav-tabs">
                          <li><a class='tablis' data-toggle="tab" href="#menu12">Daftar User</a></li>
													<li class='active'><a class='tablis' data-toggle="tab" href="#menu1">E-Digitalisasi</a></li>
													<li><a class='tablis' data-toggle="tab" href="#menu11">E-Validasi</a></li>
													<li><a class='tablis' data-toggle="tab" href="#menu4">E-TU</a></li>
													<li><a class='tablis' data-toggle="tab" href="#menu7">E-Tataruang</a></li>
                          <li><a class='tablis' data-toggle="tab" href="#menu14">E-Permohonan Ukur</a></li>
												</ul>
											</div>
											<div id='menu-sub3' class="tab-pane fade">
												<ul class="nav nav-tabs">
												</ul>
											</div>
											<div id='menu-sub4' class="tab-pane fade">
												<ul class="nav nav-tabs">
												</ul>
											</div>
											<div id='menu-sub5' class="tab-pane fade">
												<ul class="nav nav-tabs">
                          <li><a class='tablis' data-toggle="tab" href="#menu13">Layananan Pengukuran</a></li>
												</ul>
											</div>
											<div id='menu-sub6' class="tab-pane fade">
												<ul class="nav nav-tabs">
													<li><a class='tablis' data-toggle="tab" href="#menu3">E-Panitia Desa</a></li>
													<li><a class='tablis' data-toggle="tab" href="#menu5">E-Pengukuran</a></li>
													<li><a class='tablis' data-toggle="tab" href="#menu8">E-Pemetaan</a></li>
                          <li><a class='tablis' data-toggle="tab" href="#menu9">E-Yuridis</a></li>
												</ul>
											</div>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" name='semuamenu' id='checkall'>Tandai semua menu</label>
										</div>
										<div class="tab-content" id='menu'>
											<?php $start=1;
											foreach ($menu as $dd) {
												$role['type'] = "single";
												$role['table'] = "tb_userrole";
												$role['condition']['idmenu_role'] = $dd['id_has'];
												$role['condition']['idusr_role'] = $this->uri->segment(3);
												$drole = $this->crud_model->get_data($role);
												if($start==$dd['studio_has'] && $dd['studio_has']==1){
													?><div id="menu<?=$dd['studio_has']?>" class="tab-pane fade in active"><?php
													$start++;
												}

												if($start==$dd['studio_has']){
													?></div><div id="menu<?=$dd['studio_has']?>" class="tab-pane fade"><?php
													$start++;
												}

												?>
													<div class="checkbox">
														<label><input type="checkbox" class='menu' name='menu[]' <?php if($drole){echo 'checked';}?> value="<?= $dd['id_has']?>"><?= $dd['nma_has']?> <?= $dd['aksi_has']?></label>
													</div>

												<?php
											}
											 ?>
										  </div>
										</div>
									</div>
									<div class="col-sm-4">
										<table>
											<tr>
												<td>
													<label for="">Kecamatan</label>
													<?php
													if($kelurahan){
														foreach ($kelurahan as $dd) {
															?>
															<div class="checkbox">
									              <label><input type="checkbox" class='kelurahan' name='kel[]' checked value="<?= $dd['idkel_kel']?>"><?php echo $dd['nma_kel'];?></label>
									            </div>
															<?php
														}
													}
													 ?>
													<Select class="form-control" id="kecamatan">
													<option value="">-pilih kecamatan-</option>
													<?php foreach ($kecamatan as $kec) { ?>
													<option value="<?= $kec['kd_kec']; ?>"><?= $kec['nma_kec']; ?></option>
													<?php } ?>
													</Select>
												</td>
											</tr>
											<tr id="res_kel"></tr>
										</table>
									</div>
									<div class="col-sm-12 text-center">
										<input type='submit' class='btn btn-primary' value='submit'>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("#checkall").click(function(){
			$('.tab-pane.active input.menu').not(this).prop('checked', this.checked);
		});

		$(".tablis").click(function(){
			$("#checkall").prop("checked", false);
		});

	});
	$('#kecamatan').change(function(){
		$.ajax({
			url			: '<?=base_url()?>ajax/get_kelurahan',
			method		: 'GET',
			data		: {id:$(this).val()},
			async		: false,
			dataType	: 'html',
			success:function(data){
				$('#res_kel').html(data);
			}
		});
		// console.log($(this).val());
	});

	$('#template').change(function(){
		$.ajax({
			url			: '<?=base_url()?>ajax/get_template',
			method		: 'GET',
			data		: {id:$(this).val()},
			async		: false,
			dataType	: 'html',
			success:function(data){
				$('#menu').html(data);
			}
		});
	});
</script>
