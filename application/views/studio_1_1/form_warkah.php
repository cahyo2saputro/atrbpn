<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.js"></script>
<style>
	#mobile{
		display: none;
	}
	@media (max-width: 767px) {
		#mobile{
			display: block;
		}
		#wide{
			display: none;
		}
	}
</style>
<?php
$display='';
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
						<div class='col-sm-6'>
							<div class="form-group row">
								<label class="col-sm-3">Nomor Hak</label>
								<div class="col-sm-9">
									<?= $datas['nohak_warkah']?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Nomor Differensi</label>
								<div class="col-sm-9">
									<?= $datas['nodifferensi_warkah']?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Pemohon</label>
								<div class="col-sm-9">
									<?= $datas['pemohon_warkah']?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Alamat</label>
								<div class="col-sm-9">
									<?= $datas['alamat_warkah']?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Pelepas Hak</label>
								<div class="col-sm-9">
									<?= $datas['pelepashak_warkah']?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Tanggal Hak</label>
								<div class="col-sm-9">
									<?= fdate($datas['tanggalhak_warkah'],'DDMMYYYY')?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Seri</label>
								<div class="col-sm-9">
									<?= $datas['seri_warkah']?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Keterangan</label>
								<div class="col-sm-9">
									<?= $datas['ket_warkah']?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Bulan Warkah</label>
								<div class="col-sm-9">
									<?= fbulan($datas['bulan_warkah'])?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Tahun Warkah</label>
								<div class="col-sm-9">
									<?= $datas['tahun_warkah']?>
								</div>
							</div>
						</div>
						<div class='col-sm-6'>
							<div class="form-group row" style="display: <?=$display?>">
								<label class="col-sm-3"></label>
								<div class="col-sm-9" style='text-align:center'>
									<?php
									$fw = str_replace('/','_',$datas['nodifferensi_warkah']);
									$nma_kel = strtoupper($get_data['nma_kel']);
									$nma_kec = strtoupper($get_data['nma_kec']);
									$file_warkah = base_url()."/digitalisasi/$nma_kec/$nma_kel/WARKAH/W_".$fw.".pdf"
									 ?>
									<a target='_blank' class='btn btn-primary' href='<?=$file_warkah?>'>Lihat Digitalisasi (fullscreen)</a>
									<iframe id='wide' frameborder="0" width="100%" height="750" src="<?=$file_warkah?>"></iframe>
									<iframe id='mobile' frameborder="0" width="100%" height="750" src="https://docs.google.com/viewer?url=<?=$file_warkah?>&embedded=true"></iframe>

									<?php
										// }else{
										// 	foreach ($get_nma_file as $gf) {
										// 	$file_pdf = base_url().'digitalisasi/'.$get_file['nma_kec'].'/'.$get_file['nma_kel'].'/SURAT_UKUR/'.$gf->doc_su;
									?>
									<!-- <iframe frameborder="0" width="100%" height="750" src="<?php //$file_pdf?>"></iframe> -->
									<?php //}} ?>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
