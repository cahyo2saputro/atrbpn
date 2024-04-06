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
									<th style="text-align: center; vertical-align: middle;" rowspan='2'>Download Peta Blok</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Download Peta PTSL</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">DHKP</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">Bidang Tanah Terentri</th>
									<th style="text-align: center; vertical-align: middle;" width="20%" rowspan="2">Action</th>
								</tr>
								<tr>
									<th style="text-align: center;">Sudah Sertipikat</th>
									<th style="text-align: center;">Belum Sertipikat</th>
									<th style="text-align: center;">Total</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									$sudah=0;
									$belum=0;
									$dhkp=0;
									foreach ($block as $blk) {

										$url_peta_block = base_url().'PETA/PETA_BLOCK/'.$blk->petblk_blk;
										$disabled = "";

								 	if (empty($blk->petblk_blk)) {
								 		$button_block = '<button class="btn btn-sm btn-danger btn-open-block" data-toggle="tooltip" title="upload peta blok" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-upload"></span></button>';
								 	}else{
								 		$button_block = '<a href="'.$url_peta_block.'">
												<button data-toggle="tooltip" title="download peta blok" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
												</a>';
								 	}

									if ($blk->jml_ptsl==0) {
										$button_ptsl = '';
									}else{
										$button_ptsl = '<a class="open-berkas" data-id="'.$blk->idblk_blk.'" data-jenis="ptsl">
												<button data-toggle="tooltip" title="download peta ptsl" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
											</a>';
									}

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td>Blok <?=$blk->nama_blk?></td>
									<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(129, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_block?><?php
													}
												 ?>
								 		</div>
								 	</td>
									<td style="text-align: center;">
										<div class="btn-group">
											<?php
													if (in_array(129, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_ptsl?><?php
													}
												 ?>
								 		</div>
								 	</td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
												echo $blk->jml_dhkp;$dhkp+=$blk->jml_dhkp;
													// if (in_array(21, $_SESSION['menu']) || $user['level_usr']==1) {
													// 	?><?php //$button_ptsl?><?php
													// }
												 ?>

								 		</div>
								 	</td>
								 	<td style="text-align: center;"><?=$blk->jml_terdaftar?><?php $sudah+=$blk->jml_terdaftar;?></td>
								 	<td style="text-align: center;"><?=$blk->jml_tidak?><?php $belum+=$blk->jml_tidak;?></td>
								 	<td style="text-align: center;"><?=$total = $blk->jml_terdaftar+$blk->jml_tidak?></td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
									 			<button <?=$disabled?> data-toggle="tooltip" title="lihat data e-desa" class="btn btn-sm btn-default" id="btn-view" data-id="<?=$blk->idblk_blk?>"><span class="fa fa-clipboard"></span></button>
												<?php
												if (in_array(31, $_SESSION['menu']) || $user['level_usr']==1) {
													?>
													<a data-toggle="tooltip" title="input saksi ptsl" href='<?= base_url()?>studio_3_2/formsaksi/<?=$blk->idblk_blk;?>'><button class="btn btn-sm btn-default" id="btn-saksi"><span class="fa fa-address-book-o"></span></button></a>
													<a data-toggle="tooltip" title="input puldatan" href='<?= base_url()?>studio_3_2/formpuldatan/<?=$blk->idblk_blk;?>'><button class="btn btn-sm btn-primary" id="btn-puldatan"><span class="fa fa-address-book-o"></span></button></a>
													<?php
												}
												if (in_array(23, $_SESSION['menu']) || $user['level_usr']==1) {
									        		?><a data-toggle="tooltip" title='export data nominatif belum sertipikat' href="<?= base_url('studio_3_1/export').'?search='.$blk->idkel_blk.'&blok='.$blk->idblk_blk; ?>" class="btn btn-sm btn-info"><span class="fa fa-file"></span></a>
													<a data-toggle="tooltip" title='export data nominatif hak link nop' href="<?= base_url('studio_3_1/exportnub').'?search='.$blk->idkel_blk.'&blok='.$blk->idblk_blk; ?>" class="btn btn-sm btn-warning"><span class="fa fa-file"></span></a><?php
									      		}
												?>
												<?php
												if((in_array(104, $_SESSION['menu']) || $user['level_usr']==1) && $blk->petonline_blk){
													?><button data-toggle="tooltip" title='lihat map' class="btn btn-sm btn-danger" data-id="<?=$blk->idblk_blk?>" id="btn-map-online" type="button"><span class="fa fa-map"></span></button><?php
												}
												 ?>

										</div>
								 	</td>
								 </tr>
								<?php } ?>
								<tr>
									<th style='text-align:center' colspan='4'>Total</th>
									<th style='text-align:center'><?=$dhkp;?></th>
									<th style='text-align:center'><?=$sudah;?></th>
									<th style='text-align:center'><?=$belum;?></th>
									<th style='text-align:center'><?=$sudah+$belum;?></th>
									<th></th>
								</tr>
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

					<div class="form-group row">
						<label class="col-sm-3">File Upload</label>
						<div class="col-sm-9">
							<input type="file" class="form-control input-sm" name="petptsl_blk" id="petptsl_blk">
						</div>
					</div>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-ptsl">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- modal block -->
<div id="modal-block" class="modal fade" role="dialog">
	<form id="form-block" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-block">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">

					<div class="form-group row">
						<label class="col-sm-3">File Upload</label>
						<div class="col-sm-9">
							<input type="file" class="form-control input-sm" name="petblk_blk" id="petblk_blk">
						</div>
					</div>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-block">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- modal ptsl -->
<div id="modal-downloadptsl" class="modal fade" role="dialog">
	<form id="form-ptsl" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-downloadptsl">Bagian heading modal</h5>
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
		$('#btn-tambah').on('click',function () {
			window.open('<?=base_url()?>Studio_2_1/form?idkel='+<?=$url_set?>,'_self',false);
		})

		$('#btn-cari').on('click',function () {
			var nama = $('#nama_blk').val();
			window.open('<?=base_url()?>Studio_2_1/index/?search='+nama,'_self',false);
		})

		$('#tabel-body').on('click','#btn-edit',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>Studio_2_1/form/'+id,'_self',false);
		})

		$("#tabel-body").on('click','#btn-view',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>studio_3_2/data/?search='+id,'_self',false);
		})

		$('#tabel-body').on('click','#btn-hapus',function () {
				var kode 	= $(this).data('id');
				var nama 	= $(this).data('nama');
				swal({
					title: "Apakah anda yakin?",
					text: "Untuk menghapus data : " + nama,
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						$.ajax({
							type: 		'ajax',
							method: 	'post',
							url: 		'<?=base_url()?>'+'studio_2_1/hapus/' + kode,
							async: 		true,
							dataType: 	'json',
							success: 	function(response){
								if(response==true){
									/*tabel_studio.ajax.reload(null,false);*/
									location. reload(true);
									swal("Hapus Data Berhasil !", {
									  icon: "success",
									});
								}else{
									swal("Hapus Data Gagal !", {
									  icon: "warning",
									});
								}
							},
							error: function(){
								swal("ERROR", "Hapus Data Gagal.", "error");
							}
						});
					} else {
						swal("Cancelled", "Hapus Data Dibatalkan.", "error");
					}
				});
		});

		$('#tabel-body').on('click','#btn-map-online',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio_3_1/petaonline/'+s_filter,'_self',false);
		});

		$('.open-berkas').click(function(){
			var idblk = $(this).data('id');
			var jenis = $(this).data('jenis');
			$('.mt-downloadptsl').html('Download Peta PTSL');
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
			$('#modal-downloadptsl').modal('show');
		});

		$('.btn-open-ptsl').click(function(){
			var idblk = $(this).data('id');
			$('#form-ptsl').attr('action','<?=base_url()?>Studio_2_1/simpan_peta_ptsl/'+idblk);
			$('#form-ptsl')[0].reset();
			$('.mt-ptsl').html('Upload Data Peta PTSL');
			$('#modal-ptsl').modal('show');
		});

		$('.btn-open-block').click(function(){
			var idblk = $(this).data('id');
			$('#form-block').attr('action','<?=base_url()?>Studio_2_1/simpan_peta_block/'+idblk);
			$('#form-block')[0].reset();
			$('.mt-block').html('Upload Data Peta Block');
			$('#modal-block').modal('show');
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

		$('#btn-simpan-block').click(function() {
				$('#form-block').ajaxForm({
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
	})
</script>
