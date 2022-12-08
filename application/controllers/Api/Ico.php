<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';


class Ico extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['userLogin_post']['limit'] = 5;
        $this->methods['user_post']['limit'] = 5;
        $this->load->model(array('WebServiceModel', 'Mdl_ico'));
    }

    function icoList_post()
    {
        $userId = $this->input->post('id');
        $authToken = $this->input->post('authToken');

        $start = $this->input->post('start');
        $limit = 5;

        // check start is greater than 1 or not
        if(is_int(intval($start)) && $start > 0){
            $start = intval(($start - 1) * $limit);
        } else {
            $start = $this->config->item("page_start");
        }


        $limitData = 5;
        $searchParameter = $this->input->post('search_parameter');


        $icoType = $this->input->post('type');
        $icoCondition = '1=1';
        if ($icoType == "upcoming") {
            $icoCondition = array('start_date >' => date('Y-m-d'));
        }
        if ($icoType == "recent") {
            $icoCondition = array('end_date <=' => date('Y-m-d'));
        }
        if ($icoType == "active") {
            $icoCondition = array('start_date <=' => date('Y-m-d'), 'end_date >' => date('Y-m-d'));
        }

        $this->form_validation->set_rules('id', 'User Id', 'required');
        $this->form_validation->set_rules('authToken', 'Token', 'required');

        $this->form_validation->set_rules('start', 'Start', 'required');
        //$this->form_validation->set_rules('limit', 'Limit', 'required');


        $this->form_validation->set_message('required', '%s is required');


        if ($this->form_validation->run() === FALSE) {
            $stripMessage = strip_tags(validation_errors(""));
            $this->response(array(
                $this->config->item('rest_status_field_name') => FALSE,
                'error' => trim(preg_replace("/\r\n|\r|\n/", ',', $stripMessage), ","),
                'responseCode' => self::HTTP_BAD_REQUEST
            ), REST_Controller::HTTP_BAD_REQUEST);
        }


        $tokenData = $this->userAccess($userId, $authToken);

        $icoData = array();
        $icoData = $this->Mdl_ico->ico_list($start, $limitData, $icoCondition, $userId, $searchParameter);

//        if (count($icoData) > 0) {
//            foreach ($icoData as $key => $icoMember) {
//
//                $like = $icoMember['like_count'];
//                $icoLikeData = restyle_text($like);
//                $icoData[$key]['like_count'] = $icoLikeData;
//
//
//                $icoId = $icoMember['company_id'];
//                $icoMemberData = $this->Mdl_ico->lcoMember($icoId);
//                if (!empty($icoMemberData)) {
//                    $icoData[$key]['memberData'] = $icoMemberData;
//                } else {
//                    $icoData[$key]['memberData'] = '';
//                }
//            }
//
//
////            foreach ($icoData as $key => $icoFinancial) {
////                $icoId = $icoFinancial['company_id'];
////                $icoFinancialData = $this->Mdl_ico->lcoFinancial($icoId);
////
////                if (!empty($icoFinancialData)) {
////                    $icoData[$key]['financialData'] = $icoFinancialData;
////                } else {
////                    $icoData[$key]['financialData'] = '';
////                }
////            }
//        }


        if (!empty($icoData)) {
            $this->response(array(
                $this->config->item('rest_status_field_name') => TRUE,
                'success' => $icoData,
                'responseCode' => self::HTTP_OK
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                $this->config->item('rest_status_field_name') => FALSE,
                $this->config->item('rest_message_field_name') => 'No Data Found',
                'responseCode' => self::HTTP_NOT_FOUND
            ), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    function icoDetail_post()
    {
        $userId = $this->input->post('id');
        $authToken = $this->input->post('authToken');
        $icoId = $this->input->post('icoId');


        $this->form_validation->set_rules('id', 'User Id', 'required');
        $this->form_validation->set_rules('authToken', 'Token', 'required');
        $this->form_validation->set_rules('icoId', 'Ico Id', 'required');

        $this->form_validation->set_message('required', '%s is required');


        if ($this->form_validation->run() === FALSE) {
            $stripMessage = strip_tags(validation_errors(""));
            $this->response(array(
                $this->config->item('rest_status_field_name') => FALSE,
                'error' => trim(preg_replace("/\r\n|\r|\n/", ',', $stripMessage), ","),
                'responseCode' => REST_Controller::HTTP_BAD_REQUEST
            ), REST_Controller::HTTP_BAD_REQUEST);
        }


        $tokenData = $this->userAccess($userId, $authToken);

        $icoData = array();
        $icoData = $this->Mdl_ico->viewIcoDetail($icoId, $userId);

        if (count($icoData) > 0) {
            foreach ($icoData as $key => $icoMember) {

                $like = $icoMember['like_count'];
                $icoLikeData = restyle_text($like);
                $icoData[$key]['like_count'] = $icoLikeData;

                $icoId = $icoMember['company_id'];
                $icoMemberData = $this->Mdl_ico->lcoMember($icoId);
                if (!empty($icoMemberData)) {
                    $icoData[$key]['memberData'] = $icoMemberData;
                } else {
                    $icoData[$key]['memberData'] = '';
                }
            }


            foreach ($icoData as $key => $lcoMilestone) {
                $icoId = $lcoMilestone['company_id'];
                $lcoMilestoneData = $this->Mdl_ico->lcoMilestone($icoId);

                if (!empty($lcoMilestoneData)) {
                    $icoData[$key]['MilestoneData'] = $lcoMilestoneData;
                } else {
                    $icoData[$key]['MilestoneData'] = '';
                }
            }

        }
        // echo "<pre>";printArray($icoData); die();

        if (!empty($icoData)) {
            $this->response(array(
                $this->config->item('rest_status_field_name') => TRUE,
                'success' => $icoData,
                'responseCode' => REST_Controller::HTTP_OK
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                $this->config->item('rest_status_field_name') => FALSE,
                $this->config->item('rest_message_field_name') => 'No Data Found',
                'responseCode' => REST_Controller::HTTP_NOT_FOUND
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
