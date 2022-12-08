<?php
/**
 * Created by PhpStorm.
 * User: dt-user09
 * Date: 3/19/2019
 * Time: 12:36 PM
 */

class Mdl_company extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function getCompanyData($companyId = '')
	{
		$this->db->select("tcom.company_id,tcom.company_name,tcom.description,tcom.address,tc.city_id,tc.city_name,tcom.postal_code,
							tcon.country_id,tcon.country_name,tcom.is_active,ts.state_id,ts.state_name,
							tcom.site_name,tcom.site_url,tcom.phone_no,tcom.fax_no,tcom.email");
		$this->db->from("tbl_companies as tcom");
		$this->db->join("tbl_country as tcon","tcon.country_id = tcom.country_id","left");
		$this->db->join("tbl_state as ts","ts.state_id = tcom.state_id","left");
		$this->db->join("tbl_city as tc","tc.city_id = tcom.city_id","left");
		$this->db->where('tcom.company_id', $companyId);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function getCompanyInfoData($companyId = '')
	{
		$this->db->select("tcom.company_id,tcom.company_name,tcom.description,tcom.address,tc.city_id,tc.city_name,tcom.postal_code,
							tcon.country_id,tcon.country_name,tcom.is_active,ts.state_id,ts.state_name,
							tcom.site_name,tcom.site_url,tcom.phone_no,tcom.fax_no,tcom.email");
		$this->db->from("tbl_companies as tcom");
		$this->db->join("tbl_country as tcon","tcon.country_id = tcom.country_id","left");
		$this->db->join("tbl_state as ts","ts.state_id = tcom.state_id","left");
		$this->db->join("tbl_city as tc","tc.city_id = tcom.city_id","left");
		$this->db->where('tcom.company_id', $companyId);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function deleteRecord($companyId)
	{
		$companyId = explode(',',$companyId);
		$tables = array('tbl_companies');
        $this->db->where_in('company_id',$companyId);
        $this->db->delete($tables);

        $ids = is_array($companyId) ? implode(',',$companyId) : $companyId;
		$response = array();
        if ($this->db->affected_rows()) {
            $response['success'] = "true";
        }
	
		return $response;
	}

	function getCompanyDD($filterParameter = '',$page = 1,$companyIdActive ,$start = 0, $limit = 10)
	{
		if($page != 1){
			$start = ($page * $limit) - $limit;
		}

		$this->db->start_cache();
		$this->db->select('company_id as id,company_name as text');
		$this->db->from('tbl_companies');
		if ($filterParameter != '') {
			$this->db->like('company_name', $filterParameter, 'both');
		}
		$this->db->or_where_in('company_id',$companyIdActive);
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

}
