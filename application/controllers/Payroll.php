<?php
class Payroll extends DT_CI_Controller
{

	public function __construct(){
		parent::__construct();
		$this->load->model("Mdl_payroll");
		$this->lang->load('payroll');
		$this->load->library('Dt_mpdf');
	}

	public function index(){
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/media/fancybox.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/core/libraries/jquery_ui/widgets.min.js",
			"/js/plugins/pickers/anytime.min.js",
			"/js/maps/jquery.geocomplete.js",
		);
		$select2 = array(
			'employeeName'   => true
		);

		if($this->dt_ci_acl->checkAccess('Payroll|index')){
			$data['bulk_upload_url'] = base_url('Payroll/ajaxUploadFile');
			$data['bulk_upload_sample_download'] = array(
				"Payroll" => base_url('download/sample_upload/sample_employee.csv')
			);
			$data['supporting_views'] = array('v_salary_bulk_upload',"v_common_js", "v_select2");
			$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);
			$this->dt_ci_template->load("default", "payroll/v_payroll", $data);
		}
	}
	
	public function GenerateSalarySlip($empId='',$month=''){
		if($empId ==  '' && $month == ''){	
			$empId = $this->input->post('emp_id');
			$month = $this->input->post('month');
		}
        $results = $this->Mdl_payroll->GenerateSalarySlip($empId,$month);
        $data['EmpData'] = $results;
        $salary_view = $this->load->view('payroll/v_salary_slip', $data, TRUE);
        echo json_encode($salary_view);
	}


	function ExportToPdf()
	{
		$empId = $this->input->post('emp_id');
		$month = $this->input->post('month');
		$results = $this->Mdl_payroll->GenerateSalarySlip($empId,$month);
		$data    = $results;
		$html = $this->load->view('payroll/v_salary_pdf',['EmpData' => $data], true);
		$pdfFilePath ="EmployeeSalarySlip-".time().".pdf";
		$pdf = $this->dt_mpdf->generate($html,$pdfFilePath,"D",null);
		
	}

	public function ajaxUploadFile($return = false, $uploadType = 0, $file = array())
	{
		$this->load->library('bulkUploads/Payroll_uploads');
		$userId     = $this->ion_auth->get_user_id();
		$dirAbsPath = EMP_SALARY_PATH;
		$response   = $this->dt_ci_common->uploadFile($_FILES, $dirAbsPath, $file);
		if(isset($response['success']) && $response['success']){
			$data = $this->payroll_uploads->uploadEmployeeSalarySlip($userId, $return, $file);
			echo json_encode($data);
		}
	}
}
