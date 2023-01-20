<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class mdata extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    public function insert($data) {
        if($this->db->insert('data',$data)) {
            return true;
        }else {
            return false;
        }
    }
    public function fetchMarkers() {
        $this->db->order_by('id', 'desc');
		$query = $this->db->get('data');
        if ($query->num_rows() > 0) 
        {
            foreach ($query->result() as $row) 
            {	
                $data[] = $row;
            }
             
            return $data;
        }
		return false;
    }
}