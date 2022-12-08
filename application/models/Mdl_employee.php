<?php


class Mdl_employee extends DT_CI_Model
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}

	public function getEmployeeData($employeeId)
	{
		$imagePath = IMAGE_DIR_URL . $this->config->item('employee_image');
		$this->db->select("e.emp_id,e.employee_code,e.first_name,e.last_name,e.email,e.password,e.mobile_no,tg.gender_id,tg.gender_name,
		tm.marital_status_id,tm.marital_status,date_format(e.birth_date,'" . DATE_FORMATE_MYSQL . "') as birth_date,e.age,date_format(e.hire_date,'" . DATE_FORMATE_MYSQL . "') as hire_date,
        e.status,et.type_id,et.type_name,tds.designation_id,tds.designation_name,td.department_id,td.dept_name,e.address,
        tc.city_id,tc.city_name,e.postal_code,tcon.country_id,tcon.country_name,ts.salary_id,ts.amount,
        tr.role_id,tr.role,tw.work_week_id,tw.days_per_week,tw.days_name,CONCAT(tw.title,' | ',tw.days_per_week) as days_title,tcom.company_id,tcom.company_name,
        tes.shift_id,tes.shift_name,tsn.state_id,tsn.state_name,teds.employee_details_id,teds.last_employeer_name,teds.description,
        tad.account_details_id,tad.bank_name,tad.bank_code,tad.holder_name,tad.account_number,ts.esic,ts.ip_no,ts.epf,ts.uan_no,
        e.aadhar_card_no,e.pan_card_no,tmwd.month_work_id,tmwd.title as working_days_title");
		$this->db->select('COALESCE(CONCAT("' . $imagePath . '",ef.filename),"") as employee_image');
		$this->db->from("tbl_employee as e");
		$this->db->join('tbl_gender as tg', 'e.gender_id = tg.gender_id', 'left');
		$this->db->join('tbl_marital_status as tm', 'e.marital_status_id = tm.marital_status_id', 'left');
		$this->db->join('tbl_employee_file as ef', 'e.emp_id = ef.emp_id', 'left');
		$this->db->join('tbl_department as td', 'e.department_id = td.department_id', 'left');
		$this->db->join('tbl_designation as tds', 'e.designation_id = tds.designation_id', 'left');
		$this->db->join('tbl_city as tc', 'e.city_id = tc.city_id', 'left');
		$this->db->join('tbl_country as tcon', 'e.country_id = tcon.country_id', 'left');
		$this->db->join('tbl_salary as ts', 'e.emp_id = ts.emp_id', 'left');
		$this->db->join('tbl_role as tr', 'e.role_id = tr.role_id', 'left');
		$this->db->join('tbl_state as tsn', 'e.state_id = tsn.state_id', 'left');
		$this->db->join('tbl_work_weeks as tw', 'e.work_week_id = tw.work_week_id', 'left');
		$this->db->join('tbl_companies as tcom', 'e.company_id = tcom.company_id', 'left');
		$this->db->join('tbl_employee_shift as tes', 'tes.shift_id = e.shift_id', 'left');
		$this->db->join('tbl_employee_details as teds', 'teds.emp_id = e.emp_id', 'left');
		$this->db->join('tbl_account_details as tad', 'tad.emp_id = e.emp_id', 'left');
		$this->db->join('tbl_employee_type as et', 'et.type_id = e.type_id', 'left');
		$this->db->join('tbl_monthly_week_days as tmwd', 'tmwd.month_work_id = e.month_work_id', 'left');
		$this->db->where('e.emp_id', $employeeId);
		$query = $this->db->get()->row_array();
		$data = $query;
		return $data;
	}

	public function getEmployeeInfoData($employeeId)
	{
		$imagePath = IMAGE_DIR_URL . $this->config->item('employee_image');
		$this->db->select("e.emp_id,e.employee_code,e.first_name,e.last_name,e.email,e.password,e.mobile_no,tg.gender_id,tg.gender_name,
		tm.marital_status_id,tm.marital_status,date_format(e.birth_date,'" . DATE_FORMATE_MYSQL . "') as birth_date,e.age,
		date_format(e.hire_date,'" . DATE_FORMATE_MYSQL . "') as hire_date,
        e.status,et.type_id,et.type_name,tds.designation_id,tds.designation_name,td.department_id,td.dept_name,e.address,
        tc.city_id,tc.city_name,e.postal_code,tcon.country_id,tcon.country_name,ts.salary_id,ts.amount,ts.ip_no,ts.uan_no,
        tr.role_id,tr.role,tw.work_week_id,tw.days_per_week,tw.days_name,tw.title as work_week_title,tcom.company_id,tcom.company_name,
        tes.shift_id,tes.shift_name,tsn.state_id,tsn.state_name,tad.account_details_id,tad.bank_name,tad.bank_code,tad.holder_name,
        tad.account_number,e.status,teds.employee_details_id,teds.last_employeer_name,teds.description,
        tad.account_details_id,tad.bank_name,tad.bank_code,tad.holder_name,tad.account_number,
        e.aadhar_card_no,e.pan_card_no,ts.esic,ts.epf");
		$this->db->select('COALESCE(CONCAT("' . $imagePath . '",ef.filename),"") as employee_image');
		$this->db->from("tbl_employee as e");
		$this->db->join('tbl_gender as tg', 'e.gender_id = tg.gender_id', 'left');
		$this->db->join('tbl_account_details as tad', 'tad.emp_id = e.emp_id', 'left');
		$this->db->join('tbl_marital_status as tm', 'e.marital_status_id = tm.marital_status_id', 'left');
		$this->db->join('tbl_employee_file as ef', 'e.emp_id = ef.emp_id', 'left');
		$this->db->join('tbl_department as td', 'e.department_id = td.department_id', 'left');
		$this->db->join('tbl_designation as tds', 'e.designation_id = tds.designation_id', 'left');
		$this->db->join('tbl_city as tc', 'e.city_id = tc.city_id', 'left');
		$this->db->join('tbl_country as tcon', 'e.country_id = tcon.country_id', 'left');
		$this->db->join('tbl_salary as ts', 'e.emp_id = ts.emp_id', 'left');
		$this->db->join('tbl_role as tr', 'e.role_id = tr.role_id', 'left');
		$this->db->join('tbl_state as tsn', 'e.state_id = tsn.state_id', 'left');
		$this->db->join('tbl_work_weeks as tw', 'e.work_week_id = tw.work_week_id', 'left');
		$this->db->join('tbl_companies as tcom', 'e.company_id = tcom.company_id', 'left');
		$this->db->join('tbl_employee_shift as tes', 'tes.shift_id = e.shift_id', 'left');
		$this->db->join('tbl_employee_details as teds', 'teds.emp_id = e.emp_id', 'left');
		$this->db->join('tbl_employee_type as et', 'et.type_id = e.type_id', 'left');
		$this->db->where('e.emp_id', $employeeId);
		$query = $this->db->get()->row_array();
		$data = $query;
		return $data;
	}

	public function getImage($employeeId)
	{
		$this->db->select("employee_file_id,emp_id,filename");
		$this->db->from('tbl_employee_file');
		$this->db->where_in('emp_id', $employeeId);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
		
	}

	public function getCountryId($countryName)
	{
		$this->db->select("country_id");
		$this->db->from('tbl_country');
		$this->db->where('Lower(country_name)', $countryName);
//		$this->db->where('country_name', $countryName);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;

	}

	public function deleteRecord($employeeId)
	{
		$tables = array('tbl_employee','tbl_employee_file','tbl_employee_details','tbl_account_details','tbl_salary','tbl_login_details');
		$this->db->where_in('emp_id',$employeeId);
		$this->db->delete($tables);
		$ids = is_array($employeeId) ? implode(',',$employeeId) : $employeeId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}else{
			$response['success'] = "false";
		}

		return $response;
	}

	public function deleteEmployeeImageEntry($imageId)
	{
		$this->db->where('employee_file_id', $imageId);
		$this->db->delete('tbl_employee_file');

		$ids = is_array($imageId) ? implode(',', $imageId) : $imageId;

		if ($this->db->affected_rows()) {
			$response['success'] = true;
			return $response;
		} else {
			$response['success'] = false;
			return $response;
		}
	}
	
	public function insertUpdateEmployeeImageEntry($dataArray)
	{
		
		$this->db->select("employee_file_id,emp_id,filename");
		$this->db->from('tbl_employee_file');
		$this->db->where('emp_id', $dataArray['emp_id']);
		$query = $this->db->get();
		$result = $query->row_array();
	
		$imageUrl    = $this->config->item('employee_image').$result['filename'];
		
		if(! empty($result)){
			$this->db->where('emp_id', $dataArray['emp_id']);
            $employeeData  =  $this->db->update('tbl_employee_file', $dataArray);
			if (file_exists($imageUrl)) {
				unlink($imageUrl);
			}
			
		}else{
			 $this->db->insert('tbl_employee_file', $dataArray);
		}
	}

	public function makeQuery()
	{
		$imagePath = IMAGE_DIR_URL . $this->config->item('employee_image');
		//$orderColumn = array('e.emp_id','ef.filename','e.employee_code','CONCAT(e.first_name," ",e.last_name) as emp_name','tr.role','e.status');
		$orderColumn = array('e.emp_id','ef.filename','e.employee_code','e.first_name','tr.role','e.status');
		$this->db->select("e.emp_id,e.employee_code,CONCAT(e.first_name,' ',e.last_name) as emp_name,e.email,e.password,e.mobile_no,tg.gender_name,tm.marital_status,
        date_format(e.birth_date,'" . DATE_FORMATE_MYSQL . "') as birth_date,e.age,date_format(e.hire_date,'" . DATE_FORMATE_MYSQL . "') as hire_date,
        e.status,et.type_id,et.type_name,tds.designation_id,tds.designation_name,td.dept_name,e.address,tc.city_name,e.postal_code,tcon.country_name,ts.amount
        ,tr.role,tw.title,tcom.company_name,tes.shift_id,tes.shift_name,tsn.state_id,tsn.state_name,teds.employee_details_id
        ,teds.last_employeer_name,teds.description");
		$this->db->select('COALESCE(CONCAT("' . $imagePath . '",ef.filename),"") as employee_image');
		$this->db->from("tbl_employee as e");
		$this->db->where('e.emp_id !=',$_POST['empId']);
		$this->db->where('LOWER(tr.role) !=','admin');
		$this->db->join('tbl_gender as tg', 'e.gender_id = tg.gender_id', 'left');
		$this->db->join('tbl_marital_status as tm', 'e.marital_status_id = tm.marital_status_id', 'left');
		$this->db->join('tbl_employee_file as ef', 'e.emp_id = ef.emp_id', 'left');
		$this->db->join('tbl_department as td', 'e.department_id = td.department_id', 'left');
		$this->db->join('tbl_designation as tds', 'e.designation_id = tds.designation_id', 'left');
		$this->db->join('tbl_state as tsn', 'e.state_id = tsn.state_id', 'left');
		$this->db->join('tbl_city as tc', 'e.city_id = tc.city_id', 'left');
		$this->db->join('tbl_country as tcon', 'e.country_id = tcon.country_id', 'left');
		$this->db->join('tbl_salary as ts', 'e.emp_id = ts.emp_id', 'left');
		$this->db->join('tbl_role as tr', 'e.role_id = tr.role_id', 'left');
		$this->db->join('tbl_work_weeks as tw', 'e.work_week_id = tw.work_week_id', 'left');
		$this->db->join('tbl_companies as tcom', 'e.company_id = tcom.company_id', 'left');
		$this->db->join('tbl_employee_shift as tes', 'tes.shift_id = e.shift_id', 'left');
		$this->db->join('tbl_employee_details as teds', 'teds.emp_id = e.emp_id', 'left');
		$this->db->join('tbl_employee_type as et', 'et.type_id = e.type_id', 'left');
		
		if (isset($_POST['empStatus']) && $_POST['empStatus'] == 'CurrentEmployee') {
			$this->db->where('e.status',1);
		}else if (isset($_POST['empStatus']) && $_POST['empStatus'] == 'PastEmployee') {
			$this->db->where('e.status',0);
		}
		if (isset($_POST['search']['value'])) {
			//$this->db->like('concat(e.emp_name,e.email,e.employee_code)', $_POST['search']['value']);
			$this->db->like('concat(e.first_name,e.last_name,e.email,e.employee_code)', $_POST['search']['value']);
		}
		
		if (isset($_POST['order'])) {
			$this->db->order_by($orderColumn[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		}else{
			$this->db->order_by('e.emp_id','desc');
		}
	}

	public function getEmployeeListing()
	{
		$this->makeQuery();
		if($_POST["length"] != -1){
			$this->db->limit($_POST["length"], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();


	}

	public function getFilteredData()
	{
		$this->makeQuery();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function getAllData()
	{
		$this->db->select("*");
		$this->db->from("tbl_employee");
		return $this->db->count_all_results();
	}

	function getEmployeeListDD($data)
	{
		$this->db->select("te.emp_id as id,concat(te.first_name,' ',te.last_name,' |  ',te.email) as text");
		$this->db->from('tbl_employee as te');
		$this->db->join('tbl_role as tr', 'te.role_id = tr.role_id', 'left');
		$this->db->where('LOWER(tr.role) !=','admin');
		if($data['ex_emp'] == 'yes'){

		}else{
			$this->db->where('te.status !=',0);
		}
		if (isset($data['emp_ids'])){
			$this->db->where_in('te.emp_id',$data['emp_ids']);
		}
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("concat(te.first_name,' ',te.last_name)", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		if (isset($data['all_employee']) && $data['all_employee'] == 'true') {
				$arr = array( 
				'id' => 'all',
				'text' => 'All Employee'
				);
				array_unshift($result['result'] ,$arr);
			}
			
		return json_encode($result);
	}

	public function insertInLogin($empId='')
	{
		$dataArray = array('emp_id' => $empId);
		$this->db->insert('tbl_login_details', $dataArray);
	}
	
	public function getRole($empId=''){
		if($empId != ""){
			$this->db->select('tr.role');
			$this->db->where('te.emp_id',$empId);
			$this->db->from('tbl_employee as te');
			$this->db->join('tbl_role as tr','te.role_id = tr.role_id');
			$data = $this->db->get()->row_array();
			return $data;
			
			
		}
	}
	
	public function getLoginEmployee($empId=''){
		if($empId != ""){
			$this->db->select("tr.role,te.emp_id,CONCAT(te.first_name,' ',te.last_name,' | ',te.email) as emp_name");
			$this->db->where('te.emp_id',$empId);
			$this->db->where('LOWER(tr.role) !=','admin');
			$this->db->from('tbl_employee as te');
			$this->db->join('tbl_role as tr','te.role_id = tr.role_id');
			$data = $this->db->get()->row_array();
			return $data;
			
			
		}
	}
	
	public function getEmployeeEmail($empId=''){
		if($empId != ""){
			$this->db->select("email,CONCAT(first_name,' ',last_name) as emp_name");
			$this->db->where('emp_id',$empId);
			$this->db->from('tbl_employee');
			$data = $this->db->get()->row_array();
			return $data;
			
		}
	}
	
	function getTeamHeadListDD($data)
	{
		$this->db->select("te.emp_id as id,concat(te.first_name,' ',te.last_name,' |  ',te.email,' |  ',tr.role) as text");
		$this->db->from('tbl_employee as te');
		$this->db->join('tbl_role as tr', 'te.role_id = tr.role_id', 'left');
		//$where = '(LOWER(tr.role)="team leader" or LOWER(tr.role) = "project manager")';
		$where = '(LOWER(tr.role)="team leader")';
        $this->db->where($where);
		$this->db->where('te.status !=',0);
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("concat(te.first_name,' ',te.last_name)", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}
	
	function getTeamMembersListDD($data)
	{
		if($data['teamhead_id'] == ""){
			$result['result'] = array ( array ( 'id' => 0 , 'text' => 'First Select Team Head...' ));
		}else{
			$roleDetails = $this->getRole($data['teamhead_id']);
			$this->db->select("te.emp_id as id,concat(te.first_name,' ',te.last_name,' |  ',te.email) as text");
			$this->db->from('tbl_employee as te');
			$this->db->join('tbl_role as tr', 'te.role_id = tr.role_id');
			if(isset($roleDetails['role']) && strtolower($roleDetails['role']) == 'project manager'){
				$where = '(LOWER(tr.role)="team leader" or LOWER(tr.role) = "employee")';
				$this->db->where($where);
			}else{
				$this->db->where('LOWER(tr.role)','employee');
			}
			$this->db->where('te.status !=',0);
			if (isset($data['filter_param']) && $data['filter_param'] != '') {
				$this->db->like("concat(te.first_name,' ',te.last_name)", $data['filter_param'], 'both');
			}
			
			$query = $this->db->get();
			$result['result'] = $query->result_array();
		}
		return json_encode($result);
	}

	public function getTLMembersDD($data){
		if($data['team_head_id'] == ""){
			$result['result'] = array ( array ( 'id' => 0 , 'text' => 'First Select Team Head...' ));
		}else{
			$this->db->select('tt.emp_id_listing');
			$this->db->from('tbl_team as tt');
			$this->db->where('tt.emp_id', $data['team_head_id']);
			$query = $this->db->get();
			$queryData = $query->row_array();
			$emp_ids = explode(",",$queryData['emp_id_listing']);

			$this->db->select("te.emp_id as id,concat(te.first_name,' ',te.last_name,' |  ',te.email) as text");
			$this->db->from('tbl_employee as te');
			if(isset($emp_ids) && $emp_ids != "")
				$this->db->where_in('te.emp_id', $emp_ids);

			if (isset($data['filter_param']) && $data['filter_param'] != '') {
				$this->db->like("concat(te.first_name,' ',te.last_name)", $data['filter_param'], 'both');
			}
			$query = $this->db->get();
			$result['result'] = $query->result_array();
		}
		return json_encode($result);
	}
	
	public function getEmployeeCode(){
	    $this->db->select("max(Convert(employee_code, SIGNED)) as code");
		$this->db->from('tbl_employee');
		$data = $this->db->get()->row_array();
		$number = substr($data['code'],2);
		$number = $data['code'] + 1;
		return '00'.$number;
	}

	public function getTotalEmployee(){
	    $this->db->select("count(*) as empName");
		$this->db->from('tbl_employee');
		$this->db->where('status',1);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getEmployeeBirthDate(){
		//$imagePath = IMAGE_DIR_URL . $this->config->item('employee_image');
	    $this->db->select('CONCAT(e.first_name," ",e.last_name) as empBirthday ');
	    //$this->db->select('COALESCE(CONCAT("' . $imagePath . '",ef.filename),"") as employee_image');
		$this->db->from('tbl_employee as e');
		//$this->db->join('tbl_employee_file as ef', 'e.emp_id = ef.emp_id', 'left');
		$this->db->where('DATE(e.birth_date)=CURDATE()');
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getUpcomingHolidays(){
	    $this->db->select("thc.title,date_format(thc.holiday_from_date,'" . DATE_FORMATE_MYSQL . "') as holiday_from_date");
		$this->db->from('tbl_holiday_calendar as thc');
		$this->db->where('thc.holiday_from_date > CURDATE()');
		$query = $this->db->get();
		$queryData = $query->result_array();
		return $queryData;
	}
	public function getTeamLeads(){
		$imagePath = IMAGE_DIR_URL . $this->config->item('employee_image');
	    $this->db->select("CONCAT(te.first_name,' ',te.last_name) as teamHead,tt.team_name");
		$this->db->select('COALESCE(CONCAT("' . $imagePath . '",ef.filename),"") as employee_image');
		$this->db->from('tbl_employee as te');
		$this->db->join('tbl_role as tr','te.role_id = tr.role_id');
		$this->db->join('tbl_employee_file as ef', 'te.emp_id = ef.emp_id', 'left');
		$this->db->join('tbl_team as tt','tt.emp_id = te.emp_id');
		$this->db->where('tr.role','team leader');
		$query = $this->db->get();
		$queryData = $query->result_array();
		return $queryData;
	}

	public function getRecentActivities(){
	    $this->db->select("tp.project_name as name,date_format(tp.created_at,'" . DATE_FORMATE_MYSQL . "') as created_at");
		$this->db->from('tbl_project as tp');
		$this->db->where('DATE(tp.created_at) = CURDATE()');
		$query = $this->db->get();
		$queryData = $query->result_array();
		return $queryData;
	}
	public function getRecentActivity(){
	    $this->db->select("CONCAT(te.first_name,' ',te.last_name) as employeeName,date_format(te.hire_date,'" . DATE_FORMATE_MYSQL . "') as hire_date");
		$this->db->from('tbl_employee as te');
		$this->db->where('DATE(te.hire_date) = CURDATE()');
		$query = $this->db->get();
		$queryData = $query->result_array();
		return $queryData;
	}

	public function getTotalWorkingProject()
	{
		$this->db->select("count(*) as workingProject");
		$this->db->from('tbl_project_management as tpm');
		$this->db->where('tpm.emp_id_listing',$this->session->userdata['emp_id']);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}
	public function getTotalTeamProject()
	{
		$this->db->select("count(*) as totalProject");
		$this->db->from('tbl_task_management as ttm');
		$this->db->where('ttm.emp_id_listing',$this->session->userdata['emp_id']);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

}
