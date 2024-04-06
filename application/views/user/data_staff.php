<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Datatables-->
<link href="<?php echo base_url();?>assets/js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url();?>assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables/responsive.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="col-sm-12">
						<div class="pull-right">
							<button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></button>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th>No</th>
									<th>NIP</th>
									<th>Nama User</th>
									<th>Username</th>
									<th>Hak Akses User</th>
									<th>Keterangan</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
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
		<div class="modal-dialog modal-lg">
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

					<input type="text" hidden name="id_st" id="id_st">

					<div class="form-group row">
						<label class="col-sm-3">NIP</label>
						<div class="col-sm-9">
							<input type="text" name="nip_usr" id="nip_usr" class="form-control input-sm">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Nama</label>
						<div class="col-sm-9">
							<input type="text" name="name_usr" id="name_usr" class="form-control input-sm">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3">Keterangan</label>
						<div class="col-sm-9">
							<input type="text" name="ket_usr" id="ket_usr" class="form-control input-sm" placeholder='jabatan'>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3">Username</label>
						<div class="col-sm-9">
							<input type="text" name="usrid_usr" id="usrid_usr" class="form-control input-sm">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Password</label>
						<div class="col-sm-9">
							<input type="password" name="pasid_user" id="pasid_user" class="form-control input-sm">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Ulangi Password</label>
						<div class="col-sm-9">
							<input type="password" name="pasid_user_ret" id='pasid_user_ret' class="form-control input-sm">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Activity User</label>
						<div class="col-sm-9">
							<select class="form-control input-sm" name="activ_usr" id="activ_usr" style="width: 100%">
								<option>Pilih Activity</option>
								<option value="1">Ya</option>
								<option value="0">Tidak</option>
							</select>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-3">Hak Akses</label>
						<div class="col-sm-9">
							<select class="form-control input-sm" name="level_usr" id="level_usr" style="width: 100%">
								<option>Pilih Hak Akses</option>
								<option value="1">Admin</option>
								<option value="2">Satgas</option>
								<option value="3">BP2KAD</option>
								<option value="4">PPAT</option>
							</select>
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


		$('[data-toggle="tooltip"]').tooltip();
		
		$('#activ_usr').select2();
		$('#level_usr').select2();

		$('#v_t_su').css({"display":"none"});
		$('#v_t_bt').css({"display":"none"});
		$('#v_d_bt').css({"display":"none"});
		$('#v_d_su').css({"display":"none"});
		$('#v_d_gu').css({"display":"none"});

		$('.f_pass').css({"display":"none"});

		$('#level_usr').change(function () {
			var lvl = $('#level_usr').val();
			if(lvl == "1"){
				$('#v_t_su').css({"display":"none"});
				$('#v_t_bt').css({"display":"none"});
				$('#v_d_bt').css({"display":"none"});
				$('#v_d_su').css({"display":"none"});
				$('#v_d_gu').css({"display":"none"});
			}else if(lvl == "2"){
				$('#v_t_su').css({"display":""});
				$('#v_t_bt').css({"display":""});
				$('#v_d_bt').css({"display":""});
				$('#v_d_su').css({"display":""});
				$('#v_d_gu').css({"display":""});
			}else{
				$('#v_t_su').css({"display":"none"});
				$('#v_t_bt').css({"display":"none"});
				$('#v_d_bt').css({"display":"none"});
				$('#v_d_su').css({"display":"none"});
				$('#v_d_gu').css({"display":"none"});
			}
		})

		var tabel = $('#data-staff').DataTable({
			"bProcessing": 	true,
			"bAutoWidth": 	false,
			"bSort": 		true,
			"deferRender": true,
			"order": 		[],
			"sAjaxSource": 	'<?=base_url()?>user/data',
			"aoColumns":	[
								{ "mData"	: "no", orderable:false, searchable:false},
								{ "mData" 	: "nip"},
								{ "mData"	: "nama"},
								{ "mData"	: "usr"},
								{ "mData"	: "level"},
								{ "mData"	: "ket"},
								{ "mData"	: "action", orderable:false, searchable:false}
								],
			"columnDefs": 	[
								{ className: "text-center", "targets": [0,3,5] },//1,2,3,4,5,6,7,8,9,10,11,12,13,14
								{ width: 30, targets: 0},
								{ width: 80, targets: 5}
							],
			"fixedColumns": true
		})

		$('#btn-tambah').click(function(){
			$('#form-tambah').attr('action','<?=base_url()?>user/tambah');
			$('#form-tambah')[0].reset();
			$('.modal-title').html('Tambah Data');
			$('#modal-tambah').modal('show');
		});

		$('#btn-simpan').click(function() {
			$('#form-tambah').ajaxForm({
				success: 	function(response){
					if(response=='true'){
						tabel.ajax.reload(null,false);
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

		$('#btn-simpan-usr').click(function() {
			$('#form-usr').ajaxForm({
				success: 	function(response){
					if(response=='true'){
						tabel.ajax.reload(null,false);
						swal("Good job!", $('.modal-title').html() + " Sukses", "success");
						$('#form-usr')[0].reset();
						$('#modal-usr').modal('hide');
					}else{
						swal("Error!", $('.modal-title').html() + " Gagal", "error");
					}
				},
				error: function(){
					swal("Error!", "Response Gagal", "error");
				}
			}).submit();
		});

		$('#tabel-body').on('click','#btn-usr',function () {
			$('.modal-title').html('Ubah Data');
				$('#form-usr').attr('action','<?=base_url()?>user/add_usr');
				$.ajax({
					type: 		'ajax',
					method: 	'get',
					url: 		'<?=base_url()?>'+'user/staff_id?id=' + $(this).data('id'),
					async: 		true,
					dataType: 	'json',
					success: 	function(response){
						$('#id_usr').val(response[0].idusr_usr);
						$('#name_usre').val(response[0].name_usr);
						$('#nip_usre').val(response[0].nip_usr);
					},
					error: function(){
						swal("Error");
					}
				});
				$('#modal-usr').modal('show');
		})

		$('#tabel-body').on('click','#btn-edit_usr',function () {
			$('.modal-title').html('Ubah Data');
			$('#txt_pas').html('Password Lama');
			$('.f_pass').css({"display":""});
			$('#form-usr').attr('action','<?=base_url()?>user/edit_usr');
			$.ajax({
				type: 		'ajax',
				method: 	'get',
				url: 		'<?=base_url()?>'+'user/staff_id?id=' + $(this).data('id'),
				async: 		true,
				dataType: 	'json',
				success:function (response) {
					$('#id_usr').val(response[0].idusr_usr);
					$('#nip_usre').val(response[0].nip_usr);
					$('#name_usre').val(response[0].name_usr);
					$('#usrid_usr').val(response[0].usrid_usr);
					$('#ket_usr').val(response[0].ket_usr);
				},
				error:function () {
					swal('Error');
				}
			});
			$('#modal-usr').modal('show');
		})


		$('#tabel-body').on('click', '#btn-edit', function(){
				$('.modal-title').html('Ubah Data');
				$('#form-tambah').attr('action','<?=base_url()?>user/edit');
				$.ajax({
					type: 		'ajax',
					method: 	'get',
					url: 		'<?=base_url()?>'+'user/staff_id?id=' + $(this).data('id'),
					async: 		true,
					dataType: 	'json',
					success: 	function(response){
						$('#id').val(response[0].idusr_usr);
						var activ = response[0].activ_usr;
						$('#activ_usr').val(activ).trigger('change');
						var lvl = response[0].level_usr;
						$('#level_usr').val(lvl).trigger('change');
						$('#name_usr').val(response[0].name_usr);
						$('#nip_usr').val(response[0].nip_usr);
						$('#id_st').val(response[0].id_st);
						$('#ket_usr').val(response[0].ket_usr);
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
							url: 		'<?=base_url()?>'+'user/hapus/' + kode,
							async: 		true,
							dataType: 	'json',
							success: 	function(response){
								if(response==true){
									tabel.ajax.reload(null,false);
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
