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
					<form id="form-tambah" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4);?>">
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
              <div class='col-sm-4'>
								<div class='form-group'>
									<label>Hari Berkas</label>
									<input type='text' id='day' disabled value='' class='form-control' placeholder='hari'>
								</div>
							</div>
              <div class='col-sm-4'>
								<div class='form-group'>
									<label>Tanggal Berkas</label>
									<input type='text' name='tanggal' id='tanggal' value='<?=$saksi['tgl_spt'];?>' class='form-control datepicker' placeholder='tanggal berkas'>
								</div>
							</div>
              <div class='col-sm-4'>
								<div class='form-group'>
									<label>No Surat Ket Riwayat</label>
									<input type='text' name='nosurat' value='<?=$saksi['nosurat_spt'];?>' class='form-control' placeholder='no. surat'>
								</div>
							</div>
							<div id='penduduk' class='col-sm-12'>
								<h4>SAKSI-SAKSI</h4>
                <div class='col-sm-6'>
                  <div class='form-group col-sm-8'>
                    <label>NIK</label> :
                    <input type='text' name='niksaksi1' id='niksaksi1' class='form-control' value='<?= $saksi['niksp1_spt'];?>' placeholder='nik lengkap'>
                  </div>
									<div class='col-sm-4'>
										<label>Cek Internal</label>
										<input type='button' value='Cek' id='cktp1' class='btn btn-warning'>
									</div>
                  <div class='form-group col-sm-12'>
                    <label>Nama</label> :
                    <input type='text' name='namasaksi1' id='namasaksi1' class='form-control' placeholder='nama lengkap' value="<?= stripslashes($saksi1['nma_pdk']);?>">
                  </div>
                  <div class='form-inline col-sm-6'>
  									<label>Pekerjaan</label> :
  									<select name='pekerjaansaksi1' id='pekerjaansaksi1' style='margin:5px' class='form-control'>
  										<?php
  										foreach ($pekerjaan as $dd) {
  											?><option value='<?=$dd['idpkr_pkr']?>' <?php if($saksi1['idpeker_pdk']==$dd['idpkr_pkr']){echo 'selected';}?>><?=$dd['nama_pkr']?></option><?php
  										}
  										 ?>
  									</select>
  								</div>
									<div class='form-group col-sm-6'>
  									<label>Agama</label> :
  									<select name='agamasaksi1' id='agamasaksi1' style='margin:5px' class='form-control'>
  										<option value='1' <?php if($saksi1['agm_pdk']=='1'){echo 'selected';}?>>Islam</option>
  										<option value='2' <?php if($saksi1['agm_pdk']=='2'){echo 'selected';}?>>Kristen</option>
  										<option value='3' <?php if($saksi1['agm_pdk']=='3'){echo 'selected';}?>>Katholik</option>
  										<option value='4' <?php if($saksi1['agm_pdk']=='4'){echo 'selected';}?>>Budha</option>
  										<option value='5' <?php if($saksi1['agm_pdk']=='5'){echo 'selected';}?>>Hindu</option>
  										<option value='6' <?php if($saksi1['agm_pdk']=='6'){echo 'selected';}?>>Konghucu</option>
  									</select>
  								</div>
									<div class='form-group col-sm-6'>
  									<label>Tempat Lahir</label> :
  									<input type='text' name='lahirsaksi1' id='lahirsaksi1' value='<?= $saksi1['ttl_pdk'];?>' class='form-control'>
  								</div>
                  <div class='form-group col-sm-6'>
  									<label>Tanggal Lahir</label> :
  									<input type='text' name='tanggalsaksi1' id='tanggalsaksi1' value='<?= $saksi1['ttg_pdk'];?>' class='form-control datepicker'>
  								</div>
                  <div class='form-group col-sm-12'>
  									<label>Alamat Lengkap</label> :
  									<textarea name='alamatsaksi1' id='alamatsaksi1' class='form-control' placeholder='alamat lengkap'><?=$saksi1['almat_pdk'];?></textarea>
  								</div>
									<div class='form-group col-sm-3'>
										<label>RT</label> :
										<input type='text' name='rt1' id='rt1' class='form-control' placeholder='RT' value='<?= $saksi1['rt_pdk'];?>'>
									</div>
									<div class='form-group col-sm-3'>
										<label>RW</label> :
										<input type='text' name='rw1' id='rw1' class='form-control' placeholder='RW' value='<?= $saksi1['rw_pdk'];?>'>
									</div>
									<div class='form-group col-sm-6'>
										<label>Kelurahan</label> :
										<input type='text' name='kel1' id='kel1' class='form-control' placeholder='Kelurahan' value='<?= $saksi1['kel_pdk'];?>'>
									</div>
									<div class='form-group col-sm-6'>
										<label>Kecamatan</label> :
										<input type='text' name='kec1' id='kec1' class='form-control' placeholder='Kecamatan' value='<?= $saksi1['kec_pdk'];?>'>
									</div>
									<div class='form-group col-sm-6'>
										<label>Kabupaten</label> :
										<input type='text' name='kab1' id='kab1' class='form-control' placeholder='Kabupaten' value='<?= $saksi1['kab_pdk'];?>'>
									</div>
                </div>
                <div class='col-sm-6'>
                  <div class='form-group col-sm-8'>
                    <label>NIK</label> :
                    <input type='text' name='niksaksi2' id='niksaksi2' class='form-control' value='<?= $saksi['niksp2_spt'];?>' placeholder='nama lengkap'>
                  </div>
									<div class='col-sm-4'>
										<label>Cek Dukcapil</label>
										<input type='button' value='Cek' id='cktp2' class='btn btn-warning'>
									</div>
                  <div class='form-group col-sm-12'>
                    <label>Nama</label> :
                    <input type='text' name='namasaksi2' id='namasaksi2' class='form-control' placeholder='nama lengkap' value="<?= stripslashes($saksi2['nma_pdk']);?>">
                  </div>
                  <div class='form-inline col-sm-6'>
  									<label>Pekerjaan</label> :
  									<select name='pekerjaansaksi2' id='pekerjaansaksi2' style='margin:5px' class='form-control'>
  										<?php
  										foreach ($pekerjaan as $dd) {
  											?><option value='<?=$dd['idpkr_pkr']?>' <?php if($saksi2['idpeker_pdk']==$dd['idpkr_pkr']){echo 'selected';}?>><?=$dd['nama_pkr']?></option><?php
  										}
  										 ?>
  									</select>
  								</div>
                  <div class='form-group col-sm-6'>
  									<label>Agama</label> :
  									<select name='agamasaksi2' id='agamasaksi2' style='margin:5px' class='form-control'>
                      <option value='1' <?php if($saksi2['agm_pdk']=='1'){echo 'selected';}?>>Islam</option>
  										<option value='2' <?php if($saksi2['agm_pdk']=='2'){echo 'selected';}?>>Kristen</option>
  										<option value='3' <?php if($saksi2['agm_pdk']=='3'){echo 'selected';}?>>Katholik</option>
  										<option value='4' <?php if($saksi2['agm_pdk']=='4'){echo 'selected';}?>>Budha</option>
  										<option value='5' <?php if($saksi2['agm_pdk']=='5'){echo 'selected';}?>>Hindu</option>
  										<option value='6' <?php if($saksi2['agm_pdk']=='6'){echo 'selected';}?>>Konghucu</option>
  									</select>
  								</div>
									<div class='form-group col-sm-6'>
  									<label>Tempat Lahir</label> :
  									<input type='text' name='lahirsaksi2' id='lahirsaksi2' value='<?= $saksi2['ttl_pdk'];?>' class='form-control'>
  								</div>
                  <div class='form-group col-sm-6'>
  									<label>Tanggal Lahir</label> :
  									<input type='text' name='tanggalsaksi2' id='tanggalsaksi2' value='<?= $saksi2['ttg_pdk'];?>' class='form-control datepicker'>
  								</div>
                  <div class='form-group col-sm-12'>
  									<label>Alamat Lengkap</label> :
  									<textarea name='alamatsaksi2' id='alamatsaksi2' class='form-control' placeholder='alamat lengkap'><?=$saksi2['almat_pdk'];?></textarea>
  								</div>
									<div class='form-group col-sm-3'>
										<label>RT</label> :
										<input type='text' name='rt2' id='rt2' value='<?= $saksi2['rt_pdk'];?>' class='form-control' placeholder='RT'>
									</div>
									<div class='form-group col-sm-3'>
										<label>RW</label> :
										<input type='text' name='rw2' id='rw2' value='<?= $saksi2['rw_pdk'];?>' class='form-control' placeholder='RW'>
									</div>
									<div class='form-group col-sm-6'>
										<label>Kelurahan</label> :
										<input type='text' name='kel2' id='kel2' value='<?= $saksi2['kel_pdk'];?>' class='form-control' placeholder='Kelurahan'>
									</div>
									<div class='form-group col-sm-6'>
										<label>Kecamatan</label> :
										<input type='text' name='kec2' id='kec2' value='<?= $saksi2['kec_pdk'];?>' class='form-control' placeholder='Kecamatan'>
									</div>
									<div class='form-group col-sm-6'>
										<label>Kabupaten</label> :
										<input type='text' name='kab2' id='kab2' value='<?= $saksi2['kab_pdk'];?>' class='form-control' placeholder='Kabupaten'>
									</div>
                </div>
                <div class='form-group col-sm-12'>
                  <label>Nama Kepala Desa</label> :
                  <input type='text' name='kepaladesa' class='form-control' value='<?= stripslashes($saksi['kades_spt']);?>' placeholder='nama lengkap'>
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

		$('#tanggal').change(function() {
      $.ajax({
        url:'<?=base_url()?>ajax/gethari',
        method:'post',
        data:'tanggal='+$(this).val(),
        dataType:'html',
        beforeSend: function() {
            $('#day').val('loading...');
        },
        success: function(response) {
            $('#day').val(response);
        }
      });
		});

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
