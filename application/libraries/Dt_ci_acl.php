<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dt_ci_acl
{
    private $CI;

    private $permitableMethodsArray = array(
        "Country" =>
            array(
                array("index" => "View Country"),
                array("save"  => "Modify Country"),
            ),
        "State" =>
            array(
                array("index" => "View State"),
            ),
        "City" =>
            array(
                array("index" => "View City"),
            ),
		"Designation" =>
            array(
                array("index" => "View Designation"),
                array("manage"  => "Modify Designation"),
				 array("delete"  => "Delete Designation")
            ),
		"Salary" =>
            array(
                array("index" => "View Salary"),
                array("manage"  => "Modify Salary"),
				array("delete"  => "Delete Salary")
            ),
		"Role" =>
            array(
                array("index" => "View Role"),
                array("manage"  => "Modify Role"),
				array("delete"  => "Delete Role"),
            ),
			
		"LeaveType" =>
            array(
                array("index" 	=> "View LeaveType"),
                array("manage"  => "Modify LeaveType"),
				array("delete"  => "Delete LeaveType"),
            ),
		"WorkWeek" =>
            array(
                array("index" => "View WorkWeek"),
                array("manage"  => "Modify WorkWeek"),
				array("delete"  => "Delete WorkWeek"),
            ),
		"EmployeeShift" =>
            array(
                array("index" => "View EmployeeShift"),
                array("manage"  => "Modify EmployeeShift"),
				array("delete"  => "Delete EmployeeShift"),
            ),
		"EmployeeType" =>
            array(
                array("index" => "View EmployeeShift"),
                array("manage"  => "Modify EmployeeShift"),
				array("delete"  => "Delete EmployeeShift"),
            ),
		"HolidayCalendar" =>
            array(
                array("index" => "View HolidayCalendar"),
                array("manage"  => "Modify HolidayCalendar"),
				array("delete"  => "Delete HolidayCalendar"),
            ),
		"EmployeeEntryLog" =>
            array(
                array("index" => "View EmployeeEntryLog"),
				array("delete"  => "Delete EmployeeEntryLog"),
            ),

		"Department" =>
            array(
                array("index" => "View Department"),
                array("manage"  => "Modify Department"),
				array("delete"  => "Delete Department"),
            ),
		"Company" =>
            array(
                array("index" => "View Company"),
                array("manage"  => "Modify Company"),
				array("delete" => "Delete Company"),
				array("getCompanyDataListing" => "Display Company Details"),
            ),
		"Gender" =>
            array(
                array("index" => "View Gender"),
                array("manage"  => "Modify Gender"),
				array("delete"  => "Delete Gender"),
            ),
		"TeamManagement" =>
			array(
				//array("index" =>  "View Team Management"),
				//array("delete" => "Delete Team Management"),
				//array("manage" =>  "Modify Team Management"),
				//array("getTeamManagementListing" => "Display Team Management "),
			),
		"ProjectManagement" =>
			array(
				array("index" =>  "View Project Management"),
				array("delete" => "Delete Project Management"),
				array("manage" =>  "Modify Project Management"),
				array("getProjectManagementListing" => "Display Project Management "),
			),
		"TaskManagement" =>
			array(
				//array("index" =>  "View Task Management"),
				array("delete" => "Delete Task Management"),
				//array("manage" =>  "Modify Task Management"),
				//array("getTaskManagementListing" => "Display Task Management "),
			),
		"Payroll" =>
            array(
                array("index" => "View Payroll"),
            ),
		"MaritalStatus" =>
            array(
                array("index" => "View MaritalStatus"),
                array("manage"  => "Modify MaritalStatus"),
				array("delete"  => "Delete MaritalStatus"),
            ),
		"ShiftManagement" =>
            array(
                array("index" => "View ShiftManagement"),
                array("manage"  => "Modify ShiftManagement"),
				array("delete"  => "Delete ShiftManagement"),
            ),
		"RoleManagement" =>
            array(
                array("index" => "View RoleManagement"),
                array("manage"  => "Modify RoleManagement"),
				array("delete"  => "Delete RoleManagement"),
            ),
		"EmployeeAttendance" =>
            array(
				array("index" =>  "View EmployeeAttendance"),
				array("manage" => "Modify EmployeeAttendance"),
                array("delete" => "Delete EmployeeAttendance"),
				array("attendanceCorrection" => "Admin Attendance Correction"),
				array("attendanceCorrectionManage" => "Admin Attendance Correction Update"),
				array("correctionList" => "Employee Attendance Correction"),
				array("getAttendanceCorrectionData" => "Display Employee Attendance Correction Details"),
				array("deleteEmpWiseAttendance" => "Delete EmployeeWiseAttendance"),
				array("attendanceDetails" => "Display EmployeeAttendance Details"),
            ),
		"Employee" =>
            array(
				array("index" =>  "View Employee"),
                array("delete" => "Delete Employee"),
				array("manage" => "Modify Employee"),
				array("getEmployeeDataListing" => "Delete Employee Details"),
            ),
			"OldEmployee" =>
            array(
				array("index" =>  "View Old Employee"),
            ),
		"AccountDetails" =>
            array(
				array("index" =>  "View AccountDetails"),
                array("delete" => "Delete AccountDetails"),
				array("manage" => "Modify AccountDetails"),
				array("getAccountDetailsDataListing" => "Display AccountDetails Details"),
            ),
		"Project" =>
            array(
				array("index" =>  "View Project"),
                array("delete" => "Delete Project"),
				array("manage" => "Modify Project"),
//				array("getProjectListing" => "Display Project"),
            ),
		"LeaveReason" =>
			array(
				array("index" 	=> "View Leave Reason"),
				array("manage"  => "Modify Leave Reason"),
				array("delete"  => "Delete Leave Reason"),
			),
		"Task" =>
            array(
				array("index" =>  "View Task"),
                array("delete" => "Delete Task"),
				array("manage" => "Modify Task"),
//				array("getTaskListing" => "Display Task"),
            ),
		"EmployeeLeaveType" =>
            array(
				//array("index" =>  "View EmployeeLeave"),
                array("delete" => "Delete EmployeeLeave"),
               // array("manage" => "Modify EmployeeLeave"),
				array("getLeaveDataListing" => "Display EmployeeLeave Details"),
            ),
//        "Auth" =>
//            array(
//                array("edit_group" => "Modify Groups"),
//                array("manage_groups" => "View Groups"),
//                array("index" => "View Users"),
//                array("edit_user" => "Modify Users"),
//            ),
    );

    private $friendlyControllerNames = array(
        "auth" => "User access"
    );

    // Construct
    function __construct()
    {
        // Get Codeigniter instance
        $this->CI = get_instance();

		// Get all controllers
//		$this->setControllers();
	}

    public function checkAdmin()
    {
        return $this->CI->ion_auth->is_admin();
    }

    public function checkAccess($requiredPermission)
    {


//		$trace = debug_backtrace();
//		$callingMethod  = $trace[1]['function'];
//		$callingClass  = $trace[1]['class'];
//		$requiredPermission = $callingClass."|".$callingMethod;



		if ($this->CI->ion_auth->is_admin()) {
            return true;
        }

		//echo $requiredPermission; die();
		$requiredPermissionArr = explode("|", $requiredPermission);
        $requiredPClass = $requiredPermissionArr[0];
        $requiredPMethod = $requiredPermissionArr[1];
		//print_r($requiredPermissionArr); die;

//        echo "<pre>"; print_r($requiredPermission);
        // echo "<br/>";
//       print_r($requiredPMethod);
	

        if (isset($this->permitableMethodsArray[$requiredPClass]) && $this->checkArray($requiredPMethod, $this->permitableMethodsArray[$requiredPClass])) {
			$empId 			   = $this->CI->session->userdata('emp_id');
            $currentPermission = $this->CI->ion_auth->get_current_user_permission();
//            printArray($currentPermission,1);

            if (in_array('admin', ($currentPermission)) && in_array('hr', ($currentPermission))
				&& in_array('team leader', ($currentPermission))) {
                return true;
            } else {
				$currentURl = $this->CI->uri->uri_string();
				$url 		= array();

				$CI =& get_instance();
				$CI->load->model('Mdl_team_management'); 
				$data 	= $CI->Mdl_team_management->getTeamMember($empId);
//				$empIds = explode(",",$data['emp_id_listing']);
//				array_push($empIds,$empId);
					
				if($this->CI->uri->segment(4) != ""){
//					foreach($empIds as $Id){
//						array_push($url,$requiredPClass.'/'.$requiredPMethod.'/'.$Id.'/'.$this->CI->uri->segment(4));
//					}

				} else{
//					foreach($empIds as $Id){
//						array_push($url,$requiredPClass.'/'.$requiredPMethod.'/'.$Id);
//					}
				}
				
				if(in_array($currentURl,$url)){
					return true;
				}
				else {
					return false;
				}
            }
        } else {
            return true;
        }
    }

    private function checkArray($needle, $haystack)
    {
        foreach ($haystack as $item) {
            if ($item == $needle) {
    			return true;
            } else if (is_array($item)) {
    			reset($item);
                if (key($item) == $needle) {
                	return true;
    			}
    		}
    	}
    	return false;
    }

    public function getPermissableMethods()
    {
    	return $this->permitableMethodsArray;
    }

    public function getActiveMenu($path, $class = 'active')
    {
		
        if ($path == uri_string()) {
            return "class='$class'";
        }
    }


}
// EOF
