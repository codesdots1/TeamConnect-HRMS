<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends DT_CI_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model(array('Mdl_employee_leave_type','Mdl_admin_email_notification','Mdl_employee','Mdl_salary','Mdl_employee_type'));
		$this->lang->load('admin_email_notification');
    }
    public function index ()
    {
        $data['extra_js'] = array(
            "js/plugins/tables/datatables/datatables.min.js",
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/selects/select2.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/pages/form_layouts.js",
            "js/plugins/forms/jquery.form.min.js",
        );

		//Admin Dashboard
		$data['salary']   				= $this->Mdl_salary->getTotalSalary();
		$data['empName']  				= $this->Mdl_employee->getTotalEmployee();
		$data['empBirthday']  			= $this->Mdl_employee->getEmployeeBirthDate();
		$data['upcomingHoliday']  		= $this->Mdl_employee->getUpcomingHolidays();
		$data['teamLeads']  			= $this->Mdl_employee->getTeamLeads();
		$data['name']  		        	= $this->Mdl_employee->getRecentActivities();
		$data['employeeName']  		    = $this->Mdl_employee->getRecentActivity();
		$data['empLeave']  				= $this->Mdl_employee_type->getEmployeeLeaveToday();
		$data['employeeLeave']  	    = $this->Mdl_employee_type->getEmployeeLeaveType();

		//Admin Dashboard
		$data['empTotalProject']      = $this->Mdl_employee->getTotalWorkingProject();
		$data['empTotalProjectTeam']  = $this->Mdl_employee->getTotalTeamProject();
		$data['employeeTotalLeave']   = $this->Mdl_employee_type->getEmployeeTotalLeave();
		$data['empTotalTime']  		  = $this->Mdl_employee_type->getEmployeeTotalTime();
		$data['employeeLeaveTaken']   = $this->Mdl_employee_type->getEmployeeTotalTaken();

		$todaysEntry   = $this->Mdl_admin_email_notification->checkTodayEntry();
		$leaveTypeData = $this->Mdl_employee_leave_type->adminLeaveNotificationMail();

		if(!empty($leaveTypeData)){
			if(empty($todaysEntry) || (count($todaysEntry) > 0 && $todaysEntry['no_of_records'] != count($leaveTypeData))){
				if(count($leaveTypeData) > 0 ){
					$dt_ci_email = new dt_ci_email();
					$toEmail = FROM_EMAIL;
					$message = 'Hello <strong>'.FROM_NAME.'</strong>,<br/><br/>
								We have some pending leave for today or tomorrow, please respond them.<br/><br/>
								<strong>Details are as below :</strong> <br/><br/>';
					foreach($leaveTypeData as $data){
						$message .=  'Name : <strong>'.$data['emp_name'].'</strong> <br/>
										Email Id : <strong>'.$data['email'].'</strong><br/> 
										Leave Type : <strong>'.$data['leave_type'].'</strong><br/> 
										From Date : <strong>'.date("d-m-Y", strtotime($data['leave_from_date'])).'</strong><br/>
										To Date : <strong>'.date("d-m-Y", strtotime($data['leave_to_date'])).'</strong><br/>
										Total Days : <strong>'.$data['no_of_days'].'</strong><br/>
										Reason : <strong>'.$data['leave_reason'].'</strong><br/>
										Apply Date : <strong>'.date("d-m-Y", strtotime($data['apply_date'])).'</strong><br/><br/>';
					}

					$message .= 'Thank you,<br/>
								'.FROM_NAME;
					$status = $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('pending_leave_notification'), $message);
					if($status && empty($todaysEntry)){
						$emailNotification = array(
							'notification_id'   => 	'',
							'email'    		    => 	$toEmail,
							'no_of_records'     => 	count($leaveTypeData),
							'status'            => 	"send",
							'date'              => 	date('Y-m-d')
							);

						$adminEmailNotification = $this->Mdl_admin_email_notification->saveNotification($emailNotification);
					}else if($status && count($todaysEntry) != 0){
						$emailNotification = array(
							'email'    		    => 	$toEmail,
							'no_of_records'     => 	count($leaveTypeData),
							'status'            => 	"send",
							'date'              => 	date('Y-m-d')
						);
						$notificationId = $todaysEntry['notification_id'];
						$adminEmailNotification = $this->Mdl_admin_email_notification->saveNotification($emailNotification,$notificationId);
					}
				}else{
					$emailNotification = array(
						'notification_id'   =>  '',
						'email'    		    =>	"",
						'no_of_records'     => 	count($leaveTypeData),
						'status'            => 	"No entry found",
						'date'              =>	 date('Y-m-d')
					);

					$adminEmailNotification = $this->Mdl_admin_email_notification->saveNotification($emailNotification);
				}
			}
		}
		if($this->session->userdata['role'] == 'admin'){
			$this->dt_ci_template->load("default", "v_dashboard", $data);
		} else {
			$this->dt_ci_template->load("default", "v_employee_dashboard", $data);
		}
    }
}
?>
