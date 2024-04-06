<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
$menu = $this->uri->segment(1);
$fungsi = $this->uri->segment(2);

if($this->input->get('bulan')){
	$month = $this->input->get('bulan');
}else{
	$month = '';
}

?>
<script>
$(function() {
   $(".month").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm',
    onClose: function(dateText, inst) {
        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(year, month, 1));
    }
    });
 });
</script>
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
									if (in_array(41, $_SESSION['menu']) || $user['level_usr']==1) {
										?><button class="btn btn-sm btn-primary" id="btn-tambah-adm" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></button><?php
									}
								 ?>
						</div>
						<div class='row'>
							<form action='' method='get'>
									<div class='col-sm-2'>
										<label>No Surat</label><br>
										<input type='text' class='form-control' name='no' value='<?=$this->input->get('no')?>'>
									</div>
									<div class='col-sm-2'>
										<label>Perihal</label><br>
										<input type='text' class='form-control' name='perihal' value='<?=$this->input->get('perihal')?>'>
									</div>
									<div class='col-sm-2'>
										<label>Bulan</label><br>
										<input type='text' class='form-control month' name='bulan' value='<?=$month?>'>
									</div>
								<div class="col-sm-4">
									<label></label><br>
									<button style='margin-top:5px;' class="btn btn-sm btn-primary" id="btn-cari">Cari <span class="fa fa-search"></span></button>
								</div>
							</form>
							<br><br>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table class="table table-bordered" cellspacing="0" id='data_kelurahan'>
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;">No</th>
									<th style="text-align: center; vertical-align: middle;">Kategori</th>
									<th style="text-align: center; vertical-align: middle;">Nomor Surat</th>
									<th style="text-align: center; vertical-align: middle;">Perihal</th>
									<th style="text-align: center; vertical-align: middle;">Tanggal</th>
									<th style="text-align: center; vertical-align: middle;">Aksi</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no=0;
									foreach ($administrasi as $datas) {$no++;
								 ?>
								<tr>
								 	<td><?=$no;?></td>
									<td><?=$datas['name_adm'];?></td>
								 	<td><?=$datas['no_adt'];?></td>
								 	<td><?=$datas['perihal_adt'];?></td>
								 	<td><?=fdate($datas['tanggal_adt'],'DDMMYYYY');?></td>
								 	<td>
								 		<div class="form-group">
											<a data-toggle="tooltip" title="detail administrasi" target='_blank' href='<?= base_url()?>DATA/SURAT/<?=$datas['image_adt']?>' class='btn btn-warning'><i class="fa fa-file" aria-hidden="true"></i></a>
											<?php
													if (in_array(41, $_SESSION['menu']) || $user['level_usr']==1) {
														?><button data-toggle="tooltip" title="edit administrasi" class="btn btn-sm btn-warning" id="btn-edit-surat" type="button" data-id="<?=$datas['id_adt']?>"><span class="fa fa-edit"></span></button>
														<button data-toggle="tooltip" title="hapus administrasi" class="btn btn-sm btn-danger" id="btn-hapus-surat" type="button" data-id="<?=$datas['id_adt']?>" data-nama="<?=$datas['no_adt']?>"><span class="fa fa-trash-o"></span></button><?php
													}
											 ?>

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

		$('#btn-tambah-adm').on('click',function () {
			window.open('<?=base_url()?>Studio4/form','_self',false);
		});
		$('#tabel-body').on('click','#btn-edit-surat',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>Studio4/form/'+id,'_self',false);
		});
		$('#tabel-body').on('click','#btn-hapus-surat',function () {
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
							url: 		'<?=base_url()?>'+'studio4/hapus/' + kode,
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
