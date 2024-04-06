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
									<th style="text-align: center; vertical-align: middle;" rowspan="2">No</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Kecamatan</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Kelurahan</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Peta Analog</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Jumlah Block</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">DHKP</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">Sudah Sertipikat</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">Belum Sertipikat</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Action</th>
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

										if ($data->jml_analog==0) {
									 		$button_analog = '';
									 	}else{
											$button_analog = '<a class="open-berkas" data-id="'.$data->kd_full.'" data-jenis="ukur">
													<button data-toggle="tooltip" title="download peta analog" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
												</a>';
									 	}
								 ?>
								 <tr>
								 	<td><?=$no++?></td>
									<td><?=$data->nma_kec?></td>
									<td><?=$data->nma_kel?></td>
									<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(121, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_analog?><?php
													}
												 ?>

								 		</div>
								 	</td>
									<td style="text-align: center;"><?=$data->jml_blk?></td>
									<td style="text-align: center;"><?=$data->jml_dhkp?></td>
									<td style="text-align: center;"><?=$data->jml_terdaftar?></td>
									<td style="text-align: center;"><?=$data->jml_sudahhak?></td>
									<td style="text-align: center;"><?=($data->jml_terdaftar-$data->jml_sudahhak)?></td>
									<td style="text-align: center;"><?=$data->target?></td>
									<td style="text-align: center;"><?=$data->jmlluas?></td>
									<td style="text-align: center;"><?=($data->target-$data->jmlluas)?></td>
								 	<td>
								 		<div class="btn-group">
											<?php
											if((in_array(109, $_SESSION['menu']) || $user['level_usr']==1) && $data->jml_peta > 0){
												?><button data-toggle="tooltip" title='lihat map' class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" id="btn-map-online" type="button"><span class="fa fa-map"></span></button><?php
											}
											?>
								 			<button data-toggle="tooltip" title='lihat daftar blok' class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" id="btn-cari-kelurahan" type="button"><span class="fa fa-search"></span></button>
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
			window.open('<?=base_url()?>studio_6_1/index/?search='+s_filter,'_self',false);
		});

		$('#cari_filter').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
			window.open('<?=base_url()?>studio6/?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan,'_self',false);
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
				window.open('<?=base_url()?>studio6/petaonline/'+s_filter,'_self',false);
			});

	})
</script>
