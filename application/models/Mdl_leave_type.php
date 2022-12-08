<?php


class Mdl_leave_type extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}


	public function deleteRecord($leaveTypeId = '')
	{
		$leaveTypeId = explode(',',$leaveTypeId);
		$tables = array('tbl_leave_type');
        $this->db->where_in('leave_type_id',$leaveTypeId);
        $this->db->delete($tables);

        $ids = is_array($leaveTypeId) ? implode(',',$leaveTypeId) : $leaveTypeId;
		$response = array();
        if ($this->db->affected_rows()) {
            $response['success'] = "true";
        }
	
		return $response;

	}

	public function getLeaveTypeData($leaveTypeId)
	{
		$this->db->select('tlt.leave_type_id,tlt.leave_type,tlt.payment_status,tlt.no_of_days,tlt.increament_days,
		tlt.monthly_status,tlt.yearly_status,tlt.days_type,tlt.description');
		$this->db->from('tbl_leave_type as tlt');
		$this->db->where('tlt.leave_type_id', $leaveTypeId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}


	function getLeaveTypeDD($data)
	{
		$this->db->select('tlt.leave_type_id as id,tlt.leave_type as text');
		$this->db->from('tbl_leave_type as tlt');
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("tlt.leave_type", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}
	

	public function makeQuery()
	{
		$this->increamentTotalDays();
		$order_column = array('tlt.leave_type_id','tlt.leave_type','tlt.no_of_days','tlt.increament_days','tlt.payment_status');
		$this->db->select("tlt.leave_type_id,tlt.leave_type,tlt.no_of_days,tlt.days_type,tlt.increament_days,tlt.increament_status,
		tlt.description,tlt.payment_status");
		$this->db->from("tbl_leave_type as tlt");
		if (isset($_POST['search']['value'])) {
			$this->db->like('tlt.leave_type', $_POST['search']['value']);
			//$this->db->or_like('c.description', $_POST['search']['value']);
		}
		if (isset($_POST['order'])) {
			$this->db->order_by($order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		}else{
			$this->db->order_by('tlt.leave_type','asc');
		}
	}

	public function getLeaveTypeListing()
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
		$this->db->from("tbl_leave_type");
		return $this->db->count_all_results();
	}
	
	public function getAllResult(){
		$this->db->select("*");
		$this->db->from("tbl_leave_type");
		$query = $this->db->get();
		return $query->result();
		
	}
	
	public function increamentTotalDays(){
		$allResult = $this->getAllResult();

		foreach($allResult as $row){
			$daysType = $row->days_type;
			$date = date('d');
			$increamentStatus =  $row->increament_status;
			$leaveTypeId = $row->leave_type_id;
			if($date == "01" && $increamentStatus == 0 && $daysType == "increamental"){
				
				$noOfDays = $row->no_of_days + $row->increament_days;
				$dataArray = array( 'no_of_days' => $noOfDays, 'increament_status' => 1);
				$this->db->where('leave_type_id',$leaveTypeId);
				$this->db->update('tbl_leave_type', $dataArray);
			}
		}	
	}
	
	
}
