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
								<div class='form-group col-sm-6'>
									<label>Nomor Berita Cara Pemasangan dan Persetujuan Tanda Batas</label>
									<input type='text' name='nobatandabatas' value='<?=$panitia['no_batandabatas'];?>' class='form-control' placeholder='No Berita Cara Pemasangan dan Persetujuan Tanda Batas'>
								</div>
								<div class='form-group col-sm-6'>
									<label>Tanggal Berita Cara Pemasangan dan Persetujuan Tanda Batas</label>
									<input type='date' name='datebatandabatas' value='<?=$panitia['date_batandabatas'];?>' class='form-control' placeholder='Tanggal Berita Cara Pemasangan dan Persetujuan Tanda Batas'>
								</div>
								<div class='form-group'>
									<label>Nomor Berita Acara Persetujuan Penunjuk Batas</label>
									<input type='text' name='nobabatasbidang' value='<?=$panitia['no_babatasbidang'];?>' class='form-control' placeholder='No Berita Acara Persetujuan Penunjuk Batas'>
								</div>
								<div class='form-group'>
									<label>Inisial Tim</label>
									<input type='text' name='tim' value='<?=$panitia['tim_pnt'];?>' class='form-control' placeholder='Inisial Tim'>
								</div>
								<div class='form-group'>
									<label>Nomor SK</label>
									<input type='text' name='no' value='<?=$panitia['no_pnt'];?>' class='form-control' placeholder='No SK'>
								</div>
								<div class='form-group'>
									<label>Tanggal SK</label>
									<input type='text' name='tgl' value='<?=$panitia['tgl_pnt'];?>' class='form-control datepicker' placeholder='Tanggal SK'>
								</div>
								<div class='form-group'>
									<label>Ketua Panitia Ajudikasi</label>
									<input type='text' name='ketua' value='<?=$panitia['ketua_pnt'];?>' class='form-control' placeholder='ketua panitia'>
								</div>
								<div class='form-group'>
									<label>NIP Ketua Panitia Ajudikasi</label>
									<input type='text' name='nipketua' value='<?=$panitia['nipketua_pnt'];?>' class='form-control' placeholder='NIP ketua panitia'>
								</div>
								<div class='form-group'>
									<label>Wakil Ketua Bidang Fisik</label>
									<input type='text' name='fisik' value='<?=$panitia['wakafis_pnt'];?>' class='form-control' placeholder='Wakil Ketua Bidang Fisik'>
								</div>
								<div class='form-group'>
									<label>Wakil Ketua Bidang Yuridis</label>
									<input type='text' name='yuridis' value='<?=$panitia['wakayur_pnt'];?>' class='form-control' placeholder='Wakil Ketua Bidang Yuridis'>
								</div>
								<div class='form-group'>
									<label>Sekretaris</label>
									<input type='text' name='sekre' value='<?=$panitia['sekre_pnt'];?>' class='form-control' placeholder='Sekretaris'>
								</div>
								<div class='form-group col-sm-6'>
									<label>Satgas Yuridis BPN</label> :
									<input type='text' name='satgasbpn' class='form-control' value='<?= $panitia['bpn_pnt'];?>' placeholder='Satgas Yuridis BPN'>
								</div>
												<!-- <div class='form-group col-sm-6'> -->
								<!-- <label>Satgas Yuridis Babinsa</label> : -->
								<input type='hidden' name='satgasbabinsa' class='form-control' value='<?= $panitia['babinsa_pnt'];?>' placeholder='nama lengkap'>
								<!-- </div> -->
								<div class='form-group col-sm-6'>
									<label>NIP Satgas Yuridis BPN</label> :
									<input type='text' name='nipsatgasbpn' class='form-control' value='<?= $panitia['nipbpn_pnt'];?>' placeholder='nip satgas yuridis bpn'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Tipe Panitia</label> :
									<select class='form-control' name='tipe' id='tipe'>
										<option value='1' <?php if($panitia['tipe']==1){echo 'selected';}?>>ASN</option>
										<option value='2' <?php if($panitia['tipe']==2){echo 'selected';}?>>Pihak Ketiga</option>
									</select>
								</div>
								<?php
									$namapt = $caption = $satgas = '';
									if($panitia['tipe']==2){
										$pt = explode("-",$panitia['bpnfisik_pnt']);
										$namapt = $pt[0];
										if(isset($pt[1])){
											$caption = $pt[1];
										}
										if(isset($pt[2])){
											$satgas = $pt[2];
										}
										
									}else{
										$satgas = $panitia['bpnfisik_pnt'];
									}
									?>
								<div class='form-group col-sm-6 third'>
									<label>Nama PT</label> :
									<input type='text' name='pt' class='form-control' value='<?= $namapt;?>' placeholder='Nama PT'>
								</div>
								<div class='form-group col-sm-6 third'>
									<label>Caption</label> :
									<input type='text' name='caption' class='form-control' value='<?= $caption;?>' placeholder='caption PT'>
								</div>
								<div class='form-group col-sm-6'>
									<label>Satgas Fisik BPN</label> :
									<input type='text' name='satgasfisikbpn' class='form-control' value='<?= $satgas;?>' placeholder='Satgas Fisik BPN'>
								</div>
								<div class='form-group col-sm-6'>
									<label>NIP Satgas Fisik BPN</label> :
									<input type='text' name='nipsatgasfisikbpn' class='form-control' value='<?= $panitia['nipbpnfisik_pnt'];?>' placeholder='nip satgas fisik bpn'>
								</div>
								<div class='form-group'>
									<label>Anggota Fisik</label>
									<input type='text' name='angfisik' value='<?=$panitia['fisik_pnt'];?>' class='form-control' placeholder='anggota fisik'>
								</div>
								<div class='form-group'>
									<label>Anggota Yuridis</label>
									<input type='text' name='angyuridis' value='<?=$panitia['yuridis_pnt'];?>' class='form-control' placeholder='anggota yuridis'>
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

		if($('#tipe').val()==2){
			$('.third').show();
		}else{
			$('.third').hide();
		}

	$('#tipe').change(function() {
		if($(this).val()==2){
			$('.third').show();
		}else{
			$('.third').hide();
		}
	});

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

	})
</script>
