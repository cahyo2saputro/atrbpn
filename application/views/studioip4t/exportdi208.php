<?php
    @$namafile="DI208 Kelurahan ".$kecamatan['nma_kel']." Block ".$kecamatan['nama_blk'];
    header("Content-type:application/vnd.ms-excel");
    header("Content-disposition:attachment; filename=\"$namafile.xls\"");
    header("Pragma:no-cache");
    header("Cache-Control:must-revalidate, post-check=\"0\", pre-check=\"0\"");
    header("Expires:0");
?>
<table border=1>
    <thead>
      <tr>
        <th rowspan=4>Nomor Inventarisasi Sementara (NIS)</th>
        <th rowspan=4>NOP</th>
        <th colspan=15>Berkaitan dengan Subyek</th>
        <th></th>

        <th colspan=15>Berkaitan dengan Subyek</th>
        <th></th>

        <th colspan=20>Berkaitan dengan Obyek</th>
        <th></th>

        <th rowspan=4>Keadaan Tanah</th>
        <th colspan=7 rowspan=2>Berkaitan dengan Akses</th>
        <th rowspan=4>Keterangan</th>
      </tr>
      <tr>
        <th colspan=15>Pemilikan</th>
        <th></th>

        <th colspan=15>Penguasaan</th>
        <th></th>

        <th colspan=7 rowspan=2>Letak Tanah</th>
        <th rowspan=3>Luas Tanah (m2)</th>
        <th rowspan=3>Penguasaan Tanah</th>
        <th rowspan=3>Perolehan Tanah</th>
        <th rowspan=3>Pemilikan Tanah</th>
        <th rowspan=3>Penguasaan Bidang Tanah saat ini</th>
        <th rowspan=3>Jenis Pemanfaatan Bidang Tanah saat ini</th>
        <th rowspan=3>Nomor Sertipikat</th>
        <th rowspan=3>Indikasi Tanah Terlantar</th>
        <th rowspan=3>Sengketa, Konflik, Perkara Pertanahan</th>
        <th rowspan=3>Potensi Tol</th>
        <th rowspan=3>Nilai Tanah</th>
        <th rowspan=3>ZNT</th>
        <th rowspan=3>RTRW</th>
        <th></th>
      </tr>
      <tr>
        <th rowspan=2>Nama Pemilik</th>
        <th colspan=7>Alamat</th>
        <th rowspan=2>KTP (NIK)</th>
        <th rowspan=2>Pekerjaan</th>
        <th rowspan=2>Umur (KK)</th>
        <th rowspan=2>Status Perkawinan (M,BM,PM)</th>
        <th rowspan=2>Jumlah Anggota Keluarga</th>
        <th rowspan=2>Domisili saat ini</th>
        <th rowspan=2>Memiliki tanah ini sejak tahun</th>
        <th></th>

        <th rowspan=2>Nama Yang Menguasai</th>
        <th colspan=7>Alamat</th>
        <th rowspan=2>KTP (NIK)</th>
        <th rowspan=2>Pekerjaan</th>
        <th rowspan=2>Umur (KK)</th>
        <th rowspan=2>Status Perkawinan (M,BM,PM)</th>
        <th rowspan=2>Jumlah Anggota Keluarga</th>
        <th rowspan=2>Domisili saat ini</th>
        <th rowspan=2>Menguasai tanah ini sejak tahun</th>
        <th></th>
        <th></th>
        <th rowspan=2>Penjaminan Sertipikat</th>
        <th rowspan=2>Potensi Akses</th>
        <th colspan=3>Bantuan diterima</th>
        <th colspan=2>Pendapatan</th>
      </tr>
      <tr>
        <th>Dusun/Jalan</th>
        <th>RT</th>
        <th>RW</th>
        <th>Desa / Kelurahan</th>
        <th>Kecamatan</th>
        <th>Kabupaten / Kota</th>
        <th>Provinsi</th>
        <th></th>

        <th>Dusun/Jalan</th>
        <th>RT</th>
        <th>RW</th>
        <th>Desa / Kelurahan</th>
        <th>Kecamatan</th>
        <th>Kabupaten / Kota</th>
        <th>Provinsi</th>
        <th></th>

        <th>Dusun/Jalan</th>
        <th>RT</th>
        <th>RW</th>
        <th>Desa / Kelurahan</th>
        <th>Kecamatan</th>
        <th>Kabupaten / Kota</th>
        <th>Provinsi</th>

        <th></th>
        <th>Jenis Bantuan</th>
        <th>Dari</th>
        <th>Tanggal</th>
        <th>Sebelum</th>
        <th>Sesudah</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($dataip4t as $data) {
        ?>
        <tr>
          <td><?=$data['nis_ip4t']?></td>
          <?php
          $pemilik['table'] = "tb_penduduk";
  				$pemilik['type'] = "single";
  				$pemilik['condition']['idpdk_pdk'] = $data['a1nik_ip4t'];
  				$pemilik['join']['table'] = "tb_pekerjaan";
  				$pemilik['join']['key'] = "idpkr_pkr";
  				$pemilik['join']['ref'] = "idpeker_pdk";
  				$datapemilik = $this->crud_model->get_data($pemilik);

          $birthdate = new DateTime($datapemilik['ttg_pdk']);
			    $today= new DateTime(date("Y-m-d"));
			    $agemilik = $birthdate->diff($today)->y;

          $penguasaan['table'] = "tb_penduduk";
  				$penguasaan['type'] = "single";
  				$penguasaan['condition']['idpdk_pdk'] = $data['a2nik_ip4t'];
  				$penguasaan['join']['table'] = "tb_pekerjaan";
  				$penguasaan['join']['key'] = "idpkr_pkr";
  				$penguasaan['join']['ref'] = "idpeker_pdk";
  				$datapenguasaan = $this->crud_model->get_data($penguasaan);

          $birthdate = new DateTime($datapenguasaan['ttg_pdk']);
			    $today= new DateTime(date("Y-m-d"));
			    $agekuasa = $birthdate->diff($today)->y;

  				$dnop['type']                   = "single";
  				$dnop['table']                  = "tb_dhkp";
  				$dnop['join']['table']          = "tb_block,tb_nub,tb_hak,tb_ptsldhkp,tb_ptsl";
  				$dnop['join']['key']            = "idblk_blk,iddhkp_nub,no_hak,iddhkp_ptsl,id_ptsl";
  				$dnop['join']['ref']            = "idblk_dhkp,id_dhkp,nohak_nub,id_dhkp,idptsl_ptsl";
  				$dnop['column'] 								= "idkel_blk,nama_blk,nosppt_dhkp,nib_hak,nib_ptsl,nub_ptsl,idpdk_ptsl
  																						,luasfisik_ptsl,luassppt_dhkp";
  				$dnop['condition']['id_dhkp']   = $data['iddhkp_ip4t'];
  				$datanop                        = $this->crud_model->get_data($dnop);

  				$pdk['table'] = "tb_penduduk";
  				$pdk['type'] = "single";
  				$pdk['condition']['idpdk_pdk'] = $datanop['idpdk_ptsl'];
  				$datapenduduk = $this->crud_model->get_data($pdk);

  				$nop = createkodebpkad($datanop['idkel_blk']).''.$datanop['nama_blk'].''.$datanop['nosppt_dhkp'];

          if($datanop['luasfisik_ptsl']){
						$luas = $datanop['luasfisik_ptsl'];
					}else if($datanop['luassppt_dhkp']){
						$luas = $datanop['luassppt_dhkp'];
					}else{
						$luas = '';
					}

           ?>
          <td>'<?=$nop?></td>
          <td><?=$datapemilik['nma_pdk']?></td>
          <td><?=$datapemilik['almat_pdk']?></td>
          <td><?=$datapemilik['rt_pdk']?></td>
          <td><?=$datapemilik['rw_pdk']?></td>
          <td><?=$datapemilik['kel_pdk']?></td>
          <td><?=$datapemilik['kec_pdk']?></td>
          <td><?=$datapemilik['kab_pdk']?></td>
          <td>Jawa Tengah</td>
          <td><?=$datapemilik['noktp_pdk']?></td>
          <td><?=$datapemilik['nama_pkr']?></td>
          <td><?=$agemilik?></td>
          <td><?=status($datapemilik['status_pdk'],'statusnikah')?></td>
          <td><?=$datapemilik['anggota_pdk']?></td>
          <td><?=status($datapemilik['domisili_pdk'],'domisili')?></td>
          <td><?=$data['a1miliktanah_ip4t']?></td>
          <td></td>

          <td><?=$datapenguasaan['nma_pdk']?></td>
          <td><?=$datapenguasaan['almat_pdk']?></td>
          <td><?=$datapenguasaan['rt_pdk']?></td>
          <td><?=$datapenguasaan['rw_pdk']?></td>
          <td><?=$datapenguasaan['kel_pdk']?></td>
          <td><?=$datapenguasaan['kec_pdk']?></td>
          <td><?=$datapenguasaan['kab_pdk']?></td>
          <td>Jawa Tengah</td>
          <td><?=$datapenguasaan['noktp_pdk']?></td>
          <td><?=$datapenguasaan['nama_pkr']?></td>
          <td><?=$agekuasa?></td>
          <td><?=status($datapenguasaan['status_pdk'],'statusnikah')?></td>
          <td><?=$datapenguasaan['anggota_pdk']?></td>
          <td><?=status($datapenguasaan['domisili_pdk'],'domisili')?></td>
          <td><?=$data['a2kuasatanah_ip4t']?></td>

          <td></td>

          <td><?=$datapenduduk['almat_pdk']?></td>
          <td><?=$datapenduduk['rt_pdk']?></td>
          <td><?=$datapenduduk['rw_pdk']?></td>
          <td><?=$datapenduduk['kel_pdk']?></td>
          <td><?=$datapenduduk['kec_pdk']?></td>
          <td><?=$datapenduduk['kab_pdk']?></td>
          <td>Jawa Tengah</td>
          <td><?=$luas?></td>
          <td><?=status($data['bkuasatanah_ip4t'],'kuasatanah')?></td>
          <td><?=status($data['bolehtanah_ip4t'],'olehtanah')?></td>
          <td><?=status($data['bmiliktanah_ip4t'],'miliktanah')?></td>
          <td><?=status($data['bgunatanah_ip4t'],'gunabidang')?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>

          <td></td>

          <td></td>
          <td><?=status($data['csertif_ip4t'],'sertif')?></td>
          <td><?=status($data['cpotensi_ip4t'],'potensiakses')?></td>
          <td><?=$data['cbantuanjenis_ip4t']?></td>
          <td><?=$data['cbantuandari_ip4t']?></td>
          <td><?=fdate($data['cbantuantanggal_ip4t'],'DDMMYYYY')?></td>
          <td><?=$data['cpendapatanbelum_ip4t']?></td>
          <td><?=$data['cpendapatansudah_ip4t']?></td>

        </tr>
        <?php
      }
      ?>
    </tbody>
</table>
