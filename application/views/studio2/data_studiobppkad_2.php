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
									<div class='col-sm-4'>
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
									<th style="text-align: center; vertical-align: middle; width:10%" rowspan="2">Jumlah Block</th>
									<th style="text-align: center; vertical-align: middle;" colspan="2">Upload</th>
									<th style="text-align: center; vertical-align: middle;" colspan="2">Download</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Action</th>
								</tr>
								<tr>
										<th style="text-align: center; vertical-align: middle;width:10%">Peta Sismiop</th>
										<th style="text-align: center; vertical-align: middle;width:15%">Data DHKP</th>
										<th style="text-align: center; vertical-align: middle; width:15%">Data K4</th>
										<th style="text-align: center; vertical-align: middle; width:15%">Peta PTSL</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $data) {

										if(empty($data->petkerja_pt)){
											$button_kerja = '
														<div class="btn-group">
															<button data-toggle="tooltip" title="upload peta sismiop" class="btn btn-sm btn-danger btn-open-kerja" id="" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
														</div>
											';
										}else{
											$button_kerja = '
														<div class="btn-group">
															<a data-toggle="tooltip" title="download peta sismiop" href="'.base_url().'PETA/PETA_KERJA/'.$data->petkerja_pt.'" download="'.$data->petkerja_pt.'"><button class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
															</a>
															<button data-toggle="tooltip" title="upload peta sismiop" class="btn btn-sm btn-danger btn-open-kerja" id="" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
														</div>
											';
										}

										if(empty($data->datadhk_pt)){
											$button_dhk = '
															<button data-toggle="tooltip" title="upload data dhkp" class="btn btn-sm btn-danger btn-open-dhk" id="" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
											';
										}else{
											$button_dhk = '
															<a data-toggle="tooltip" title="download data dhkp" href="'.base_url().'filelibrary/dhkp/'.$data->datadhk_pt.'" download="'.$data->datadhk_pt.'"><button class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
															</a>
															<button data-toggle="tooltip" title="upload data dhkp" class="btn btn-sm btn-danger btn-open-dhk" id="" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
											';
										}

										if(empty($data->datak4_pt)){
											$button_k4 = '
															<button data-toggle="tooltip" title="upload data K4" class="btn btn-sm btn-danger btn-open-k4" id="" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
											';
										}else{
											$button_k4 = '
															<button data-toggle="tooltip" title="upload data K4" class="btn btn-sm btn-danger btn-open-k4" id="" data-id="'.$data->kd_full.'"><span class="fa  fa-cloud-upload"></span></button>
															<a data-toggle="tooltip" title="download data K4" href="'.base_url().'filelibrary/k4/'.$data->datak4_pt.'" download="'.$data->datak4_pt.'">
															<button class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
															</a>
											';
										}


										if(empty($data->petkkp_pt)){
											$button_ptsl = '';
										}else{
											$button_ptsl = '
														<div class="btn-group">
															<a data-toggle="tooltip" title="download peta ptsl" href="'.base_url().'PETA/PETA_KKP/'.$data->petkkp_pt.'" download="'.$data->petkkp_pt.'">
															<button class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
															</a>
														</div>
											';
										}

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
									<td><?=$data->nma_kec?></td>
									<td><?=$data->nma_kel?></td>
									<td style='text-align:center'><?= $data->jml_blk?></td>
										 <td><?php
 											if (in_array(91, $_SESSION['menu']) || $user['level_usr']==1) {
 												echo $button_kerja;
 											}
 										 ?></td>
										 <td>
											 <div class="btn-group"><?php
			 								if (in_array(89, $_SESSION['menu']) || $user['level_usr']==1) {
			 										echo $button_dhk;
			 								}
			 								if (in_array(93, $_SESSION['menu']) || $user['level_usr']==1) {
			 										?><a data-toggle="tooltip" title="import data DHKP" href='<?=base_url()?>studio2/import_ptsl/<?=$data->kd_full?>' alt='Import Data DHKP' type="button" id="btn-import-bpkad" class="btn btn-sm btn-info" style='float:right'><span class="fa fa-upload"></span></a><?php
			 								}
			 							 ?></div>
									  </td>
										<td>
											<div class="btn-group">
											<?php
											 if (in_array(88, $_SESSION['menu']) || $user['level_usr']==1) {
														echo $button_k4;
											 }
											 if (in_array(94, $_SESSION['menu']) || $user['level_usr']==1) {
													 ?><a data-toggle="tooltip" title="import data K4" href='<?=base_url()?>studio_1_1/import_studio' alt='Import Hak' type="button" id="btn-import-studio" class="btn btn-sm btn-info" style="float:right;"><span class="fa fa-upload"></span></a><?php
											 }
											?>
											</div>
										</td>
										<td>
											<div class="btn-group">
											<?php
											 if (in_array(111, $_SESSION['menu']) || $user['level_usr']==1) {
														echo $button_ptsl;
											 }
											?>
											</div>
										</td>
										<td style="text-align: center;">
									 		<div class="btn-group">
												<?php
												if((in_array(92, $_SESSION['menu']) || $user['level_usr']==1) && $data->petonline_pt){
													?><button data-toggle="tooltip" title='lihat map' class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" id="btn-map-online" type="button"><span class="fa fa-map"></span></button><?php
												}
												if((in_array(112, $_SESSION['menu']) || $user['level_usr']==1) && $data->petonline_pt){
													?><button data-toggle="tooltip" title='lihat data nib' class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" id="btn-nib" type="button"><span class="fa fa-info"></span></button><?php
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

<!-- modal kkp -->
<div id="modal-kkp" class="modal fade" role="dialog">
	<form id="form-kkp" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-kkp">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">

					<div class="form-group row">
						<label class="col-sm-3">File Upload</label>
						<div class="col-sm-9">
							<input type="file" class="form-control input-sm" name="petkkp_pt" id="petkkp_pt">
						</div>
					</div>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-kkp">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- modal dhkp -->
<div id="modal-dhkp" class="modal fade" role="dialog">
	<form id="form-dhkp" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-dhkp">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">

					<div class="form-group row">
						<label class="col-sm-3">File Upload</label>
						<div class="col-sm-9">
							<input type="file" class="form-control input-sm" name="dhkp" id="dhkp">
						</div>
					</div>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-dhkp">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- modal kerja -->
<div id="modal-kerja" class="modal fade" role="dialog">
	<form id="form-kerja" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-kerja">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">

					<div class="form-group row">
						<label class="col-sm-3">File Upload</label>
						<div class="col-sm-9">
							<input type="file" class="form-control input-sm" name="petkerja_pt" id="petkerja_pt">
						</div>
					</div>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-kerja">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- modal kerja -->
<div id="modal-k4" class="modal fade" role="dialog">
	<form id="form-k4" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-k4">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">

					<div class="form-group row">
						<label class="col-sm-3">File Upload</label>
						<div class="col-sm-9">
							<input type="file" class="form-control input-sm" name="datak4" id="datak4">
						</div>
					</div>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-k4">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">
	$(document).ready(function () {
		$('#filter_kecamatan').select2();
		$('#filter_kelurahan').select2();

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

		$('#cari_filter').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
			window.open('<?=base_url()?>studio2/bppkad?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan,'_self',false);
		})

		$('#tabel-body').on('click','#btn-cari-kelurahan',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio_2_1/index/?search='+s_filter,'_self',false);
		});

		$('#tabel-body').on('click','#btn-map-online',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio2/petaonline/'+s_filter,'_self',false);
		});

		$('#tabel-body').on('click','#btn-nib',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio2/datanib/'+s_filter,'_self',false);
		});

		$('.btn-open-kerja').click(function(){
			var idkel = $(this).data('id');
			$('#form-kerja').attr('action','<?=base_url()?>studio2/simpan_peta_kerja/' + idkel);
			$('#form-kerja')[0].reset();
			$('.mt-kerja').html('Upload Data Peta Kerja');
			$('#modal-kerja').modal('show');
		});

		$('.btn-open-k4').click(function(){
			var idkel = $(this).data('id');
			$('#form-k4').attr('action','<?=base_url()?>studio2/simpan_k4/' + idkel);
			$('#form-k4')[0].reset();
			$('.mt-k4').html('Upload Data K4');
			$('#modal-k4').modal('show');
		});

		$('.btn-open-kkp').click(function(){
			var idkel = $(this).data('id');
			$('#form-kkp').attr('action','<?=base_url()?>studio2/simpan_peta_kkp/' + idkel);
			$('#form-kkp')[0].reset();
			$('.mt-kkp').html('Upload Data Peta Geo KKP');
			$('#modal-kkp').modal('show');
		});

		// BUTTON dhkp
		$('.btn-open-dhk').click(function(){
			var idkel = $(this).data('id');
			$('#form-dhkp').attr('action','<?=base_url()?>studio2/simpan_dhkp/' + idkel);
			$('#form-dhkp')[0].reset();
			$('.mt-dhkp').html('Upload Data DHKP');
			$('#modal-dhkp').modal('show');
		});

		$('#btn-simpan-kerja').click(function() {
				$('#form-kerja').ajaxForm({
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

		$('#btn-simpan-k4').click(function() {
					$('#form-k4').ajaxForm({
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

		$('#btn-simpan-dhkp').click(function() {
					$('#form-dhkp').ajaxForm({
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

		$('#btn-simpan-kkp').click(function() {
				$('#form-kkp').ajaxForm({
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

		$('#btn-simpan-ptsl').click(function() {
				$('#form-ptsl').ajaxForm({
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

		$('.btn-open-ptsl').click(function () {
				var id = $(this).data('id');
				$('#modal-ptsl').modal('show');
				$('.mt-ptsl').html('Data Peta PTSL');
				$.ajax({
					url:'<?=base_url()?>studio2/cari_peta_ptsl/'+id,
					method:'post',
					async:false,
					dataType:'json',
					success:function(data){
						var html = '';
						var i;
						for(i=0;i<data.length;i++){
							html += '<tr>'+
										'<td>'+data[i].nama_blk+'</td>'+
										'<td>'+'<a href="'+'<?=base_url()?>'+'PETA/PETA_PTSL/'+data[i].petptsl_blk+'"'+' class="btn btn-sm btn-primary">'+'Download File</a>'
										'</td>'
									'</tr>';
						}
						$('#data_ptsl').html(html);
					}
				});
			})
	})
</script>
