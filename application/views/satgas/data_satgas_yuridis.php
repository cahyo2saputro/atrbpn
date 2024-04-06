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
						<div class="pull-right">
							<?php 
								if ($user['level_usr'] == '2') {
							 ?>
								<button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 5px;">Tambah<span class="fa fa-plus-square-o"></span></button>
							<?php }else if($user['level_usr'] == '1'){ ?>
								<button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 5px;">Tambah<span class="fa fa-plus-square-o"></span></button>
							<?php } ?>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-satgas_administrator" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle; width: 5%" rowspan="2">NO</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">SATGAS ADMINISTRATOR</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">ENTRY SURAT UKUR</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">ENTRY BUKU TANAH</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">DIGITALISASI GAMBAR UKUR</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">DIGITALISASI SURAT UKUR</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">DIGITALISASI BUKU TANAH</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">ACTION</th>
								</tr>
								<tr>
									<th style="width: 20%; text-align: center;">TARGET</th>
									<th style="width: 20%; text-align: center;">REALISASI</th>
									<th style="width: 20%; text-align: center;">PROGRES (%)</th>
									<th style="width: 20%; text-align: center;">TARGET</th>
									<th style="width: 20%; text-align: center;">REALISASI</th>
									<th style="width: 20%; text-align: center;">PROGRES (%)</th>
									<th style="width: 20%; text-align: center;">TARGET</th>
									<th style="width: 20%; text-align: center;">REALISASI</th>
									<th style="width: 20%; text-align: center;">PROGRES (%)</th>
									<th style="width: 20%; text-align: center;">TARGET</th>
									<th style="width: 20%; text-align: center;">REALISASI</th>
									<th style="width: 20%; text-align: center;">PROGRES (%)</th>
									<th style="width: 20%; text-align: center;">TARGET</th>
									<th style="width: 20%; text-align: center;">REALISASI</th>
									<th style="width: 20%; text-align: center;">PROGRES (%)</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php 
									$no = 0;
									foreach ($satgas as $data) {$no++;
								 ?>
								 <tr>
								 	<td><?=$no?></td>
								 	<td><?=$data->name_usr?></td>
								 	<td><?=$data->tarsu_st?></td>
								 	<td></td>
								 	<td></td>
								 	<td><?=$data->tarbt_st?></td>
								 	<td></td>
								 	<td></td>
								 	<td><?=$data->tadgu_st?></td>
								 	<td></td>
								 	<td></td>
								 	<td><?=$data->tadsu_st?></td>
								 	<td></td>
								 	<td></td>
								 	<td><?=$data->tadbt_st?></td>
								 	<td></td>
								 	<td></td>
								 	<td>
								 		<div class="btn-group">
								 			<button id="btn-edit" class="btn btn-sm btn-warning" data-id="<?=$data->id_st?>"><span class="fa fa-edit"></span></button>
								 			<button id="btn-hapus" class="btn btn-sm btn-danger" data-id="<?=$data->id_st?>" data-nama="<?=$data->name_usr?>"><span class="fa fa-trash-o"></span></button>
								 		</div>
								 	</td>
								 </tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div id="modal-tambah" class="modal fade" role="dialog">
	<form id="form-tambah" method="POST" action="">
		<div class="modal-dialog">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">

					<input type="text" hidden name="id" id="id">

					<div class="form-group row">
						<label class="col-sm-3">Petugas</label>
						<div class="col-sm-9">
							<select class="form-control input-sm" id="idusr_usr" name="idusr_usr" style="width:100%">
								<option>Pilih Petugas</option>
								<?php 
									foreach ($petugas as $petugas) {
										echo '<option value="'.$petugas->idusr_usr.'">'.$petugas->name_usr.'</option>';
									}
								?>
							</select>	
						</div>
							
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Entry surat ukur</label>
						<div class="col-sm-9">
							<input type="text" name="target_e_su" id="target_e_su" class="form-control input-sm" placeholder="Taget Surat Ukur" required>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Entry buku tanah</label>
						<div class="col-sm-9">
							<input type="text" name="target_e_bt" id="target_e_bt" class="form-control input-sm" placeholder="Taget Buku Tanah" required>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Digitalisasi gambar ukur</label>
						<div class="col-sm-9">
							<input type="text" name="target_d_gu" id="target_d_gu" class="form-control input-sm" placeholder="Taget Gambar Ukur" required>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Digitalisasi surat ukur</label>
						<div class="col-sm-9">
							<input type="text" name="target_d_su" id="target_d_su" class="form-control input-sm" placeholder="Taget Surat Ukur" required>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Digitalisasi buku tanah</label>
						<div class="col-sm-9">
							<input type="text" name="target_d_bt" id="target_d_bt" class="form-control input-sm" placeholder="Taget Buku Tanah" required>
						</div>
					</div>
				
				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('#data-satgas_administrator').DataTable();
		$('#idusr_usr').select2();

		$('#btn-tambah').click(function(){
			$('#form-tambah').attr('action','<?=base_url()?>satgas_administrator/tambah');
			$('#form-tambah')[0].reset();
			$('.modal-title').html('Tambah Data');
			$('#modal-tambah').modal('show');
		});

		$('#btn-simpan').click(function() {
			$('#form-tambah').ajaxForm({
				success: 	function(response){
					if(response=='true'){
						location. reload(true);
						swal("Good job!", $('.modal-title').html() + " Sukses", "success");
						$('#form-tambah')[0].reset();
						$('#modal-tambah').modal('hide');
					}else{
						swal("Error!", $('.modal-title').html() + " Gagal", "error");
					}
				},
				error: function(){
					swal("Error!", "Response Gagal", "error");
				}
			}).submit();
		});


		$('#tabel-body').on('click', '#btn-edit', function(){
				$('.modal-title').html('Ubah Data');
				$('#form-tambah').attr('action','<?=base_url()?>satgas_administrator/edit');
				$.ajax({
					type: 		'ajax',
					method: 	'get',
					url: 		'<?=base_url()?>'+'satgas_administrator/show_edit?id=' + $(this).data('id'),
					async: 		true,
					dataType: 	'json',
					success: 	function(response){
						$('#id').val(response[0].id_st);
						var petugas = response[0].idusr_usr;
						$('#idusr_usr').val(petugas).trigger('change');
						$('#target_e_su').val(response[0].tarsu_st);
						$('#target_e_bt').val(response[0].tarbt_st);
						$('#target_d_bt').val(response[0].tadbt_st);
						$('#target_d_su').val(response[0].tadsu_st);
						$('#target_d_gu').val(response[0].tadgu_st);
					},
					error: function(){
						swal("Error");
					}
				});
				$('#modal-tambah').modal('show');
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
							url: 		'<?=base_url()?>'+'satgas_administrator/hapus/' + kode,
							async: 		true,
							dataType: 	'json',
							success: 	function(response){
								if(response==true){
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
	})
</script>