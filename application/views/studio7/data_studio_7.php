<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
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
									<br>
									<button class="btn btn-sm btn-primary" id="cari_filter">Cari</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">No</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">Kecamatan</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">Kelurahan</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">Jumlah Block</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">DHKP</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">Sudah Sertipikat</th>
									<th style="text-align: center; vertical-align: middle;" colspan="6">Belum Sertipikat</th>
									<th style="text-align: center; vertical-align: middle;" width="20%" rowspan="3">Action</th>
								</tr>
								<tr>
									<th style="text-align: center;" rowspan='2'>Target</th>
									<th style="text-align: center;" rowspan='2'>Selesai</th>
									<th style="text-align: center;" rowspan='2'>Sisa</th>
									<th style="text-align: center;" colspan="3">PBT</th>
									<th style="text-align: center;" colspan="3">SHAT</th>
								</tr>
								<tr>
									<th style="text-align: center;">Target</th>
									<th style="text-align: center;">Selesai</th>
									<th style="text-align: center;">Sisa</th>
									<th style="text-align: center;">Target</th>
									<th style="text-align: center;">Selesai</th>
									<th style="text-align: center;">Sisa</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $data) {

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
									<td><?=$data->nma_kec?></td>
									<td><?=$data->nma_kel?></td>
									<td style="text-align: center;"><?=$data->jml_blk?></td>
									<td style="text-align: center;"><?=$data->jml_dhkp?></td>
									<td style="text-align: center;"><?=$data->jml_terdaftar?></td>
									<td style="text-align: center;"><?=$data->jml_sudahhak?></td>
									<td style="text-align: center;"><?=($data->jml_terdaftar-$data->jml_sudahhak)?></td>
									<td style="text-align: center;"><?=$data->target?></td>
									<td style="text-align: center;"><?=$data->jmlluas?></td>
									<td style="text-align: center;"><?=($data->target-$data->jmlluas)?></td>
									<td style="text-align: center;"><?=$data->targetshat?></td>
									<td style="text-align: center;"><?=$data->jmlyuridis?></td>
									<td style="text-align: center;"><?=($data->targetshat-$data->jmlyuridis)?></td>
								 	<td>
								 		<div class="btn-group">
								 			<button data-toggle="tooltip" title='lihat daftar blok' class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" id="btn-cari-kelurahan" type="button"><span class="fa fa-search"></span></button>
											<?php
											if((in_array(110, $_SESSION['menu']) || $user['level_usr']==1) && $data->jml_peta > 0){
												?><button data-toggle="tooltip" title='lihat map' class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" id="btn-map-online" type="button"><span class="fa fa-map"></span></button><?php
											}
											if (in_array(75, $_SESSION['menu']) || $user['level_usr']==1) {
								        ?><a data-toggle="tooltip" title='input target SHAT' href="<?= base_url('studio7/target?target=').$data->kd_full; ?>" class="btn btn-sm btn-info"><span class="fa fa-bullseye"></span></a><?php
								      }
											if (in_array(135, $_SESSION['menu']) || $user['level_usr']==1) {
												?><a data-toggle="tooltip" title='export data nominatif belum sertipikat' href="<?= base_url('studio_3_1/export').'?search='.$data->kd_full; ?>" class="btn btn-sm btn-info"><span class="fa fa-file"></span></a>
												<?php
											}
											if (in_array(136, $_SESSION['menu']) || $user['level_usr']==1) {
												?>
												<a data-toggle="tooltip" title="input panitia ptsl" data-id="<?=$data->kd_full?>" href='<?= base_url()?>studio_3_2/formpanitia/<?=$data->kd_full;?>'><button class="btn btn-sm btn-info" id="btn-panitia"><span class="fa fa-info"></span></button></a>
												<?php
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

		$('#tabel-body').on('click','#btn-cari-kelurahan',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio_7_1/index/?search='+s_filter,'_self',false);
		});

		$('#cari_filter').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
			window.open('<?=base_url()?>studio7/?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan,'_self',false);
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

			$('#tabel-body').on('click','#btn-map-online',function () {
				var s_filter = $(this).data('id');
				window.open('<?=base_url()?>studio7/petaonline/'+s_filter,'_self',false);
			});
	})
</script>
