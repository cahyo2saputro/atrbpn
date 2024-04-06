<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<?php
					if($mode=='add'){
						?>
						<form enctype="multipart/form-data" id="form-tambah" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>">
						<?php
					}else if($mode=='edit'){
						?>
						<form enctype="multipart/form-data" id="form-edit" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>/<?=$this->uri->segment(4)?>">
						<?php
					}

						if($user['level_usr']==1){
							?><div class='form-group col-sm-12'>
								<label>PPAT</label> :
								<select class='form-control' name='ppat'>
									<?php
									foreach ($ppat as $data) {
										?><option <?php if($report['idppat_rpt']==$data['idusr_usr']){ echo 'selected';}?> value='<?=$data['idusr_usr']?>'><?=$data['name_usr']?></option><?php
									}
									?>
								</select>
							</div><?php
						}
						 ?>
						<div class='col-sm-6'>
							<div id='penduduk' class='col-sm-12'>
								<div class='form-group col-sm-12'>
									<label>Nomor Akta</label> :
									<input type='text' name='noakta' id='noakta' class='form-control' placeholder='nomor akta' value='<?=$report['noakta_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>BPH</label> :
									<select class='form-control' name='bph' id='bph'>
										<option value='1' <?php if($report['bph_rpt']=='1'){ echo 'selected';}?>>AJB - Akta Jual Beli</option>
										<option value='2' <?php if($report['bph_rpt']=='2'){ echo 'selected';}?>>Hibah</option>
										<option value='3' <?php if($report['bph_rpt']=='3'){ echo 'selected';}?>>APHB - Akta Pembagian Hak Bersama</option>
										<option value='4' <?php if($report['bph_rpt']=='4'){ echo 'selected';}?>>APHT - Akta Pemberitahuan Hak Tanggungan</option>
										<option value='5' <?php if($report['bph_rpt']=='5'){ echo 'selected';}?>>SKMHT - Surat Kuasa Membebankan Hak Tanggungan</option>
										<option value='6' <?php if($report['bph_rpt']=='6'){ echo 'selected';}?>>ATM</option>
									</select>

								</div>
								<div class='form-group col-sm-12'>
									<label>Pihak Penerima</label> :
									<input type='text' name='penerima' id='penerima' class='form-control' placeholder='penerima' value='<?=$report['pterima_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Lokasi</label> :
									<input type='text' name='lokasi' id='lokasi' class='form-control' placeholder='lokasi' value='<?=$report['lokasi_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Luas Bangunan</label> :
									<input type='number' name='luas' id='luas' class='form-control' placeholder='luas' value='<?=$report['luasbangunan_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>SPPT NOP</label> :
									<input type='text' name='spptnop' id='spptnop' class='form-control' placeholder='SPPT NOP' value='<?=$report['spptnop_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>SSP Tanggal</label> :
									<input type='text' name='ssptanggal' id='ssptanggal' class='form-control datepicker' placeholder='SSP Tanggal' value='<?=$report['ssptanggal_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>SPPD Tanggal</label> :
									<input type='text' name='sppdtanggal' id='sppdtanggal' class='form-control datepicker' placeholder='SPPD Tanggal' value='<?=$report['sppdtanggal_rpt']?>'>
								</div>
							</div>

						</div>
						<div class='col-sm-6'>
							<div id='tanah' class='col-sm-12'>
								<div class='form-group col-sm-12'>
									<label>Tanggal Akta</label> :
									<input type='text' name='tanggalakta' id='tanggalakta' class='form-control datepicker' placeholder='tanggal akta' value='<?=$report['tglakta_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Pihak Alih</label> :
									<input type='text' name='pihakalih' id='pihakalih' class='form-control' placeholder='Pihak Alih' value='<?=$report['palih_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Hak</label> :
									<input type='text' name='hak' id='hak' class='form-control' placeholder='nomor hak' value='<?=$report['nohak_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Luas Tanah</label> :
									<input type='number' name='luastanah' id='luastanah' class='form-control' placeholder='luas tanah' value='<?=$report['luastanah_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Nilai Transaksi</label> :
									<input type='number' name='nilai' id='nilai' class='form-control' placeholder='nilai Transaksi' value='<?=$report['nilai_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>SPPT NJOP</label> :
									<input type='text' name='spptnjop' id='spptnjop' class='form-control' placeholder='SPPT NJOP' value='<?=$report['spptnjop_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>SSP Nilai</label> :
									<input type='text' name='sspnilai' id='sspnilai' class='form-control' placeholder='SSP Nilai' value='<?=$report['sspnilai_rpt']?>'>
								</div>
								<div class='form-group col-sm-12'>
									<label>SPPD Nilai</label> :
									<input type='number' name='sppdnilai' id='sppdnilai' class='form-control' placeholder='SPPD Nilai' value='<?=$report['sppdnilai_rpt']?>'>
								</div>
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
