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
                        <form id="form-tambah" method="post" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2);?>">
                            <div class='col-sm-12'>
															<div class='form-group row'>
																<div class='col-sm-4'>
                                    <div class='form-group'>
                                        <label>Target</label>
                                        <input type="hidden" name="kdfull" value="<?= $this->input->get('target'); ?>">
                                    </div>
                                </div>
                                <div class='col-sm-4'>
                                    <div class='form-group'>
                                        <label>Tahun</label>
                                    </div>
                                </div>
																<div class='col-sm-4'></div>
															</div>
															<?php
															$id=0;
															 foreach ($target as $tgt): $id++;?>
															 <div id='obj<?=$id?>' class='form-group row'>
																<div class='col-sm-4'>
                                    <div class='form-group'>
                                        <input type="text" name="target[]" id="target" class="form-control" value="<?= $tgt['target_tgt']; ?>">
                                    </div>
                                </div>
                                <div class='col-sm-4'>
                                    <div class='form-group'>
                                        <input type="text" name="tahun[]" id="tahun" class="form-control" value="<?= $tgt['tahun_tgt']; ?>">
                                    </div>
                                </div>
																<div class='col-sm-4'><a class='btn btn-danger' onclick='removechild(<?=$id?>)'>-</a></div>
															</div>
															<?php endforeach; ?>
															<input type='hidden' id='sumfile' value='<?=count($target)?>'>
																<div class="form-group row" id='beforethis'>
																	<div class='col-sm-8'>
																		<a id='plus' class='btn btn-warning'>+</a>
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
<script>

	function removechild(id){
		$('#obj'+id).remove();
	}

	$('#plus').on('click',function () {
		var id = $('#sumfile').val();
		id = parseInt(id)+1;
		// var name = $('#nameform').val();
		var text = "<div id='obj"+id+"' class='form-group row'><div class='col-sm-4'><div class='form-group'><input type='text' name='target[]' class='form-control'></div></div><div class='col-sm-4'><div class='form-group'><input type='text' name='tahun[]' class='form-control'></div></div><div class='col-sm-4'><a class='btn btn-danger' onclick='removechild("+id+")'>-</a></div></div><"
		$(text).insertBefore($('#beforethis'));
		// $('#sumfile').val(id);
	});
</script>
