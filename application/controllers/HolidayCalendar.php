<?php


class HolidayCalendar extends DT_CI_Controller
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

		$this->dt_ci_template->load("default", "holidayCalendar/v_holiday_calendar", $data);
	}

	// ajax call to the data listing
	public function getHolidayCalendarListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("thc.holiday_calendar_id,thc.title,
					thc.holiday_type,date_format(thc.holiday_from_date,'" . DATE_FORMATE_MYSQL . "') as holiday_from_date,
					date_format(thc.holiday_to_date,'" . DATE_FORMATE_MYSQL . "') as holiday_to_date");
		$this->datatables->from("tbl_holiday_calendar as thc");
		
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($holidayCalendarId = '') // change here manage
	{

		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/media/fancybox.min.js",
			"js/additional-methods.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/core/libraries/jquery_ui/widgets.min.js",
			"/js/plugins/pickers/anytime.min.js",
		);
		if($holidayCalendarId != '') {
			$data['getHolidayCalendarData'] = $this->Mdl_holiday_calendar->getHolidayCalendarData($holidayCalendarId);

		}

		$this->dt_ci_template->load("default", "holidayCalendar/v_holiday_calendar_manage", $data);
	}

	public function getHolidayCalendarDataListing($holidayCalendarId = '') // change here manage
	{

		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/media/fancybox.min.js",
			"js/additional-methods.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/core/libraries/jquery_ui/widgets.min.js",
			"/js/plugins/pickers/anytime.min.js",
		);
		if($holidayCalendarId != '') {
			$data['getHolidayCalendarData'] = $this->Mdl_holiday_calendar->getHolidayCalendarData($holidayCalendarId);

		}

		$this->dt_ci_template->load("default", "holidayCalendar/v_holiday_calendar_data", $data);
	}



	// Save function here
	public function save()
	{
		$holidayCalendarId    = $this->input->post('holiday_calendar_id');
		$title   		   	  = $this->input->post('title', TRUE);
		$description   		  = $this->input->post('description', TRUE);
		$holidayFromDate      = $this->input->post('holiday_from_date', TRUE);
		$holidayToDate     	  = $this->input->post('holiday_to_date', TRUE);
		$totalDays     	      = $this->input->post('total_days', TRUE);
		$holidayType  		  = $this->input->post('holiday_type', TRUE);

		$this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
		$this->form_validation->set_rules('description', $this->lang->line('description'), 'required');
		$this->form_validation->set_rules('holiday_from_date', $this->lang->line('holiday_from_date'), 'required');
		$this->form_validation->set_rules('holiday_to_date', $this->lang->line('holiday_to_date'), 'required');
		$this->form_validation->set_rules('total_days', $this->lang->line('total_days'), 'required');
		$this->form_validation->set_rules('holiday_type', $this->lang->line('holiday_type'), 'required');

		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$holidayCalendarArray = array(
				'holiday_calendar_id'	=> $holidayCalendarId,
				'title'  				=> $title,
				'description'  			=> $description,
				'holiday_from_date'     => DMYToYMD($holidayFromDate),
				'holiday_to_date'      	=> DMYToYMD($holidayToDate),
				'total_days'      	    => $totalDays ,
				'holiday_type'   		=> $holidayType,
			);

			$holidayCalendarData  = $this->Mdl_holiday_calendar->insertUpdateRecord($holidayCalendarArray, 'holiday_calendar_id', 'tbl_holiday_calendar', 1);

			if (isset($holidayCalendarId) && $holidayCalendarId != '') {
				if ($holidayCalendarData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), HOLIDAYCALENDAR);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), HOLIDAYCALENDAR);
				}
			} else {
				if ($holidayCalendarData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), HOLIDAYCALENDAR);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), HOLIDAYCALENDAR);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$holidayCalendarId = $this->input->post('deleteId',TRUE);

		$holidayCalendarData = $this->Mdl_holiday_calendar->deleteRecord($holidayCalendarId);

		if ($holidayCalendarData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),HOLIDAYCALENDAR);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),HOLIDAYCALENDAR);
		}
		echo json_encode($response);
	}
}
