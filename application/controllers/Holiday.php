<?php


class Holiday extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_holiday_calendar"));
		$this->lang->load('holiday_calendar');
	}

	//Index page
	public function index()
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js"

		);

		$this->dt_ci_template->load("default", "holidayCalendar/v_holiday", $data);
	}

	// ajax call to the data listing
	public function getHolidayCalendarListing()
	{
		$startDate = $this->input->post('startDate',TRUE);
		$endDate = $this->input->post('endDate',TRUE);
		
		$this->load->library('datatables');
		$this->datatables->select("thc.holiday_calendar_id,thc.title,date_format(thc.holiday_from_date,'" . DATE_FORMATE_MYSQL . "') as holiday_from_date,
			date_format(thc.holiday_to_date,'" . DATE_FORMATE_MYSQL . "') as holiday_to_date, thc.holiday_type");
		$this->datatables->from("tbl_holiday_calendar as thc");
		
		if($startDate != ''){
			$this->datatables->where('thc.holiday_from_date >=', DMYToYMD($startDate));
			$this->datatables->where('thc.holiday_to_date >=', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->datatables->where('thc.holiday_from_date <=', DMYToYMD($endDate));
			$this->datatables->where('thc.holiday_to_date <=', DMYToYMD($endDate));
		}
		
		echo $this->datatables->generate();
	}
}
