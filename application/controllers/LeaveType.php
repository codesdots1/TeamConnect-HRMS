<?php
class LeaveType extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Curl');
		$this->load->model(array("Mdl_leave_type"));
		$this->lang->load('leave_type');
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

		$this->dt_ci_template->load("default", "leaveType/v_leave_type", $data);
	}

	// ajax call to the data listing
	public function getLeaveTypeListing()
	{
		$this->load->library('datatables');
		//$response = $this->curl->simple_post('http://alitainfotech.com/Employee_Management/Api/EmployeeManage/getLeaveTypeListing', $_POST);
		$response = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/getLeaveTypeListing', $_POST);
		$response = json_decode($response,1);
		
		$output = array(
			"draw" 			  => intval($_POST["draw"]),
			"recordsTotal" 	  => $this->Mdl_leave_type->getAllData(),
			"recordsFiltered" => $this->Mdl_leave_type->getFilteredData(),
			"data" => $response['data']
		);
		echo json_encode($output);
	}

	//insert and update function
	public function manage($leaveTypeId = '') // change here manage
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
		);
		if($leaveTypeId != '') {
			$data['getLeaveTypeData'] = $this->Mdl_leave_type->getLeaveTypeData($leaveTypeId);

		}

		$this->dt_ci_template->load("default", "leaveType/v_leave_type_manage", $data);
	}



	// Save function here
	public function save()
	{
		$leaveTypeId    = $this->input->post('leave_type_id');
		$leaveType    	= $this->input->post('leave_type', TRUE);
		$paymentStatus   = $this->input->post('payment_status', TRUE);
		$noOfDaysType   = $this->input->post('no_of_days_type', TRUE);
		$noOfDays    	= $this->input->post('no_of_days', TRUE);
		$description    = $this->input->post('description', TRUE);
		$userId         = $this->session->userdata['emp_id'];
		$increamentDays    	= $this->input->post('increament_days', TRUE);
		
		$this->form_validation->set_rules('leave_type', $this->lang->line('leave_type'), 'required');
			
		if(strtolower($noOfDaysType) == "increamental"){
			$this->form_validation->set_rules('increament_days', $this->lang->line('no_of_days_per_month'), 'required');
			$increamentDays    	= $this->input->post('increament_days', TRUE);
			$monthlyStatus    	= $this->input->post('monthly_status', TRUE);
			if(strtolower($monthlyStatus) == "carry_forward"){
				$yearlyStatus    	= $this->input->post('yearly_status', TRUE);
			}else{
				$yearlyStatus    	= "";
			}
			
		}else{
			$increamentDays    	= "";
			$monthlyStatus		= "";
		}

		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$leaveTypeArray = array(
				'leave_type_id'	   => $leaveTypeId,
				'leave_type'  	   => $leaveType,
				'payment_status'   => $paymentStatus,
				'no_of_days'  	   => $noOfDays,
				'days_type'		   => $noOfDaysType,
				'increament_days'  => $increamentDays,
				'monthly_status'   => $monthlyStatus,
				'yearly_status'    => $yearlyStatus,
				'description'      => $description,
				'created_by'	   => $userId,
				'updated_by'	   => $userId
				);
			//print_r($leaveTypeArray); die;
			$leaveTypeData = $this->Mdl_leave_type->insertUpdateRecord($leaveTypeArray, 'leave_type_id', 'tbl_leave_type', 1);

			if (isset($leaveTypeId) && $leaveTypeId != '') {
				if ($leaveTypeData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), LEAVETYPE);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), LEAVETYPE);
				}
			} else {
				if ($leaveTypeData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), LEAVETYPE);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), LEAVETYPE);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$leaveTypeId = $this->input->post('deleteId',TRUE);
		$ids = is_array($leaveTypeId) ? implode(',',$leaveTypeId) : $leaveTypeId;
		
		  if(is_reference_in_table('leave_type_id', 'tbl_employee_leaves', $leaveTypeId)){
            $response['success'] = false;
            logActivity('Tried to delete Leave Type [LeaveTypeID: ' . $ids . ']',$this->data['userId'],'City');
            $response['msg'] = $this->lang->line('delete_record_dependency');
            echo json_encode($response);
            exit;
        }

		//delete  leave
		//$LeaveTypeData = $this->curl->simple_post('http://alitainfotech.com/Employee_Management/Api/EmployeeManage/deleteLeaveType', array('leave_type_id'=>$ids));
		$LeaveTypeData = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/deleteLeaveType', array('leave_type_id'=>$ids));
		$LeaveTypeData = json_decode($LeaveTypeData);

		if ($LeaveTypeData->status == "true") {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),LEAVETYPE);
		} else {
			$response['success'] = false;
			$response['msg'] =  sprintf($this->lang->line('error_delete_record'),LEAVETYPE);
		}

		echo json_encode($response);
	}


	public function getLeaveTypeDD(){
		$leaveTypeId   = $this->input->post("leave_type_id");
		$searchTerm    = $this->input->post("filter_param");

		$data = array(
			"leave_type_id"     => $leaveTypeId,
			"filter_param"      => $searchTerm
		);
		echo $this->Mdl_leave_type->getLeaveTypeDD($data);
	}

}
