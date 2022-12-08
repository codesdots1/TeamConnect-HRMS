<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_country extends DT_CI_Model {
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code

    }

	public function deleteRecord($countryId)
	{
		
		$countryId = explode(',',$countryId);
		$tables = array('tbl_country');
        $this->db->where_in('country_id',$countryId);
        $this->db->delete($tables);

        $ids = is_array($countryId) ? implode(',',$countryId) : $countryId;
		$response = array();
        if ($this->db->affected_rows()) {
            $response['success'] = "true";
        }
	
		return $response;
       
	}

    public function getData($countryId = '')
    {
        if($countryId != '') {
            $this->db->where('country_id', $countryId);
        }
        $data = $this->db->get("tbl_country");
        $query = $data->result_array();
        return $query;
    }

    function getCountryDD($filterParameter = '',$page = 1,$countryIdActive ,$start = 0, $limit = 10)
    {
        if($page != 1){
            $start = ($page * $limit) - $limit;
        }

        $this->db->start_cache();
        $this->db->select('country_id as id,country_name as text');
        $this->db->from('tbl_country');
        if ($filterParameter != '') {
            $this->db->like('country_name', $filterParameter, 'both');
        }
        $this->db->where('is_active',1);
        $this->db->or_where_in('country_id',$countryIdActive);
        $this->db->stop_cache();
        $totalRows = $this->db->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        $result['result'] = $query->result_array();
        $result['totalRows'] = $totalRows;
        $result['page'] = $page;
        $this->db->flush_cache();

        return json_encode($result);
    }


	public function makeQuery()
	{
		$order_column = array("c.country_name","c.is_active",null);
		$this->db->select("c.country_id,c.country_name,c.is_active");
		$this->db->from("tbl_country as c");
		if (isset($_POST['search']['value'])) {
			$this->db->like('c.country_name', $_POST['search']['value']);
			//$this->db->or_like('c.description', $_POST['search']['value']);
		}
		if (isset($_POST['order'])) {
			$this->db->order_by($order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		}else{
			$this->db->order_by('c.country_name','asc');
		}
	}

	public function getCountryListing()
	{
		$this->makeQuery();
		if($_POST["length"] != -1){
			$this->db->limit($_POST["length"], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();


	}

	public function getFilteredData()
	{
		$this->makeQuery();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function getAllData()
	{
		$this->db->select("*");
		$this->db->from("tbl_country");
		return $this->db->count_all_results();
	}
}
?>
