<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class DT_ci_advanced_filters
    {
        var $CI;

        function __construct()
        {
            $this->CI =& get_instance();

            $this->CI->load->model('Mdl_employee_attendance');

        }

        function getFilters($data, $from = '')
        {
            $data['empAttendanceList']    = $this->CI->Mdl_employee_attendance->getEmployeeAttendanceData();
            printArray($data['empAttendanceList'],1);
            $data['from']       = $from;
        }
    }

?>
