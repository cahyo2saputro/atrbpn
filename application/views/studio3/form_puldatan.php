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
              				<?php
							$i=1;
							if($puldatan){
								
								foreach($puldatan as $pd){
									if($i==1){
										?>
										<div id='penduduk' class='col-sm-12'>
											<h4>Nama-nama Puldatan</h4>
											<div class='form-group col-sm-8'>
											<input type='text' name='puldatan[]' class='form-control' value='<?= stripslashes($pd['nama_pul']);?>' placeholder='nama puldatan'>
											</div>
											<div class='form-group col-sm-4'>
												<a id='plusfile' class='btn btn-warning'>+</a>
											</div>
										</div>
										<?php
									}else{
										?>
										<div id='file<?=$i?>' class='col-sm-12'>
											<div class='form-group col-sm-8'>
											<input type='text' name='puldatan[]' class='form-control' value='<?= stripslashes($pd['nama_pul']);?>' placeholder='nama puldatan'>
											</div>
											<div class='form-group col-sm-4'>
												<a onclick='removefile("<?=$i?>")' class='btn btn-danger'>x</a>
											</div>
										</div>
										<?php
									}
									$i++;
								}	
							}else{
							?>
							<div id='penduduk' class='col-sm-12'>
								<h4>Nama-nama Puldatan</h4>
								<div class='form-group col-sm-8'>
								<input type='text' name='puldatan[]' class='form-control' value='' placeholder='nama puldatan'>
								</div>
								<div class='form-group col-sm-4'>
									<a id='plusfile' class='btn btn-warning'>+</a>
								</div>
							</div>
							<?php }
							?>
							<div class="row" id='beforethisfile'>
							</div>
							<input type='hidden' id='sumfile' value='<?=$i?>'>
							
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

		$('#plusfile').on('click',function () {
			var id = $('#sumfile').val();
			id = parseInt(id)+1;
			var text = "<div id='file"+id+"' class='col-sm-12'><div class='form-group col-sm-8'><input type='text' name='puldatan[]' class='form-control' placeholder='nama puldatan'></div><div class='form-group col-sm-4'><a onclick='removefile("+id+")' class='btn btn-danger'>x</a></div></div>";
			$(text).insertBefore($('#beforethisfile'));
			$('#sumfile').val(id);
		});		

	function removefile(id){
		$('#file'+id).remove();
	}
</script>
