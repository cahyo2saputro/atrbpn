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
						<div class='col-sm-6'>
						<table id="data-staff" class="table table-striped">
							<tbody>
								<tr>
									<td><h3>Data user</h3></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>Nama</td>
									<td>:</td>
									<td><?=$studio['nma_reg']?></td>
								</tr>
								<tr>
									<td>NO HP</td>
									<td>:</td>
									<td><?=$studio['nohp_reg']?></td>
								</tr>
								<tr>
									<td>Kecamatan</td>
									<td>:</td>
									<td><?=$studio['nma_kec']?></td>
								</tr>
								<tr>
									<td>Kelurahan</td>
									<td>:</td>
									<td><?=$studio['nma_kel']?></td>
								</tr>
								<tr>
									<td>Dokumen</td>
									<td>:</td>
									<td><iframe src='<?= base_url()?>Penduduk/<?= $studio['ktp_reg']?>'></iframe>
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
									<td><?= typeuser($studio['typeusr_reg'],'type')?>
									</td>
								</tr>
							</tbody>
						</table>
						</div>
						<div class='col-sm-6'>
							<table id="data-staff" class="table table-striped">
								<tbody>
									<tr>
										<td><h3>Role</h3></td>
										<td></td>
										<td></td>
									</tr>
									<form action='<?= base_url()?>Publics/changerole/<?=$this->uri->segment(3)?>' method='post'>
									<tr>
										<td>Role</td>
										<td>:</td>
										<td>
											<select class='form-control' name='role' id='role'>
												<option value='0' <?php if($studio['typeusr_reg']==0){ echo 'selected';}?>>Guest</option>
												<option value='1' <?php if($studio['typeusr_reg']==1){ echo 'selected';}?>>Kades / Sekdes</option>
												<option value='3' <?php if($studio['typeusr_reg']==3){ echo 'selected';}?>>Admin Desa</option>
												<option value='4' <?php if($studio['typeusr_reg']==4){ echo 'selected';}?>>Petugas Ukur</option>
											</select>
										</td>
									</tr>
									<tr class='kelurahan'>
										<td>Kecamatan</td>
										<td>:</td>
										<td>
											<select class='form-control' name='kecamatan' id='kecamatan'>
												<?php foreach ($kecamatan as $data) {
													?><option value='<?=$data['kd_kec']?>' <?php if($data['kd_kec']==$kelurahan['kd_kec']){echo 'selected';}?>><?= $data['nma_kec']?></option><?php
												} ?>
											</select>
										</td>
									</tr>
									<tr class='kelurahan'>
										<td>Kelurahan</td>
										<td>:</td>
										<td>
											<select class='form-control' name='kelurahan' id='kelurahan'>
												<option value='<?= $kelurahan['kd_full']?>'><?=$kelurahan['nma_kel']?></option>
											</select>
										</td>
									</tr>
									<tr>
										<td></td>
										<td></td>
										<td>
											<button type='submit' class='btn btn-primary'>Simpan</button>
										</td>
									</tr>
									</form>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		var role = $('#role').val();
		if(role==1 || role==3){
			$('.kelurahan').show();
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cari_kelurahanfull/'+$('#kelurahan').val(),
					data: 'kec='+$('#kecamatan').val(),
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$("#kelurahan").html(response);
					}
			});
		}else{
			$('.kelurahan').hide();
		}

		$('#role').on('change',function () {
			role = $(this).val();
			if(role==1 || role==3){
				$('.kelurahan').show();
			}else{
				$('.kelurahan').hide();
			}
		});

		$('#kecamatan').on('change',function () {
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cari_kelurahanfull',
					data: 'kec='+$(this).val(),
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$("#kelurahan").html(response);
					}
			});
		});
	});
</script>
