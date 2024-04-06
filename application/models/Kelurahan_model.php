<?php
	class Kelurahan_model extends CI_Model
	{

		function __construct()
		{
			parent::__construct();
		}

		public function show_kelurahan($model,$column)
		{
			$ci =& get_instance();
		  $ci->load->model('crud_model');

		  $dd['table']  = $model['table'];
			$dd['type']   = "multiple";
			$dd['column'] = $column.",nma_kec,(SELECT nma_kel FROM ms_kelurahan WHERE kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel
											,(SELECT kd_full FROM ms_kelurahan WHERE kdkec_kel=kec_srt AND kd_kel=kel_srt) as kdfull";
			$dd['join']['table'] = 'ms_kecamatan';
			$dd['join']['key']   = 'kd_kec';
			$dd['join']['ref']   = 'kec_srt';
			$dd['groupby']       = "kdfull";

		  return $ci->crud_model->get_data($dd);
		}

	}
 ?>
