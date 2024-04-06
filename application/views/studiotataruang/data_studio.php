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
									<th style="text-align: center; vertical-align: middle;" colspan="2">Bidang Tanah</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Jumlah Block</th>
									<th style="text-align: center; vertical-align: middle;" colspan="2">Peta Tata Ruang</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Daftar Bidang</th>
								</tr>
								<tr>
									<th style="text-align: center;">Sudah Sertipikat (K4)</th>
									<th style="text-align: center;">DHKP</th>
									<th style="text-align: center;">Fisik</th>
									<th style="text-align: center;">Peta Online</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $data) {
										if(empty($data->pettr_pt)){
											$button_tataruang = '
														<div class="btn-group">
															<button data-toggle="tooltip" title="upload peta tataruang" class="btn btn-sm btn-danger btn-open-tataruang" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
														</div>
											';
										}else{
											$button_tataruang = '
														<div class="btn-group">
															<a data-toggle="tooltip" title="download peta tataruang" href="'.base_url().'PETA/PETA_TATARUANG/'.$data->pettr_pt.'" download="'.$data->pettr_pt.'"><button class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
															</a>
															<button data-toggle="tooltip" title="upload peta tataruang" class="btn btn-sm btn-danger btn-open-tataruang" id="" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
														</div>
											';
										}

										if(empty($data->pettronline_pt)){
											$button_tataruangonline = '
														<div class="btn-group">
															<button data-toggle="tooltip" title="upload peta online" class="btn btn-sm btn-danger btn-open-online" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
														</div>
											';
										}else{
											$button_tataruangonline = '
														<div class="btn-group">
															<a data-toggle="tooltip" title="download peta online" href="'.base_url().'PETA/PETA_TATARUANGONLINE/'.$data->pettronline_pt.'" download="'.$data->pettronline_pt.'"><button class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
															</a>
															<button data-toggle="tooltip" title="upload peta online" class="btn btn-sm btn-danger btn-open-online" id="" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
														</div>
											';
										}
								 ?>
								 <tr>
								 	<td><?=$no++?></td>
									<td><?=$data->nma_kec?></td>
									<td><?=$data->nma_kel?></td>
								 	<td style="text-align: center;"><?=$data->jml_terdaftar?></td>
								 	<td style="text-align: center;"><?=$data->jml_dhkp?></td>
								 	<td style="text-align: center;"><?=$data->jml_blk?></td>
									<td style="text-align: center;"><?php
											if (in_array(58, $_SESSION['menu']) || $user['level_usr']==1) {
												echo $button_tataruang;
											}
									 ?></td>
									 <td style="text-align: center;"><?php
 											if (in_array(95, $_SESSION['menu']) || $user['level_usr']==1) {
 												echo $button_tataruangonline;
 											}
											if (in_array(96, $_SESSION['menu']) || $user['level_usr']==1) {
 												?><button data-toggle="tooltip" title="lihat map" class="btn btn-sm btn-warning" data-id="<?=$data->kd_full?>" id="btn-map-kelurahan" type="button"><span class="fa fa-map"></span></button><?php
 											}

 									 ?></td>
								 	<td>
								 		<div class="btn-group">
								 			<button data-toggle="tooltip" title='lihat bidang' class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" id="btn-cari-kelurahan" type="button"><span class="fa fa-search"></span></button>
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

<!-- modal kkp -->
<div id="modal-tataruang" class="modal fade" role="dialog">
	<form id="form-tataruang" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-tataruang">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">

					<div class="form-group row">
						<label class="col-sm-3">File Upload</label>
						<div class="col-sm-9">
							<input type="file" class="form-control input-sm" name="pettataruang_pt" id="pettataruang_pt">
						</div>
					</div>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-tataruang">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#filter_kecamatan').select2();
		$('#filter_kelurahan').select2();

		$('#tabel-body').on('click','#btn-cari-kelurahan',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studiotataruang_1/index/?search='+s_filter,'_self',false);
		});

		$('#tabel-body').on('click','#btn-map-kelurahan',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studiotataruang/petaonline/?search='+s_filter,'_self',false);
		});

		$('#cari_filter').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
			window.open('<?=base_url()?>studiotataruang/?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan,'_self',false);
		})

		$('.btn-open-tataruang').click(function(){
			var idkel = $(this).data('id');
			$('#form-tataruang').attr('action','<?=base_url()?>studio2/simpan_peta_tataruang/' + idkel);
			$('#form-tataruang')[0].reset();
			$('.mt-tataruang').html('Upload Data Peta Tata Ruang');
			$('#modal-tataruang').modal('show');
		});

		$('.btn-open-online').click(function(){
			var idkel = $(this).data('id');
			$('#form-tataruang').attr('action','<?=base_url()?>studio2/simpan_peta_tataruangonline/' + idkel);
			$('#form-tataruang')[0].reset();
			$('.mt-tataruang').html('Upload Data Peta Online');
			$('#modal-tataruang').modal('show');
		});

		$('#btn-simpan-tataruang').click(function() {
				$('#form-tataruang').ajaxForm({
					success: 	function(response){
						if(response=='true'){
							swal("Success!", "Response Berhasil", "success");
							location. reload(true);
						}else{
							swal("Error!", "Response Gagal", "error");
						}
					},
					error: function(){
						swal("Error!", "Response Gagal", "error");
					}
				}).submit();
			});

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
