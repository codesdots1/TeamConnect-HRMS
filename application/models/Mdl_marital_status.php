<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_marital_status extends DT_CI_Model
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function deleteRecord($maritalStatusId = '')
    {
        $tables = array('tbl_marital_status');
        $this->db->where_in('marital_status_id', $maritalStatusId);
        $this->db->delete($tables);
        $ids = is_array($maritalStatusId) ? implode(',', $maritalStatusId) : $maritalStatusId;
		$response = array();
        if ($this->db->affected_rows()) {
            $response['success'] = true;
            logActivity('MaritalStatus Deleted [MaritalStatusID: ' . $ids . ']', $this->data['userId'], 'MaritalStatus');
        } 
        
		return $response;
     

    }


    public function getMaritalStatusData($maritalStatusId)
    {
        $this->db->select('tms.marital_status_id,tms.marital_status');
        $this->db->from('tbl_marital_status as tms');
        $this->db->where('tms.marital_status_id', $maritalStatusId);
        $query = $this->db->get();
        $queryData = $query->row_array();
        return $queryData;
    }


    function getMaritalStatusDD($data){
        $this->db->select('ms.marital_status_id as id,ms.marital_status as text');
        $this->db->from('tbl_marital_status as ms');
        if (isset($data['filter_param']) && $data['filter_param'] != '') {
            $this->db->like("ms.marital_status", $data['filter_param'], 'both');
        }
        $query = $this->db->get();
        $result['result'] = $query->result_array();
        return json_encode($result);
    }

    public function getExistingMaritalStatus($excludeId = '')
    {
        $this->db->select('marital_status');
        $this->db->from('tbl_marital_status');
        if($excludeId != ''){
            $this->db->where('marital_status_id != ',$excludeId);
        }
        $query = $this->db->get();
        $queryData = $query->result_array();
        return $queryData;

    }

}
