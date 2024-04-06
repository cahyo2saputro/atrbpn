
<header class="main-header">
<!-- Logo -->

<?php
    $user = $this->auth_model->get_userdata();
     ?>
<a href="../../index2.html" class="logo">
  <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><b>EATRBPN</b></span>
  <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><b>e-ATRBPN</b> Kab.Semarang</span>
</a>
<!-- Header Navbar: style can be found in header.less -->

<nav class="navbar navbar-static-top">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </a>

  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <li>
        <a id='errorbutton' href="#" class='btn btn-info'>Aduan  Error/Keluhan Aplikasi di sini</a>
      </li>
      <!-- Messages: style can be found in dropdown.less-->
      <?php if($user['level_usr']==1){ ?>
       <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <?php
              $notif['table'] = "tb_hakppat";
              $notif['type'] = "multiple";
              $notif['join']['table']  = "ms_users";
        			$notif['join']['key']    = "idusr_usr";
        			$notif['join']['ref']    = "idppat_hpat";
              $notif['condition']['open_hpat'] = '0';
              $dnotif = $this->crud_model->get_data($notif);

              if($dnotif){
                ?><span class="label label-warning"><?=count($dnotif)?></span><?php
              }
              ?>
            </a>
              <?php
              if($dnotif){
                ?><ul class="dropdown-menu">
                  <li class="header">Ada <?=count($dnotif)?> pengajuan </li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu">
                    <?php
                    foreach ($dnotif as $dd) {
                      ?>
                      <li>
                        <a href="<?= base_url()?>Studioppat/detail/<?=$dd['id_hpat']?>">
                          <?= $dd['name_usr'].' -> '.$dd['nohak_hpat']?>
                        </a>
                      </li>
                      <?php
                    }
                    ?>
                  </ul>
                <!-- </li>
                <li class="footer"><a href="#">View all</a></li> -->
              </ul>
            </li><?php
              }
              ?>
       <?php } ?>
       <!-- Tasks: style can be found in dropdown.less -->

      <!-- User Account: style can be found in dropdown.less -->
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
           <?php
             /* if($user['photo_emp']==NULL){*/
                  $user_image = base_url("images/avatar.png");
             /* }*/
            /*  else{
                  $user_image = base_url("images/".$user['photo_emp']);
              } */
          ?>

          <span class="">
            <?php echo $user['name_usr'] ?>
          </span>
        </a>
        <ul class="dropdown-menu">
          <!-- User image -->
          <li class="user-header">
            <img src="<?php echo $user_image;?>" class="img-circle" alt="User Image">

            <p>

              <?php echo $user['name_usr'] ?>
              <small><?php /*echo get_reference("LEVEL",$user['level_usr']);*/ if($user['level_usr'] == '1'){ echo "Admin"; }else if($user['level_usr'] == '2'){ echo "Satgas Administrasi"; } ?></small>
            </p>
          </li>
          <!-- Menu Body -->
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
              <a href="<?php echo base_url('biodata'); ?>" class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
              <a href="<?php echo base_url('auth/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
            </div>
          </li>
        </ul>
      </li>
      <!-- Control Sidebar Toggle Button -->
      <li>
        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
      </li>
    </ul>
  </div>
  <?php if ($user = 2 OR $user = 1) { ?>
  <div align="center">
  <span><?php //echo $echo; ?></span></a> </div>
  <?php } ?>
</nav>
</header>
<div id="modal-error" class="modal fade" role="dialog">
	<form id="form-error" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-kkp">Form Aduan Error atau keluhan</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">
          <div class="form-group row">
              <label class="col-form-label col-md-3 label-align" for="stp">Upload Screenshoot Keluhan</label>
              <div class="col-md-4">
                  <div style='margin:10px;border:1px solid #dadada;padding:5px' class="demo demo-contenteditable" contenteditable>klik di sini untuk paste screenshoot keluhan</div>
                  <input type="file" id="scr" name='scr'>
                  <div id='link'></div>
                  <input type='hidden' id='idimage'>
              </div>
          </div>
          <div class="form-group row">
						<label class="col-sm-3">Keluhan</label>
						<div class="col-sm-9">
							<textarea class="form-control input-sm" id='keluhan' name="keluhan" placeholder='keluhan/error menggunakan aplikasi'></textarea>
						</div>
					</div>
				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-error">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
    $('#errorbutton').click(function(){
			$('#modal-error').modal('show');
		});

    $(document).on('change', '#scr', function(){
			var name = this.files[0].name;
			
			var form_data = new FormData();
			var ext = name.split('.').pop().toLowerCase();

			if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
			{
				alert("Invalid Image File");
			}
			// alert(name);

			var oFReader = new FileReader();
			oFReader.readAsDataURL(this.files[0]);
			var f = this.files[0];
			var fsize = f.size||f.fileSize;
			// alert(bag.html());
			if(fsize > 3000000)
			{
				alert("Image File Size is very big");
			}else{
				form_data.append("file", this.files[0],this.files[0].name);
        form_data.append('id', $('#idimage').val());
				$.ajax({
					url:'<?php echo base_url();?>ajax/uploadimage',
					method:"POST",
          dataType: 'json',
					data: form_data,
          contentType: false,
          cache: false,
          processData: false,
					success:function(data)
					{
						$('#link').html("<a class='btn btn-primary' target='_blank' href='<?php echo base_url();?>/screenshoot/"+data.filename+"'>lihat gambar</a>");
            $('#idimage').val(data.id);
					}
				});
			}
		});

    document.addEventListener('DOMContentLoaded', () => {
        document.onpaste = function(event){
            const file = getFileFromPasteEvent(event);
            if (!file) { return; }
            uploadImage(file);
            $('#embedfile').hide();
        }
    });

    function getFileFromPasteEvent(event) {
        const items = (event.clipboardData || event.originalEvent.clipboardData).items;
        for (let index in items) {
            const item = items[index];
            if (item.kind === 'file') {
              return item.getAsFile();
            }
        }
    }

    function uploadImage(file) {
            var formData = new FormData();
            formData.append('file', file, file.name);
            formData.append('id', $('#idimage').val());
            $.ajax({
              url:'<?php echo base_url();?>ajax/uploadimage',
              method:"POST",
              data: formData,
              dataType: 'json',
              contentType: false,
              cache: false,
              processData: false,
							success: 	function(response){
						    $('#link').html("<a class='btn btn-primary' target='_blank' href='<?php echo base_url();?>/screenshoot/"+response.filename+"'>lihat gambar</a>");
                $('#idimage').val(response.id);
							}
						});
        }

    $('#btn-simpan-error').click(function(){
      var formData = new FormData();
      formData.append('keluhan', $('#keluhan').val());
      formData.append('id', $('#idimage').val());
      $.ajax({
        url:'<?php echo base_url();?>ajax/updatekeluhan',
        method:"POST",
        data: formData,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        success: 	function(response){
          if(response){
              alert('Data berhasil disimpan, kami akan segera memproses keluhan bapak/ibu');
              $('#link').html("");
              $('#idimage').val("");
              $('#keluhan').val("");
              $('.result').html("");
          }
          $('#modal-error').modal('hide');
        }
      });
    });
</script>