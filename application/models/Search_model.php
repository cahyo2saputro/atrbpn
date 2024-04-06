<?php
//birds_model.php (Array of Objects)
class Search_model extends CI_Model{
  function get_search($q){
    $this->db->select('*');
    $this->db->like('name_prd', $q);
    $query = $this->db->get('ms_product');
    if($query->num_rows() > 0){
      foreach ($query->result_array() as $row){
        $new_row['label']=htmlentities(stripslashes($row['name_prd']));
        $new_row['value']=htmlentities(stripslashes($row['idprd_prd']));
        $row_set[] = $new_row; //build an array
      }
      echo json_encode($row_set); //format the array into json data
    }
  }
}
?>