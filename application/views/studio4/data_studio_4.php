<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<form action='<?= base_url()?>Studioppat' method='get'>
							<div class='col-sm-2'>
								<label>Pilih User PPAT</label><br>
								<select class="form-control input-sm" name='user' id="filter_user" style='width:150px'>
									<option value="">Semua PPAT</option>
									<?php
										foreach ($user as $fk) {
									 ?>
									 <option value="<?=$fk['idusr_usr']?>"><?=$fk['name_usr']?></option>
									<?php } ?>
								</select>
							</div>
							<div class='col-sm-2'>
								<label>No Hak</label>
								<input type='text' class='form-control' name='nohak' value='<?=$this->input->get('nohak')?>'>
							</div>
							<div class='col-sm-2'>
								<label>Bulan</label>
								<input type='text' class='form-control month' name='bulan' value='<?=$month?>'>
							</div>
							<div class='col-sm-2'>
								<label>Status</label>
								<select class="form-control input-sm" name='status' id='filter_status' style='width:150px'>
									<option value="">Semua</option>
									<option value="1">Belum KW 1</option>
									<option value="2">Sudah KW 1</option>
								</select>
							</div>
						<div class="col-sm-2">
							<label></label><br>
							<button style='margin-top:5px;' class="btn btn-sm btn-primary" id="btn-cari">Cari <span class="fa fa-search"></span></button>
						</div>
					</form>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th>No</th>
									<th>PPAT</th>
									<th>No.Hak</th>
									<th>Status</th>
									<th>Tanggal</th>
									<th style="width: 13%">Action</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $st) {

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td><?=$st['nama']?></td>
								 	<td><?=$st['nomorhak']?></td>
								 	<td><?php
									if($st['status']==2){
										echo "<span class='btn btn-primary'>Sudah KW1</span>";
									}else if($st['status']==1){
										echo "<span class='btn btn-warning'>Belum KW1</span>";
									}else{
										echo "<span class='btn btn-danger'>Belum Terdaftar</span>";
									}

									?>
									</td>
									<td><?=fdate($st['tanggal'],'HHDDMMYYYY')?></td>
								 	<td>
								 		<div class="btn-group">
											<?php
											if($st['status']!=2){
													?><a data-toggle="tooltip" title="Ubah ke kw1" href='<?= base_url()?>/Apimobile/deliverhak/<?=$st['idhak']?>' class="btn btn-sm btn-warning" id="btn-edit" ><span class="fa fa-check-square-o"></span></a><?php
											}
											 ?>
											 <a data-toggle="tooltip" title="Berkas sudah ready" href='<?= base_url()?>/Apimobile/confirmhak/<?=$st['idhak']?>' class="btn btn-sm btn-primary" id="btn-view" ><span class="fa fa-check-square-o"></span></a>
								 			<a data-toggle="tooltip" title="Detail Pengecekan" href='<?= base_url()?>/Studioppat/detail/<?=$st['idhak']?>' class="btn btn-sm btn-default" id="btn-view" ><span class="fa fa-file"></span></a>
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
	$('#filter_user').select2();
	$('#filter_status').select2();
});
</script>
