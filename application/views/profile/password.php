<div class="box box-primary">
    <div class='row'>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class='box-body'>
    				<?php echo $messages."<font color='red'>".validation_errors()."</font>";?>
    		        <form action="" method="POST">
    				<div class="form-group">
    					<label>Password Lama</label>
    					<input type="password" name="old" placeholder="Tulis Password Lama Anda..." class="form-control password">
    				</div>
    				<div class="form-group">
    					<label>Password Baru</label>
    					<input type="password" name="new" placeholder="Tulis Password Baru Anda..." class="form-control password">
    				</div>
    				<div class="form-group">
    					<label>Ulangi Password Baru</label>
    					<input type="password" name="retype" placeholder="Ulangi Password Baru Anda..." class="form-control password">
    				</div>
    				<div class="form-group">
    					<button class="btn btn-primary">Simpan</button>
    				</div>
    			   </form>
                    <script type="text/javascript">
                    	$('.password').hidePassword(true);
                    </script>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box-body -->
    </div><!-- /.box-body -->
    </div><!-- /.box-body -->
</div><!-- /.row -->



<script type="text/javascript" src="<?php echo base_url("assets/javascript/hideShowPassword.min.js");?>"></script>