<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_state extends DT_CI_Model {
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code

    }


    public function deleteRecord($stateId)
    {
        $tables = array('tbl_state');
        $this->db->where_in('state_id',$stateId);
        $this->db->delete($tables);

        $ids = is_array($stateId) ? implode(',',$stateId) : $stateId;

		$response = array();
		 if ($this->db->affected_rows()) {
            $response['success'] = "true";
        }
        return $response;
    }

    public function getData($stateId = '')
    {
        if($stateId != ''){
            $this->db->where('state_id', $stateId);
        }
        $data = $this->db->get("tbl_state");
        $query = $data->result_array();
        return $query;
    }



    function getStateDD($filterParameter = '',$page, $countryId  ,$stateIdActive,$start = 0, $limit = 10)
    {
		if($page != 1){
			$start = ($page * $limit) - $limit;
		}

		$this->db->select('state_id as id,state_name as text');
		$this->db->from('tbl_state');
		$this->db->where_in("country_id", $countryId);
		if($countryId == ''){
			$result['result'] = array ( array ( 'id' => 0 , 'text' => 'First Select Country...' ));
		}else{
			
			if ($filterParameter != '') {
				$this->db->like('state_name', $filterParameter, 'both');
			}
			
			$this->db->where('is_active',1);

			$query = $this->db->get();
			$result['result'] = $query->result_array();
		}

		return json_encode($result);
    }
	public function getStateListing($data = ''){
		$limit = DATA_LIMIT;
		$this->db->select("s.state_id,s.state_name");
		$this->db->from("tbl_state as s");
		if (isset($data['search']) && $data['search'] != '') {
			$this->db->like('s.state_name', $data['search'],'both');
		}
		if (isset($data['start'])) {
			$this->db->limit($limit, $data['start'] * $limit);
		}
		$query = $this->db->get()->result_array();
		return $query;
	}

    public function checkExistState($stateId = '', $countryId = '', $stateName = ''){
        $this->db->select('count(ts.state_name) as state');
        $this->db->from('tbl_state as ts');
        $this->db->join('tbl_country as tc','tc.country_id = ts.country_id','left');
        $this->db->where('tc.country_id', $countryId);
        if($stateId != ''){
            $this->db->where('ts.state_id != ',$stateId);
        }
        $this->db->where('ts.state_name',$stateName);
        $state = $this->db->get()->row_array();
        return isset($state['state']) ? $state['state'] : 0;
    }

}
?>

