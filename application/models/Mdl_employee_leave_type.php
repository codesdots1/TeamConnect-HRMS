<?php
class Mdl_employee_leave_type extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model('Mdl_leave_type');
	}


	public function deleteRecord($leaveId = '')
	{
		$tables = array('tbl_employee_leaves');
	
		$this->db->where_in('leave_id',$leaveId);
		$this->db->delete($tables);
		$ids = is_array($leaveId) ? implode(',',$leaveId) : $leaveId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getEmployeeLeaveData($leaveTypeId)
	{
		$this->db->select("tel.leave_id,tlt.leave_type_id,tlt.leave_type,tel.half_day,tel.leave_reason,tel.is_active,tel.is_rejected,tel.note,
		date_format(tel.apply_date,'".DATE_FORMATE_MYSQL."') as apply_date,
		date_format(tel.leave_from_date,'".DATE_FORMATE_MYSQL."') as leave_from_date,
		date_format(tel.leave_to_date,'".DATE_FORMATE_MYSQL."') as leave_to_date,tel.no_of_days,te.emp_id,
		CONCAT(te.first_name,' ',te.last_name,' | ',te.email) as emp_name,CONCAT(te.first_name,' ',te.last_name) as name,te.email");
		$this->db->from('tbl_employee_leaves as tel');
		$this->db->join('tbl_leave_type as tlt','tlt.leave_type_id = tel.leave_type_id','left');
		$this->db->join('tbl_employee as te','te.emp_id = tel.emp_id','left');
		$this->db->where('tel.leave_id', $leaveTypeId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function makeQuery()
	{
		$order_column = array("tlt.leave_type,date_format(tel.leave_from_date,'".DATE_FORMATE_MYSQL."') as leave_from_date,
		date_format(tel.leave_to_date,'".DATE_FORMATE_MYSQL."') as leave_to_date,tel.leave_reason",null);
		$this->db->select("tel.leave_id,tlt.leave_type_id,tlt.leave_type,date_format(tel.leave_from_date,'".DATE_FORMATE_MYSQL."') as leave_from_date,
		date_format(tel.leave_to_date,'".DATE_FORMATE_MYSQL."') as leave_to_date,tel.no_of_days,tel.leave_reason,tel.is_active");
		$this->db->from("tbl_employee_leaves as tel");
		$this->db->join("tbl_leave_type as tlt","tlt.leave_type_id = tel.leave_type_id","left");
		if (isset($_POST['search']['value'])) {
			$this->db->like('tlt.leave_type', $_POST['search']['value']);
		}
		if (isset($_POST['order'])) {
			$this->db->order_by($order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		} else{
			$this->db->order_by('tlt.leave_type','asc');
		}
	}

	public function getEmployeeLeaveListing()
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
		$this->db->from("tbl_employee_leaves");
		return $this->db->count_all_results();
	}
	
	public function getTotalDays($leaveTypeId = 0,$empId = 0,$year='',$month=''){
	
		$year 	= ($year != '') ? $year : date('Y');
		$month  = ($month != '') ? $month : date('m');
	
		$this->db->select("no_of_days as total,days_type,increament_days,monthly_status");
		$this->db->from("tbl_leave_type");
		$this->db->where('leave_type_id', $leaveTypeId);
		$query = $this->db->get();
		$resultArray = $query->row_array();
		if(strtolower($resultArray['days_type']) == 'unlimited'){
			$total = 'Unlimited';
		} else if(strtolower($resultArray['days_type']) == 'increamental'){
			if(strtolower($resultArray['monthly_status']) == 'carry_forward'){
				$this->db->select("hire_date");
			    $this->db->from("tbl_employee");
				$this->db->where("emp_id",$empId);
				$query = $this->db->get();
				$empDetails =  $query->row_array();
	
				$joiningYear = date('Y', strtotime($empDetails['hire_date']));
				if($year == $joiningYear){
					$total = $resultArray['increament_days'] * (($month - date('m', strtotime($empDetails['hire_date'])))+1);
				}else{
					$total = $resultArray['increament_days'] * $month;
				}
			}else{
				$total = $resultArray['increament_days'];
			}
			
		} else{
			$total = $resultArray['total'];
		}
		if($empId != 0){
			$leaveTypeDetails = $this->Mdl_leave_type->getLeaveTypeData($leaveTypeId);
			$this->db->select("sum(no_of_days) as total_leave");
			$this->db->from("tbl_employee_leaves");
			//year(leave_from_date) = year(CURDATE())
			if($year != ''){
				$year = $year;
			} else {
				$year = date("Y");
			}

			if(strtolower($leaveTypeDetails['days_type']) == 'increamental'){
				$condition = array('leave_type_id' => $leaveTypeId, 'emp_id' => $empId,'is_rejected' => 0, 'year(leave_from_date)' => $year);
			} else {
				$condition = array('leave_type_id' => $leaveTypeId, 'emp_id' => $empId,'is_rejected' => 0, 'year(leave_from_date)' => $year );
			}

			$this->db->where($condition);
			$query = $this->db->get();
			$result =  $query->row_array();
			if($result['total_leave'] != ''){
				$leave = $result['total_leave'];
			} else{
				$leave = 0;
			}

		} else{
			$leave = 0;
		}
		if(strtolower($resultArray['days_type']) == 'unlimited'){
			$available 		= 'Unlimited';
			$available_days = 500;
		}else{
			$available 		= $total - $leave;
			$available_days = $available;
		}

	    $response = array(
			"total_leave" 		=> $total,
			"leave_taken" 		=> $leave,
			"available_leave" 	=> $available,
			"available_days"	=> $available_days
		);
		return $response;
	}
	
	public function adminLeaveNotificationMail(){
		$this->db->select("tel.*,te.email,CONCAT(te.first_name,' ',te.last_name) as emp_name,tlt.leave_type");
		$this->db->from("tbl_employee_leaves tel");
		$this->db->join('tbl_leave_type as tlt','tel.leave_type_id = tlt.leave_type_id','left');
		$this->db->join('tbl_employee as te','tel.emp_id = te.emp_id','left');
		$condition = array('tel.is_active' => 0,'tel.is_rejected' => 0);
		$this->db->where($condition); 
		$where = "(tel.leave_from_date = '".date("Y-m-d")."' OR tel.leave_from_date = '".date("Y-m-d",strtotime("tomorrow"))."')";
        $this->db->where($where);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
}
