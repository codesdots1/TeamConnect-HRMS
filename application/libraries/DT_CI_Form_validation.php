<?php

class DT_CI_Form_validation extends CI_Form_validation
{

  public function edit_unique($str, $field)
    {	
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $id);

        $variable_id = '';
		if ($field == "country_name") {
			$variable_id = 'country_id';
		}
		elseif ($field == "team_name" && $table == 'tbl_team') {
			$variable_id = 'team_id';
		}
		elseif ($field == "team_name" && $table == 'tbl_project_management') {
			$variable_id = 'project_manage_id';
		}
		elseif ($field == "state_name") {
			$variable_id = 'state_id';
		}
		elseif ($field == "city_name") {
			$variable_id = 'city_id';
		}
        elseif ($field == "gender_name") {
            $variable_id = 'gender_id';
        }
        elseif ($field == "marital_status") {
            $variable_id = 'marital_status_id';
        }
        elseif ($field == "dept_name") {
            $variable_id = 'department_id';
        }
        elseif ($field == "amount" || $field == "esic" || $field == "pf" ) {
            $variable_id = 'salary_id';
        }
        elseif ($field == "title" || $field == "role" ) {
            $variable_id = 'role_id';
        }
        elseif (($field == "first_name" || $field == "password" || $field == "employee_code" || $field == "email") && ($table == 'tbl_employee')) {
            $variable_id = 'emp_id';
        }
		elseif ($field == "emp_name") {
            $variable_id = 'emp_id';
        }
		elseif ($field == "type_name") {
            $variable_id = 'type_id';
        }
		elseif ($field == "designation_name") {
            $variable_id = 'designation_id';
        }
		elseif ($field == "leave_type_id") {
            $variable_id = 'leave_id';
        }
		elseif ($field == "project_name") {
            $variable_id = 'project_id';
        }
		elseif ($field == "task_name") {
            $variable_id = 'task_id';
        }
		elseif ($field == "leave_reason_name") {
			$variable_id = 'leave_reason_id';
		}
		elseif ($field == "leave_type") {
            $variable_id = 'leave_type_id';
        }
		elseif ($field == "title") {
            $variable_id = 'month_work_id';
        }
        elseif (($field == "company_name" || $field == "address" || $field == "email") && ($table == 'tbl_companies')) {
            $variable_id = 'company_id';
        }
        elseif ($field == "bank_name" || $field == "holder_name" || $field == "account_number" || $field == "bank_code") {
            $variable_id = 'account_details_id';
        }
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str, $variable_id . ' !=' => $id))->num_rows() === 0)
            : FALSE;
    }


}
