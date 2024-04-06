<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
$menu = $this->uri->segment(1);
$fungsi = $this->uri->segment(2);

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

							<?php
								if($user['level_usr'] == '1'){
							 ?>

							<button type="button" id="btn-tambah-dusun" class="btn btn-sm btn-primary" style="margin-bottom: 5px;">Tambah <span class="fa fa-plus-square-o"></span></button>

							<?php
								}else if($user['level_usr'] == '2'){

								}
							?>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table class="table table-bordered" cellspacing="0" id='data_kelurahan'>
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;">NO</th>
									<th style="text-align: center; vertical-align: middle;">KODE</th>
									<th style="text-align: center; vertical-align: middle;">KODE PBB</th>
									<th style="text-align: center; vertical-align: middle;">KECAMATAN</th>
									<th style="text-align: center; vertical-align: middle;">KELURAHAN</th>
									<th style="text-align: center; vertical-align: middle;">DUSUN</th>
									<th style="text-align: center; vertical-align: middle;">ACTION</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no=0;
									foreach ($kel as $data_kelurahan_kw) {$no++;
								 ?>
								<tr>
								 	<td><?=$no;?></td>
								 	<td><?=$data_kelurahan_kw['kd_full'];?></td>
									<td><?=$data_kelurahan_kw['kdpbb_kel'];?></td>
								 	<td><?=$data_kelurahan_kw['nma_kec'];?></td>
								 	<td><?=$data_kelurahan_kw['nma_kel'];?></td>
								 	<td>
								 		<div class="form-group">
											<button data-toggle="tooltip" title="edit data" class="btn btn-sm btn-warning" id="btn-edit-kelurahan" type="button" data-id="<?=$data_kelurahan_kw['id_kel']?>"><span class="fa fa-edit"></span></button>
											<button data-toggle="tooltip" title="hapus data" class="btn btn-sm btn-danger" id="btn-hapus-kelurahan" type="button" data-id="<?=$data_kelurahan_kw['id_kel']?>" data-nama="<?=$data_kelurahan_kw['nma_kel']?>"><span class="fa fa-trash-o"></span></button>
										</div>
								 	</td>
								 </tr>
								<?php } ?>
							</tbody>
						</table>
						<?php
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
		$('#data_kelurahan').DataTable();
		$('[data-toggle="tooltip"]').tooltip();

		$('#btn-tambah-dusun').on('click',function () {
			window.open('<?=base_url()?>Dusun/form_dusun','_self',false);
		});
		$('#tabel-body').on('click','#btn-edit-kelurahan',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>Dusun/form_dusun/'+id,'_self',false);
		});
		$('#tabel-body').on('click','#btn-hapus-kelurahan',function () {
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
							url: 		'<?=base_url()?>'+'kelurahan/hapus_kelurahan/' + kode,
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
