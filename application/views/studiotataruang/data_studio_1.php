<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$url_set = $this->input->get('search');
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
									<input type="text" class="form-control input-sm" name="nama_blk" id="nama_blk" placeholder="Cari Nama Block">
								</div>
								<div class="form-group">
									<button class="btn btn-sm btn-primary" id="btn-cari">Cari <span class="fa fa-search"></span></button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">No</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Block</th>
									<th style="text-align: center; vertical-align: middle;" colspan="2">Bidang Tanah</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Peta Block</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Peta PTSL</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Peta RT/RW</th>
									<th style="text-align: center; vertical-align: middle;" width="12%" rowspan="2">Action</th>
								</tr>
								<tr>
									<th style="text-align: center;">Sudah Terdaftar</th>
									<th style="text-align: center;">Belum Terdaftar</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($block as $blk) {

										$url_peta_block = base_url().'PETA/PETA_BLOCK/'.$blk->petblk_blk;
										$url_peta_tr = base_url().'PETA/PETA_TATARUANGBLOCK/'.$blk->pettr_blk;
										$disabled = "";

								 	if (empty($blk->petblk_blk)) {
								 		$button_block = '<button data-toggle="tooltip" title="upload peta blok" class="btn btn-sm btn-danger btn-open-block" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-upload"></span></button>';
								 	}else{
								 		$button_block = '<a href="'.$url_peta_block.'">
												<button data-toggle="tooltip" title="download peta blok" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
												</a>';
								 	}

								 	if ($blk->jml_ptsl==0) {
								 		$button_ptsl = '';
								 	}else{
								 		$button_ptsl = '
											<button data-toggle="tooltip" title="upload peta ptsl" '.$disabled.' class="btn btn-sm btn-warning btn-open-ptsl" id="" data-id="'.$blk->idblk_blk.'" data-jenis="ptsl"><span class="fa  fa-cloud-download"></span></button>';
								 	}

									if (empty($blk->pettr_blk)) {
								 		$button_tr = '<button data-toggle="tooltip" title="upload peta RT/RW" class="btn btn-sm btn-danger btn-open-tr" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-upload"></span></button>';
								 	}else{
								 		$button_tr = '<a href="'.$url_peta_tr.'">
												<button data-toggle="tooltip" title="download peta RT/RW" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
											</a>
											<button data-toggle="tooltip" title="upload peta RT/RW" '.$disabled.' class="btn btn-sm btn-danger btn-open-tr" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-upload"></span></button>';
								 	}
								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td><?=$blk->nama_blk?></td>
								 	<td style="text-align: center;"><?=$blk->jml_terdaftar?></td>
								 	<td style="text-align: center;"><?=$blk->jml_tidak?></td>
									<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(98, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_block?><?php
													}
												 ?>
								 		</div>
								 	</td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(97, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_ptsl?><?php
													}
												 ?>

								 		</div>
								 	</td>
									<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(60, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_tr?><?php
													}
												 ?>
								 		</div>
								 	</td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
									 			<button data-toggle="tooltip" title="lihat data" <?=$disabled?> class="btn btn-sm btn-default" id="btn-view" data-id="<?=$blk->idblk_blk?>"><span class="fa fa-file"></span></button>
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

<!-- modal tata ruang -->
<div id="modal-tr" class="modal fade" role="dialog">
	<form id="form-tr" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-tr">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">

					<div class="form-group row">
						<label class="col-sm-3">File Upload</label>
						<div class="col-sm-9">
							<input type="file" class="form-control input-sm" name="pettr_blk" id="pettr_blk">
						</div>
					</div>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-tr">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- modal ptsl -->
<div id="modal-ptsl" class="modal fade" role="dialog">
	<form id="form-ptsl" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-ptsl">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">
					<div class='dataku'>

					</div>
				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">close</button>
				</div>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">
	$(document).ready(function () {
		$('#btn-cari').on('click',function () {
			var nama = $('#nama_blk').val();
			window.open('<?=base_url()?>Studiotataruang_1/index/?search='+nama,'_self',false);
		})

		$("#tabel-body").on('click','#btn-view',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>studiotataruang_2/index/?search='+id,'_self',false);
		})

		$('.btn-open-tr').click(function(){
			var idblk = $(this).data('id');
			$('#form-tr').attr('action','<?=base_url()?>Studiotataruang_1/simpan_peta_tataruang/'+idblk);
			$('#form-tr')[0].reset();
			$('.mt-tr').html('Upload Data Peta RT RW');
			$('#modal-tr').modal('show');
		});

		$('#btn-simpan-tr').click(function() {
				$('#form-tr').ajaxForm({
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

			$('.btn-open-ptsl').click(function(){
				var idblk = $(this).data('id');
				var jenis = $(this).data('jenis');
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>ajax/download_berkas/'+jenis,
						data: 'idblk='+idblk,
						dataType: 'html',
						beforeSend: function() {
						},
						success: function(response) {
								$(".dataku").html(response);
						}
				});
				$('.mt-ptsl').html('Download Data Peta PTSL');
				$('#modal-ptsl').modal('show');
			});

	})
</script>
