<?php defined('BASEPATH') OR exit('No direct script access allowed');

class DT_CI_Controller extends CI_Controller {

    function __construct()
    { 
        parent::__construct();

        $this->data['userId'] = $this->ion_auth->get_user_id();
        $this->load->model("DT_CI_Model");

        if(!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        $global_data = array('defaultCompanyId' => $this->session->userdata('company_id'));

        //Send the data into the current view
        //http://ellislab.com/codeigniter/user-guide/libraries/loader.html
        $this->load->vars($global_data);
        $this->data['fields'] = 1;
//        $this->data['defaultCompanyId'] = $this->session->userdata('company_id');
        $this->lang->load('common_message');
    }

    function page_construct($page, $meta = array(), $data = array()) {
        $meta['message'] = isset($data['message']) ? $data['message'] : $this->session->flashdata('message');
        $meta['error'] = isset($data['error']) ? $data['error'] : $this->session->flashdata('error');
        $meta['warning'] = isset($data['warning']) ? $data['warning'] : $this->session->flashdata('warning');
//        $meta['info'] = $this->site->getNotifications();
//        $meta['events'] = $this->site->getUpcomingEvents();
        $meta['ip_address'] = $this->input->ip_address();
        $meta['Settings'] = $this->data['Settings'];
        $meta['assets'] = $this->data['assets'];
        $meta['GP'] = $this->data['GP'];
        $this->load->view($this->theme . 'v_header', $meta);
        $this->load->view($this->theme . $page, $data);
        $this->load->view($this->theme . 'v_footer');
    }

    function _remap($method,$arguments)
    {
        if(is_numeric($method)){
            $method = "index";
        }
        if (method_exists( $this, $method) ) {
            return call_user_func_array(array($this, $method), $arguments);
        } else if(method_exists( $this->DT_CI_Model, $method)) {
//            $Model = new DT_CI_Model();
            return call_user_func_array(array($this->DT_CI_Model, $method), $arguments);
        } else {
            $this->dt_ci_template->load("auth", "templates/v_error_404", $this->data);
        }
    }
}
