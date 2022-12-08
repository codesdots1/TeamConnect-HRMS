<?php


class Mdl_holiday_calendar extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($holidayCalendarId)
	{
		$tables = array('tbl_holiday_calendar');
		$this->db->where_in('holiday_calendar_id',$holidayCalendarId);
		$this->db->delete($tables);
		$ids = is_array($holidayCalendarId) ? implode(',',$holidayCalendarId) : $holidayCalendarId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getHolidayCalendarData($holidayCalendarId)
	{
		$this->db->select("thc.holiday_calendar_id,thc.title,thc.description,
					thc.holiday_type,date_format(thc.holiday_from_date,'" . DATE_FORMATE_MYSQL . "') as holiday_from_date,
					date_format(thc.holiday_to_date,'" . DATE_FORMATE_MYSQL . "') as holiday_to_date");
		$this->db->from('tbl_holiday_calendar as thc');
		$this->db->where('thc.holiday_calendar_id', $holidayCalendarId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}
	
	public function holidayDate($date=''){
		$this->db->select("thc.holiday_calendar_id");
		$this->db->from('tbl_holiday_calendar as thc');
		$this->db->where('thc.holiday_from_date <=', DMYToYMD($date));
		$this->db->where('thc.holiday_to_date >=', DMYToYMD($date));
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
		
	}
}
