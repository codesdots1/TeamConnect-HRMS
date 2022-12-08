<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @name manage_permissions
 * @uses Manage permissions for employee and branch users
 * @author Kamlesh
 */
class Permission extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    /**
     * @name permissions()
     * @uses Manage permissions for light admin
     * @author KAT
     * */
    public function manage($type, $admin_id) {
        $data['extra_js'] = array(
            "js/plugins/tables/datatables/datatables.min.js",
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/plugins/forms/jquery.form.min.js",
            "js/plugins/forms/selects/select2.min.js",
        );
        if (is_numeric($admin_id)) {
            $where = 'emp_id = ' . $this->db->escape($admin_id);
            $user_data = $this->Mdl_employee->getEmployeeListing('tbl_employee', 'emp_id', $where, array('single' => TRUE));
            if (count($user_data) > 0) {
                $data['heading'] = 'Manage Light Admin Permissions';
                $data['user_detail'] = $user_data;
                $role_id = $this->session->userdata('role_id');
                $where = '';
                if($type == 'Employee') {
                    $where = ' controller_name != "Employee"';
                } elseif($type == 'EmployeeAttendance') {
                    $where = ' controller_name != "Employee" && controller_name != "EmployeeAttendance"';
                }
                $data['all_admin_purmissions'] = $this->Mdl_common_model->sql_select('tbl_controller_detail', 'id, name, controller_name', $where);
                $data['user_purmissions'] = array_column($this->Mdl_common_model->sql_select('tbl_users_permission', 'controller_detail_id', "emp_id = $admin_id"), 'controller_detail_id');
                $ids = $this->input->post('id_list');
                if ($this->input->method() == 'post') {
                    $where = array('emp_id' => $admin_id);
                    $this->Mdl_common_model->delete(['emp_id' => $admin_id], 'tbl_users_permission');
                    foreach ($ids as $value) {
                        $insert_array['emp_id'] = $admin_id;
                        $insert_array['controller_detail_id'] = $value;
                        $this->Mdl_common_model->insert('tbl_users_permission', $insert_array);
                    }
                    $this->session->set_flashdata('success', 'Light admin\'s permissions successfully updated.');
                    if($type == 'Employee') {
                        redirect('Employee');
                    } else {
                        redirect('manage');
                    }
                }
                $this->dt_ci_template->load("default", "permissions/permissions", $data);
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                if($type == 'Employee') {
                    redirect('Employee');
                } else {
                    redirect('EmployeeAttendance');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
            if($type == 'Employee') {
                redirect('Employee');
            } else {
                redirect('EmployeeAttendance');
            }
        }
    }
}
