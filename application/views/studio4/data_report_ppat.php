<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
if($this->input->get('bulan')){
	$month = $this->input->get('bulan');
}else{
	$month = date('Y-m');
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
<?php
if($user['level_usr']==1){
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" style='background:#fff'>
      <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>" role="tab" aria-controls="home" aria-selected="false">Laporan Bulanan</a>
  </li>
  <li class="nav-item">
      <a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/bulanan" role="tab" aria-controls="profile" aria-selected="false">Rekap Bulanan</a>
  </li>
</ul>
<?php
}
?>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="pull-left">
					</div>
					<div class="pull-right">
						 <button type="button" id="btn-tambah-studio" class="btn btn-sm btn-primary" style="margin:0 0 5px 5px;">Tambah <span class="fa fa-plus-square-o"></span></button>
						 <button type="button" id="btn-excel" class="btn btn-sm btn-primary" style="margin:0 0 5px 5px;">Export <span class="fa fa-download"></span></button>
					</div>
					<div class="row">
					<form action='<?= base_url()?>Reportppat' method='get'>
						<?php
						if($user['level_usr']==1){
							$export = 'bulan='.$this->input->get('bulan').'&user='.$this->input->get('user');
						?>
							<div class='col-sm-3'>
								<label>Pilih User PPAT</label><br>
								<select class="form-control input-sm" name='user' id="filter_user">
									<option value="">Semua PPAT</option>
									<?php
										foreach ($userr as $fk) {
									 ?>
									 <option value="<?=$fk['idusr_usr']?>"><?=$fk['name_usr']?></option>
									<?php } ?>
								</select>
							</div>
							<?php
						}else{
							$export = 'bulan='.$month;
						}
							?>
							<div class='col-sm-3'>
								<label>Bulan Laporan</label><br>
								<input type='text' class='form-control month' name='bulan' value='<?=$month?>'>
							</div>
						<div class="col-sm-4">
							<label></label><br>
							<button style='margin-top:5px;' class="btn btn-sm btn-primary" id="btn-cari">Cari <span class="fa fa-search"></span></button>
						</div>
					</form>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th rowspan=2>No</th>
									<?php if($user['level_usr']==1){
											?><th rowspan=2>PPAT</th><?php
									} ?>
									<th colspan=2 style='text-align:center'>Akta</th>
									<th rowspan=2>BPH</th>
									<th rowspan=2>Pihak Alih</th>
									<th rowspan=2>Pihak Penerima</th>
									<th rowspan=2>Jenis Hak/No Hak</th>
									<th rowspan=2>Lokasi</th>
									<th rowspan=2>Luas Tanah</th>
									<th rowspan=2>Luas Bangunan</th>
									<th rowspan=2>Nilai Transaksi</th>
									<th colspan=2 style='text-align:center'>SPPT</th>
									<th colspan=2 style='text-align:center'>SSP</th>
									<th colspan=2 style='text-align:center'>SPPD</th>
									<th rowspan=2 style="width: 13%">Action</th>
								</tr>
								<tr>
									<th>No</th>
									<th>Tanggal</th>
									<th>NOP</th>
									<th>NJOP</th>
									<th>Tanggal</th>
									<th>Nilai</th>
									<th>Tanggal</th>
									<th>Nilai</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $st) {

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
									<?php if($user['level_usr']==1){
											?><td><?=$st['name_usr']?></td><?php
									} ?>
								 	<td><?=$st['noakta_rpt']?></td>
									<td><?=fdate($st['tglakta_rpt'],'DDMMYYYY')?></td>
								 	<td><?php
									if($st['bph_rpt']==1){
											echo 'AJB';
									}else if($st['bph_rpt']==2){
											echo 'Hibah';
									}else if($st['bph_rpt']==3){
											echo 'APHB';
									}else if($st['bph_rpt']==4){
											echo 'APHT';
									}else if($st['bph_rpt']==5){
											echo 'SKHMT';
									}else if($st['bph_rpt']==6){
										echo 'ATM';
									}else{
										echo 'undefined';
									}?></td>
									<td><?=$st['palih_rpt']?></td>
									<td><?=$st['pterima_rpt']?></td>
									<td><?=$st['nohak_rpt']?></td>
									<td><?=$st['lokasi_rpt']?></td>
									<td><?=$st['luastanah_rpt']?></td>
									<td><?=$st['luasbangunan_rpt']?></td>
									<td><?=$st['nilai_rpt']?></td>
									<td><?=$st['spptnop_rpt']?></td>
									<td><?=$st['spptnjop_rpt']?></td>
									<td><?=fdate($st['ssptanggal_rpt'],'DDMMYYYY')?></td>
									<td><?=$st['sspnilai_rpt']?></td>
									<td><?=fdate($st['sppdtanggal_rpt'],'DDMMYYYY')?></td>
									<td><?=$st['sppdnilai_rpt']?></td>
								 	<td>
								 		<div class="btn-group">
											 <a data-toggle="tooltip" title="Edit Laporan" href='<?= base_url()?>/Reportppat/edit_reportppat/<?=$st['id_rpt']?>' class="btn btn-sm btn-primary" id="btn-view" ><span class="fa fa-edit"></span></a>
								 			<a data-toggle="tooltip" title="Hapus laporan" class="btn btn-sm btn-danger" id="btn-hapus" data-id="<?=$st['id_rpt']?>"><span class="fa fa-trash"></span></a>
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
<script>
$(document).ready(function () {
	/*$('#data_kelurahan').DataTable();*/
	$('#btn-tambah-studio').on('click',function () {
		window.open('<?=base_url().''.$this->uri->segment(1)?>/form_reportppat','_self',false);
	});

	$('#btn-excel').on('click',function () {
		window.open('<?=base_url().''.$this->uri->segment(1)?>/excel_reportppat?<?=$export?>','_self',false);
	});

	$('#filter_user').select2();

	$('#tabel-body').on('click','#btn-hapus',function () {
			var kode 	= $(this).data('id');
			swal({
				title: "Apakah anda yakin?",
				text: "Untuk menghapus data tersebut",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$.ajax({
						type: 		'ajax',
						method: 	'post',
						url: 		'<?=base_url()?>'+'<?php echo $this->uri->segment(1)?>/delete/' + kode,
						async: 		true,
						dataType: 	'json',
						success: 	function(response){
							if(response==true){
								/*tabel_studio.ajax.reload(null,false);*/
							/*	location. reload(true);*/
								swal("Hapus Data Berhasil !", {
									icon: "success",
								});
								window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>/','_self',false);
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
});
</script>
