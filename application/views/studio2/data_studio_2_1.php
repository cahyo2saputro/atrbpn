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
						<div class="pull-right">
							<?php
									if (in_array(17, $_SESSION['menu']) || $user['level_usr']==1) {
										?><button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></button><?php
									}
								 ?>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">No</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Block</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">Bidang Tanah</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Peta Block</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Peta PTSL</th>
									<th style="text-align: center; vertical-align: middle;" width="12%" rowspan="2">Action</th>
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
									foreach ($block as $blk) {

										$url_peta_block = base_url().'PETA/PETA_BLOCK/'.$blk->petblk_blk;
										$disabled = "";

								 	if (empty($blk->petblk_blk)) {
								 		$button_block = '<button data-toggle="tooltip" title="upload peta blok" class="btn btn-sm btn-danger btn-open-block" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-upload"></span></button>';
								 	}else{
										if ($user['level_usr']==1) {
											$button_block = '<a href="'.$url_peta_block.'">
													<button data-toggle="tooltip" title="download peta blok" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
													</a>
													<button data-toggle="tooltip" title="upload peta blok" '.$disabled.' class="btn btn-sm btn-danger btn-open-block" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-upload"></span></button>';
										}else{
											$button_block = '<a href="'.$url_peta_block.'">
													<button data-toggle="tooltip" title="download peta blok" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
													</a>';
										}

								 	}

								 	if ($blk->jml_ptsl==0) {
								 		$button_ptsl = '<button data-toggle="tooltip" title="upload peta ptsl" class="btn btn-sm btn-danger btn-open-ptsl" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-upload"></span></button>';
								 	}else{
								 		$button_ptsl = '
											<button '.$disabled.' data-toggle="tooltip" title="upload peta ptsl" class="btn btn-sm btn-warning btn-open-ptsl" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-download"></span></button>';
								 	}
								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td><?=$blk->nama_blk?></td>
								 	<td style="text-align: center;"><?=$blk->jml_terdaftar?></td>
								 	<td style="text-align: center;"><?=$blk->jml_tidak?></td>
								 	<td style="text-align: center;"><?=$total = $blk->jml_terdaftar+$blk->jml_tidak?></td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(20, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_block?><?php
													}
												 ?>
								 		</div>
								 	</td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(21, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_ptsl?><?php
													}
												 ?>
								 		</div>
								 	</td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(17, $_SESSION['menu']) || $user['level_usr']==1) {
														?><button data-toggle="tooltip" title="edit data" class="btn btn-sm btn-warning" id="btn-edit" data-id="<?=$blk->idblk_blk?>"><span class="fa fa-edit"></span></button><?php
													}
													if (in_array(19, $_SESSION['menu']) || $user['level_usr']==1) {
														?><button data-toggle="tooltip" title="hapus data" class="btn btn-sm btn-danger" id="btn-hapus" data-id="<?=$blk->idblk_blk?>" data-nama="<?=$blk->nama_blk?>"><span class="fa fa-trash"></span></button><?php
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
<div id="modal-gu" class="modal fade" role="dialog">
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
					<div class="form-group row" id='beforethis'>
						<div class='col-sm-8'>
							<a id='plus' class='btn btn-warning'>+</a>
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
<script type="text/javascript">

	function removechild(id){
		$('#obj'+id).remove();
	}

	function hapus(id,jenis){
		cek = confirm('Apakah kamu yakin mau menghapus berkas ?');
		if(cek==true){
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/hapus_berkas/'+jenis,
					data: 'id='+id,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$('#exs'+id).remove();
					}
			});
		}else{
			return false;
		}

	}

	function updatedesc(id,jenis){
		$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/update_berkas/'+jenis,
				data: 'id='+id+'&desc='+$('#desc'+id).val(),
				dataType: 'html',
				beforeSend: function() {
				},
				success: function(response) {
						alert("deskripsi berkas diupdate !");
				}
		});
	}

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
			window.open('<?=base_url()?>Studio_2_2/data/?search='+id,'_self',false);
		})

		$('#plus').on('click',function () {
			var id = $('#sumfile').val();
			id = parseInt(id)+1;
			var name = $('#nameform').val();
			var desc = $('#descform').val();
			var text = "<div id='obj"+id+"' class='form-group row'><label class='col-sm-3'>File Upload</label><div class='col-sm-3'><input type='file' class='form-control input-sm' name='"+name+"'></div><div class='col-sm-3'><input type='text' placeholder='deskripsi' class='form-control input-sm' name='"+desc+"'></div><div class='col-sm-3'><a class='btn btn-danger' onclick='removechild("+id+")'>-</a></div></div><"
			$(text).insertBefore($('#beforethis'));
			$('#sumfile').val(id);
		});

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

		$('.btn-open-ptsl').click(function(){
			var idblk = $(this).data('id');
			$('#form-ptsl').attr('action','<?=base_url()?>Studio_2_1/simpan_peta_ptsl/'+idblk);
			$('#form-ptsl')[0].reset();
			$('.mt-ptsl').html('Upload Data Peta PTSL');
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cari_berkas/ptsl',
					data: 'idblk='+idblk,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$(".dataku").html(response);
					}
			});
			$('#modal-gu').modal('show');
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
