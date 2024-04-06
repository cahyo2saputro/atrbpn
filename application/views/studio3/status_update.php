<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="form-group row file-nohak">
						<label class="col-sm-12">Status Update Sudah Sertipikat</label>
						<label class="col-sm-3">NIB</label>
						<label class="col-sm-3">Nomor Hak</label>
						<label class="col-sm-3">NOP</label>
						<label class="col-sm-3">STATUS</label>

						<?php
						foreach ($status as $dd) {
							?>
							<label class="col-sm-3"><?=$dd['nib']?></label>
							<label class="col-sm-3"><?=$dd['nohak']?></label>
							<label class="col-sm-3"><?=$dd['nop']?></label>
							<label class="col-sm-3"><?=$dd['status']?></label>
							<?php
						} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
