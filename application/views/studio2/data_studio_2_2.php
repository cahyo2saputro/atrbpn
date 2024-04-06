<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
							<button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></button>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th>No</th>
									<th>NUB</th>
									<th>No.Hak</th>
									<th>Status</th>
									<th style="width: 13%">Action</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php 
									$no = $this->uri->segment('3') + 1;
									$nub = $this->uri->segment('3') + 1;
									foreach ($studio as $st) {

									if (empty($st->nohak_nub)) {
										$id = $st->idform_fnub;
										$set = "2";
										$status = "Belum Memiliki Nomor Hak";
										$nohak = "Belum Memiliki Nomor Hak";
									}else{
										$id = $st->idnub_nub;
										$set = "1";
										$status = "Sudah Memiliki Nomor Hak";
										$nohak = $st->nohak_nub;
									}
								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td>NUB-<?=$nub++?></td>
								 	<td><?=$nohak?></td>
								 	<td><?=$status?></td>
								 	<td>
								 		<div class="btn-group">
								 			<button class="btn btn-sm btn-warning" id="btn-edit" data-id="<?=$id?>" data-set="<?=$set?>"><span class="fa fa-edit"></span></button>
								 			<button class="btn btn-sm btn-danger" id="btn-hapus" data-id="<?=$id?>" data-set="<?=$set?>" data-nama="<?=$nohak?>"><span class="fa fa-trash"></span></button>
								 			<button class="btn btn-sm btn-default" id="btn-view" data-id="<?=$nohak?>" data-set="<?=$set?>"><span class="fa fa-clipboard"></span></button>
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

<script type="text/javascript">
	$(document).ready(function () {
		$('#btn-tambah').on('click',function () {
			window.open('<?=base_url()?>studio_2_2/form/?search='+'<?=$idblk?>','_self',false);
		});

		$('#tabel-body').on('click','#btn-edit',function () {
			var id = $(this).data('id');
			var set = $(this).data('set');
			window.open('<?=base_url()?>studio_2_2/form/'+id+'/'+set+'/'+'<?=$idblk?>','_self',false);
		});

		$("#tabel-body").on('click','#btn-view',function () {
			var id = $(this).data('id');
			var status = $(this).data('set');
			if(status == 2){
				swal('Error','Nomor Hak Kosong','error');
			}else{
				window.open('<?=base_url()?>studio_1_1/index/?search='+id,'_blank');
			}
		
		})

		$('#tabel-body').on('click','#btn-hapus',function () {
				var kode 	= $(this).data('id');
				var nama 	= $(this).data('nama');
				var status  = $(this).data('set');
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
							url: 		'<?=base_url()?>'+'studio_2_2/hapus/' + kode + '/' +status,
							async: 		true,
							dataType: 	'json',
							success: 	function(response){
								if(response==true){
									/*tabel_studio.ajax.reload(null,false);*/
								/*	location. reload(true);*/
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