<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
	<ul class="nav nav-tabs" id="myTab" role="tablist">
	  <?php
	      if (in_array(133, $_SESSION['menu']) || $user['level_usr']==1) {
	        ?><li class="nav-item" style='background:#f0f0f0'>
	          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/permohonandesa/" role="tab" aria-controls="home" aria-selected="false">Permohonan Ukur</a>
	        </li><?php
	      }
	      if (in_array(134, $_SESSION['menu']) || $user['level_usr']==1) {
	        ?><li class="nav-item">
	          <a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/validasisertipikat/" role="tab" aria-controls="profile" aria-selected="false">Validasi Sertipikat</a>
	        </li><?php
	      }
	     ?>
	</ul>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="col-sm-12">
						<div class="pull-left">
							<div class="form-inline">
								<div class="form-group">
									<div class='col-sm-12'>
										<label>Kecamatan</label><br>
										<select class="form-control input-sm" id="filter_kecamatan" style='width:150px'>
											<option value="0">Pilih Kecamatan</option>
											<?php
												foreach ($filter_kecamatan as $fk) {
											 ?>
											 <option value="<?=$fk->kd_kec?>"><?=$fk->nma_kec?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class='col-sm-4'>
										<label>Kelurahan</label><br>
										<select class="form-control input-sm" id="filter_kelurahan" style='width:150px'>
											<option value="0">Pilih Kelurahan</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class='col-sm-4'>
										<label>NIK</label><br>
										<input type='text' name='nik' id='nik' class='form-control'>
									</div>
								</div>
								<div class="form-group">
									<br>
									<button class="btn btn-sm btn-primary" id="cari_filter">Cari</button>
								</div>
							</div>
						</div>
						<div class="pull-right">
							<?php
									if (in_array(135, $_SESSION['menu']) || $user['level_usr']==1) {
										?><button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></button><?php
									}
								 ?>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;">No</th>
									<th style="text-align: center; vertical-align: middle;">Kecamatan</th>
									<th style="text-align: center; vertical-align: middle;">Kelurahan</th>
									<th style="text-align: center; vertical-align: middle;">Identitas</th>
									<th style="text-align: center; vertical-align: middle;">No. Hak</th>
									<th style="text-align: center; vertical-align: middle;">Status</th>
									<th style="text-align: center; vertical-align: middle;">Diajukan</th>
									<th style="text-align: center; vertical-align: middle;">Action</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $data) {
								 ?>
								 <tr>
								 	<td><?=$no++?></td>
									<td><?=$data['nma_kec']?></td>
									<td><?=$data['nma_kel']?></td>
									<td><b><?=$data['nik_reg']?></b><br><?=$data['nma_reg']?></td>
									<td><?= '11.26.'.$data['kec_srt'].'.'.$data['kel_srt'].'.'.$data['ref_srt']?></td>
									<td><?=status($data['tracking_srt'],'statuskw')?></td>
									<td><?=fdate($data['tgldiajukan'],'HHDDMMYYYY')?></td>
								 	<td>
								 		<div class="btn-group">
											<?php
											if((in_array(139, $_SESSION['menu']) || $user['level_usr']==1)){
												?><button data-toggle="tooltip" title='detail' class="btn btn-sm btn-info" data-id="<?=$data['id_srt']?>" id="btn-doc" type="button"><span class="fa fa-search"></span></button><?php
											}
											if($data['tracking_srt']!=2){
													?><a data-toggle="tooltip" title="Ubah ke kw1" href='<?= base_url()?>/Apimobilepublic/deliverhak/<?=$data['id_srt']?>' class="btn btn-sm btn-warning" id="btn-edit" ><span class="fa fa-check-square-o"></span></a><?php
											}
											 ?>
											 <a data-toggle="tooltip" title="Berkas sudah ready" href='<?= base_url()?>/Apimobilepublic/confirmhak/<?=$data['id_srt']?>' class="btn btn-sm btn-primary" id="btn-view" ><span class="fa fa-check-square-o"></span></a>
											 <?php
											if((in_array(137, $_SESSION['menu']) || $user['level_usr']==1)){
												?><button data-toggle="tooltip" title='edit' class="btn btn-sm btn-info" data-id="<?=$data['id_srt']?>" id="btn-edit" type="button"><span class="fa fa-edit"></span></button><?php
											}


											?>
								 		</div>
								 	</td>
								 </tr>
								<?php } ?>
							</tbody>
						</table>
						<?php
						echo $link;
						 ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>


<script type="text/javascript">
	$(document).ready(function () {
		$('#filter_kecamatan').select2();
		$('#filter_kelurahan').select2();

		$('#cari_filter').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
			var nik = $('#nik').val();
			window.open('<?=base_url()?>Publics/validasisertipikat/?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan+'&nik='+nik,'_self',false);
		})

			$('#filter_kecamatan').on('change',function () {
				$.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>kelurahan/cekkelurahan',
            data: 'kecamatan='+$(this).val(),
            dataType: 'html',
            beforeSend: function() {
                $('#filter_kelurahan').html('Loading ....');
            },
            success: function(response) {
                $("#filter_kelurahan").html(response);
            }
        });
			});

			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>kelurahan/cekkelurahan',
					data: 'kecamatan='+$('#filter_kecamatan').val(),
					dataType: 'html',
					beforeSend: function() {
							$('#filter_kelurahan').html('Loading ....');
					},
					success: function(response) {
							$("#filter_kelurahan").html(response);
					}
			});

			$('#tabel-body').on('click','#btn-doc',function () {
				var s_filter = $(this).data('id');
				window.open('<?=base_url()?>Publics/detailpengecekan/'+s_filter,'_self',false);
			});

			$('#tabel-body').on('click','#btn-edit',function () {
				var s_filter = $(this).data('id');
				window.open('<?=base_url()?>Publics/editpermohonan/'+s_filter,'_self',false);
			});

			$('#btn-tambah').on('click',function () {
				window.open('<?=base_url()?>Publics/addpermohonan/','_self',false);
			});

	})
</script>
