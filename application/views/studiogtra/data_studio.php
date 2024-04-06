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
									<th style="text-align: center; vertical-align: middle;">No</th>
									<th style="text-align: center; vertical-align: middle;">Desa/Kelurahan</th>
									<th style="text-align: center; vertical-align: middle;">Kecamatan</th>
									<th style="text-align: center; vertical-align: middle;">Dusun</th>
									<th style="text-align: center; vertical-align: middle;">Obyek Redist</th>
									<th style="text-align: center; vertical-align: middle;">Subyek Redist</th>
									<th style="text-align: center; vertical-align: middle;">KK Subyek Redist</th>
									<th style="text-align: center; vertical-align: middle;">Lain-lain</th>
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
									<td><?=$data->nma_kel?></td>
									<td><?=$data->nma_kec?></td>
									<td style="text-align: center;"><?=$data->jml_dsn?></td>
									<td style="text-align: center;"><?=$data->jml_oby?></td>
									<td style="text-align: center;"><?=$data->jml_sby?></td>
									<td style="text-align: center;"><?=$data->jml_kksby?></td>
									<td style="text-align: center;"><?=$data->jml_lain?></td>
								 	<td>
								 		<div class="btn-group">
								 			<button title='lihat daftar blok' class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" data-toggle="tooltip" id="btn-cari-kelurahan" type="button"><span class="fa fa-search"></span></button>

											<?php
											if (in_array(22, $_SESSION['menu']) || $user['level_usr']==1) {
								        ?><a data-toggle="tooltip" title='input target' href="<?= base_url('egtra/target?target=').$data->kd_full; ?>" class="btn btn-sm btn-info"><span class="fa fa-bullseye"></span></a><?php
								      }
											if (in_array(132, $_SESSION['menu']) || $user['level_usr']==1) {
								        ?><a data-toggle="tooltip" title='input kepala desa dan sekretaris' href="<?= base_url('egtra/form_desa/').$data->kd_full; ?>" class="btn btn-sm btn-info"><span class="fa fa-file-o"></span></a><?php
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

<!-- modal GU -->
<div id="modal-analog" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-analog">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">
					<div class='dataku'>

					</div>
				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#filter_kecamatan').select2();
		$('#filter_kelurahan').select2();

		$('.open-berkas').click(function(){
			var idkel = $(this).data('id');
			$('.mt-analog').html('Download Peta Analog');
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/download_berkas/analog',
					data: 'idblk='+idkel,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$(".dataku").html(response);
					}
			});
			$('#modal-analog').modal('show');
		});

		$('#tabel-body').on('click','#btn-cari-kelurahan',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>egtra/list/?search='+s_filter,'_self',false);
		});

		$('#cari_filter').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
			window.open('<?=base_url()?>egtra/?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan,'_self',false);
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

	})
</script>
