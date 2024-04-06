<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<div class="col-md-12 box-body">
						<?php
						if($mode=='input'){
							?><form action='<?= base_url()?>Templateuser/add' method='post'><?php
						}else{
							?><form action='<?= base_url()?>Templateuser/edit/<?= $this->uri->segment(3)?>' method='post'><?php
						}
						 ?>

						<div class="col-sm-12 table-responsive">
							<table class='table table-striped'>
								<tr>
									<td>Nama</td>
									<td>:</td>
									<td><input type='text' class='form-control' name='template' placeholder='nama template' value='<?= $template['name_tmp']?>'></td>
								</tr>
								<tr>
									<td>Deskripsi</td>
									<td>:</td>
									<td><input type='text' class='form-control' name='desc' placeholder='deskripsi template' value='<?=$template['desc_tmp']?>'></td>
								</tr>
							</table>
							<div class="col-md-12">
									<div class="col-sm-12">
										<ul class="nav nav-tabs" style='font-weight:bold'>
											<li><a data-toggle="tab" href="#menu-sub1">E-Walidata</a></li>
											<li class='active'><a class='tablis' data-toggle="tab" href="#menu-sub2">E-BPN</a></li>
											<li><a class='tablis blur' >E-BPKAD</a></li>
											<li><a class='tablis blur' >E-PUPR</a></li>
											<li><a class='tablis blur' >E-Desa</a></li>
											<li><a class='tablis' data-toggle="tab" href="#menu-sub6">E-PTSL</a></li>
											<li><a class='tablis' data-toggle="tab" href="#menu6">E-PPAT</a></li>
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
													<li class='active'><a class='tablis' data-toggle="tab" href="#menu1">E-Digitalisasi</a></li>
													<li><a class='tablis' data-toggle="tab" href="#menu11">E-Validasi</a></li>
													<li><a class='tablis' data-toggle="tab" href="#menu4">E-TU</a></li>
													<li><a class='tablis' data-toggle="tab" href="#menu7">E-Tataruang</a></li>
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
										<div class="checkbox">
											<label><input type="checkbox" name='semuamenu' id='checkall'>Tandai semua menu</label>
										</div>
										<div class="tab-content">
											<?php $start=1;
											foreach ($menu as $dd) {
												$role['type'] = "single";
												$role['table'] = "tb_templaterole";
												$role['condition']['idmenu_role'] = $dd['id_has'];
												$role['condition']['idtmp_role'] = $this->uri->segment(3);
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
									<div class="col-sm-12 text-center">
										<input type='submit' class='btn btn-primary' value='submit'>
									</div>
							</div>
						</div>
						</form>
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
</script>
