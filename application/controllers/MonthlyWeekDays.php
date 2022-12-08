<?php

class MonthlyWeekDays extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_monthly_week_days"));
		$this->lang->load('monthly_week_day');
	}

	public function index()
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/pages/form_layouts.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/ui/moment/moment.min.js",
			"js/plugins/pickers/anytime.min.js",
			"js/plugins/ui/ripple.min.js",
		);

		$this->dt_ci_template->load("default", "monthlyWeekDays/v_monthly_week_days", $data);
	}

	public function getMonthlyWeekDaysListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("tmw.month_work_id,tmw.title,tmw.total_full_days,tmw.total_half_days,twm.month_work_day_id,twm.week_days_name");
		$this->datatables->from("tbl_monthly_week_days as tmw");
		$this->datatables->join("tbl_month_work_day as twm","twm.month_work_id = tmw.month_work_id","left");
		$this->datatables->group_by("tmw.month_work_id",'desc');
		echo $this->datatables->generate();
	}

	public function manage($monthWorkId = '')
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/pages/form_layouts.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/ui/moment/moment.min.js",
			"js/plugins/pickers/anytime.min.js",
			"js/core/libraries/jquery_ui/widgets.min.js",
			"js/plugins/media/fancybox.min.js",
			"js/pages/gallery.js",
			"js/additional-methods.min.js",
		);
		if($monthWorkId != '') {
			$data['getMonthlyWeekDaysData'] = $this->Mdl_monthly_week_days->getMonthlyWeekDaysData($monthWorkId);
		}

		$this->dt_ci_template->load("default", "monthlyWeekDays/v_monthly_week_days_manage", $data);
	}

	public function getMonthlyWeekDaysDataListing($monthWorkId = '')
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
			"/js/maps/jquery.geocomplete.js",
		);

		if($monthWorkId != '') {
			$data['getMonthlyWeekDaysData']    = $this->Mdl_monthly_week_days->getMonthlyWeekDaysData($monthWorkId);
			
		}

		$this->dt_ci_template->load("default", "monthlyWeekDays/v_month_week_data",$data);
	}

	public function save()
	{
		$monthWorkId        	  = $this->input->post('month_work_id');
		$title    				  = $this->input->post('title', TRUE);
		$totalFullDays    		  = $this->input->post('total_full_days', TRUE);
		$totalHalfDays    		  = $this->input->post('total_half_days', TRUE);

		$this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$monthWeekArray = array(
				'month_work_id'	    => $monthWorkId,
				'title'  	   		=> $title,
				'total_full_days'  	=> $totalFullDays,
				'total_half_days'  	=> $totalHalfDays,
			);

			$monthWeekData   = $this->Mdl_monthly_week_days->insertUpdateRecord($monthWeekArray, 'month_work_id', 'tbl_monthly_week_days', 1);
			$lastMonthWorkId = $monthWeekData['lastInsertedId'];

			for ($i = 1; $i<=5; $i++){
				$days 		   = $this->input->post('work_week'.$i, TRUE);
				$weekState 	   = implode(',',$days);
				$days		   = implode('_',$days);
				$days		   = explode('_',$days);
				$workName 	   = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat","Sun");
				$weekdays 	   = '';

				foreach($days as $j => $v){
					if(in_array($v,$workName)){
						$weekdays .= $v.",";
					}
				}
				$weekdays = rtrim($weekdays,',');
			
				$monthWorkDayId = $this->input->post('month_work_day_id'.$i, TRUE);
				if($monthWorkDayId != ''){
					
					$monthWeekUpdateArray[$i-1] = array(
						'month_work_day_id'  => $monthWorkDayId,
						'month_work_id'  	  => $monthWorkId,
						'week_days_name'  	  => $weekdays,
						'work_week_state'  	  => $weekState,
					);
				} else {
					$monthWeekInsertArray[$i-1] = array(
						'month_work_id'  	  => $lastMonthWorkId,
						'week_days_name'  	  => $weekdays,
						'work_week_state'  	  => $weekState,
					);
				}
				
			}

			if(!empty($monthWeekInsertArray)){
				$this->Mdl_monthly_week_days->batchInsert($monthWeekInsertArray, 'tbl_month_work_day');
			}
			if(!empty($monthWeekUpdateArray)){
				$this->Mdl_monthly_week_days->batchUpdate($monthWeekUpdateArray,'month_work_day_id' ,'tbl_month_work_day');
			}

			if (isset($monthWorkId) && $monthWorkId != '') {
				if ($monthWeekData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), MONTHWORKDAYS);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), MONTHWORKDAYS);
				}
			} else {
				if ($monthWeekData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), MONTHWORKDAYS);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), MONTHWORKDAYS);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$monthWorkId = $this->input->post('deleteId',TRUE);

		if( is_reference_in_table('month_work_id', 'tbl_employee', $monthWorkId)) {

			$response['success'] = false;
			$response['msg'] = $this->lang->line('delete_record_dependency');
			echo json_encode($response);
			exit;

		}

		$workWeekData = $this->Mdl_monthly_week_days->deleteRecord($monthWorkId);
		if ($workWeekData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),MONTHWORKDAYS);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),MONTHWORKDAYS);
		}
		echo json_encode($response);
	}
	
	public function getMonthlyWeekDaysDD()
	{
		
		$searchTerm  = $this->input->post("filter_param");
		$monthWorkId  = $this->input->post("monthly_working_days_id");
		
		
		$data = array(
			"month_work_id"        =>  $monthWorkId,
			"filter_param"         =>  $searchTerm
		);
		echo $this->Mdl_monthly_week_days->getMonthlyWeekDaysDD($data);
	}
}
