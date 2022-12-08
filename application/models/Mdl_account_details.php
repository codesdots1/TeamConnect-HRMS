<?php
/**
 * Created by PhpStorm.
 * User: dt-user09
 * Date: 3/19/2019
 * Time: 12:36 PM
 */

class Mdl_account_details extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function getAccountDetailsData($accountDetailsId = '')
	{
		$this->db->select("tad.account_details_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name,
							tad.bank_name,tad.holder_name,tad.bank_code,tad.account_number");
		$this->db->from("tbl_account_details as tad");
		$this->db->join("tbl_employee as te","te.emp_id = tad.emp_id","left");
		$this->db->where('tad.account_details_id', $accountDetailsId);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function deleteRecord($accountDetailsId)
	{
		$tables = ('tbl_account_details');
		$this->db->where_in('account_details_id',$accountDetailsId);
		$this->db->delete($tables);
		if ($this->db->affected_rows()) {
			$response['success'] = true;
			return $response;
		} else {
			$response['success'] = false;
			return $response;
		}
	}

	public function getAccountDetailsInfoData($accountDetailsId = '')
	{
		$this->db->select("tad.account_details_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name,
							tad.bank_name,tad.holder_name,tad.bank_code,tad.account_number");
		$this->db->from("tbl_account_details as tad");
		$this->db->join("tbl_employee as te","te.emp_id = tad.emp_id","left");
		$this->db->where('tad.account_details_id', $accountDetailsId);
		$query = $this->db->get()->row_array();
		return $query;
	}
}
