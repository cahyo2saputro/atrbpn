<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
h4{
	border-bottom:1px solid #f0f0f0;
	padding:10px;
	font-weight: bold;
}
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<form id="form-tambah" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>">
						<div class='col-sm-12'>
							<div class='col-sm-6'>
								<div class='form-group'>
									<label>Kelurahan</label>
									<input type='text' disabled name='nub' value='<?=$kecamatan['nma_kel'];?>' class='form-control' placeholder='no. nub'>
								</div>
							</div>
							<div class='col-sm-6'>
								<div class='form-group'>
									<label>Kecamatan</label>
									<input type='text' name='nib' disabled value='<?=$kecamatan['nma_kec'];?>' class='form-control' placeholder='no. nib'>
								</div>
							</div>
              <div class='col-sm-6'>
								<div class='form-group'>
									<label>Nama Kepala Desa</label>
									<input type='text' name='kades' value='<?=$template['kepala_kel']?>' class='form-control' placeholder='nama kades'>
								</div>
							</div>
              <div class='col-sm-6'>
								<div class='form-group'>
									<label>Sekretaris Desa</label>
									<input type='text' name='sekre'  value='<?=$template['sekre_kel'];?>' class='form-control' placeholder='nama sekretaris'>
								</div>
							</div>
						<div class="box-footer">
							<div class="col-sm-12">
								<div class="pull-right">
									<button type="submit" id="btn-simpan" class="btn btn-primary">Simpan</button>
								</div>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {

		$('#pekerjaansaksi1').select2();
		$('#pekerjaansaksi2').select2();

		$('#cktp1').click(function () {
			$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/get_nikinternal',
				data: 'nik='+$('#niksaksi1').val(),
				async: 		false,
				dataType: 'json',
				success: function(response) {
						console.log(response);
							$("#namasaksi1").val(response.nma_pdk);
							$("#lahirsaksi1").val(response.ttl_pdk);
							$("#tanggalsaksi1").val(response.ttg_pdk);
							$("#alamatsaksi1").val(response.almat_pdk);
							$("#rt1").val(response.rt_pdk);
							$("#rw1").val(response.rw_pdk);
							$("#kec1").val(response.kec_pdk);
							$("#kel1").val(response.kel_pdk);
							$("#kab1").val(response.kab_pdk);
							$("#pekerjaansaksi1").val(response.idpeker_pdk);
							$('#pekerjaansaksi1').select2();
							var agama = response.agm_pdk;
							if(agama=='ISLAM'){
								agama=1;
							}else if(agama=='KRISTEN'){
								agama=2;
							}else if(agama=='KATHOLIK'){
								agama=3;
							}else if(agama=='BUDHA'){
								agama=4;
							}else if(agama=='HINDU'){
								agama=5;
							}else if(agama=='KONGHUCU'){
								agama=6;
							}
							$("#agamasaksi1").val(agama);
				}
			});
		});

		$('#cktp2').click(function () {
			$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/get_nikinternal',
				data: 'nik='+$('#niksaksi2').val(),
				async: 		false,
				dataType: 'json',
				success: function(response) {
						console.log(response);
							$("#namasaksi2").val(response.nma_pdk);
							$("#lahirsaksi2").val(response.ttl_pdk);
							$("#tanggalsaksi2").val(response.ttg_pdk);
							$("#alamatsaksi2").val(response.almat_pdk);
							$("#rt2").val(response.rt_pdk);
							$("#rw2").val(response.rw_pdk);
							$("#kec2").val(response.kec_pdk);
							$("#kel2").val(response.kel_pdk);
							$("#kab2").val(response.kab_pdk);
							$("#pekerjaansaksi2").val(response.idpeker_pdk);
							$('#pekerjaansaksi2').select2();
							var agama = response.agm_pdk;
							if(agama=='ISLAM'){
								agama=1;
							}else if(agama=='KRISTEN'){
								agama=2;
							}else if(agama=='KATHOLIK'){
								agama=3;
							}else if(agama=='BUDHA'){
								agama=4;
							}else if(agama=='HINDU'){
								agama=5;
							}else if(agama=='KONGHUCU'){
								agama=6;
							}
							$("#agamasaksi2").val(agama);

				}
			});
		});

	})
</script>
