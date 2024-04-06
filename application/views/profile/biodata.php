<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-3">
			        <div align="center" style="padding: 10px;"><?php
									if($biodata['foto_usr']!=''){
										if(file_exists(FCPATH."images/USER/".$biodata['foto_usr'])){
											$user_image = base_url("images/USER/".$biodata['foto_usr']);
										}else{
											$user_image = base_url("images/avatar.png");  
										}
									}else{
										$user_image = base_url("images/avatar.png");
									}
			            ?>
			            <img src="<?php echo $user_image; ?>" width="80%" alt="User Image" />
			        </div>
				</div>
				<div class="col-md-9">
				    <div class="box-body table-responsive">
    		        	<form action="<?php echo base_url();?>biodata/update" method="POST" enctype="multipart/form-data">
				        <div class="form-group">
				            <label>Username</label>
				            <input type="text" name="usrpw" class="form-control" data-validation-length="20"  placeholder="Username.." readonly="readonly"  data-validation-error-msg="Maximal panjang username 20 karakter" value="<?php echo $biodata['usrid_usr'];?>">
				        </div>
				        <div class="form-group">
				            <label>NIP</label>
				            <input type="text" name="nip" class="form-control" data-validation-length="20"  placeholder="Username.."  data-validation-error-msg="Maximal panjang username 20 karakter" value="<?php echo $biodata['nip_usr'];?>">
				        </div>
				        <div class="form-group">
				            <label>Nama Lengkap</label>
				            <input type="text" name="nama" class="form-control" data-validation-length="20"  placeholder="Username.."  data-validation-error-msg="Maximal panjang username 20 karakter" value="<?php echo $biodata['name_usr'];?>">
				        </div>
				        <div class="form-group">
				            <label>Hak Akses User</label>
				           	<input type="text" name="usrpw" class="form-control" data-validation-length="20"  placeholder="Username.." readonly="readonly" data-validation-error-msg="Maximal panjang username 20 karakter" value="<?php if($biodata['level_usr'] == '1'){echo "Admin";}else if($biodata['level_usr'] == '2'){echo "Satgas Administrasi";}?>">
				        </div>
								<div class="form-group">
				            <label>Foto</label>
				           	<input type="file" name="foto" class="form-control">
				        </div>
				        <div class="form-group">
	    							<button class="btn btn-primary">Update</button>
	    					</div>
				    </form>
				    </div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
