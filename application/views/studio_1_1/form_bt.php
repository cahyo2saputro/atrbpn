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
$user = $this->auth_model->get_userdata();

// get_berkas('BT',$get_data,$user['idusr_usr']);

defined('BASEPATH') OR exit('No direct script access allowed');
if($status=="edit"){
	$id_kelurahan_fake = $get_data['kd_full'];
	$id_kelurahan_edit = "$('#id_kelurahan').val('$id_kelurahan_fake').trigger('change');";

	$no_hak  	= $get_data['nohak_bt'];

	$fisik_fake = $get_data['fisik_bt'];
	$fisik_edit = "$('#fisik').val('$fisik_fake').trigger('change');";
	$validasi_fake = $get_data['validasi_bt'];
	$validasi_edit = "$('#validasi').val('$validasi_fake').trigger('change');";

	$id 		= $get_data['id_bt'];
	$get_file 		= $this->db->query("SELECT UPPER(nma_kel) as nma_kel,UPPER(nma_kec) as nma_kec FROM ms_kelurahan LEFT JOIN ms_kecamatan ON kd_kec = kdkec_kel WHERE kd_full = '$id_kelurahan_fake' ")->row_array();

	$get_nma_file = $this->db->query("SELECT a.doc_bt, a.jenis_bt, a.page_bt FROM tb_upbt a LEFT JOIN tb_bt b ON a.id_bt = b.id_bt WHERE a.jenis_bt <> 'gambar' AND b.nohak_bt = '$no_hak'")->result();

	$display 	= "";


}else{
	$id_kelurahan_fake = $get_data['kd_full'];
	$id_kelurahan_edit = "$('#id_kelurahan').val('$id_kelurahan_fake').trigger('change');";

	$no_hak 					= $get_data['no_hak'];

	$fisik_edit			 		= "";
	$validasi_edit 				= "";

	$id							= "";

	$get_file 		= $this->db->query("SELECT UPPER(nma_kel) as nma_kel,UPPER(nma_kec) as nma_kec FROM ms_kelurahan LEFT JOIN ms_kecamatan ON kd_kec = kdkec_kel WHERE kd_full = '$id_kelurahan_fake' ")->row_array();
	$get_data['nolem_bt'] 		= "";
	$get_data['norak_bt'] 		= "";
	$get_data['nobalb_bt']		= "";
	$get_data['nosertif_bt']	= "";
	$get_data['nohakfile'] = str_replace('.','',$no_hak);

	$display 					= "";


}

?>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<form id="form-tambah" action="<?=$link?>" method="post">

						<input type="hidden" name="id_bt" id="id_bt" value="<?=$id?>">
						<input type="hidden" name="id_kelurahan_real" id="id_kelurahan_real" value="<?=$id_kelurahan_fake?>">
						<div class='col-sm-6'>
							<div class="form-group row">
								<label class="col-sm-3">Daftar Desa</label>
								<div class="col-sm-9">
									<select class="form-control input-sm" id="id_kelurahan" name="id_kelurahan" disabled>
										<option>Pilih Kelurahan</option>
										<?php
											foreach ($kelurahan as $kelurahan) {
												echo '<option value="'.$kelurahan->kd_full.'">'.$kelurahan->kd_full.'-'.$kelurahan->nma_kel.'</option>';
											}
										 ?>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Nomor Sertifikat</label>
								<div class="col-sm-9">
									<input type="text" name="no_sertifikat" id="no_sertifikat" class="form-control input-sm" value="<?=$get_data['nosertif_bt']?>">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Nomor Hak</label>
								<div class="col-sm-9">
									<input type="text" name="no_hak" id="no_hak" class="form-control input-sm" readonly value="<?=$no_hak?>">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Nomor Lemari</label>
								<div class="col-sm-9">
									<input type="text" class="form-control input-sm" id="no_lemari" name="no_lemari" value="<?=$get_data['nolem_bt']?>">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Nomor Rak</label>
								<div class="col-sm-9">
									<input type="text" class="form-control input-sm" id="no_rak" name="no_rak" value="<?=$get_data['norak_bt']?>">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Nomor Buku Album</label>
								<div class="col-sm-9">
									<input type="text" class="form-control input-sm" id="no_buku_album" name="no_buku_album" value="<?=$get_data['nobalb_bt']?>">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Fisik</label>
								<div class="col-sm-9">
									<select class="form-control input-sm" id="fisik" name="fisik" >
										<option>Pilih Fisik</option>
										<option value="1">ADA</option>
										<option value="0">Tidak Ada</option>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Validasi</label>
								<div class="col-sm-9">
									<select class="form-control input-sm" id="validasi" name="validasi" >
										<option>Pilih Validasi</option>
										<option value="1">ADA</option>
										<option value="0">Tidak Ada</option>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Posisi Page</label>
								<div class="col-sm-9">
									<input type="text" name="posisi_page" id="posisi_page" class="form-control input-sm">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Jenis Upload File</label>
								<div class="col-sm-9">
									<select class="form-control input-sm" name="jenis_upload" id="jenis_upload">
										<option>Pilih Jenis File</option>
										<!-- <option value="gambar">Gambar</option> -->
										<option value="pdf">PDF</option>
									</select>
								</div>
							</div>
							<div class="form-group row file-gambar">
								<div class="col-sm-3"></div>
								<div class="col-sm-9">
									<input type="file" accept="image/*" capture="camera" id="gambar" name="gambar" class="form-control input-sm"><br>
									<img id="frame" class="img-responsive">
								</div>
							</div>
							<div class="form-group row file-pdf">
								<div class="col-sm-3"></div>
								<div class="col-sm-9">
									<input type="file" name="pdf" id="pdf" class="form-control input-sm">
								</div>
							</div>
						</div>
						<div class='col-sm-6'>
							<div class="form-group row" style="display: <?=$display?>">
								<label class="col-sm-3"></label>
								<div class="col-sm-9" style='text-align:center'>
									<?php
											// $file_pdf_kosong = base_url().'digitalisasi/'.$this->session->userdata['view_data']['datax'].'#toolbar=0';
											$file_pdf_kosong = 'https://api.e-atrbpn.net/digitalisasi/'.str_replace(' ','_',strtoupper($get_file['nma_kec'])).'/'.str_replace(' ','_',strtoupper($get_file['nma_kel'])).'/BUKU_TANAH/BT_'.$get_data['nohakfile'].'.pdf';
									?>
									<?php
										?><a target='_blank' class='btn btn-primary' href='<?= $file_pdf_kosong; ?>'>Lihat Digitalisasi (fullscreen)</a>
									<object id='wide' frameborder="0" width="100%" height="750" data="<?=$file_pdf_kosong?>"></object>
									<object id='mobile' frameborder="0" width="100%" height="750" data="https://docs.google.com/viewer?url=<?=$file_pdf_kosong?>&embedded=true"></object>
								</div>
							</div>
						</div>
					<div class="box-footer">
						<div class="col-sm-12">
							<div class="pull-right">
								<button type="button" id="btn-batal" class="btn btn-warning">Batal</button>
								<button type="button" id="btn-simpan" class="btn btn-primary">Simpan</button>
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

<div class="modal fade" id="modal-camera">
<!-- <form id="form-tambah" method="POST" action=""> -->
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Camera</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div id="load_camera" style="margin-left: 25%"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<button type="button" id="btn_ambil_foto" class="btn btn-primary" data-dismiss="modal">Ambil Foto</button>
			</div>
		</div>
	</div>
			<!-- </form> -->
</div>

<script type="text/javascript">
	$(document).ready(function () {

		var camera = document.getElementById('gambar');
		var frame = document.getElementById('frame');

		camera.addEventListener('change', function(e) {
		    var file = e.target.files[0];
		    // Do something with the image file.
		    frame.src = URL.createObjectURL(file);
 		});

		$('#id_kelurahan').select2();
		$('#id_kecamatan').select2();
		$('#kode_hak').select2();
		$('#su_gs').select2();
		$('#fisik').select2();
		$('#validasi').select2();
		$('#jenis_upload').select2();
		$('.file-gambar').css({"display":"none"});
		$('.file-pdf').css({"display":"none"});

		<?=$id_kelurahan_edit?>
		<?=$fisik_edit?>
		<?=$validasi_edit?>

		/*Webcam.set({//penggunaan webcame
			width: 320,
			height: 240,
			image_format: 'jpeg',
			jpeg_quality: 90
		});
		Webcam.attach('#load_camera');

		$('#open_camera').click(function () {
			$('#modal-camera').modal('show');
			$('.modal-title').html('WebCame');
		});*/

		/*$('#foto').bind('change', function() {
			var file_size = this.files[0].size;
			if (file_size>2000000){
				swal ('Size terlalu besar');
				$('#foto').val('');
			} else {
				readURL(this);
			}
		});

		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
				$('#prevgambar').attr('src', e.target.result);
			}
				reader.readAsDataURL(input.files[0]);
			}
		};*/

		$('#jenis_upload').on('change',function () {
			var x = $(this).val();
			if(x === "gambar"){
				$('.file-gambar').css({"display":""});
				$('.file-pdf').css({"display":"none"});
				$('#url_camera').val();
			}else if(x === "pdf"){
				$('.file-gambar').css({"display":"none"});
				$('.file-pdf').css({"display":""});
			}
		})

		/*$('#btn_ambil_foto').on('click',function () {
			Webcam.snap( function(data_uri) {
				$('#url_camera').val(data_uri);
		  		document.getElementById('prevcamera').innerHTML =
		  		'<img id="cameraimg" src="'+data_uri+'"/>';
		  	});
		})*/

		$('#id_kelurahan').on('change',function () {
			var value = $('#id_kelurahan').val();
			$('#id_kelurahan_real').val(value);
		})

		$('#btn-batal').click(function () {
			window.close();
		})

		$('#btn-simpan').click(function() {
				$('#form-tambah').ajaxForm({
						success: 	function(response){
						if(response=='true'){
							swal("Success!", "Response Berhasil", "success");
							window.close();
						}else{
							swal("Error!", "Response Gagal", "error");
						}
					},
					error: function(){
						swal("Error!", "Response Gagal", "error");
					}
				}).submit();
			});

		/*document.addEventListener('keydown', function() {
		  if (event.keyCode == 123) {
		    alert("This function has been disabled to prevent you from stealing my code!");
		    return false;
		  } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
		    alert("This function has been disabled to prevent you from stealing my code!");
		    return false;
		  } else if (event.ctrlKey && event.keyCode == 85) {
		    alert("This function has been disabled to prevent you from stealing my code!");
		    return false;
		  }
		}, false);

		if (document.addEventListener) {
		  document.addEventListener('contextmenu', function(e) {
		    alert("This function has been disabled to prevent you from stealing my code!");
		    e.preventDefault();
		  }, false);
		} else {
		  document.attachEvent('oncontextmenu', function() {
		    alert("This function has been disabled to prevent you from stealing my code!");
		    window.event.returnValue = false;
		  });
		}*/

	})
  
</script>
