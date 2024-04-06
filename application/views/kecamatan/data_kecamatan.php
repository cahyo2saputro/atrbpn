<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
$menu = $this->uri->segment(1);
$fungsi = $this->uri->segment(2);

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
								if($user['level_usr'] == '1'){ 
							 ?>

							<button type="button" id="btn-tambah-kelurahan" class="btn btn-sm btn-primary" style="margin-bottom: 5px;">Tambah <span class="fa fa-plus-square-o"></span></button>

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
									<th style="text-align: center; vertical-align: middle;width: 10%">NO</th>
									<th style="text-align: center; vertical-align: middle;">KECAMATAN</th>
									<th style="text-align: center; vertical-align: middle;">ACTION</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php 
									$no=0;
									foreach ($kec as $data_kelurahan_kw) {$no++;
								 ?>
								<tr>
								 	<td style="text-align: center;"><?=$no;?></td>
								 	<td style="text-align: center;"><?=$data_kelurahan_kw->nama_kecamatan;?></td>
								 	<td style="text-align: center;">
								 		<div class="form-group">
											<button class="btn btn-sm btn-warning" id="btn-edit-kelurahan" type="button" data-id="<?=$data_kelurahan_kw->nama_kecamatan?>"><span class="fa fa-edit"></span></button>
											<button class="btn btn-sm btn-danger" id="btn-hapus-kelurahan" type="button" data-id="<?=$data_kelurahan_kw->nama_kecamatan?>" data-nama="<?=$data_kelurahan_kw->nama_kecamatan?>"><span class="fa fa-trash-o"></span></button>
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
<script type="text/javascript">
	$(document).ready(function () {
		$('#data_kelurahan').DataTable();
		
		$('#btn-tambah-kelurahan').on('click',function () {
			window.open('<?=base_url()?>kecamatan/form_kecamatan','_self',false);
		});
		$('#tabel-body').on('click','#btn-edit-kelurahan',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>kecamatan/form_kecamatan/'+id,'_self',false);
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