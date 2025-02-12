<?php
    @$namafile="data NIB ".$kelurahan['nma_kel'];
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
            <td><?= $kelurahan['nma_kel'];?></td>
        </tr>
        <tr>
            <th>Kecamatan</th>
            <td><?= $kelurahan['nma_kec']; ?></td>
        </tr>
    </tbody>
</table>
<table border=1>
    <thead>
      <tr>
        <th style="text-align: center; vertical-align: middle;">No</th>
        <th style="text-align: center; vertical-align: middle;">NIB</th>
        <th style="text-align: center; vertical-align: middle;">No Hak</th>
        <th style="text-align: center; vertical-align: middle;">No SU</th>
        <th style="text-align: center; vertical-align: middle;">Luas</th>
        <th style="text-align: center; vertical-align: middle;">Pemilik Akhir</th>
        <th style="text-align: center; vertical-align: middle;">Jenis KW</th>
        <th style="text-align: center; vertical-align: middle;">Blok</th>
        <th style="text-align: center; vertical-align: middle;">NOP</th>
        <th style="text-align: center; vertical-align: middle;">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $no = $this->uri->segment('4') + 1;
        foreach ($studio as $data) {
          $nib['table'] = "tb_nib";
          $nib['type']  = "single";
          $nib['condition']['nib_nib']   = $data;
          $nib['condition']['idkel_nib'] = $this->uri->segment('3');
          $datanib = $this->crud_model->get_data($nib);
          if($datanib){
            $status = $datanib['status_nib'];
          }else{
            $status = 2;
          }

          if($status==1){
            $nub['table'] 				 = "tb_nub";
            $nub['type']  				 = "single";
            $nub['column'] = "tb_hak.no_hak,tb_hak.id_kelurahan,tb_hak.nosu_hak,tb_su.luas_su,
                              tb_hak.jenis_kw_awal,tb_hak.pmi_hak,tb_hak.buku_tanah,tb_hak.entry_su_tekstual,
                              tb_hak.su_spasial,tb_hak.bidang_tanah,tb_block.nama_blk,tb_dhkp.nosppt_dhkp";
            $nub['join']['table'] = "tb_hak,tb_su,tb_block,tb_dhkp";
            $nub['join']['key']   = "no_hak,nohak_su,idblk_blk,id_dhkp";
            $nub['join']['ref']   = "nohak_nub,no_hak,idblk_nub,iddhkp_nub";
            $nub['condition']['idnub_nub']   = $datanib['idref_nib'];
            $datanub = $this->crud_model->get_data($nub);

            if($datanub['buku_tanah'] === "1" && $datanub['bidang_tanah'] === "1" && $datanub['entry_su_tekstual'] === "1" && $datanub['su_spasial'] === "1"){
              $kw_akhir = "KW1";
            }else if($datanub['buku_tanah'] === "1" && $datanub['bidang_tanah'] === "1" && $datanub['entry_su_tekstual'] == "1"){
              $kw_akhir = "KW2";
            }else if($datanub['buku_tanah'] === "1" && $datanub['bidang_tanah'] === "1"){
              $kw_akhir = "KW3";
            }else if($datanub['buku_tanah'] === "1" && $datanub['entry_su_tekstual'] === "1" && $datanub['su_spasial'] === "1"){
              $kw_akhir = "KW4";
            }else if($datanub['buku_tanah'] === "1" && $datanub['entry_su_tekstual'] === "1" ){
              $kw_akhir = "KW5";
            }else if($datanub['buku_tanah'] === "1"){
              $kw_akhir = "KW6";
            }else{
              $kw_akhir =$datanub['jenis_kw_awal'];
            }

            $content['nohak'] = $datanub['no_hak'];
            $content['nosu'] = $datanub['nosu_hak'];
            $content['luas'] = $datanub['luas_su'];
            $content['pmi'] = $datanub['pmi_hak'];
            $content['kw'] = $kw_akhir;
            $content['nop'] = createkodebpkad($datanub['id_kelurahan']).''.$datanub['nama_blk'].''.$datanub['nosppt_dhkp'];
            $content['blok'] = $datanub['nama_blk'];
          }else if($status==0){
            $ptsl['table'] 				 = "tb_ptsl";
            $ptsl['type']  				 = "single";
            $ptsl['column'] = "idkel_blk,nama_blk,nosppt_dhkp";
            $ptsl['join']['table'] = "tb_block,tb_ptsldhkp,tb_dhkp";
            $ptsl['join']['key']   = "idblk_blk,idptsl_ptsl,id_dhkp";
            $ptsl['join']['ref']   = "idblk_ptsl,id_ptsl,iddhkp_ptsl";
            $ptsl['condition']['id_ptsl']   = $datanib['idref_nib'];
            $dataptsl = $this->crud_model->get_data($ptsl);

            $content['nohak'] = '';
            $content['nosu'] = '';
            $content['luas'] = '';
            $content['pmi'] = '';
            $content['kw'] = '';
            $content['nop'] = createkodebpkad($dataptsl['idkel_blk']).''.$dataptsl['nama_blk'].''.$dataptsl['nosppt_dhkp'];
            $content['blok'] = $dataptsl['nama_blk'];
          }else{
            $content['nohak'] = '';
            $content['nosu'] = '';
            $content['luas'] = '';
            $content['pmi'] = '';
            $content['kw'] = '';
            $content['nop'] = '';
            $content['blok'] = '';
          }

          if($status==1){
            $txtstatus = '<td style="background:green;color:#fff;">Sudah Sertipikat</td>';
          }else{
            $txtstatus = '<td style="background:yellow;">Belum Sertipikat</td>';
          }
          ?>
          <tr>
            <td><?=$no?></td>
            <td>'<?=$data?></td>
            <td><?=$content['nohak']?></td>
            <td><?=$content['nosu']?></td>
            <td><?=$content['luas']?></td>
            <td><?=$content['pmi']?></td>
            <td><?=$content['kw']?></td>
            <td>'<?=$content['blok']?></td>
            <td>'<?=$content['nop']?></td>
            <?=$txtstatus?>
          </tr>
          <?php
          $no++;
        }
      ?>
    </tbody>
</table>
