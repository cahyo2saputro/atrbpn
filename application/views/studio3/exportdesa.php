<?php
    @$namafile="daftar nominatif Belum Sertipikat Kelurahan ".$desa['nma_kel']." Semua Block ";
    header("Content-type:application/vnd.ms-excel");
    header("Content-disposition:attachment; filename=\"$namafile.xls\"");
    header("Pragma:no-cache");
    header("Cache-Control:must-revalidate, post-check=\"0\", pre-check=\"0\"");
    header("Expires:0");
?>
<table>
    <tbody>
        <tr>
            <th>Desa</th>
            <td><?= $desa['nma_kel'];?></td>
        </tr>
        <tr>
            <th>Kecamatan</th>
            <td><?= $desa['nma_kec']; ?></td>
        </tr>
        <tr>
            <th></th>
            <td></td>
        </tr>
    </tbody>
</table>
<table border=1>
    <thead>
        <tr>
            <th rowspan="2">BLOK</th>
            <th rowspan="2">NUB</th>
            <th rowspan="2">NAMA</th>
            <th rowspan="2">TEMPAT</th>
            <th rowspan="2">TGL LAHIR</th>
            <th rowspan="2">ALAMAT</th>
            <th rowspan="2">NIK/KTP</th>
            <th colspan="3">NOMOR</th>
            <th rowspan="2">LUAS</th>
            <th rowspan="2">PENGGUNAAN TANAH</th>
            <th rowspan="2">PEMANFAATAN TANAH</th>
            <th rowspan="2">NO.SPPT</th>
            <th rowspan="2">NJOP</th>
            <th rowspan="2">LUAS SPPT</th>
            <th rowspan="2">ALAMAT OBJEK PAJAK</th>
            <th rowspan="2">ALAMAT WAJIB PAJAK</th>
            <th colspan="4">BATAS</th>
            <th rowspan="2">KET</th>
            <th rowspan="2">LUAS FISIK</th>
            <th rowspan="2">NO.BERKAS FISIK</th>
            <th rowspan="2">NIB</th>
            <th rowspan="2">KLASTER</th>
            <th rowspan="2">NO.BERKAS YURIDIS</th>
        </tr>
        <tr>
            <th>C Desa</th>
            <th>Persil</th>
            <th>Klas</th>
            <th>Utara</th>
            <th>Timur</th>
            <th>Selatan</th>
            <th>Barat</th>
        </tr>
    </thead>
    <tbody>
        <?php $num=1;
        foreach ($dat as $data) {
        ?>
        <tr>
            <td>'<?= $data['nama_blk'] ?></td>
            <td><?= $data['nub_ptsl'] ?></td>
            <td><?= stripslashes($data['nma_pdk']); ?></td>
            <td><?= $data['ttl_pdk']; ?></td>
            <td><?= fdate($data['ttg_pdk'],'DDMMYYYY'); ?></td>
            <td><?= $data['almat_pdk'].' RT.'.$data['rt_pdk'].' RW.'.$data['rw_pdk'].' '.$data['kel_pdk'].' Kec.'.$data['kec_pdk'].' Kab.'.$data['kab_pdk']; ?></td>
            <td>'<?= $data['noktp_pdk']; ?></td>
            <td><?= $data['dc_ptsl']; ?></td>
            <td><?= $data['dpersil_ptsl']; ?></td>
            <td><?= $data['dklas_ptsl']; ?></td>
            <td><?= $data['dluas_ptsl']; ?></td>
            <td><?php
                if($data['idguna_ptsl']==1){
                  echo 'Pertanian';
                }else if($data['idguna_ptsl']==2){
                  echo 'Non Pertanian';
                }else{
                  echo '';
                }
                ?>
            </td>
            <td><?= status($data['idmanfaat_ptsl'],'manfaat'); ?></td>
              <?php
              $dat['table'] = "tb_ptsldhkp";
              $dat['join']['table'] = "tb_dhkp";
              $dat['join']['key'] = "id_dhkp";
              $dat['join']['ref'] = "iddhkp_ptsl";
              $dat['type'] = "multiple";
              $dat['condition']['idptsl_ptsl'] = $data['id_ptsl'];
              $datanop = $this->crud_model->get_data($dat);
              $datnop = '';$datluas = '';$datnjop = $datawp = $dataop ='';
              foreach ($datanop as $dd) {
                $nop = createkodebpkad($desa['idkel_blk']).''.$desa['nama_blk'].''.$dd['nosppt_dhkp'];
                $datnop .= "'".$nop.'<br>';
                $datluas .= $dd['luassppt_dhkp'].'<br>';
                $datnjop .= $dd['njopsppt_dhkp'].'<br>';
                $datawp .= $dd['awpsppt_dhkp'].'<br>';
                $dataop .= $dd['aopsppt_dhkp'].'<br>';
              }
              ?>
            <td><?= $datnop?></td>
            <td><?= $datnjop?></td>
            <td><?= $datluas?></td>
            <td><?= $dataop?></td>
            <td><?= $datawp?></td>
            <td><?= $data['utara_ptsl']; ?></td>
            <td><?= $data['timur_ptsl']; ?></td>
            <td><?= $data['selatan_ptsl']; ?></td>
            <td><?= $data['barat_ptsl']; ?></td>
            <td><?= $data['note_ptsl']; ?></td>
            <td><?= $data['luasfisik_ptsl']; ?></td>
            <td><?= $data['noberkas_ptsl']; ?></td>
            <td><?= $data['nib_ptsl']; ?></td>
            <td><?= $data['klaster_ptsl']; ?></td>
            <td><?= $data['noberkasyrd_ptsl']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
