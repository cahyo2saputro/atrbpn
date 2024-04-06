<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                        <form enctype="multipart/form-data" id="form-tambah" method="post" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>">
														<div class='col-sm-12'>
															<div class='form-group'>
																	<label>Panduan upload file <a href='<?=$file;?>'>klik disini</a></label>
															</div>
														</div>
														<div class='col-sm-12'>
                                <div class='col-sm-6'>
                                    <div class='form-group'>
                                        <label>File Import</label>
                                        <input type="file" name="import"  class="form-control">
																				<input type="hidden" name="data"  class="form-control" value='1'>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <input type="submit" value="Submit" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
