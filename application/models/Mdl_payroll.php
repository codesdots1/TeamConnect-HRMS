<?php
/**
 * Created by PhpStorm.
 * User: dt-user09
 * Date: 3/19/2019
 * Time: 12:36 PM
 */

class Mdl_payroll extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function GenerateSalarySlip($empId = '',$month = '')
	{
		$timePeriod = explode(" ",$month);
		$nmonth 	= date('m',strtotime($timePeriod[0]));
		$nyear 	    = $timePeriod[1];
		
		$this->db->select("e.emp_id,e.employee_code,CONCAT(e.first_name,' ',e.last_name) as emp_name,e.email,tg.gender_name,
		tsn_emp.state_name as emp_state,tcon_emp.country_name as emp_country,tc_emp.city_name as emp_city,
        e.status,et.type_id,et.type_name,tds.designation_id,tds.designation_name,td.department_id,td.dept_name,e.address,
        tc.city_id,tc.city_name,e.postal_code,tcon.country_id,tcon.country_name,ts.salary_id,ts.amount,ts.ip_no,ts.uan_no,
		date_format(e.hire_date,'" . DATE_FORMATE_MYSQL . "') as hire_date,
        tr.role_id,tr.role,tw.work_week_id,tw.days_per_week,tw.days_name,tw.title as work_week_title,
		tcom.company_id,tcom.company_name,tcom.address as comp_address,
        tes.shift_id,tes.shift_name,tsn.state_id,tsn.state_name,tad.account_details_id,tad.bank_name,tad.bank_code,tad.holder_name,
        tad.account_number,e.status,teds.employee_details_id,teds.last_employeer_name,teds.description,
        tad.account_details_id,tad.bank_name,tad.bank_code,tad.holder_name,tad.account_number,
        e.aadhar_card_no,e.pan_card_no,ts.esic,ts.epf,tmwd.month_work_id,tmwd.title as working_days_title");
		$this->db->from("tbl_employee as e");
		$this->db->join('tbl_gender as tg', 'e.gender_id = tg.gender_id', 'left');
		$this->db->join('tbl_account_details as tad', 'tad.emp_id = e.emp_id', 'left');
		$this->db->join('tbl_marital_status as tm', 'e.marital_status_id = tm.marital_status_id', 'left');
		$this->db->join('tbl_employee_file as ef', 'e.emp_id = ef.emp_id', 'left');
		$this->db->join('tbl_department as td', 'e.department_id = td.department_id', 'left');
		$this->db->join('tbl_designation as tds', 'e.designation_id = tds.designation_id', 'left');
		$this->db->join('tbl_companies as tcom', 'e.company_id = tcom.company_id', 'left');
		$this->db->join('tbl_city as tc', 'tcom.city_id = tc.city_id', 'left');
		$this->db->join('tbl_city as tc_emp', 'e.city_id = tc_emp.city_id', 'left');
		$this->db->join('tbl_country as tcon', 'tcom.country_id = tcon.country_id', 'left');
		$this->db->join('tbl_country as tcon_emp', 'e.country_id = tcon_emp.country_id', 'left');
		$this->db->join('tbl_salary as ts', 'e.emp_id = ts.emp_id', 'left');
		$this->db->join('tbl_role as tr', 'e.role_id = tr.role_id', 'left');
		$this->db->join('tbl_state as tsn', 'tcom.state_id = tsn.state_id', 'left');
		$this->db->join('tbl_state as tsn_emp', 'e.state_id = tsn_emp.state_id', 'left');
		$this->db->join('tbl_work_weeks as tw', 'e.work_week_id = tw.work_week_id', 'left');
		$this->db->join('tbl_employee_shift as tes', 'tes.shift_id = e.shift_id', 'left');
		$this->db->join('tbl_employee_details as teds', 'teds.emp_id = e.emp_id', 'left');
		$this->db->join('tbl_employee_type as et', 'et.type_id = e.type_id', 'left');
		$this->db->join('tbl_monthly_week_days as tmwd', 'tmwd.month_work_id = e.month_work_id', 'left');
		$this->db->where('e.emp_id', $empId);
		$result = $this->db->get()->row_array();

		$this->db->reset_query();
		$this->db->select('twm.month_work_id,twm.week_days_name,twm.work_week_state');
		$this->db->from('tbl_month_work_day as twm');
		$this->db->where('twm.month_work_id', $result['month_work_id']);
		$query = $this->db->get();
		$workingDays = $query->result_array();

		// Select no of public holidays available in this month
		$this->db->reset_query();
		$this->db->select('sum(total_days) as total_days');
		$this->db->from("tbl_holiday_calendar");
		$where = array(
			'Month(holiday_from_date)' => $nmonth,
			'YEAR(holiday_from_date)'  => $nyear,
			'Month(holiday_to_date)'   => $nmonth,
			'YEAR(holiday_to_date)'    => $nyear
		);
		$this->db->where($where);
		$this->db->group_by('Month(holiday_from_date),Year(holiday_from_date)');
		$query = $this->db->get()->row_array();
		$result['public_holidays'] = $query['total_days'];

		// Work Week Details
		//$daysName =  explode(",",$result['days_name']);
		$WD = 0; $WO = 0;
		$startDate = $nyear.'-'.$nmonth.'-'.'1';
		for($i = 1; $i <= date('t',strtotime($startDate)); $i++){
			$day = date('D',mktime(0, 0, 0, date($nmonth), $i,date($nyear) ));
			$date = $nyear.'-'.$nmonth.'-'.$i;
			$weekNo = $this->weekOfMonth($date);
			$workingDayArr = explode(",",$workingDays[$weekNo]['week_days_name']);
			if (in_array($day, $workingDayArr)) {
				$WD ++;
			} else {
				$WO ++;
			}
		}
		
		$result['working_day'] = $WD - $result['public_holidays'];
		$result['working_off'] = $WO;
		
		
		// Present daysName
		$this->db->reset_query();
		$this->db->select('count(*) as present_days');
		$this->db->from("tbl_employee_attendance");
		$this->db->where('Month(attendance_date)', $nmonth);
		$this->db->where('YEAR(attendance_date)', $nyear);
		$this->db->where('emp_id', $empId);
		$query = $this->db->get()->row_array();
		$result['present_days'] = $query['present_days'];
		
		// Leave Days
		$this->db->reset_query();
		$this->db->select('sum(tel.no_of_days) as total_days');
		$this->db->from("tbl_employee_leaves as tel");
		$this->db->join('tbl_leave_type as tlt', 'tlt.leave_type_id = tel.leave_type_id');
		$where = array(
			'Month(tel.leave_from_date)' => $nmonth,
			'YEAR(tel.leave_from_date)'  => $nyear,
			'Month(tel.leave_to_date)'   => $nmonth,
			'YEAR(tel.leave_to_date)'    => $nyear,
			'tel.emp_id' 			  	 => $empId,
			'tlt.payment_status' 		 => 'yes'
		);

		$this->db->where($where);
		$this->db->group_by('Month(tel.leave_from_date),Year(tel.leave_from_date)');
		$query = $this->db->get()->row_array();
		$result['pl'] = $query['total_days'];

		$LWP = $WD - $result['present_days'] - $result['public_holidays'] - $result['pl'] ;
		$result['lwp'] = $LWP;


		//Provisional Tax calculation
		if(strtolower($result['emp_country']) == 'india'){
			$pt = $this->CalculatePT($result['emp_state'],$result['amount'],$nmonth,$result['gender_name']);
			$result['pt'] = $pt;
		}else{
			$result['pt'] = 0;
		}
	
		$result['current_month'] =  $timePeriod[0];
		$result['current_year']  =  $nyear;

		return $result;
	}
	
	
	public function weekOfMonth($date) {
		$firstOfMonth = date("Y-m-01", strtotime($date));
		return intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth)));
	}
	
	public function CalculatePT($state = '',$salary = 0,$month = '', $gender ='')
	{
		$pt  = 0;
		if(strtolower($state) == 'andhra pradesh'){
		} else if($state == 'Andhra Pradesh'){
			
			if($salary <= 15000)
				$pt = 0;
			else if($salary > 15000 && $salary <= 20000)
				$pt = 150;
			else if($salary > 20000)
				$pt = 200;
			
		} else if(strtolower($state) == 'assam'){
			
			if($salary <= 10000)
				$pt = 0;
			else if($salary > 10000 && $salary <= 15000)
				$pt = 150;
			else if($salary > 15000 && $salary < 25000)
				$pt = 180;
			else if($salary >= 25000)
				$pt = 208;
			
		} else if(strtolower($state) == 'bihar'){
			if($salary <= 25000)
				$pt = 0;
			else if($salary > 25000 && $salary <= 41666)
				$pt = 83.33;
			else if($salary > 44666 && $salary <= 83333)
				$pt = 166.67;
			else if($salary > 83333)
				$pt = 208.33;
			
		} else if(strtolower($state) == 'goa'){
			
			if($salary <= 15000)
				$pt = 0;
			else if($salary > 15000 && $salary <= 25000)
				$pt = 150;
			else if($salary > 25000)
				$pt = 200;
			
		} else if(strtolower($state) == 'gujarat'){
			
			if($salary < 6000)
				$pt = 0;
			else if($salary >= 6000 && $salary < 9000)
				$pt = 80;
			else if($salary >= 9000 && $salary < 12000)
				$pt = 150;
			else if($salary >= 12000)
				$pt = 200;
		} else if(strtolower($state) == 'jharkhand'){
			
			if($salary <= 25000)
				$pt = 0;
			else if($salary > 25000 && $salary <= 41666)
				$pt = 100;
			else if($salary > 41666 && $salary <= 66666)
				$pt = 150;
			else if($salary > 66666 && $salary <= 83333)
				$pt = 175;
			else if($salary > 83333 && $month != 'dec')
				$pt = 208;
			else if($salary > 83333 && $month == 'dec')
				$pt = 212;
		} else if(strtolower($state) == 'karnataka'){
			
			if($salary <= 15000)
				$pt = 0;
			else if($salary > 15000 )
				$pt = 200;
			
		} else if(strtolower($state) == 'kerala'){
			
			if($salary < 2000)
				$pt = 0;
			else if($salary >= 2000 && $salary < 3000)
				$pt = 20;
			else if($salary >= 3000 && $salary < 5000)
				$pt = 30;
			else if($salary >= 5000 && $salary < 7500)
				$pt = 50;
			else if($salary >= 7500 && $salary < 10000)
				$pt = 75;
			else if($salary >= 10000 && $salary < 12500)
				$pt = 100;
			else if($salary >= 12500 && $salary < 16667)
				$pt = 125;
			else if($salary >= 16667 && $salary < 20834)
				$pt = 166;
			else if($salary >= 20834)
				$pt = 208;
		
		} else if(strtolower($state) == 'madhya pradesh'){
			
			if($salary <= 18750)
				$pt = 0;
			else if($salary > 18750 && $salary <= 25000)
				$pt = 125;
			else if($salary > 25000 && $salary <= 33333)
				$pt = 167;
			else if($salary > 33333 && $month != 'dec')
				$pt = 208;
			else if($salary > 33333 && $month == 'dec')
				$pt = 212;
			
		} else if(strtolower($state) == 'maharashtra'){
			
			if(strtolower($gender) == 'women'){
				if($salary <= 10000)
					$pt = 0;
			}else{
				if($salary <= 7500)
					$pt = 0;
			    else if($salary > 7500 && $salary <= 10000)
					$pt = 175;
			}
			
			if($salary > 10000 && $month != 'dec')
				$pt = 200;
			else if($salary > 10000 && $month == 'dec')
				$pt = 300;
			
		}
		else if(strtolower($state) == 'manipur'){
			
			if($salary <= 4250)
				$pt = 0;
			else if($salary > 4250 && $salary <= 6250)
				$pt = 100;
			else if($salary > 6250 && $salary <= 8333)
				$pt = 167;
			else if($salary > 8333 && $salary <= 10416)
				$pt = 200;
			else if($salary > 10416 && $month != 'dec')
				$pt = 208;
			else if($salary > 10416 && $month == 'dec')
				$pt = 212;
			
		}
		else if(strtolower($state) == 'meghalaya'){
			
			if($salary <= 4166)
				$pt = 0;
			else if($salary > 4166 && $salary <= 6250)
				$pt = 16.50;
			else if($salary > 6250 && $salary <= 8333)
				$pt = 25;
			else if($salary > 8333 && $salary <= 12500)
				$pt = 41.50;
			else if($salary > 12500 && $salary <= 16666)
				$pt = 62.50;
			else if($salary > 16666 && $salary <= 20833)
				$pt = 83.33;
			else if($salary > 20833 && $salary <= 25000)
				$pt = 104.16;
			else if($salary > 25000 && $salary <= 29166)
				$pt = 125;
			else if($salary > 29166 && $salary <= 33333)
				$pt = 150;
			else if($salary > 33333 && $salary <= 37500)
				$pt = 175;
			else if($salary > 37500 && $salary <= 41666)
				$pt = 200;
			else if($salary > 41666 && $month != 'dec')
				$pt = 208;
			else if($salary > 41666 && $month == 'dec')
				$pt = 212;
			
		}else if(strtolower($state) == 'nagaland'){
			
			if($salary <= 4000)
				$pt = 0;
			else if($salary > 4000 && $salary <= 5000)
				$pt = 35;
			else if($salary > 5000 && $salary <= 7000)
				$pt = 75;
			else if($salary > 7000 && $salary <= 9000)
				$pt = 110;
			else if($salary > 9000 && $salary <= 12000)
				$pt = 180;
			else if($salary > 12000)
				$pt = 208;
		}else if(strtolower($state) == 'orissa'){

			if($salary <= 13304)
				$pt = 0;
			else if($salary > 13304 && $salary <= 25000)
				$pt = 125;
			else if($salary > 25000 && $month != 'dec')
				$pt = 200;
			else if($salary > 25000 && $month == 'dec')
				$pt = 300;			
		}
		else if(strtolower($state) == 'pondicherry'){
			
			if($salary <= 16666)
				$pt = 0;
			else if($salary > 16666 && $salary <= 33333)
				$pt = 41.66;
			else if($salary > 33333 && $salary <= 50000)
				$pt = 83.33;
			else if($salary > 50000 && $salary <= 66666)
				$pt = 125;
			else if($salary > 66666 && $salary <= 83333)
				$pt = 166.67;
			else if($salary > 83333 )
				$pt = 208.33;			
		}else if(strtolower($state) == 'punjab'){
			
			if($salary < 20833)
				$pt = 0;
			else if($salary >= 20833)
				$pt = 200;			
		}else if(strtolower($state) == 'sikkim'){
			
			if($salary <= 20000)
				$pt = 0;
			else if($salary > 20000 && $salary <= 30000)
				$pt = 125;
			else if($salary > 30000 && $salary <= 40000)
				$pt = 150;
			else if($salary > 40000 )
				$pt = 200;			
		}
		else if(strtolower($state) == 'tamil nadu'){
			
			if($salary <= 3500)
				$pt = 0;
			else if($salary > 3500 && $salary <= 5000)
				$pt = 22.50;
			else if($salary > 5000 && $salary <= 7500)
				$pt = 52.50;
			else if($salary > 7500 && $salary <= 10000)
				$pt = 115;
			else if($salary > 10000 && $salary <= 12500)
				$pt = 171;
			else if($salary > 12500 )
				$pt = 208;		
			
		}else if(strtolower($state) == 'telangana'){
			
			if($salary <= 15000)
				$pt = 0;
			else if($salary > 15000 && $salary <= 20000)
				$pt = 150;
			else if($salary > 20000 )
				$pt = 200;	

		}else if(strtolower($state) == 'tripura'){
			
			if($salary <= 5000)
				$pt = 0;
			else if($salary > 5000 && $salary <= 7000)
				$pt = 70;
			else if($salary > 7000 && $salary <= 9000)
				$pt = 120;
			else if($salary > 9000 && $salary <= 12000)
				$pt = 140;
			else if($salary > 12000 && $salary <= 15000)
				$pt = 190;
			else if($salary > 15000 )
				$pt = 208;			
		}
		else if(strtolower($state) == 'west bengal'){
			
			if($salary <= 10000)
				$pt = 0;
			else if($salary > 10000 && $salary <= 15000)
				$pt = 110;
			else if($salary > 15000 && $salary <= 25000)
				$pt = 130;
			else if($salary > 25000 && $salary <= 40000)
				$pt = 150;
			else if($salary > 40000 )
				$pt = 200;			
		}else{
			$pt = 0;
		}
		return $pt;
	}
}
