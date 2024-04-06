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
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" >
      <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>" role="tab" aria-controls="home" aria-selected="false">Laporan Bulanan</a>
  </li>
  <li class="nav-item" style='background:#fff'>
      <a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/bulanan" role="tab" aria-controls="profile" aria-selected="false">Rekap Bulanan</a>
  </li>
</ul>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="pull-right">
						<button type="button" id="btn-excel" class="btn btn-sm btn-primary" style="margin:0 0 5px 5px;">Export <span class="fa fa-download"></span></button>
					</div>
					<div class="row">
					<form action='<?= base_url()?>Reportppat/bulanan' method='get'>
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
									<th rowspan=2>PPAT</th>
									<th colspan=2 style='text-align:center'>Jual Beli</th>
									<th colspan=2 style='text-align:center'>hibah</th>
									<th colspan=2 style='text-align:center'>PHB</th>
									<th colspan=2 style='text-align:center'>HT</th>
									<th colspan=2 style='text-align:center'>SKMHT</th>
									<th colspan=2 style='text-align:center'>ATM</th>
									<th rowspan=2>∑</th>
									<th rowspan=2>Luas</th>
									<th rowspan=2>Nilai HT</th>
									<th rowspan=2>Transaksi Jual Beli</th>
									<th rowspan=2>SSP</th>
									<th rowspan=2>SPPD</th>
								</tr>
								<tr>
									<th>∑</th>
									<th>Luas</th>
									<th>∑</th>
									<th>Luas</th>
									<th>∑</th>
									<th>Luas</th>
									<th>∑</th>
									<th>Luas</th>
									<th>∑</th>
									<th>Luas</th>
									<th>∑</th>
									<th>Luas</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $st) {

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td><?=$st['name_usr']?></td>
								 	<td><?=$st['sumjb']?></td>
									<td><?=$st['luasjb']?></td>
									<td><?=$st['sumhibah']?></td>
									<td><?=$st['luashibah']?></td>
									<td><?=$st['sumphb']?></td>
									<td><?=$st['luasphb']?></td>
									<td><?=$st['sumht']?></td>
									<td><?=$st['luasht']?></td>
									<td><?=$st['sumskmht']?></td>
									<td><?=$st['luasskmht']?></td>
									<td><?=$st['sumatm']?></td>
									<td><?=$st['luasatm']?></td>
									<td><?=$st['sumtotal']?></td>
									<td><?=$st['luastotal']?></td>
									<td><?=$st['nilaiht']?></td>
									<td><?=$st['nilaiajb']?></td>
									<td><?=$st['nilaissp']?></td>
									<td><?=$st['nilaisppd']?></td>
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

	$('#btn-excel').on('click',function () {
		window.open('<?=base_url().''.$this->uri->segment(1)?>/excel_monthlyppat?bulan=<?=$this->input->get('bulan')?>','_self',false);
	});

	/*$('#data_kelurahan').DataTable();*/
	$('#btn-tambah-studio').on('click',function () {
		window.open('<?=base_url().''.$this->uri->segment(1)?>/form_reportppat','_self',false);
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
