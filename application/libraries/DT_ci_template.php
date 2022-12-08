<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class DT_ci_template
    {
        var $ci;

        function __construct()
        {
            $this->ci =& get_instance();
            $this->ci->lang->load('sidebar_nav');
        }

        function load($tpl_view, $body_view = null, $data = null)
        {
			
            $data['assets'] = base_url() . 'assets/';
            $data['logged_in'] = $this->ci->ion_auth->logged_in();

            if($data['logged_in']){
               $userDetails = $this->ci->ion_auth->user()->row();
			   $userImage= $this->ci->ion_auth->employeeImage($userDetails->id);
               $data['user_display_name'] = @$userDetails->first_name." ".$userDetails->last_name;
			   
			   if($userImage['empImage'] == NULL && strtolower($userDetails->gender_name) == 'male'){
					$data['employee_image'] = 'male-avatar.jpg';
			   }else if($userImage['empImage'] == NULL && strtolower($userDetails->gender_name) == 'female'){
				   $data['employee_image'] = 'female-avatar.jpg';
			   }else{
				   $data['employee_image'] = $userImage['empImage'];
			   }
			   
			   $data['company_id'] = $userDetails->company_id;			   
			   $data['employee_id'] = $userDetails->id;
			   $data['role'] = $userDetails->role;
			   $data['attendance_status'] = $this->ci->ion_auth->attendanceStatus();
            }


            if ( ! is_null( $body_view ) )
            {
                if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$body_view ) )
                {
                    $body_view_path = $tpl_view.'/'.$body_view;
                }
                else if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$body_view.'.php' ) )
                {
                    $body_view_path = $tpl_view.'/'.$body_view.'.php';
                }
                else if ( file_exists( APPPATH.'views/'.$body_view ) )
                {
                    $body_view_path = $body_view;
                }
                else if ( file_exists( APPPATH.'views/'.$body_view.'.php' ) )
                {
                    $body_view_path = $body_view.'.php';
                }
                else
                {
                    show_error('Unable to load the requested file: ' . $tpl_view.'/'.$body_view.'.php');
                }

                $body = $this->ci->load->view($body_view_path, $data, TRUE);

                if(isset($data['supporting_views'])){
                    foreach($data['supporting_views'] as $supporting_view){
                        $body.=$this->ci->load->view('supporting_views/'.$supporting_view, $data, TRUE);
                    }
                }

                if ( is_null($data) )
                {
                    $data = array('body' => $body);
                }
                else if ( is_array($data) )
                {
                    $data['body'] = $body;
                }
                else if ( is_object($data) )
                {
                    $data->body = $body;
                }
            }

            $this->ci->load->view('templates/'.$tpl_view, $data);
        }
    }

?>