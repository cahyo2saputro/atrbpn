<?php
$mod = $this->uri->segment(1);
$dua = $this->uri->segment(2);
$tiga = $this->uri->segment(3);

?>
<style type="text/css">
.inputfile {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}
.blur a span{
  color:#555;
}
</style>
<?php
$user = $this->auth_model->get_userdata();

if($user['foto_usr']!=''){
  if(file_exists(FCPATH."images/USER/".$user['foto_usr'])){
    $user_image = base_url("images/USER/".$user['foto_usr']);
  }else{
    $user_image = base_url("images/avatar.png");  
  }
}else{
  $user_image = base_url("images/avatar.png");
}

  // $arrayakses = array();
  $id = $user['idusr_usr'];
  ?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $user_image; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $user['name_usr']; ?></p>
          <small><?php /*echo get_reference("LEVEL",$user['level_usr']);*/ if($user['level_usr'] == '1'){ echo "Admin"; }else if($user['level_usr'] == '2'){ echo "Satgas"; }else{ echo "BP2KAD";} ?></small><br>
          <a href="<?php echo base_url('Home'); ?>"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
            <?php if($user['level_usr']==1){ ?>
            <li <?php if($mod=="Home"){ echo "class='active'"; } ?>>
              <a href="<?php echo base_url('Home'); ?>"><i class="fa fa-home"></i><span>Dasboard</span></a>
            </li>
            <li <?php if($mod=="Home"){ echo "class='active'"; } ?>>
              <a href="<?php echo base_url('Analysist'); ?>/index/1"><i class="fa fa-home"></i><span>Analisis Data</span></a>
            </li>
          <?php }?>
          <?php if($user['level_usr'] == 1){ ?>
            <li class="treeview <?php if($mod=="Biodata" OR $mod=="Password" OR ($mod=="Master" && $dua=="foto")){ echo "active"; } ?>">
              <a href="#">
                <i class="fa fa-user"></i> <span>Profile</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li <?php if($mod=="Biodata"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('Biodata'); ?>"><i class="fa fa-circle-o"></i> Biodata</a></li>
                <li <?php if($mod=="Password"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('Password'); ?>"><i class="fa fa-circle-o"></i> Password</a></li>
               <!--  <li <?php if($mod=="Master" && $dua=="foto"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('Master/foto/edit/'.$id); ?>"><i class="fa fa-circle-o"></i> Foto</a></li> -->
              </ul>
            </li>
            <li class="treeview <?php if($mod=="User" OR $mod=="Kelurahan" OR $mod=="Kecamatan" OR ($mod=="Master" AND ($dua=="kecamatan" OR $dua=="Administrasi")) OR $mod=="Templateuser"){ echo "active"; }?>">
              <a href="#">
                <i class="fa fa-cubes"></i><span>Master</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li <?php if($mod == "User"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('User') ?>"><i class="fa fa-circle-o"></i> User</a>
                </li>
                <li <?php if($mod == "Templateuser"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('Templateuser') ?>"><i class="fa fa-circle-o"></i> Template User</a>
                </li>
                <li <?php if($mod == "Master" && $dua == "Kecamatan"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('Master/kecamatan') ?>"><i class="fa fa-circle-o"></i> Kecamatan</a>
                </li>
                <li <?php if($mod == "Kelurahan"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('Kelurahan') ?>"><i class="fa fa-circle-o"></i> Kelurahan</a>
                </li>
                <li <?php if($mod == "Dusun"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('Dusun') ?>"><i class="fa fa-circle-o"></i> Dusun</a>
                </li>
                <li <?php if($mod == "Datapenduduk"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('Datapenduduk') ?>"><i class="fa fa-circle-o"></i> Penduduk</a>
                </li>
                <li <?php if($mod == "Master" && $dua == "administrasi"){ echo "class='active'"; } ?>>
                    <a href="<?php echo base_url('Master/Administrasi') ?>"><i class="fa fa-circle-o"></i> Kategori Administrasi</a>
                </li>

              </ul>
            </li>
            <!--  BELUM TERDAFTAR -->
            <li class="treeview <?php if($mod=="Studio2"){ echo "active"; }?>">
              <a href="#">
                <i class="fa fa-database" aria-hidden="true"></i><span>e-Walidata</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class=" <?php if($mod=="Studio2"){ echo "active"; } ?>">
                  <a href="<?php echo base_url('Studio2') ?>">
                    <i class="fa fa-circle-o"></i><span>e-Data BPN</span>
                  </a>
                </li>
                <li class=" <?php if($mod=="Studio2"){ echo "active"; } ?>">
                  <a href="<?php echo base_url('Studio2/bppkad') ?>">
                    <i class="fa fa-circle-o"></i><span>e-Data BPPKAD</span>
                  </a>
                </li>
                <!--  BELUM TERDAFTAR -->
                <li class="blur <?php if($mod=="Studio2"){ echo "active"; } ?>">
                  <a href="#">
                    <i class="fa fa-circle-o"></i><span>e-Data PUPR</span>
                  </a>
                </li>
                <!--  BELUM TERDAFTAR -->
                <li class="blur <?php if($mod=="Studio2"){ echo "active"; } ?>">
                  <a href="#">
                    <i class="fa fa-circle-o"></i><span>e-Data Desa</span>
                  </a>
                </li>
              </ul>
            </li>
          <!--  BELUM TERDAFTAR -->
          <li class="treeview <?php if($mod=="Studio1" || $mod=="Permohonanukur" || ($mod=="Publics" && ($dua!='dashboardnop' && $dua!='dashboardpermohonan' && $dua!='validasisertipikat' && $dua!='permohonandesa')) || $mod=="Studio4" || $mod=="Studiotataruang" || $mod=="Studio_5_2" || $mod=="Studio_5_1"){ echo "active"; }?>">
            <a href="#">
              <i class="fa fa-university" aria-hidden="true"></i><span>e-BPN</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
          <ul class="treeview-menu">
            <li class=" <?php if($mod=="Publics"){ echo "active"; } ?>">
              <a href="<?php echo base_url('Publics') ?>">
                <i class="fa fa-circle-o"></i><span>Daftar User</span>
              </a>
            </li>
            <li class=" <?php if($mod=="Studio1" && $dua==""){ echo "active"; } ?>">
              <a href="<?php echo base_url('Studio1') ?>">
                <i class="fa fa-cloud-upload" aria-hidden="true"></i><span>e-Digitalisasi</span>
              </a>
            </li>
            <li class=" <?php if($mod=="Studio1" && $dua=="validation"){ echo "active"; } ?>">
              <a href="<?php echo base_url('Studio1/validation') ?>">
                <i class="fa fa-check-square-o" aria-hidden="true"></i><span>e-Validasi</span>
              </a>
            </li>
            <!--  BELUM TERDAFTAR -->
            <li class="<?php if($mod=="Permohonanukur"){ echo "active"; } ?>">
              <a href="<?php echo base_url('Permohonanukur') ?>">
                <i class="fa fa-circle-o"></i><span>e-Permohonan Ukur</span>
              </a>
            </li>
            <li class=" <?php if($mod=="Studio4"){ echo "active"; } ?>">
              <a href="<?php echo base_url('Studio4') ?>">
                <i class="fa fa-envelope" aria-hidden="true"></i><span>e-TU</span>
              </a>
            </li>
            <li class=" <?php if($mod=="Studiotataruang"){ echo "active"; } ?>">
              <a href="<?php echo base_url('Studiotataruang') ?>">
                <i class="fa fa-area-chart" aria-hidden="true"></i><span>e-Tataruang</span>
              </a>
            </li>
          </ul>
        </li>
          <!--  BELUM TERDAFTAR -->
        <li class="treeview <?php if($mod=="Studio2"){ echo "active"; }?>">
          <a href="#">
            <i class="fa fa-lock" aria-hidden="true"></i><span>e-BPPKAD</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <!--  BELUM TERDAFTAR -->
            <li class="blur <?php if($mod=="Studio2"){ echo "active"; } ?>">
              <a href="#">
                <i class="fa fa-circle-o"></i><span>e-Daftar Tanah</span>
              </a>
            </li>
            <!--  BELUM TERDAFTAR -->
            <li class="blur <?php if($mod=="Studio2"){ echo "active"; } ?>">
              <a href="#">
                <i class="fa fa-circle-o"></i><span>e-Peta PTSL</span>
              </a>
            </li>
            <!--  BELUM TERDAFTAR -->
            <li class="blur <?php if($mod=="Studio2"){ echo "active"; } ?>">
              <a href="#">
                <i class="fa fa-circle-o"></i><span>e-PBB</span>
              </a>
            </li>
          </ul>
          <!--  BELUM TERDAFTAR -->
        <li class="treeview <?php if($mod=="Studio2"){ echo "active"; }?>">
          <a href="#">
            <i class="fa fa-lock" aria-hidden="true"></i><span>e-PUPR</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <!--  BELUM TERDAFTAR -->
            <li class="blur <?php if($mod=="Studio2"){ echo "active"; } ?>">
              <a href="#">
                <i class="fa fa-circle-o"></i><span>e-Peruntukan Tanah</span>
              </a>
            </li>
            <!--  BELUM TERDAFTAR -->
            <li class="blur <?php if($mod=="Studio2"){ echo "active"; } ?>">
              <a href="">
                <i class="fa fa-circle-o"></i><span>e-Penggunaan Tanah</span>
              </a>
            </li>
            <!--  BELUM TERDAFTAR -->
            <li class="blur <?php if($mod=="Studio2"){ echo "active"; } ?>">
              <a href="#">
                <i class="fa fa-circle-o"></i><span>e-RDTR</span>
              </a>
            </li>
          </ul>
          <li class="treeview <?php if(($mod=="Publics" AND ($dua=="dashboardnop" OR $dua=="dashboardpermohonan" OR $dua=="validasisertipikat" OR $dua=="permohonandesa")) OR strtolower($mod)=="studioip4t" OR strtolower($mod)=="studioip4t_1" OR strtolower($mod)=="studioip4t_2"){ echo "active"; }?>">
            <a href="#">
              <i class="fa fa-file" aria-hidden="true"></i><span>e-Desa</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="<?php if($mod=="Studioip4t"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studioip4t') ?>">
                  <i class="fa fa-circle-o"></i><span>IP4T</span>
                </a>
              </li>
              <!--  BELUM TERDAFTAR -->
              <li class="blur <?php if($mod=="Studio3" || $mod=="studio_3_1" || $mod=="studio_3_2"){ echo "active"; } ?>">
                <a href="#">
                  <i class="fa fa-circle-o"></i><span>e-Peta Desa</span>
                </a>
              </li>
              <!--  BELUM TERDAFTAR -->
              <li class="blur <?php if($mod=="Studio3" || $mod=="studio_3_1" || $mod=="studio_3_2"){ echo "active"; } ?>">
                <a href="#">
                  <i class="fa fa-circle-o"></i><span>e-Administrasi Desa</span>
                </a>
              </li>
              <!--  BELUM TERDAFTAR -->
              <li class="blur <?php if($mod=="Studio3" || $mod=="studio_3_1" || $mod=="studio_3_2"){ echo "active"; } ?>">
                <a href="#">
                  <i class="fa fa-circle-o"></i><span>e-Potensi Desa</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Publics" && $dua=="dashboardnop"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Publics/dashboardnop') ?>">
                  <i class="fa fa-circle-o"></i><span>Dashboard Pengajuan</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Publics" && $dua=="dashboardpermohonan"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Publics/dashboardpermohonan') ?>">
                  <i class="fa fa-circle-o"></i><span>Dashboard Belum Sertipikat</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Publics" && ($dua=="permohonandesa" OR $dua=="validasisertipikat")){ echo "active"; } ?>">
                <a href="<?php echo base_url('Publics/permohonandesa') ?>">
                  <i class="fa fa-circle-o"></i><span>Layanan Pengukuran</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="treeview <?php if($mod=="Studio3" OR $mod=="Studio5" OR $mod=="studio_3_2" OR $mod=="studio_3_1" OR $mod=="studio_5_1" OR $mod=="studio_5_2" OR $mod=="Studio6" OR $mod=="studio_6_2" OR $mod=="studio_6_1" OR $mod=="Studio7" OR $mod=="studio_7_2" OR $mod=="studio_7_1"){ echo "active"; }?>">
            <a href="#">
              <i class="fa fa-file" aria-hidden="true"></i><span>e-PTSL</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=" <?php if($mod=="Studio3" && $dua=='dashboard'){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio3/grafik') ?>">
                  <i class="fa fa-circle-o"></i><span>Grafik PTSL</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Studio3" && $dua=='dashboard'){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio3/dashboard') ?>">
                  <i class="fa fa-circle-o"></i><span>Dashboard PTSL Desa</span>
                </a>
              </li>
              <li class=" <?php if(($mod=="Studio3" || $mod=="studio_3_1" || $mod=="studio_3_2") AND $dua !="dashboard"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio3') ?>">
                  <i class="fa fa-circle-o"></i><span>e-Panitia Desa</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Studio5" AND $dua=="dashboard"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio5/dashboard') ?>">
                  <i class="fa fa-circle-o"></i><span>Dashboard e-Pengukuran</span>
                </a>
              </li>
              <li class=" <?php if(($mod=="Studio5" || $mod=="studio_5_1" || $mod=="studio_5_2") AND $dua !="dashboard"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio5') ?>">
                  <i class="fa fa-circle-o"></i><span>e-Pengukuran</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Studio6" AND $dua=="dashboard"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio6/dashboard') ?>">
                  <i class="fa fa-circle-o"></i><span>Dashboard e-Pemetaan</span>
                </a>
              </li>
              <li class=" <?php if(($mod=="Studio6" || $mod=="studio_6_1" || $mod=="studio_6_2") AND $dua !="dashboard"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio6') ?>">
                  <i class="fa fa-circle-o"></i><span>e-Pemetaan</span>
                </a>
              </li>
              <li class=" <?php if(($mod=="Studio7" || $mod=="studio_7_1" || $mod=="studio_7_2") AND $dua !="dashboard"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio7') ?>">
                  <i class="fa fa-circle-o"></i><span>e-Yuridis</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="treeview <?php if($mod=="egtra"){ echo "active"; }?>">
            <a href="#">
              <i class="fa fa-lock" aria-hidden="true"></i><span>e-GTRA</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=" <?php if($mod=="Studioppat" && $dua=="user"){ echo "active"; } ?>">
                <a href="<?php echo base_url('egtra') ?>">
                  <i class="fa fa-circle-o"></i><span>e-Aset Reform</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Studioppat"){ echo "active"; } ?>">
                <a href="<?php echo base_url() ?>">
                  <i class="fa fa-circle-o"></i><span>e-Akses Reform</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="treeview <?php if($mod=="Plotting"){ echo "active"; }?>">
            <a href="#">
              <i class="fa fa-file" aria-hidden="true"></i><span>Plotting Online</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=" <?php if($mod=="Plotting" && $dua=='bt'){ echo "active"; } ?>">
                <a href="<?php echo base_url('Plotting/bt') ?>">
                  <i class="fa fa-circle-o"></i><span>Pengecekan Berkas BT</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Plotting" AND $dua =="su"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Plotting/su') ?>">
                  <i class="fa fa-circle-o"></i><span>Pengecekan Berkas SU</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="treeview <?php if($mod=="Studioppat" OR $mod=="Reportppat" OR ($mod=="Master" AND $dua=="term")){ echo "active"; }?>">
            <a href="#">
              <i class="fa fa-user-o" aria-hidden="true"></i><span>e-PPAT</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=" <?php if($mod=="Studioppat" && $dua=="user"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studioppat/user') ?>">
                  <i class="fa fa-circle-o"></i><span>PPAT</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Studioppat"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studioppat') ?>">
                  <i class="fa fa-circle-o"></i><span>Daftar Pengecekan</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Master" && $dua=="term"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Master/term') ?>">
                  <i class="fa fa-circle-o"></i><span>Kebijakan Pengecekan</span>
                </a>
              </li>
              <li class=" <?php if($mod=="Reportppat"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Reportppat') ?>">
                  <i class="fa fa-circle-o"></i><span>Laporan PPAT</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="treeview <?php if($mod=="Studioppat" OR $mod=="Reportppat" OR ($mod=="Master" AND $dua=="term")){ echo "active"; }?>">
            <a href="#">
              <i class="fa fa-user-o" aria-hidden="true"></i><span>Logs</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=" <?php if($mod=="systemlog"){ echo "active"; } ?>">
                <a href="<?php echo base_url('systemlog') ?>">
                  <i class="fa fa-circle-o"></i><span>Semua Logs</span>
                </a>
              </li>
              <li class=" <?php if($mod=="systemlog"){ echo "active"; } ?>">
                <a href="<?php echo base_url('systemlog/dashboard') ?>">
                  <i class="fa fa-circle-o"></i><span>Dashboard Logs</span>
                </a>
              </li>
            </ul>
          </li>
          <li class=" <?php if($mod=="Analysist"){ echo "active"; } ?>">
            <a href="<?php echo base_url('Analysist/aduan') ?>">
              <i class="fa fa-circle-o"></i><span>Aduan/Keluhan</span>
            </a>
          </li>
        <?php }else if($user['level_usr']==2){

          $menu['type'] = "multiple";
          $menu['table'] = "tb_userrole";
          $menu['condition']['idusr_role'] = $user['idusr_usr'];

          $datmenu = $this->crud_model->get_data($menu);

          foreach($datmenu as $dd){
            // array_push($arrayakses,$dd['idmenu_role']);
            if($dd['idmenu_role']==24){
              ?>
              <li class=" <?php if($mod=="Studio3" && $dua=='dashboard'){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio3/dashboard') ?>">
                  <i class="fa fa-database"></i><span>Dashboard</span>
                </a>
              </li>
              <?php
            }
          }
          ?>
          <li class="treeview <?php if($mod=="Biodata" OR $mod=="Password" OR ($mod=="Master" && $dua=="foto")){ echo "active"; } ?>">
            <a href="#">
              <i class="fa fa-user"></i> <span>Profile</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li <?php if($mod=="Biodata"){ echo "class='active'"; } ?>>
                  <a href="<?php echo base_url('Biodata'); ?>"><i class="fa fa-circle-o"></i> Biodata</a></li>
              <li <?php if($mod=="Password"){ echo "class='active'"; } ?>>
                  <a href="<?php echo base_url('Password'); ?>"><i class="fa fa-circle-o"></i> Password</a></li>
             <!--  <li <?php if($mod=="Master" && $dua=="foto"){ echo "class='active'"; } ?>>
                  <a href="<?php echo base_url('Master/foto/edit/'.$id); ?>"><i class="fa fa-circle-o"></i> Foto</a></li> -->
            </ul>
          </li>
          <?php
          foreach($datmenu as $dd){
            if($dd['idmenu_role']==13){
              ?>
              <li class=" <?php if($mod=="Studio2"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio2') ?>">
                  <i class="fa fa-database"></i><span>e-Data</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==90){
              ?>
              <li class=" <?php if($mod=="Studio2"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio2/bppkad') ?>">
                  <i class="fa fa-circle-o"></i><span>e-Data BPPKAD</span>
                </a>
              </li>
              <?php
            }


            if($dd['idmenu_role']==1){
              ?>
              <li class=" <?php if($mod=="Studio1"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio1') ?>">
                  <i class="fa fa-database"></i><span>e-Digitalisasi</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==38){
              ?>
              <li class=" <?php if($mod=="Studio1"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio1/validation') ?>">
                  <i class="fa fa-check-square-o" aria-hidden="true"></i><span>e-Validasi</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==61){
              ?>
              <li class=" <?php if($mod=="Studio5"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio5/dashboard') ?>">
                  <i class="fa fa-map-marker" aria-hidden="true"></i><span>Dashboard e-Pengukuran</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==42){
              ?>
              <li class=" <?php if($mod=="Studio5"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio5') ?>">
                  <i class="fa fa-map-marker" aria-hidden="true"></i><span>e-Pengukuran</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==62){
              ?>
              <li class=" <?php if($mod=="Studio6"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio6') ?>">
                  <i class="fa fa-map-marker" aria-hidden="true"></i><span>e-Pemetaan</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==74){
              ?>
              <li class=" <?php if($mod=="Studio7"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio7') ?>">
                  <i class="fa fa-map-marker" aria-hidden="true"></i><span>e-Yuridis</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==24){
              ?>
              <li class=" <?php if($mod=="Studio3" && $dua=='dashboard'){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio3/dashboard') ?>">
                  <i class="fa fa-file" aria-hidden="true"></i><span>Dashboard PTSL Desa</span>
                </a>
              </li>
              <?php
            }
            if($dd['idmenu_role']==25){
              ?>
              <li class=" <?php if($mod=="Studio3" || $mod=="studio_3_1" || $mod=="studio_3_2"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio3') ?>">
                  <i class="fa fa-file" aria-hidden="true"></i><span>e-Panitia Desa</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==130){
              ?>
              <li class=" <?php if($mod=="egtra"){ echo "active"; } ?>">
                <a href="<?php echo base_url('egtra') ?>">
                  <i class="fa fa-lock" aria-hidden="true"></i><span>e-GTRA</span>
                </a>
              </li>
              <?php
            }
            if($dd['idmenu_role']==40){
              ?>
              <li class=" <?php if($mod=="Studio4"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studio4') ?>">
                  <i class="fa fa-envelope" aria-hidden="true"></i><span>e-TU</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==55){
              ?>
              <li class=" <?php if($mod=="Studioppat"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studioppat/user') ?>">
                  <i class="fa fa-database"></i><span>e-PPAT (User)</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==56){
              ?>
              <li class=" <?php if($mod=="Studioppat"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studioppat') ?>">
                  <i class="fa fa-database"></i><span>e-PPAT (Pengecekan Hak)</span>
                </a>
              </li>
              <?php
            }

            if($dd['idmenu_role']==57){
              ?>
              <li class=" <?php if($mod=="Studiotataruang"){ echo "active"; } ?>">
                <a href="<?php echo base_url('Studiotataruang') ?>">
                  <i class="fa fa-area-chart" aria-hidden="true"></i><span>e-Tataruang</span>
                </a>
              </li>
              <?php
            }

          }

         }else if($user['level_usr']==3){ ?>
           <li class="treeview <?php if($mod=="Biodata" OR $mod=="Password" OR ($mod=="Master" && $dua=="foto")){ echo "active"; } ?>">
             <a href="#">
               <i class="fa fa-user"></i> <span>Profile</span>
               <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
               </span>
             </a>
             <ul class="treeview-menu">
               <li <?php if($mod=="Biodata"){ echo "class='active'"; } ?>>
                   <a href="<?php echo base_url('Biodata'); ?>"><i class="fa fa-circle-o"></i> Biodata</a></li>
               <li <?php if($mod=="Password"){ echo "class='active'"; } ?>>
                   <a href="<?php echo base_url('Password'); ?>"><i class="fa fa-circle-o"></i> Password</a></li>
              <!--  <li <?php if($mod=="Master" && $dua=="foto"){ echo "class='active'"; } ?>>
                   <a href="<?php echo base_url('Master/foto/edit/'.$id); ?>"><i class="fa fa-circle-o"></i> Foto</a></li> -->
             </ul>
           </li>
          <li class=" <?php if($mod=="Studio2"){ echo "active"; } ?>">
            <a href="<?php echo base_url('Studio2') ?>">
              <i class="fa fa-database"></i><span>e-Data</span>
            </a>
          </li>
        <?php }else if($user['level_usr']==4){ ?>
          <li class="treeview <?php if($mod=="Biodata" OR $mod=="Password" OR ($mod=="Master" && $dua=="foto")){ echo "active"; } ?>">
            <a href="#">
              <i class="fa fa-user"></i> <span>Profile</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li <?php if($mod=="Biodata"){ echo "class='active'"; } ?>>
                  <a href="<?php echo base_url('Biodata'); ?>"><i class="fa fa-circle-o"></i> Biodata</a></li>
              <li <?php if($mod=="Password"){ echo "class='active'"; } ?>>
                  <a href="<?php echo base_url('Password'); ?>"><i class="fa fa-circle-o"></i> Password</a></li>
             <!--  <li <?php if($mod=="Master" && $dua=="foto"){ echo "class='active'"; } ?>>
                  <a href="<?php echo base_url('Master/foto/edit/'.$id); ?>"><i class="fa fa-circle-o"></i> Foto</a></li> -->
            </ul>
          </li>
          <li class=" <?php if($mod=="Reportppat"){ echo "active"; } ?>">
            <a href="<?php echo base_url('Reportppat') ?>">
              <i class="fa fa-circle-o"></i><span>Laporan PPAT</span>
            </a>
          </li>
       <?php }else if($user['level_usr']==7){ ?>
         <li class=" <?php if($mod=="Publics"){ echo "active"; } ?>">
           <a href="<?php echo base_url('Publics/permohonandesa') ?>">
             <i class="fa fa-circle-o"></i><span>Layanan Ukur</span>
           </a>
         </li>
      <?php }
        // $this->session->set_userdata('menu',$arrayakses);
        ?>
      </ul>

    </section>
  </aside>
