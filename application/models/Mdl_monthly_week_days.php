<?php


class Mdl_monthly_week_days extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}


	public function deleteRecord($monthWorkId = '')
	{
		$tables = array('tbl_monthly_week_days','tbl_month_work_day');
		$this->db->where_in('month_work_id', $monthWorkId);
		$this->db->delete($tables);
		$ids = is_array($monthWorkId) ? implode(',', $monthWorkId) : $monthWorkId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = true;
			logActivity('Month Week Deleted [GenderID: ' . $ids . ']', $this->data['userId'], 'MonthlyWeekDays');
		}
		return $response;
	}

	public function getMonthlyWeekDaysData($monthWorkId)
	{
		$this->db->select('tmw.month_work_id,tmw.title,tmw.total_full_days,tmw.total_half_days,');
		$this->db->from('tbl_monthly_week_days as tmw');
		//$this->db->join('tbl_month_work_day as twm','tmw.month_work_id = twm.month_work_id','right');
		$this->db->where('tmw.month_work_id', $monthWorkId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		
		
		$this->db->select('twm.month_work_day_id,twm.week_days_name,twm.work_week_state');
		$this->db->from('tbl_month_work_day as twm');
		$this->db->where('twm.month_work_id', $monthWorkId);
		$query = $this->db->get();
		$weekData = $query->result_array();

		
		$queryData['week_details'] = $weekData;
		return $queryData;
	}
	
	function getMonthlyWeekDaysDD($data)
	{
		$this->db->select("tmwd.month_work_id as id,tmwd.title as text");
		$this->db->from('tbl_monthly_week_days as tmwd');
		
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("tmwd.title", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}
}
