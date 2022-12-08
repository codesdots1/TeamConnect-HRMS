<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityLog extends DT_CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("Mdl_activity_log"));
        $this->lang->load(array('activity_log'));
    }

    //Index page
    public function index()
    {
        $data['extra_js'] = array(
            "js/plugins/tables/datatables/datatables.min.js",
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/plugins/forms/jquery.form.min.js",
            "js/plugins/forms/selects/select2.min.js",

        );

        $dataFilters['filters'] = array(
            array(
                "type" => "multi_select",
                "id" => "module",
                "name" => "module",
                "tbl_name" => "tbl_activity_log",
                "columns" => array('module','module'),
                "title" => "module",
                "group_by" => "module",
            ),
            array(
                "type" => "multi_select",
                "id" => "staff_id",
                "name" => "staff_id",
                "dynamic" => true,
                "title" => "user_name"
            ),
            array(
                "type" => "daterange",
                "id" => "date",
                "name" => "date",
                "title" => "date",
            )
        );

        $data['filterModel'] = $this->load->view('commonMaster/v_filterModal',
            isset($dataFilters) ? $dataFilters : [], TRUE);

        $data['datePicker'] = true;


        $this->dt_ci_template->load("default", "activityLog/v_activity_log", $data);
    }

    // ajax call to the data listing
    public function getActivityLogListing($return = false,$data = array())
    {
        if($return === TRUE)
        {
            $_POST = $data;
        }
        $format = DATE_FORMATE_MYSQL;

        $sessionSamajId = $this->session->userdata('samaj_id');
        $this->load->library('datatables');
        $this->datatables->select("ac.activity_log_id,ac.description,ac.module,date_format(ac.date,'$format') as date");
        $this->datatables->select("ac.staff_id,concat(u.first_name,' ',u.last_name) as username,ac.user_browser,ac.user_platform,ac.user_ip");
        $this->datatables->from("tbl_activity_log as ac");
        $this->datatables->join("tbl_users as u",'u.id = ac.staff_id', 'left');
        if(!$this->ion_auth->is_admin()) {
            $this->datatables->where("ac.samaj_id IN (". $sessionSamajId . ")");
        }
        $params = $this->input->post('filterParams');
        if ($params['submit_btn'] && $params['submit_btn'][0] == 'true') {
            unset($params['submit_btn']);
            foreach ($params as $key => $param) {
                if (count(array_filter($param)) > 0) {
                    if (in_array($key, ['module', 'staff_id'])) {
                        $this->datatables->where_in("ac." . $key, $param);
                    }
                    else if (in_array($key, ['date'])) {
                        $dateRangeArr = explode(" - ", $param[0]);
                        $rangeFrom    = DMYToYMD($dateRangeArr[0]);
                        $rangeTo      = DMYToYMD($dateRangeArr[1]);
                        if($rangeFrom == $rangeTo) {
                            $this->datatables->where("date_format(ac.$key,'%Y-%m-%d')", $rangeFrom);
                        }else{
                            $this->datatables->where("date_format(ac.$key,'%Y-%m-%d')>=", $rangeFrom);
                            $this->datatables->where("date_format(ac.$key,'%Y-%m-%d')<=", $rangeTo);
                        }
                    }
                }
            }
        }

        $dataReport = $this->datatables->generate();

        $dataReport = json_decode($dataReport, true);
        $data = $dataReport['data'];

        if($return){
            return $data;
        }else{
            $dataReport['data'] = $data;

            echo json_encode($dataReport);
            exit;
        }
    }

    public function delete()
    {
        $activityLogId = $this->input->post('deleteId',TRUE);
        //delete  Activity Log
        $activityLogData = $this->Mdl_activity_log->deleteRecord($activityLogId);

        if ($activityLogData['success']) {
            $response['success']  = true;
            $response['msg']      = sprintf($this->lang->line('delete_record'),ACTIVITY_LOG);
        } else {
            $response['success']  = false;
            $response['msg']      = sprintf($this->lang->line('error_delete_record'),ACTIVITY_LOG);
        }

        echo json_encode($response);
    }

    public function exportActivityLog()
    {
        $dataTableData = $this->input->post('data');
//        printArray($dataTableData,1);

        if ($this->dt_ci_acl->checkAccess('Bin|index')) {
            $headerArr = array(
                "Description",
                "Module",
                "Username",
                "User Browser",
                "User Platform",
                "User IP",
                "Date"
            );

            $rows = $this->getActivityLogListing(true,$dataTableData);

            $dataRows = array();

            foreach ($rows as $row) {
                $row["date"] = "'".$row["date"];
                $dataRows[] = array(
                    $row["description"],
                    $row["module"],
                    $row["username"],
                    $row["user_browser"],
                    $row["user_platform"],
                    $row["user_ip"],
                    $row["date"],
                );
            }

            $downloadURL = $this->Mdl_activity_log->writeCsvZipExport("activity_log_report", $headerArr, $dataRows);
            echo  $downloadURL;
        }
    }
}

?>


