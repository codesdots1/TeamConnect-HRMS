<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth
*
* Author: Ben Edmunds
*		  ben.edmunds@gmail.com
*         @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Ion_auth
{
	/**
	 * account status ('not_activated', etc ...)
	 *
	 * @var string
	 **/
	protected $status;

	/**
	 * extra where
	 *
	 * @var array
	 **/
	public $_extra_where = array();

	/**
	 * extra set
	 *
	 * @var array
	 **/
	public $_extra_set = array();

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 **/
	public $_cache_user_in_group;

	/**
	 * __construct
	 *
	 * @author Ben
	 */
	public function __construct()
	{
		$this->config->load('ion_auth', TRUE);
		
		$this->load->library(array('email'));
		$this->lang->load('ion_auth');
		$this->load->helper(array('cookie', 'language','url'));

		$this->load->library('session');
		$this->load->library('dt_ci_email');

		$this->load->model('ion_auth_model');

		$this->_cache_user_in_group =& $this->ion_auth_model->_cache_user_in_group;

		//auto-login the user if they are remembered
		if (!$this->logged_in() && get_cookie($this->config->item('identity_cookie_name', 'ion_auth')) && get_cookie($this->config->item('remember_cookie_name', 'ion_auth')))
		{
			$this->ion_auth_model->login_remembered_user();
		}

		$email_config = $this->config->item('email_config', 'ion_auth');

		if ($this->config->item('use_ci_email', 'ion_auth') && isset($email_config) && is_array($email_config))
		{
			$this->email->initialize($email_config);
		}

		$this->ion_auth_model->trigger_events('library_constructor');
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 * @param $method
	 * @param $arguments
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ion_auth_model, $method) )
		{
			throw new Exception('Undefined method Ion_auth::' . $method . '() called');
		}
		if($method == 'create_user')
		{
			return call_user_func_array(array($this, 'register'), $arguments);
		}
		if($method=='update_user')
		{
			return call_user_func_array(array($this, 'update'), $arguments);
		}
		return call_user_func_array( array($this->ion_auth_model, $method), $arguments);
	}

	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
        return get_instance()->$var;
	}


	/**
	 * forgotten password feature
	 *
	 * @param $identity
	 * @return mixed boolean / array
	 * @author Mathew
	 */
	public function forgotten_password($identity)    //changed $email to $identity
	{
		if ( $this->ion_auth_model->forgotten_password($identity) )   //changed
		{	
			// Get user information
      $identifier = $this->ion_auth_model->identity_column; // use model identity column, so it can be overridden in a controller
      //$user = $this->where($identifier, $identity)->where('status', 1)->users()->row();  // changed to get_user_by_identity from email
	  $user = $this->where('tbl_employee.emp_id', $identity)->where('status', 1)->users()->row();  // changed to get_user_by_identity from email
		//echo $identifier." ". $identity;print_r( $user ); die;
			if ($user)
			{
				$data = array(
					'identity'		=> $user->{$this->config->item('identity', 'ion_auth')},
					'forgotten_password_code' => $user->forgotten_password_code
				);

//				if(!$this->config->item('use_ci_email', 'ion_auth'))
//				{
//					$this->set_message('forgot_password_successful');
//					return $data;
//				}
//				else
//				{
//					$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth'), $data, true);
//					$this->email->clear();
//					$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
//					$this->email->to($user->email);
//					$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
//					$this->email->message($message);
//
//
//
//					if ($this->email->send())
//					{
//						$this->set_message('forgot_password_successful');
//						return TRUE;
//					}
//					else
//					{
//						$this->set_error('forgot_password_unsuccessful');
//						return FALSE;
//					}
//				}


				if(!$this->config->item('use_dt_ci_email', 'ion_auth'))
				{
					$this->set_message('forgot_password_successful');
					return $data;
				}
				else
				{	
					$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth'), $data, true);
//					$this->email->clear();
//					$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
//					$this->email->to($user->email);
//					$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
//					$this->email->message($message);
                    $dt_ci_email = new dt_ci_email();
					
					$status = $dt_ci_email->sendPasswordMail($user->email, $this->lang->line('email_forgotten_password_subject'), $message);
					if ($status)
					{
						$this->set_message('forgot_password_successful');
						return TRUE;
					}
					else
					{
						$this->set_error('forgot_password_unsuccessful');
						return FALSE;
					}
				}
			}
			else
			{
				$this->set_error('forgot_password_unsuccessful');
				return FALSE;
			}
		}
		else
		{
			
			$this->set_error('forgot_password_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * forgotten_password_complete
	 *
	 * @param $code
	 * @author Mathew
	 * @return bool
	 */
	public function forgotten_password_complete($code)
	{
		$this->ion_auth_model->trigger_events('pre_password_change');

		$identity = $this->config->item('identity', 'ion_auth');
		$profile  = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!$profile)
		{
			$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$new_password = $this->ion_auth_model->forgotten_password_complete($code, $profile->salt);

		if ($new_password)
		{
			$data = array(
				'identity'     => $profile->{$identity},
				'new_password' => $new_password
			);
//			if(!$this->config->item('use_ci_email', 'ion_auth'))
//			{
//				$this->set_message('password_change_successful');
//				$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
//					return $data;
//			}
//			else
//			{
//				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);
//
//				$this->email->clear();
//				$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
//				$this->email->to($profile->email);
//				$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_new_password_subject'));
//				$this->email->message($message);
//
//				if ($this->email->send())
//				{
//					$this->set_message('password_change_successful');
//					$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
//					return TRUE;
//				}
//				else
//				{
//					$this->set_error('password_change_unsuccessful');
//					$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
//					return FALSE;
//				}
//
//			}

			if(!$this->config->item('use_dt_ci_email', 'ion_auth'))
			{
				$this->set_message('password_change_successful');
				$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
					return $data;
			}
			else
			{
				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);

//				$this->email->clear();
//				$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
//				$this->email->to($profile->email);
//				$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_new_password_subject'));
//				$this->email->message($message);


				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);
				$status = $this->dt_ci_email->sendPasswordMail($profile->email, $this->lang->line('email_new_password_subject'), $message);

				if ($status)
				{
					$this->set_message('password_change_successful');
					$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
					return TRUE;
				}
				else
				{
					$this->set_error('password_change_unsuccessful');
					$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
					return FALSE;
				}

			}
		}

		$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
		return FALSE;
	}

	/**
	 * forgotten_password_check
	 *
	 * @param $code
	 * @author Michael
	 * @return bool
	 */
	public function forgotten_password_check($code)
	{
		$profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!is_object($profile))
		{
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}
		else
		{
			if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
				//Make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
				if (time() - $profile->forgotten_password_time > $expiration) {
					//it has expired
					$this->clear_forgotten_password_code($code);
					$this->set_error('password_change_unsuccessful');
					return FALSE;
				}
			}
			return $profile;
		}
	}

	/**
	 * register
	 *
	 * @param $identity
	 * @param $password
	 * @param $email
	 * @param array $additional_data
	 * @param array $group_ids
	 * @author Mathew
	 * @return bool
	 */
	public function register($identity, $password, $email, $additional_data = array(), $group_ids = array()) //need to test email activation
	{

		$this->ion_auth_model->trigger_events('pre_account_creation');

		$email_activation = $this->config->item('email_activation', 'ion_auth');

		$id = $this->ion_auth_model->register($identity, $password, $email, $additional_data, $group_ids);



		if (!$email_activation)
		{
			if ($id !== FALSE)
			{
				$this->set_message('account_creation_successful');
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful'));
				return $id;
			}
			else
			{
				$this->set_error('account_creation_unsuccessful');
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}
		}
		else
		{
			if (!$id)
			{
				$this->set_error('account_creation_unsuccessful');
				return FALSE;
			}

			// deactivate so the user much follow the activation flow
			$deactivate = $this->ion_auth_model->deactivate($id);

			// the deactivate method call adds a message, here we need to clear that
			$this->ion_auth_model->clear_messages();


			if (!$deactivate)
			{
				$this->set_error('deactivate_unsuccessful');
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}

			$activation_code = $this->ion_auth_model->activation_code;
			$identity        = $this->config->item('identity', 'ion_auth');
			$user            = $this->ion_auth_model->user($id)->row();

			$data = array(
				'identity'   => $user->{$identity},
				'id'         => $user->id,
				'email'      => $email,
				'activation' => $activation_code,
			);
//			if(!$this->config->item('use_ci_email', 'ion_auth'))
//			{
//				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
//				$this->set_message('activation_email_successful');
//				return $data;
//			}
//			else
//			{
//				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_activate', 'ion_auth'), $data, true);
//
//				$this->email->clear();
//				$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
//				$this->email->to($email);
//				$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_activation_subject'));
//				$this->email->message($message);
//
//				if ($this->email->send() == TRUE)
//				{
//					$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
//					$this->set_message('activation_email_successful');
//					return $id;
//				}
//
//			}

			if(!$this->config->item('use_dt_ci_email', 'ion_auth'))
			{
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
				$this->set_message('activation_email_successful');
				return $data;
			}
			else
			{
				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_activate', 'ion_auth'), $data, true);

//				$this->email->clear();
//				$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
//				$this->email->to($email);
//				$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_activation_subject'));
//				$this->email->message($message);


				$status = $this->dt_ci_email->sendPasswordMail($email, $this->lang->line('email_activation_subject'), $message);

				if ($status)
				{
					$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
					$this->set_message('activation_email_successful');
					return $id;
				}

			}

			$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful'));
			$this->set_error('activation_email_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * logout
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logout()
	{
		$this->ion_auth_model->trigger_events('logout');

		$identity = $this->config->item('identity', 'ion_auth');
		
		$user = $this->session->userdata;
		
		$dataEmployeeLog = array(
				'logout_time'  			  => date('H:i:s')
				);
		if($this->session->userdata('role') != 'admin'){
			$dataWhere = array(
					'emp_id' => $user['emp_id'],
					'login_date'  	=> date('Y-m-d'),
					'logout_time' => '00:00:00'
			);
					
			$this->db->update('tbl_employee_log',$dataEmployeeLog,$dataWhere);
		}

        if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->unset_userdata( array($identity => '', 'id' => '', 'emp_id' => '') );
                }
                else
                {
                	$this->session->unset_userdata( array($identity, 'id', 'emp_id') );
                }

		// delete the remember me cookies if they exist
		if (get_cookie($this->config->item('identity_cookie_name', 'ion_auth')))
		{
			delete_cookie($this->config->item('identity_cookie_name', 'ion_auth'));
		}
		if (get_cookie($this->config->item('remember_cookie_name', 'ion_auth')))
		{
			delete_cookie($this->config->item('remember_cookie_name', 'ion_auth'));
		}

		// Destroy the session
		$this->session->sess_destroy();

		//Recreate the session
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->sess_create();
		}
		else
		{
			if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
				session_start();
			}
			$this->session->sess_regenerate(TRUE);
		}
		
		

		$this->set_message('logout_successful');
		return TRUE;
	}

	/**
	 * logged_in
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function logged_in()
	{
		$this->ion_auth_model->trigger_events('logged_in');

        $sessionLoginToken = $this->session->userdata('loginToken');
        $dbLoginToken = $this->ion_auth_model->getLoginToken($this->get_user_id());

//
//        echo $sessionLoginToken;
//
//        echo "<pre>"; printArray($dbLoginToken);
//
//        die();
        if($this->config->item('multiple_user_check', 'ion_auth')) {
            return (bool) $this->session->userdata('identity')&&  $sessionLoginToken == $dbLoginToken['login_token'];
        }else {
            return (bool)$this->session->userdata('identity');
        }


	}

	/**
	 * logged_in
	 *
	 * @return integer
	 * @author jrmadsen67
	 **/
	public function get_user_id()
	{		
		$emp_id = $this->session->userdata('emp_id');
		if (!empty($emp_id))
		{
			return $emp_id;
		}
		return null;
	}

	public function get_current_user_permission()
	{
		$permissions = array();
		$user_id = $this->session->userdata('emp_id');
		if (!empty($user_id))
		{
			$currentGroups = $this->get_users_groups($user_id)->result();
			foreach($currentGroups as $group){
				//print_r($group); die;
				//$groupDetails = $this->group($group->id)->row();
				//$permission = unserialize(strtolower($groupDetails->role));
				$permission = $group->role;
				if(is_array($permission)){
					$permissions = array_merge($permissions, $permission);
				}else{				
				    array_push($permissions, $permission);
				}
			}
		}
		$permissions = array_unique($permissions);
		return $permissions;
	}


	/**
	 * is_admin
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function is_admin($id=false)
	{
		$this->ion_auth_model->trigger_events('is_admin');

		$admin_group = $this->config->item('admin_group', 'ion_auth');

//		print_r($admin_group); die();
        $returnValue = $this->in_group($admin_group, $id);
		return $returnValue;
	}

	/**
	 * in_group
	 *
	 * @param mixed group(s) to check
	 * @param bool user id
	 * @param bool check if all groups is present, or any of the groups
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function in_group($check_group, $id=false, $check_all = false)
	{

		$this->ion_auth_model->trigger_events('in_group');

		$id || $id = $this->session->userdata('emp_id');
   //    echo $id; die();

		if (!is_array($check_group))
		{
			$check_group = array($check_group);
		}

		if (isset($this->_cache_user_in_group[$id]))
		{	
			$groups_array = $this->_cache_user_in_group[$id];
		}
		else
		{
			$users_groups = $this->ion_auth_model->get_users_groups($id)->result();

			//echo "<pre>"; print_r($users_groups); die();
			$groups_array = array();
			foreach ($users_groups as $group)
			{
				$groups_array[$group->id] = strtolower($group->role);
			}
			$this->_cache_user_in_group[$id] = $groups_array;
			
		}

		
		foreach ($check_group as $key => $value)
		{
			$groups = (is_string($value)) ? $groups_array : array_keys($groups_array);

			/**
			 * if !all (default), in_array
			 * if all, !in_array
			 */ 
			 
			if (in_array($value, $groups) xor $check_all)
			{
				/**
				 * if !all (default), true
				 * if all, false
				 */
				//echo "in"; echo !$check_all; die;
				return !$check_all;
			}
		}

		/**
		 * if !all (default), false
		 * if all, true
		 */
		return $check_all;
	}

}