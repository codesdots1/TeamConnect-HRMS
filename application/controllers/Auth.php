<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'),
            $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->data['extra_js'] = array(
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/selects/select2.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/pages/form_layouts.js",
            "js/plugins/forms/jquery.form.min.js",
            "js/plugins/forms/validation/validate.min.js",
        );
		$this->lang->load('auth');


        $this->lang->load('common_message');
        $this->lang->load('login');
        $this->lang->load('otp');
        $this->identity_column = $this->config->item('identity', 'ion_auth');
        $this->tables  = $this->config->item('tables', 'ion_auth');
		
	}

	// redirect if needed, otherwise display the user list
	public function index()
	{	

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
            $this->data['extra_js'] = array(
                "js/plugins/tables/datatables/datatables.min.js",
                "js/plugins/notifications/sweet_alert.min.js",
                "js/plugins/forms/selects/select2.min.js",
                "js/plugins/forms/styling/uniform.min.js",
                "js/pages/form_layouts.js",
                "js/plugins/forms/jquery.form.min.js",

            );
		

			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();



			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

			$this->_render_page('auth/manage_users', $this->data, 'Manage Users', false, 'default');
		}
	}

	public function manage_groups()
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
//		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
//		{
//			// redirect them to the home page because they must be an administrator to view this
//			return show_error('You must be an administrator to view this page.');
//		}
		else
		{
            $this->data['extra_js'] = array(
                "js/plugins/tables/datatables/datatables.min.js",
                "js/plugins/notifications/sweet_alert.min.js",
                "js/plugins/forms/selects/select2.min.js",
                "js/plugins/forms/styling/uniform.min.js",
                "js/pages/form_layouts.js",
                "js/plugins/forms/jquery.form.min.js",
                "js/plugins/forms/validation/validate.min.js",

            );
//			redirect(('/'), 'refresh');
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['groups']=$this->ion_auth->groups()->result_array();

			$this->_render_page('auth/manage_groups', $this->data, 'Manage Groups', false, 'default');
		}
	}

    public function getGroups()
    {

        if ($this->ion_auth->logged_in())
        {
            $delete_link = "<button type='button' title='Delete Group' class='btn btn-sm btn-icon btn-danger btn-round waves-effect deleteMasterRecord' data-pointer='$1'><i class=\"icon md-delete\" aria-hidden=\"true\"></i></button>";
            $edit_link = "<a href='".base_url('auth/edit_group/')."$1'   data-popup='custom-tooltip' data-original-title='Edit Group'  title='Edit Group' class='btn btn-xs border-slate-400 text-slate-400 btn-flat btn-icon btn-rounded editMasterRecord'   data-pointer='$1'><i class=\"icon-pencil\" aria-hidden=\"true\"></i></a>";
            $action = $edit_link;
            $this->load->library('datatables');

            $this->datatables
                ->select("id, name, description")
                ->from("tbl_groups");

            $this->datatables->add_column("actions", $action, "id");
            echo $this->datatables->generate();
        }
        else
        {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }
    }

    public function changeStatus(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        $return = $this->ion_auth_model->changeStatus($id, $status);

        echo $return;
    }

    public function checkEmail($returnFlag = false, $email = 0){

        $duplicate = 0;
        $email = $this->input->post('email');
        $id = $this->input->post('id');

        $duplicateFlag = $this->ion_auth_model->checkDuplicateEmail($id, $email);

        if(!$returnFlag){
            echo json_encode(array("valid" => !$duplicateFlag));
            exit;
        }else{
            return $duplicateFlag;
        }

    }

    public function getUsers()
    {

        if ($this->ion_auth->logged_in())
        {
//            $delete_link = "<button type='button' title='Delete User' class='btn btn-sm btn-icon btn-danger btn-round waves-effect deleteMasterRecord' data-pointer='$1'><i class=\"icon md-delete\" aria-hidden=\"true\"></i></button>";
//            $edit_link = "<a href='".base_url('auth/edit_user/')."$1'   data-popup='custom-tooltip' data-original-title='Edit User'  title='Edit User' class='btn btn-xs border-slate-400 text-slate-400 btn-flat btn-icon btn-rounded editMasterRecord' data-pointer='$1'><i class=\"icon-pencil\" aria-hidden=\"true\"></i></a>";
            $edit_link='';
            $edit_link .= "<a href='".base_url('auth/edit_user/')."$1'   data-popup='custom-tooltip' data-original-title='Edit User'  title='Edit User' class='btn btn-xs border-slate-400 text-slate-400 btn-flat btn-icon btn-rounded editMasterRecord' data-pointer='$1'><i class=\"icon-pencil\" aria-hidden=\"true\"></i></a>".
                "<button type='button' title='Delete User' data-popup='custom-tooltip' class='btn btn-xs border-danger-400 text-danger-400 btn-flat btn-icon btn-rounded deleteMasterRecord' data-pointer='$1'><i class='icon-trash'></i></button>";

            $action = $edit_link;
            $this->load->library('datatables');

            $this->datatables
                ->select("u.id, u.first_name, u.last_name, u.email, u.active, GROUP_CONCAT(distinct(g.name) SEPARATOR ', ') as groups")
                ->join('tbl_users_groups ug','ug.user_id = u.id','left')
                ->join('tbl_groups g','g.id = ug.group_id','left')
                ->from("tbl_users u")
                ->group_by("u.id");

            $this->datatables->add_column("actions", $action, "id");
            echo $this->datatables->generate();
        }
        else
        {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }
    }
    public function deleteUser(){
        $userId=$this->input->post('id');
        $categoryData = $this->ion_auth_model->delete_user($userId);
        if ($categoryData) {
            $response['success'] = true;
            $response['msg']     = sprintf($this->lang->line('delete_record'),SOURCE);
        } else {
            $response['success'] = false;
            $response['msg'] = sprintf($this->lang->line('error_delete_record'),SOURCE);
        }
        echo json_encode($response);
    }

	// log the user in
	public function login()
	{	
        $userId = ''; //$this->session->userdata('userId');

        if($userId != ''){
            redirect('Dashboard','refresh');
        }
		
        $this->data['title'] = $this->lang->line('login_heading');

		//validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
		

        if ($this->form_validation->run() == true)
		{
//            $reCaptcha = trim($this->input->post('g-recaptcha-response'));
//            $userIp    = $this->input->ip_address();
//            $secret    = $this->config->item("recaptcha_secret_key");

//            $googleReCaptcha = googleRecaptcha($secret,$userIp,$reCaptcha);
//
//            if($googleReCaptcha['success']  == 1){
//                //echo  'Google Recaptcha Successful';
//            }else{
//                $this->session->set_flashdata('message', "Sorry google recaptcha unsuccessful!!");
//                redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
//            }

            // check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');
			$email = $this->input->post('identity');
			
//            $userDetail = $this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember);

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
//			if (!empty($userDetail))
			{
//               //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());


//                $samajId = $this->ion_auth_model->userSamaj($userId);


//                $companyId =  isset($companyId) ?  explode(",",$companyId['company_id']) : array();


//                $this->session->set_userdata();

				
				
                redirect('/', 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->_render_page('auth/login', $this->data, 'Login');
		}
	}

    //Check OTP Function
    public function otp_check()
    {
        /// Check user not login to redirect login page
        if (!isset($this->session->userdata['email']) && !isset($this->session->userdata['userId']) ) {
            redirect('auth/login', 'refresh');
        }
        /// Check user already login to not display this function
        $userId = $this->ion_auth->get_user_id();
        if($userId != ''){
            redirect('Dashboard','refresh');
			
        }

        $this->data['title'] = "CheckOTP";

        $this->form_validation->set_rules('otp', 'OTP', 'required|numeric');

        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('numeric', 'OTP must contain only numbers');

         $companyId =   $this->ion_auth_model->userCompany($this->session->userdata['userId']);


         $companyId =  isset($companyId) ?  explode(",",$companyId['company_id']) : array();

        $this->data['companyId'] =   $this->ion_auth_model->userCompanyIdName(array_filter($companyId));


        if ($this->form_validation->run() == true)
        {


            $reCaptcha = trim($this->input->post('g-recaptcha-response'));
            $userIp    = $this->input->ip_address();
            $secret    = $this->config->item("recaptcha_secret_key");

//            $googleReCaptcha = googleRecaptcha($secret,$userIp,$reCaptcha);
//
//            if($googleReCaptcha['success']  == 1){
//                //echo  'Google Recaptcha Successful';
//            }else{
//                $this->session->set_flashdata('message', "Sorry google recaptcha unsuccessful!!");
//                redirect('auth/otp_check', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
//            }

            $otp      = $this->input->post("otp",TRUE);
            $emailId  = $this->session->userdata('email');
            $userId  = $this->session->userdata('userId');

            $checkOTP = $this->checkOTP($otp,$emailId,$userId);


            if(!empty($checkOTP) && count($checkOTP) && is_array($checkOTP)){

                $databseTime = strtotime($checkOTP['created_at']);
                $currentTime = strtotime( date("Y-m-d H:i:s", strtotime("-15 minutes")));

                if($currentTime > $databseTime) {
                    // if the OTP was expired
                    // redirect them back to the OTP Page
                    $this->session->set_flashdata('message', $this->lang->line('otp_time'));
                    redirect('auth/otp_check', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
                }
                //User login and session create code
                $query = $this->db->select('email, te.emp_id , password, status, last_login')
                    ->where($this->identity_column, $emailId)
					->join('tbl_login_details as tld', "te.emp_id = tld.emp_id ",'left')
                    ->limit(1)
                    ->order_by('te.emp_id', 'desc')
                    ->get('tbl_employee as te');
                $user = $query->row();


                $loginToken = md5(time());
                $user->loginToken = $loginToken;

                $this->ion_auth->set_session($user);

                $this->session->set_userdata('company_id',$this->input->post('company_id'));


                $this->ion_auth->update_last_login($user->emp_id, $loginToken);


                $this->ion_auth->otpLatestUpdate($emailId,$otp);


                // if the OTP was successful
                // redirect them back to the login page
                $this->session->set_flashdata('message', $this->lang->line('login_successfully'));
                redirect('/', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }else{

                // if the OTP was un-successful
                // redirect them back to the OTP Page
                $this->session->set_flashdata('message', "Enter otp is invalid");
                redirect('auth/otp_check', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        }
        else
        {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->_render_page('auth/otp_check', $this->data, 'CheckOTP');
        }
    }

    //Resend OTP Function
    public function reSendOtp(){
        $emailId  = $this->session->userdata('email');
        $userId   = $this->session->userdata('userId');

        $sendOTPTime = $this->checkOTP($otp = 0,$emailId,$userId);

        if(!empty($sendOTPTime)) {
            $databaseOtpTime = strtotime($sendOTPTime['created_at']);
            $currentTime = strtotime(date("Y-m-d H:i:s", strtotime("-30 seconds")));

            if ($currentTime > $databaseOtpTime) {
                $this->sendOtp($userId, $emailId, $loginTime = 1);
            } else {
                $response['success'] = false;
                $response['msg'] = "Please wait! OTP send every 30 seconds";
                echo json_encode($response);
            }
        }
        else{
            if (isset($this->session->userdata['email']) && isset($this->session->userdata['userId']) ) {
                $this->sendOtp($userId,$emailId,$loginTime = 1);
            }
        }
    }

    //Send OTP and Resend OTP Function
    public function sendOtp($userId,$emailId,$loginTime)
    {
        $this->ion_auth->update_data(array('is_latest' => 0), 'tbl_password_reset', 'user_id', $userId);
        //$otp = GenRandomNumber(6);
        $otp = 123456;

        $sendMailData = array(
            'email'       => $emailId,
            'user_id'     => $userId,
            'otp'         => $otp,
            'is_latest'   => 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'send_mail'   => 'login'
        );

        $this->ion_auth->insert_data($sendMailData, 'tbl_password_reset');

        $message = "Your OTP is: $otp";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= "From: noreply@digitattva.in" . "\r\n";

        $dt_ci_email = new Dt_ci_email();
        //$sendMail = $dt_ci_email->sendPasswordMail($emailId, 'OTP Verification', $message);

        if($loginTime != 0) {
            $response['success'] = true;
            $response['msg']     = "OTP send this $emailId address";
            echo json_encode($response);
        }

    }

    //Check the OTP Exist or Not Server Side
    public function checkOTP($otp,$emailId,$userId){
        $this->db->select('*');
        $this->db->from('tbl_password_reset');
        if($otp != 0) {
            $this->db->where(array('email' => $emailId, 'user_id' => $userId, 'otp' => $otp, 'is_latest' => 1));
        }else{
            $this->db->where(array('email' => $emailId, 'user_id' => $userId, 'is_latest' => 1));
        }
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    // check OTP to Exist Or Not Client Side
    public function checkOtpExist()
    {
        $otp      = $this->input->post('otp');
        $emailId  = $this->session->userdata('email');
        if (trim($otp)) {
            $res = $this->ion_auth->checkOTPUnique("tbl_password_reset", "otp", $otp, "email", $emailId, array('is_latest'=>1));
            if (empty($res)) {
                echo "false";
                die();
            } else {
                echo "true";
                die();
            }
        } else {
            echo "false";
            die();
        }
    }




    // check Email to Exist Or Not Client Side
    public function checkEmailExist()
    {
        $emailId   = $this->input->post('identity',TRUE);

        if (trim($emailId)) {
            $res = $this->ion_auth->checkOTPUnique("tbl_employee", "email", $emailId, "", "", array());
            if (empty($res)) {
                echo "false";
                die();
            } else {
                echo "true";
                die();
            }
        } else {
            echo "false";
            die();
        }
    }

	// log the user out
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('auth/login', 'refresh');
	}

	// change password
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();
		//print_r($user); die;

		if ($this->form_validation->run() == false)
		{

			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

			$this->data['user_id'] = $user->emp_id;
			// render
			$this->_render_page('auth/change_password', $this->data, 'Change Password',false,'default');
		}
		else
		{
			$identity = $user->emp_id;//$this->session->userdata('identity');
            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
                $response['success']=true;
                $response['msg'] =  strip_tags($this->ion_auth->messages());

                echo json_encode($response);
                exit;
				//if the password was successfully changed
				//$this->session->set_flashdata('message', $this->ion_auth->messages());
				//$this->logout();
			}
			else
			{
                $response['success']=false;
                $response['msg'] =strip_tags($this->ion_auth->errors());

                echo json_encode($response);
                exit;
			//	$this->session->set_flashdata('message', $this->ion_auth->errors());
			//	redirect('auth/change_password', 'refresh');
			}
		}
	}

    //Check Old Password
    public function checkOldPassword()
    {
        $oldPassword = $this->input->post("old",TRUE);

        if ($this->ion_auth->get_user_id()) {
            $this->db->select('password');
            $this->db->from('tbl_employee');
            $this->db->where('emp_id', $this->ion_auth->get_user_id());
            $checkAuth = $this->db->get()->row_array();
            $dbPassword = $checkAuth['password'];
        }
        if (password_verify($oldPassword, $dbPassword)) {
            echo "true";
        } else {
            echo "false";
        }
        die();
    }

    public function newPasswordNotSameAsOldPassword()
    {
        $newPassword = $this->input->post("new",TRUE);
        $id = $this->input->post("id",TRUE);

        if ($newPassword != '') {
            $this->db->select('password');
            $this->db->from('tbl_users');
            $this->db->where('id', $id);
            $checkAuth = $this->db->get()->row_array();
            if (!empty($checkAuth)) {
                $dbPassword = $checkAuth['password'];
            } else {
                $dbPassword = '';
            }
        }
        if (password_verify($newPassword, $dbPassword)) {
            echo "false";
        } else {
            echo "true";
        }
        die();
    }

	// forgot password
	public function forgot_password()
	{
        // setting validation rules by checking whether identity is username or email
		if($this->config->item('identity', 'ion_auth') != 'email' )
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			$this->data['type'] = $this->config->item('identity','ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ( $this->config->item('identity', 'ion_auth') != 'email' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data, 'Forgot Password',false,'auth');
		}
		else
		{

		    //Google ReCaptcha Server Side Validation
            $reCaptcha = trim($this->input->post('g-recaptcha-response'));
            $userIp    = $this->input->ip_address();
            $secret    = $this->config->item("recaptcha_secret_key");



            $googleReCaptcha = googleRecaptcha($secret,$userIp,$reCaptcha);

            if($googleReCaptcha['success']  == 1){
                //echo  'Google Recaptcha Successful';
            }else{
                $this->session->set_flashdata('message', "Sorry google recaptcha unsuccessful!!");
                redirect('auth/forgot_password', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }

			$identity_column = $this->config->item('identity','ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();
		
			if(empty($identity)) {

				if($this->config->item('identity', 'ion_auth') != 'email')
				{
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				}
				else
				{
				   $this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
			
			
			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->emp_id);
			
			
			if ($forgotten)
			{
				
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors()); 
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	// reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

				$this->data['user_id'] = $user->id;
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->_render_page('auth/reset_password', $this->data, 'Reset Password');
			}
			else
			{

				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}


	// activate the user
	public function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	// deactivate the user
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->_render_page('auth/deactivate_user', $this->data, 'Deactivate User');
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}

	/*
	// create a new user
	public function create_user()
    {
        $this->data['title'] = $this->lang->line('create_user_heading');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data))
        {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['identity'] = array(
                'name'  => 'identity',
                'id'    => 'identity',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->_render_page('auth/create_user', $this->data,'Create User');
        }
    }*/

    function view_profile(){
        if ($this->ion_auth->logged_in())
        {
            $user = $this->ion_auth->user()->row();
            $this->edit_user($user->id, true);
        }
    }

	// edit a user
	public function edit_user($id = 0, $myProfileFlag = false)
	{
        $this->data['extra_js']= array(
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/plugins/forms/selects/select2.min.js",
            "js/plugins/forms/jquery.form.min.js",
        );

        if (!$this->ion_auth->logged_in())
        {
            redirect(('auth/login'), 'refresh');
        }

		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('auth', 'refresh');
		}

        $groups = $this->ion_auth->groups()->result_array();
        $user   = $this->ion_auth->user($id)->row();

        # user Company Code add
//        $userCompany = $this->ion_auth->userCompany($id);

        $userSamaj = $this->ion_auth->userSamaj($id);

     //  echo "<pre>"; print_r($user);

       //echo $user->id;die();
		if($id!=0){
            $currentGroups = $this->ion_auth->get_users_groups($id)->result();
        }else{
            $currentGroups = array();
        }


		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('samaj_id[]', $this->lang->line('edit_user_validation_samaj_label'), 'required');

//		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
//		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?

			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
                $response['success'] = 0;
                $response['msg'] = $this->lang->line('error_csrf');
//				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{

				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
//					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
                    'samaj_id'   => $this->input->post('samaj_id'),
//                    'company_id' => $this->input->post('company_id'),
				);


				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}

				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

//           echo $user->id;     echo "<pre>";print_r($_POST); die();

			// check to see if we are updating the user
                if(isset($user->id) && $user->id != 0 && $user->id != ''){

                   // echo "ok";echo "<pre>";print_r($data); die();
                    $user_update = $this->ion_auth->update($user->id, $data);

                }else{

                   //echo "insert"; die();
                    $identity_column = $this->config->item('identity','ion_auth');
                    $email    = strtolower($this->input->post('email'));
                    $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
                    $password = $this->input->post('password');

                    $additional_data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name'  => $this->input->post('last_name'),
                        'phone'      => $this->input->post('phone'),
                        'samaj_id'   => $this->input->post('samaj_id'),
                    );

                    $groups = $this->input->post('groups');

                    $user_update = $this->ion_auth->register($identity, $password, $email, $additional_data,$groups);
                }
                $response['success'] = $user_update;
			    if($user_update)
			    {
                   // $response['success']= true;
			        $response['msg'] = "User saved successfully!";
//                  echo json_encode($response);


                    // redirect them back to the admin page if admin, or to the base url if non admin
//				    $this->session->set_flashdata('message', $this->ion_auth->messages() );
//				    if ($this->ion_auth->is_admin())
//					{
//						redirect('auth', 'refresh');
//					}
//					else
//					{
//						redirect('/', 'refresh');
//					}

			    }
			    else
			    {
                    $response['msg'] = $this->ion_auth->errors();
			    	// redirect them back to the admin page if admin, or to the base url if non admin
//				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
//				    if ($this->ion_auth->is_admin())
//					{
//						redirect('auth', 'refresh');
//					}
//					else
//					{
//						redirect('/', 'refresh');
//					}

			    }
//                echo "here";
                echo json_encode($response);
                exit;

			}
			else{
                $response['success'] = false;
                $response['msg'] = validation_errors();
                echo json_encode($response);
                exit;
            }
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));




		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['userSamaj'] = $userSamaj;
		$this->data['currentGroups'] = $currentGroups;

//		$this->data['first_name'] = array(
//			'name'  => 'first_name',
//			'id'    => 'first_name',
//			'type'  => 'text',
//            'class'    => 'form-control',
//			'value' => $this->form_validation->set_value('first_name', @$user->first_name),
//		);
//		$this->data['last_name'] = array(
//			'name'  => 'last_name',
//			'id'    => 'last_name',
//			'type'  => 'text',
//            'class'    => 'form-control',
//			'value' => $this->form_validation->set_value('last_name', @$user->last_name),
//		);
//		$this->data['samaj'] = array(
//			'name'  => 'samaj',
//			'id'    => 'samaj',
//			'type'  => 'text',
//            'class'    => 'form-control',
//			'value' => $this->form_validation->set_value('samaj', @$user->samaj),
//		);
//		$this->data['phone'] = array(
//			'name'  => 'phone',
//			'id'    => 'phone',
//			'type'  => 'text',
//            'class'    => 'form-control',
//			'value' => $this->form_validation->set_value('phone', @$user->phone),
//		);
//		$this->data['password'] = array(
//			'name' => 'password',
//			'id'   => 'password',
//			'type' => 'password',
//            'class'    => 'form-control',
//		);
//		$this->data['password_confirm'] = array(
//			'name' => 'password_confirm',
//			'id'   => 'password_confirm',
//			'type' => 'password',
//            'class'    => 'form-control',
//		);

     //   $this->data['supporting_views'] = array('v_select2');
        if($myProfileFlag){
            $title = "User Profile";
        }else if($id == 0){
            $title = "Add User";
        }else{
            $title = "Edit User";
        }

        $select2 = array(
            'samaj' => true
        );

        $this->data['select2'] = $this->load->view("commonMaster/v_select2", $select2, true);
        $this->_render_page('auth/edit_user', $this->data, $title, false, 'default');
	}


//
	// create a new group
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		}
		else
		{
			// display the create group form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('auth/create_group', $this->data, 'Create Group', false, 'default');
		}
	}

	// edit a group
	public function edit_group($id = 0)
	{
		// bail if no group id given
//		if(!$id || empty($id))
//		{
//			redirect('auth', 'refresh');
//		}


        $this->data['extra_js']= array(
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/plugins/forms/jquery.form.min.js",
        );

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{

                $additionalArr = array(
                    'description'  => $_POST['group_description'],
                    'see_all_data' => isset($_POST['see_all_data']) ? 1 : 0,
                    'permissions'  => isset($_POST['permissions'])?serialize($_POST['permissions']):serialize(array())
                );

				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $additionalArr);
                $response['success'] = $group_update;

				if($group_update)
				{
                    $response['msg'] = "Group saved successfully!";
//					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
                    $response['msg'] = $this->ion_auth->errors();
//					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
                echo json_encode($response);
				exit;
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === @$group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'class'    => 'form-control',
			'value'   => $this->form_validation->set_value('group_name', @$group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
            'class'    => 'form-control',
			'value' => $this->form_validation->set_value('group_description', @$group->description),
		);

        $this->data['controllers_methods'] = $this->dt_ci_acl->getPermissableMethods();

		if($id == 0){
            $this->_render_page('auth/edit_group', $this->data, 'Add Group', false, 'default');
        }else{
            $this->_render_page('auth/edit_group', $this->data, 'Edit Group', false, 'default');
        }
	}


	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce()
	{
        return TRUE;
//		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
//		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
//		{
//			return TRUE;
//		}
//		else
//		{
//			return FALSE;
//		}
	}

	public function _render_page($view, $data=null, $title = '', $returnhtml=false, $template = 'auth')//I think this makes more sense
	{
        if(0){
            $this->viewdata = (empty($data)) ? $this->data: $data;

            $view_html = $this->load->view($view, $this->viewdata, $returnhtml);

            if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
        }else{
            $data['title'] = $title;
            $this->dt_ci_template->load($template, $view, $data);
        }
	}

	public function getUsersDD(){
        $userId = $this->input->post('user_id');

        $filterParameter = $this->input->post('filter_param');
        $userId          = isset($userId) && is_array($userId) ? $userId : explode(",",$userId);
        $page            = $this->input->post('page');

        echo $this->ion_auth_model->getUsersDD($filterParameter,$page,array_filter($userId));

    }
	
}
