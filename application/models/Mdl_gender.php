<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_gender extends DT_CI_Model
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }


    public function deleteRecord($genderId = '')
    {
        $tables = array('tbl_gender');
        $this->db->where_in('gender_id', $genderId);
        $this->db->delete($tables);
        $ids = is_array($genderId) ? implode(',', $genderId) : $genderId;
		$response = array();
        if ($this->db->affected_rows()) {
            $response['success'] = true;
            logActivity('Gender Deleted [GenderID: ' . $ids . ']', $this->data['userId'], 'Gender');

        }
		return $response;

    }

    public function getGenderData($genderId)
    {
        $this->db->select('tg.gender_id,tg.gender_name');
        $this->db->from('tbl_gender as tg');
        $this->db->where('tg.gender_id', $genderId);
        $query = $this->db->get();
        $queryData = $query->row_array();
        return $queryData;
    }


    function getGenderDD($data){
        $this->db->select('tg.gender_id as id,tg.gender_name as text');
        $this->db->from('tbl_gender as tg');
        if (isset($data['filter_param']) && $data['filter_param'] != '') {
            $this->db->like("tg.gender_name", $data['filter_param'], 'both');
        }
        $query = $this->db->get();
        $result['result'] = $query->result_array();
        return json_encode($result);
    }

    public function getExistingGender($excludeId = '')
    {
        $this->db->select('gender_name');
        $this->db->from('tbl_gender');
        if($excludeId != ''){
            $this->db->where('gender_id != ',$excludeId);
        }
        $query = $this->db->get();
        $queryData = $query->result_array();
        return $queryData;

    }
}
