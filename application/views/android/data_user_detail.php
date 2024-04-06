<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-striped">
							<tbody>
								<tr>
									<td>Nama</td>
									<td>:</td>
									<td><?=$studio['nma_reg']?></td>
								</tr>
								<tr>
									<td>Alamat</td>
									<td>:</td>
									<td><?=$studio['alamat_reg']?></td>
								</tr>
								<tr>
									<td>NO HP</td>
									<td>:</td>
									<td><?=$studio['nohp_reg']?></td>
								</tr>
								<tr>
									<td>Dokumen</td>
									<td>:</td>
									<td><?php if($studio['ktp_reg']){?><img style='max-height:300px' class='fancybox' href='<?= base_url()?>Penduduk/<?= $studio['ktp_reg']?>' src='<?= base_url()?>Penduduk/<?= $studio['ktp_reg']?>'><?php }?>
									</td>
								</tr>
								<tr>
									<td>Status</td>
									<td>:</td>
									<td><?= typeuser($studio['idusr_reg'],'valid')?>
									</td>
								</tr>
								<tr>
									<td>User Role</td>
									<td>:</td>
									<td><?= typeuser($studio['typeusr_reg'],'type')?> <?=$studio['nma_kel']?>
									</td>
								</tr>
								<tr>
									<td>Aksi</td>
									<td>:</td>
									<td>
										<?php
										if($studio['idusr_reg']==0){
											?><a href='<?= base_url()?>apimobilepublic/validregis/<?= $studio['id_reg']?>' class='btn btn-primary'>Validasi</a><?php
										}else{
											?><a href='<?= base_url()?>apimobilepublic/validregis/<?= $studio['id_reg']?>' class='btn btn-warning'>Unvalidasi</a><?php
										}
										?>
									</td>
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
<script>
		$(document).ready(function(){
			//FANCYBOX
			//https://github.com/fancyapps/fancyBox
			$(".fancybox").fancybox({
					openEffect: "none",
					closeEffect: "none"
			});
		});
</script>
