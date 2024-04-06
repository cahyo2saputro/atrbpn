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
defined('BASEPATH') OR exit('No direct script access allowed');
if($status=="edit"){
	$id_kelurahan_fake = $get_data['kd_full'];
	$id_kelurahan_edit = "$('#id_kelurahan').val('$id_kelurahan_fake').trigger('change');";
	/*$id_kecamatan_fake = $get_data['id_kecamatan'];
	$id_kecamatan_edit = "$('#id_kecamatan').val('$id_kecamatan_fake').trigger('change');";*/

	$fisik_fake = $get_data['fisik_gu'];
	$fisik_edit = "$('#fisik').val('$fisik_fake').trigger('change');";

	$id 		= $get_data['id_gu'];
	$display 	= "";
}else{
	$id_kelurahan_fake = $get_data['kd_full'];
	$id_kelurahan_edit = "$('#id_kelurahan').val('$id_kelurahan_fake').trigger('change');";
	/*$id_kecamatan_fake = $get_data['id_kecamatan'];
	$id_kecamatan_edit = "$('#id_kecamatan').val('$id_kecamatan_fake').trigger('change');";*/

	$fisik_edit			 		= "";

	$no_su 						= $get_data['no_su'];
	$thn_su						= $get_data['thn_su'];

	$id							= "";
	$get_data['thn_gu'] 		= "";
	$get_data['no_gu'] 			= "";
	$get_data['nosu1_gu'] 		= $no_su;
	$get_data['nosu2_gu']		= "";
	$get_data['thnsu_gu']		= $thn_su;
	$get_data['nolem_gu'] 		= "";
	$get_data['nobalb_gu'] 	= "";
	$get_data['norak_gu']			= "";
/*	$get_data['nib']			= "";*/
	$display 					= "none";
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

						<input type="hidden" name="id_detail_gu" id="id_detail_gu" value="<?=$id?>">
						<input type="hidden" name="id_kelurahan_real" id="id_kelurahan_real" value="<?=$id_kelurahan_fake?>">
						<!-- <input type="hidden" name="id_kecamatan_real" id="id_kecamatan_real" value="<?=$id_kecamatan_fake?>"> -->

						<!-- <div class="form-group row">
							<label class="col-sm-3">Daftar Kecamatan</label>
							<div class="col-sm-9">
								<select class="form-control input-sm" id="id_kecamatan" name="id_kecamatan" disabled>
									<option>Pilih Kecamatan</option>
									<?php
										foreach ($kecamatan as $kecamatan) {
											echo '<option value="'.$kecamatan->id_kecamatan.'">'.$kecamatan->id_kecamatan.'-'.$kecamatan->nama_kecamatan.'</option>';
										}
									 ?>
								</select>
							</div>
						</div> -->
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
								<label class="col-sm-3">Tahun GU</label>
								<div class="col-sm-9">
									<input type="text" name="thn_gu" id="thn_gu" class="form-control input-sm" value="<?=$get_data['thn_gu']?>">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Nomor GU</label>
								<div class="col-sm-9">
									<input type="text" name="no_gu" id="no_gu" class="form-control input-sm" value="<?=$get_data['no_gu']?>">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Nomor SU</label>
								<div class="col-sm-9">
									<div class="form-inline">
										<input type="text" name="no_su_1" id="no_su_1" class="form-control input-sm" value="<?=$get_data['nosu1_gu']?>">
										<input type="text" name="no_su_2" id="no_su_2" class="form-control input-sm" value="<?=$get_data['nosu2_gu']?>">
										<div class="input-group">
									      <div class="input-group-addon">Tahun</div>
									      <input type="text" class="form-control input-sm" id="thn_su" name="thn_su" value="<?=$get_data['thnsu_gu']?>">
									    </div>
									</div>
								</div>
							</div>

							<!-- <div class="form-group row">
								<label class="col-sm-3">NIB</label>
								<div class="col-sm-9">
									<input type="text" name="nib" id="nib" class="form-control input-sm" value="<?=$get_data['nib']?>">
								</div>
							</div> -->

							<div class="form-group row">
								<label class="col-sm-3">Nomor Lemari</label>
								<div class="col-sm-9">
									<input type="text" class="form-control input-sm" id="no_lemari" name="no_lemari" value="<?=$get_data['nolem_gu']?>">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Nomor Rak</label>
								<div class="col-sm-9">
									<input type="text" class="form-control input-sm" id="no_rak" name="no_rak" value="<?=$get_data['norak_gu']?>">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3">Nomor Buku Album</label>
								<div class="col-sm-9">
									<input type="text" class="form-control input-sm" id="no_buku_album" name="no_buku_album" value="<?=$get_data['nobalb_gu']?>">
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
										<option value="gambar">Gambar</option>
										<option value="camera">Camera</option>
									</select>
								</div>
							</div>

							<div class="form-group row file-gambar">
								<label class="col-sm-3">File Gambar</label>
								<div class="col-sm-9">
									<input id="foto" name="foto" class="form-control-file form-control-sm" placeholder="foto" type="file" accept="image/x-png,image/jpeg">
								</div>
							</div>

							<div class="form-group row file-gambar">
								<label class="col-sm-3"></label>
								<div class="col-sm-9">
									<img id="prevgambar" src="<?=base_url()?>images/NoImageFound.jpg.png" style="height: 200px; max-width: 340px; text-align: center;">
								</div>
							</div>

							<div class="form-group row file-camera">
								<label class="col-sm-3"></label>
								<div class="col-sm-9">
									<input type="text" hidden  name="url_camera" id="url_camera">
									<a class="btn btn-sm btn-primary" id="open_camera" style="color:#fff">Open Camera</a>
								</div>
							</div>

							<div class="form-group row file-camera">
								<label class="col-sm-3"></label>
								<div class="col-sm-9">
									<div id="prevcamera" style="height: 200px; max-width: 340px; margin-bottom: 10px;"></div>
								</div>
							</div>
						</div>
						<div class='col-sm-6'>
							<div class="form-group row" style="display: <?=$display?>">
								<label class="col-sm-3"></label>
								<div class="col-sm-9">
									<embed style="width: 100%;height: 300px" <?=$load_prev?>></embed>
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
		$('#id_kelurahan').select2();
		$('#id_kecamatan').select2();
		$('#fisik').select2();
		$('#jenis_upload').select2();
		$('.file-gambar').css({"display":"none"});
		$('.file-camera').css({"display":"none"});

		/*<?=$id_kecamatan_edit?>*/
		<?=$id_kelurahan_edit?>
		<?=$fisik_edit?>

		Webcam.set({//penggunaan webcame
			width: 320,
			height: 240,
			image_format: 'jpeg',
			jpeg_quality: 90
		});
		Webcam.attach('#load_camera');

		$('#open_camera').click(function () {
			$('#modal-camera').modal('show');
			$('.modal-title').html('WebCame');
		});

		$('#foto').bind('change', function() {
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
		};

		$('#jenis_upload').on('change',function () {
			var x = $(this).val();
			if(x === "gambar"){
				$('.file-gambar').css({"display":""});
				$('.file-camera').css({"display":"none"});
				$('#url_camera').val();
			}else if(x === "camera"){
				$('.file-gambar').css({"display":"none"});
				$('.file-camera').css({"display":""});
			}
		})

		$('#btn_ambil_foto').on('click',function () {
			Webcam.snap( function(data_uri) {
				$('#url_camera').val(data_uri);
		  		document.getElementById('prevcamera').innerHTML =
		  		'<img id="cameraimg" src="'+data_uri+'"/>';
		  	});
		})

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

	})
</script>
