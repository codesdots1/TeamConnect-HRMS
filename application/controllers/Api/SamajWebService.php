<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class SamajWebService extends REST_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            "Mdl_samaj",
            "Mdl_post",
            "Mdl_poll",
            "Mdl_gallery",
            "Mdl_event",
            "Mdl_member",
            "Mdl_business",
            "Mdl_push_notification_master",
            "Mdl_language",
            "Mdl_samaj_webservice",
            "Mdl_business_type",
            "Mdl_pachkhan",
            "Mdl_state",
            "Mdl_city",

        ));
    }

    /**@api {post} Api/SamajWebService/getEventList Event-List
     * @apiName Event Listing
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Event
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse language_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getEventList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Event Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                   "event_id": "5",
     *                   "event_name": "FASHION SHOW",
     *                   "short_description": "A fashion show (French défilé de mode) is an event put on by a fashion designer to show",
     *                   "long_description": "<p><span class=\"ILfuVd\">A <b>fashion show</b> (French défilé de mode) is an <b>event</b> put on by a <b>fashion</b> designer to showcase their upcoming line of clothing and/or accessories during <b>Fashion Week</b>. ... In a typical <b>fashion show</b>, models walk the catwalk dressed in the clothing created by the designer.</span></p>\r\n",
     *                   "start_date": "25-02-2019",
     *                   "end_date": "25-02-2019",
     *                   "location_geo": "Apollo Bandar, Colaba, Mumbai, Maharashtra, India",
     *                   "location_general": "Apollo Bandar, Colaba, Mumbai, Maharashtra 400001",
     *                   "is_required": "0",
     *                   "start_time": "13:40",
     *                   "end_time": "13:40",
     *                   "lat": "18.9203886",
     *                   "lng": "72.83013059999996",
     *                   "file": "b46b2ca9076ebc7a27709554819ab7c5.jpg",
     *                   "gallery_image_path": "http://192.168.0.128/samaj/uploads/event_path/b46b2ca9076ebc7a27709554819ab7c5.jpg"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getEventList
     */
    function getEventList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Auth Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required|integer');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_rules('event_id', 'Event Id', 'integer');
        $this->form_validation->set_rules('member_detail_id', 'Member Id', 'integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s enter only Integer');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status" => FALSE,
                "message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data" => null,
                'responseCode' => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $existData = '';
        $memberId = $this->input->post('member_detail_id');
        $eventId  = $this->input->post('event_id');
        if ($eventId != '' && is_numeric($eventId)) {
            $existence = $this->Mdl_event->checkEventId($eventId, 'tbl_events');
            if ($existence['count'] == 0) {
                $this->response(array(
                    "status"        => FALSE,
                    "message"       => "No Such Events Exists",
                    'responseCode'  => self::HTTP_BAD_REQUEST,
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }

        $start    = $this->input->post('start');
        $search   = $this->input->post('search');
        $start    = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        if (isset($eventId) && !empty($eventId) && isset($memberId) && !empty($memberId)) {
            $EventRsvpData = array(
                'event_id'  => $eventId,
                'member_id' => $memberId,
            );
            $existData = $this->Mdl_samaj_webservice->checkDuplicate($EventRsvpData, 'tbl_event_rsvp');
        }
        $languageId = $this->input->post('language_id');
        if (isset($languageId) && !empty($languageId)) {
            $languageData = array(
                'language_id' => $languageId,
                'table_name'  => 'tbl_language_master'
            );
            $existence = $this->Mdl_samaj_webservice->checkExistId($languageData);
            if ($existence['count'] == 0) {
                $this->response(array(
                    "status"        => FALSE,
                    "message"       => "No Such Language Exist",
                    'responseCode'  => self::HTTP_BAD_REQUEST,
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }

        if (isset($eventId) && !empty($eventId)) {
            $eventDataArray = array(
                'event_id'      => $eventId,
                'table_name'    => 'tbl_events'
            );
            $existence = $this->Mdl_samaj_webservice->checkExistId($eventDataArray);
            if ($existence['count'] == 0) {
                $this->response(array(
                    "status"        => FALSE,
                    "message"       => "No Such Event Exists",
                    'responseCode'  => self::HTTP_BAD_REQUEST,
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }

        $eventSearchArray = array(
            'start'         => $start,
            'search'        => $search,
            'language_id'   => $languageId,
            'event_id'      => $eventId,
            'member_id'     => $memberId
        );
        $eventData = $this->Mdl_event->getEvents($eventSearchArray);

        foreach ($eventData as $key => $value) {
            $fileName = $this->Mdl_event->getFileNamesByEventId($value['event_id']);
            $eventData[$key]['event_image_path'] = array_column($fileName, 'filename');
        }


        if (!empty($eventData)) {
            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Event Listing successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $eventData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => $eventData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    /**@api {post} Api/SamajWebService/getSamajList Samaj-List
     * @apiName Samaj Listing
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Samaj
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse language_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getSamajList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Samaj Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                     "samaj_id": "32",
     *                     "samaj_name": "Hindu",
     *                     "parent_samaj_id": "33",
     *                     "parent_samaj": "",
     *                     "short_description": "Hindu",
     *                     "long_description": "<p>Hindu</p>\n",
     *                     "is_active": "1"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getSamajList
     */
    public function getSamajList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Auth Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required|integer');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be numeric');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status" => FALSE,
                "message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data" => null,
                'responseCode' => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $languageId = $this->input->post('language_id');
        if (isset($languageId) && !empty($languageId)) {
            $languageData = array(
                'language_id' => $languageId,
                'table_name'  => 'tbl_language_master'
            );
            $existence = $this->Mdl_samaj_webservice->checkExistId($languageData);
            if ($existence['count'] == 0) {
                $this->response(array(
                    "status"        => FALSE,
                    "message"       => "No Such Language Exist Exist",
                    'responseCode'  => self::HTTP_BAD_REQUEST,
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        $start = $this->input->post('start');
        $search = $this->input->post('search');
        // check start is greater than 1 or not
        $start = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $samajSearchArray = array(
            'start' => $start,
            'search' => $search,
            'language_id' => $languageId
        );
        $samajData = $this->Mdl_samaj->getSamajs($samajSearchArray);
        if (!empty($samajData)) {
            $this->response(array(
                'status' => TRUE,
                'responseCode' => self::HTTP_OK,
                "message" => 'Samaj Listing successfully',
                'limit' => DATA_LIMIT,
                'data' => $samajData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => FALSE,
                'responseCode' => self::HTTP_NOT_FOUND,
                'message' => "No Data Found",
                'limit' => DATA_LIMIT,
                "data" => $samajData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/getPollListing Poll-Listing
     * @apiName Poll Listing
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Poll
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     * @apiUse language_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getPollListing
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Poll Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                         "poll_id": "1",
     *                         "question": "Gender",
     *                         "sort_order": "8",
     *                         "is_active": "0",
     *                         "is_required": "1",
     *                         "is_multiple": "1",
     *                         "field_type": "text",
     *                         "samaj_id": "13",
     *                         "samaj_name": "Christ"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getPollListing
     */
    public function getPollList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'MemberId', 'required|integer');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be numeric');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $start      = $this->input->post('start');
        $search     = $this->input->post('search');
        $languageId = $this->input->post('language_id');
        $start      = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $pollSearchArray = array(
            'start'         => $start,
            'search'        => $search,
            'language_id'   => $languageId,
        );
        $pollData = $this->Mdl_poll->getPolls($pollSearchArray);

        foreach ($pollData as $key => $value) {
            $pollValues = $this->Mdl_poll->getPollValues($value['poll_id']);
            $pollData[$key]['poll_values'] = array_column($pollValues, 'poll_value');
            $pollData[$key]['poll_sort_order'] = array_column($pollValues, 'sort_order');
            if ($pollData[$key]['field_type'] == 'radio') {
                $pollData[$key]['field_type'] = 0;
            } else if ($pollData[$key]['field_type'] == 'checkbox') {
                $pollData[$key]['field_type'] = 1;
            } else {
                $pollData[$key]['field_type'] = 2;
            }
        }

        if (!empty($pollData)) {
            $this->response(array(
                'status' => TRUE,
                'responseCode' => self::HTTP_OK,
                "message" => 'Poll Listing successfully',
                'limit' => DATA_LIMIT,
                'data' => $pollData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => FALSE,
                'responseCode' => self::HTTP_NOT_FOUND,
                'message' => "No Data Found",
                'limit' => DATA_LIMIT,
                "data" => $pollData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    /**@api {post} Api/SamajWebService/getGalleryListing Gallery-List
     * @apiName Gallery List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Gallery
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     * @apiUse language_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getGalleryList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Gallery Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                           "gallery_id": "1",
     *                           "gallery_name": "Art",
     *                           "parent_gallery_name": null,
     *                           "filename": "382aadc9012c72056ab73e1a592e03b3.jpg",
     *                           "gallery_image_path": "http://192.168.0.128/samaj/uploads/gallery_path/382aadc9012c72056ab73e1a592e03b3.jpg"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getGalleryList
     */
    public function getGalleryList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'MemberId', 'required|integer');
        $this->form_validation->set_rules('gallery_id', 'Gallery Id', 'integer|integer');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be integer');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $start      = $this->input->post('start');
        $search     = $this->input->post('search');
        $languageId = $this->input->post('language_id');
        $galleryId  = $this->input->post('gallery_id');

        $start = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;

        $gallerySearchArray = array(
            'start' => $start,
            'search' => $search,
            'language_id' => $languageId,
            'gallery_id' => $galleryId
        );
        $galleryData = $this->Mdl_gallery->getGalleries($gallerySearchArray);

        foreach ($galleryData as $key => $value) {
            $filenames = $this->Mdl_gallery->getFileNamesByGalleryId($value['gallery_id']);
            $galleryData[$key]['gallery_image'] = array_column($filenames, 'filename');
        }
        if (!empty($galleryData)) {
            $this->response(array(
                'status' => TRUE,
                'responseCode' => self::HTTP_OK,
                "message" => 'Gallery Listing successfully',
                'limit' => DATA_LIMIT,
                'data' => $galleryData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => FALSE,
                'responseCode' => self::HTTP_NOT_FOUND,
                'message' => "No Data Found",
                'limit' => DATA_LIMIT,
                "data" => $galleryData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /** @api {post} Api/SamajWebService/getMemberList Member-List
     * @apiName Member List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Member
     * @apiVersion 0.1.0
     *
     *
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     * @apiUse language_id
     * @apiParam {int} [member_id] member id to get particular Member Detail
     * @apiUse parent_member_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getMemberList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Member Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                   "member_id": "1",
     *                   "member_name": "Jamesss Rrr Goslinn",
     *                   "member_number": "BH-00111212",
     *                   "file": "0574a16046e21957fba985455256a5de.jpg",
     *                   "education": "Mscs",
     *                   "age": "22",
     *                   "hometown": "Timminss",
     *                   "current_work": "Google Inc.s",
     *                   "member_image_path": "http://192.168.0.128/samaj/uploads/member_image/0574a16046e21957fba985455256a5de.jpg"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getMemberList
     */
    public function getMemberList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required|integer');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_rules('member_id', 'Member Id', 'integer');
        $this->form_validation->set_rules('parent_member_id', 'Parent Member', 'integer');
        $this->form_validation->set_rules('member_detail_id', 'Member Id', 'integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be numeric');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }

        $samajString            = explode(',', $this->input->post('samaj_string'));
        $businessString         = explode(',', $this->input->post('business_string'));
        $surnameString          = explode(',', $this->input->post('surname_string'));
        $maritalStatusString    = explode(',', $this->input->post('marital_string'));
        $educationString        = explode(',', $this->input->post('education_string'));
        $genderString           = explode(',', $this->input->post('gender_string'));

        $isAdmin    = $this->input->post('is_admin');
        $samajId    = $this->input->post('samaj_id');
        $memberId   = $this->input->post('member_detail_id');
        if (isset($memberId) && !empty($memberId)) {
            $memberData = array(
                'member_id'     => $memberId,
                'table_name'    => 'tbl_members'
            );
            $existence = $this->Mdl_samaj_webservice->checkExistId($memberData);
            if ($existence['count'] == 0) {
                $this->response(array(
                    "status"        => FALSE,
                    "message"       => "No Such member Exists",
                    'responseCode'  => self::HTTP_BAD_REQUEST,
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        $parentMemberId = $this->input->post('parent_member_id');
        if (isset($parentMemberId) && !empty($parentMemberId)) {
            $memberData = array(
                'parent_member_id'  => $parentMemberId,
                'table_name'        => 'tbl_members'
            );
            $existence = $this->Mdl_samaj_webservice->checkExistId($memberData);
            if ($existence['count'] == 0) {
                $this->response(array(
                    "status"        => FALSE,
                    "message"       => "No Such Parent member Exist",
                    'responseCode'  => self::HTTP_BAD_REQUEST,
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }

        $languageId = $this->input->post('language_id');
        if (isset($languageId) && !empty($languageId)) {
            $languageData = array(
                'language_id'   => $languageId,
                'table_name'    => 'tbl_language_master'
            );
            $existence = $this->Mdl_samaj_webservice->checkExistId($languageData);
            if ($existence['count'] == 0) {
                $this->response(array(
                    "status"        => FALSE,
                    "message"       => "No Such Language Exist Exist",
                    'responseCode'  => self::HTTP_BAD_REQUEST,
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        $start  = $this->input->post('start');
        $search = $this->input->post('search');
        // check start is greater than 1 or not
        $start = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $memberArray = array(
            'start'             => $start,
            'search'            => $search,
            'language_id'       => $languageId,
            'member_id'         => $memberId,
            'parent_member_id'  => $parentMemberId,
            'samaj_id'          => $samajId,
            'samaj_string_id'   => $samajString,
            'business_type_id'  => $businessString,
            'surname_id'        => $surnameString,
            'marital_status_id' => $maritalStatusString,
            'education_id'      => $educationString,
            'gender_id'         => $genderString,
            'is_admin'          => $isAdmin,
        );
        $memberData = $this->Mdl_member->getMembers($memberArray);
       if(isset($memberId) && !empty($memberId)) {
		   $memberData['marriage_date'] = $memberData['marital_status_id']  == 1 ? $memberData['marriage_date'] : "";
	   } else {
		   foreach ($memberData as $key => $value) {
			   $memberData[$key]['marriage_date'] = $memberData[$key]['marital_status_id']  == 1 ? $memberData[$key]['marriage_date'] : "";
		   }
	   }

        if(isset($memberData['education_id'])){
            $educationData  = $this->Mdl_member->getEducationName($memberData);
            $education      = array();
            foreach ($educationData as $keyEducation => $valueEducation){
                array_push($education,$valueEducation);
            }
            $memberData['education'] = $education;
            unset( $memberData[0]['education_id']);

        } else {
            foreach ($memberData as $key => $value) {
                $educationData  = $this->Mdl_member->getEducationName($value);
                $education      = array();
                foreach ($educationData as $keyEducation => $valueEducation){
                    array_push($education,$valueEducation);
                }
                $memberData[$key]['education'] = $education;
                unset( $memberData[$key]['education_id']);
            }
        }

        if (!empty($memberData)) {
            $this->response(array(
                'status' => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Member Listing successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $memberData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => $memberData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/login Login
     * @apiName Login
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Login
     * @apiVersion 0.1.0
     *
     * @apiUse mobile
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getMemberList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Notification Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                         "otp": "874376",
     *                         "otp_validity": "2018-12-13 17:32:22",
     *                         "member_id": "12"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/login
     */
    public function login_post()
    {
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be Integer');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $mobileNumber   = $this->input->post('mobile');
        $loginData      = $this->Mdl_member->checkMobile($mobileNumber);

        if (isset($loginData) && !empty($loginData)) {
            if ($loginData['is_active'] == 1) {
                $generateOtp            = GenRandomNumber(6);
                $endTime                = strtotime("+15 minutes", strtotime(date('Y-m-d H:i:s')));
                $generateOtpValidity    = date('Y-m-d H:i:s', $endTime);
                $OtpData = array(
                    'otp'           => $generateOtp,
                    'otp_validity'  => $generateOtpValidity,
                    'member_id'     => $loginData['member_id']
                );

                $otpResultData = $this->Mdl_member->updateOtp($OtpData, $loginData['member_id']);

                if ($otpResultData == 1) {
                    sendSms($mobileNumber, $OtpData['otp']);
                    unset($OtpData['otp']);
                    unset($OtpData['otp_validity']);

                    $this->response(array(
                        'status'        => TRUE,
                        'responseCode'  => self::HTTP_OK,
                        "message"       => 'OTP Sent Successfully',
                        'limit'         => DATA_LIMIT,
                        'data'          => $OtpData,
                    ), REST_Controller::HTTP_OK);
                } else {
                    $this->response(array(
                        "status"        => FALSE,
                        'responseCode'  => self::HTTP_NOT_FOUND,
                        'message'       => "OTP Not Sent",
                        'limit'         => DATA_LIMIT,
                        "data"          => null,
                    ), REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response(array(
                    "status"        => FALSE,
                    'responseCode'  => self::HTTP_NOT_FOUND,
                    'message'       => "Your account is inactive",
                    'limit'         => DATA_LIMIT,
                    "data"          => $loginData,
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "Mobile Number Does Not Exist",
                'limit'         => DATA_LIMIT,
                "data"          => $loginData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    /**@api {post} Api/SamajWebService/checkOtp Check-Otp
     * @apiName Check Otp
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Login
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse otp
     * @apiUse member_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getMemberList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Member Data is Available",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                         "member_id": "12",
     *                         "samaj_id": "13",
     *                         "member_number": "565656565",
     *                         "parent_member_id": "28",
     *                         "member_name": "Blake Patel",
     *                         "samaj_name": "Christ",
     *                         "first_name": "Blake",
     *                         "middle_name": "Dummy",
     *                         "surname": "Gaines",
     *                         "email": "widop@mailinator.net",
     *                         "blood_group": "Quas",
     *                         "marital_status": "Perspiciatis",
     *                         "date_of_birth": "29-10-2018",
     *                         "aadhar_card_no": "134564654414",
     *                         "auth_token": "zjul8iiptd2lxq71pkcb",
     *                         "is_active": "1",
     *                         "member_image_path": "http://192.168.0.128/samaj/uploads/member_image/0574a16046e21957fba985455256a5de.jpg"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     * @apiUse InvalidOtp
     * @apiUse ExpireOtp
     *
     * @apiSampleRequest Api/SamajWebService/login
     */
    public function checkOtp_post()
    {
        $this->form_validation->set_rules('otp', 'OTP', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be Integer');

        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $otp        = $this->input->post('otp');
        $memberId   = $this->input->post('member_id');
        $languageId = $this->input->post('language_id');

        $getOtpData = $this->Mdl_member->checkOtp($memberId);
        if (isset($getOtpData) && !empty($getOtpData)) {
            if ($otp == $getOtpData['otp']) {
                $currentTime    = new DateTime(date('Y-m-d H:i:s'));
                $validityTime   = new DateTime(date('Y-m-d H:i:s', strtotime($getOtpData['otp_validity'])));
                $interval       = $currentTime->diff($validityTime);
                $diffTime       = $interval->format('%R%I');
                if ($diffTime >= 0) {
                    $getAuthToken       = GenRandomAlphaNumeric(20);
                    $lastDay            = strtotime("+3 months", strtotime(date('Y-m-d')));
                    $AuthTokenValidity  = date('Y-m-d', $lastDay);
                    $updateAuthTokenArray = array(
                        'auth_token'          => $getAuthToken,
                        'auth_token_validity' => $AuthTokenValidity
                    );
                    $updatedAuthToken = $this->Mdl_member->updateAuthToken($memberId, $updateAuthTokenArray);
                    $memberData = array(
                        'member_id'     => $memberId,
                        'language_id'   => $languageId
                    );
                    $memberData = $this->Mdl_member->getMembers($memberData);

					$educationData  = $this->Mdl_member->getEducationName($memberData);
					$education      = array();
					foreach ($educationData as $keyEducation => $valueEducation){
						array_push($education,$valueEducation);
					}
					unset($memberData['education_id']);
					$memberData['education'] = $education;

                    if ($updatedAuthToken == 1) {
                        if (isset($memberData) && !empty($memberData)) {
                            $this->response(array(
                                'status'        => TRUE,
                                'responseCode'  => self::HTTP_OK,
                                "message"       => 'Verify Number Successfully',
                                'limit'         => DATA_LIMIT,
                                'data'          => $memberData,
                            ), REST_Controller::HTTP_OK);
                        } else {
                            $this->response(array(
                                "status"        => FALSE,
                                'responseCode'  => self::HTTP_NOT_FOUND,
                                'message'       => "No data Found",
                                'limit'         => DATA_LIMIT,
                                "data"          => null,
                            ), REST_Controller::HTTP_NOT_FOUND);
                        }
                    } else {
                        $this->response(array(
                            "status"        => FALSE,
                            'responseCode'  => self::HTTP_NOT_FOUND,
                            'message'       => "No data Found",
                            'limit'         => DATA_LIMIT,
                            "data"          => null,
                        ), REST_Controller::HTTP_NOT_FOUND);
                    }
                } else {
                    $this->response(array(
                        "status"        => FALSE,
                        'responseCode'  => self::HTTP_NOT_FOUND,
                        'message'       => "OTP validity has been expired",
                        'limit'         => DATA_LIMIT,
                        "data"          => null,
                    ), REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response(array(
                    "status"        => FALSE,
                    'responseCode'  => self::HTTP_NOT_FOUND,
                    'message'       => "invalid OTP",
                    'limit'         => DATA_LIMIT,
                    "data"          => null,
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => null,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/getNotificationList Notification-List
     * @apiName Notification List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Notification
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getNotificationList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Notification Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                    "notification_id": "227",
     *                    "notification_title": "New Data",
     *                    "description": "New Description",
     *                    "send_to": "Android",
     *                    "send_type": "Send_Now",
     *                    "notification_image": "668241f8d0658353ecd9f8c8be48d287.jpg",
     *                    "notification_type_id": "0",
     *                    "notification_type": "general",
     *                    "is_active": "1",
     *                    "created_at": "2018-10-05 11:45:44",
     *                    "created_by": "1",
     *                    "updated_at": "2018-10-05 11:45:44",
     *                    "updated_by": "1",
     *                    "notification_image_path": "http://192.168.0.128/samaj/uploads/notification_image/668241f8d0658353ecd9f8c8be48d287.png"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getNotificationList
     */
    public function getNotificationList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required|integer');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('notification_id', 'Notification Id', 'integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be numeric');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $start          = $this->input->post('start');
        $notificationId = $this->input->post('notification_id');
        $search         = $this->input->post('search');
        $start          = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $notificationDataArray = array(
            'start'             => $start,
            'search'            => $search,
            'notification_id'   => $notificationId
        );

        $notificationData = $this->Mdl_push_notification_master->getNotificationData($notificationDataArray);
        foreach ($notificationData as $key => $value) {
			if($notificationData[$key]['notification_image'] == "") {
				$notificationData[$key]['notification_image_path'] = null;
			}
		}

        if (!empty($notificationData)) {
            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Notification Listing successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $notificationData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => $notificationData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/getPostList Post-List
     * @apiName Post List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Post
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getPostList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Post Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                   "post_id": "2",
     *                   "title": "new",
     *                   "tags": "",
     *                   "is_active": "1",
     *                   "samaj_name": null,
     *                   "post_date": "14-12-2018",
     *                   "category_name": null,
     *                   "user_name": null,
     *                   "total_post_like": "3",
     *                   "content": "ply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only",
     *                   "file": "bd0e7bf92b2d8f3e0c03e398ef6700ce.jpg",
     *                   "post_image_path": "http://192.168.0.128/samaj/uploads/post_path/bd0e7bf92b2d8f3e0c03e398ef6700ce.jpg"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getPostList
     */
    public function getPostList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'MemberId', 'required|integer');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_rules('member_id', 'Member Id', 'integer');
        $this->form_validation->set_rules('post_id', 'Post Id', 'integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be numeric');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $start      = $this->input->post('start');
        $search     = $this->input->post('search');
        $languageId = $this->input->post('language_id');
        $memberId   = $this->input->post('member_detail_id');
        $postId     = $this->input->post('post_id');

        $start = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $postSearchArray = array(
            'start'         => $start,
            'search'        => $search,
            'language_id'   => $languageId,
            'member_id'     => $memberId,
            'post_id'       => $postId,

        );

        $postData = $this->Mdl_post->getPosts($postSearchArray);
        foreach ($postData as $key => $value) {
            $postImageData  = $this->Mdl_post->getFileNamesByPostId($value['post_id']);
            $image          = array();
            foreach ($postImageData as $keyImage => $valueImage){
                array_push($image,$valueImage);
            }
            $postData[$key]['post_image_path'] = $image;
        }


        if (!empty($postData)) {
            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Post Listing successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $postData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => $postData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/addEditPost Add/Edit Post
     * @apiName  Add/Edit Post
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Post
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse member_id
     * @apiParam {int} [post_id] Post Id to edit particular Post.
     * @apiUse samaj_id
     * @apiUse content
     * @apiUse tags
     * @apiUse is_active
     * @apiUse category_id
     * @apiUse other_oil
     * @apiUse post_image
     * @apiUse language_id
     *
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json}  Add/Edit Post
     * {
     * "status": true,
     * "responseCode": 200,
     * "message": "Post inserted/updated successfully"
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/addEditPost
     */
    public function addEditPost_post()
    {
        $this->db->trans_begin();
        $memberId = $this->input->post('member_detail_id');
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required');
        $this->form_validation->set_rules('member_id', 'Member Details Id', 'required|integer');
        $this->form_validation->set_rules('content', 'Content', 'required');
        $this->form_validation->set_rules('category_id[]', 'Category Id', 'required|integer');
        $this->form_validation->set_rules('tags[]', 'Tags', 'required');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required');
        $this->form_validation->set_message('required', '%s is required');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status" => FALSE,
                "message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data" => null,
                'responseCode' => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $postId         = $this->input->post('post_id');
        $categoryArray  = $this->input->post('category_id[]', true);
        $tagsArray      = $this->input->post('tags[]', true);
        $title          = $this->input->post('title', true);
        if(strlen($title) > 255){
            $this->response(array(
                'status' => FALSE,
                'responseCode' => self::HTTP_BAD_REQUEST,
                'message' => 'Please Enter Only 255 Characters in Title',
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $content        = $this->input->post('content', true);
        if(strlen($content) > 255){
            $this->response(array(
                'status' => FALSE,
                'responseCode' => self::HTTP_BAD_REQUEST,
                'message' => 'Please Enter Only 255 Characters in Content',
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $isActive       = $this->input->post('is_active', true);
        $postDescId     = $this->input->post('post_description_id');
        $languageId     = $this->input->post('language_id');

        $postArray = array(
            'post_id'       => $postId,
            'member_id'     => $memberId,
            'samaj_id'      => $this->input->post('samaj_id'),
            'tags'          => is_array($tagsArray) ? implode(",", $tagsArray) : '',
            'is_active'     => isset($isActive) ? 1 : 0,
            'created_at'    => date('Y-m-d h:i:s'),
            'created_by'    => $memberId,
            'updated_at'    => date('Y-m-d h:i:s'),
            'updated_by'    => $memberId,
        );
        if ((isset($postId) && $postId != '')) {
            $successMessage = 'Post updated successfully';
            $errorMessage = 'No data found';
        } else {
            unset($postArray['post_id']);
            $successMessage = 'Post inserted successfully';
            $errorMessage = 'Post insert unsuccessful';
        }
        $postData = $this->Mdl_samaj_webservice->insertUpdateRecordApi($postArray, 'post_id', 'tbl_posts', 1);
        $lastPostId = $postData['lastInsertedId'];


		$languageData   = $this->Mdl_language->getLanguageListing();

		if(isset($postId) && !empty($postId) && isset($languageId) && !empty($languageId)){
            $postParameterArray = array(
				'post_id' => $postId,
				'language_id' => $languageId
			);
            $postDescId     = $this->Mdl_post->getPostDescId($postParameterArray);
            if(empty($postDescId)) {
                $this->response(array(
                    'status' => FALSE,
                    'responseCode' => self::HTTP_NOT_FOUND,
                    'message' => 'No Post Found With Intended Language',
                ), REST_Controller::HTTP_NOT_FOUND);
            } else {
                $postUpdateArray[] = array (
                    'post_description_id' => $postDescId[0]['post_description_id'],
                    'post_id'             => $lastPostId,
                    'language_id'         => $languageId,
                    'title'               => $title,
                    'content'             => $content,
                    'created_at'          => date('Y-m-d h:i:s'),
                    'created_by'          => $memberId,
                    'updated_at'          => date('Y-m-d h:i:s'),
                    'updated_by'          => $memberId,
                );
            }
        }
		foreach ($languageData as $key => $value) {
            if($postId == ''){
                $postInsertArray[] = array(
					'post_id'       => $lastPostId,
					'language_id'   => $value['language_id'],
					'title'         => $title,
					'content'       => $content,
					'created_at'    => date('Y-m-d h:i:s'),
					'created_by'    => $memberId,
					'updated_at'    => date('Y-m-d h:i:s'),
					'updated_by'    => $memberId,
				);
			}
		}



        if (!empty($postInsertArray)) {
            $this->Mdl_post->batchInsert($postInsertArray, 'tbl_post_description');
        }
        if (!empty($postUpdateArray)) {
            $this->Mdl_post->batchUpdate($postUpdateArray, 'post_description_id', 'tbl_post_description');
        }

        $categoryDeleteResult = $this->Mdl_post->deletePostCategory($lastPostId);

        foreach ($categoryArray as $key => $value) {
            $dataArray = array(
                "post_id" => $lastPostId,
                "category_id" => $value
            );
            $this->Mdl_samaj_webservice->insertUpdateData($dataArray, 'postCategory');
        }

        if ($postData['success'] && $postData['lastInsertedId'] != '') {
            if (isset($_FILES["post_image"])) {
                $PostPath       = $this->config->item('post_path');
                $imageResult    = $this->dt_ci_file_upload->UploadMultipleFile('post_image', MAX_IMAGE_SIZE_LIMIT, $PostPath, true, true, array('jpeg', 'png', 'jpg', 'JPG','gif'));

                if ($imageResult['success'] == false) {
                    $this->response(array(
                        'status' => FALSE,
                        'responseCode' => self::HTTP_OK,
                        'message' => strip_tags($imageResult['msg']),
                    ), REST_Controller::HTTP_OK);
                } else {
                    unset($imageResult['success']);
                    $batchArray = array();
                    foreach ($imageResult as $key => $data) {
                        $batchArray[] = array(
                            'post_file_id' => '',
                            'post_id' => $lastPostId,
                            'filename' => $data['file_name'],
                        );
                    }
                    $this->db->insert_batch('tbl_post_file', $batchArray);
                }
            }
            $postRemoveFile = $this->input->post("post_remove_file");
            if ($postRemoveFile != '') {
                $postImageResult = $this->Mdl_post->getImage($postRemoveFile);
                foreach ($postImageResult as $postMediaData) {
                    if (isset($postMediaData) && !empty($postMediaData)) {
                        if (file_exists($this->config->item('post_path') . $postMediaData['filename'])) {
                            unlink($this->config->item('post_path') . $postMediaData['filename']);
                        }
                    }
                }
                $this->Mdl_post->deletePostImageEntryByPost($postRemoveFile);
            }

            $this->db->trans_commit();
            $this->response(array(
                'status' => TRUE,
                'responseCode' => self::HTTP_OK,
                'message' => $successMessage,
            ), REST_Controller::HTTP_OK);

        } else {
            $this->db->trans_rollback();
            $this->response(array(
                'status' => FALSE,
                'responseCode' => self::HTTP_NOT_FOUND,
                'message' => $errorMessage,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/deletePost Delete-Post
     * @apiName Delete Post
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Post
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse post_id
     *
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} deletePost
     * {
     *      "status": true,
     *      "responseCode": 200,
     *      "message": "Post Listing successfully",
     *      "limit": 10,
     *      "data":
     *          {
     *           "success":  true
     *          }
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/deletePost
     */
    public function deletePost_post()
    {
        $postId = $this->input->post('post_id');
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'MemberId', 'required|integer');
        $this->form_validation->set_rules('post_id', 'PostId', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
		$postData        = array();
        $postImageResult = $this->Mdl_post->getPostImageListing($postId);

        foreach ($postImageResult as $postMediaData) {
            //delete image file from folder
            if (isset($postMediaData) && !empty($postMediaData)) {
                if (file_exists($this->config->item('post_path') . $postMediaData['filename'])) {
                    @unlink($this->config->item('post_path') . $postMediaData['filename']);
                }
            }
        }
		$postData = $this->Mdl_post->deleteRecord($postId);

        if ($postData['success']) {
            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Post deleted successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $postData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "Deleting Post Failed",
                'limit'         => DATA_LIMIT,
                "data"          => $postData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/deleteBusiness Delete-Business
     * @apiName Delete Business
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Business
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse business_id
     *
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} deleteBusiness
     * {
     *      "status": true,
     *      "responseCode": 200,
     *      "message": "Business deleted successfully",
     *      "limit": 10,
     *      "data":
     *          {
     *           "success": true
     *          }
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/deleteBusiness
     */
    public function deleteBusiness_post()
    {
        $businessData = array();
        $businessId = $this->input->post('business_id');
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required|integer');
        $this->form_validation->set_rules('business_id', 'Business Id', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be integer');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $businessImageResult = $this->Mdl_business->getImage($businessId);
        if (isset($businessImageResult) && !empty($businessImageResult)) {
            foreach ($businessImageResult as $businessMediaData) {
                //delete image file from folder
                if (isset($businessMediaData) && !empty($businessMediaData)) {
                    if (file_exists($this->config->item('business_path') . $businessMediaData['filename'])) {
                        @unlink($this->config->item('business_path') . $businessMediaData['filename']);
                    }
                }
            }
        }
        $businessData = $this->Mdl_business->deleteRecord($businessId);

        if ($businessData['success']) {
            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Business deleted successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $businessData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "Business Failed to delete",
                'limit'         => DATA_LIMIT,
                "data"          => $businessData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/getBusinessList Business-List
     * @apiName Business List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Business
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     * @apiUse language_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getBusinessList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Business Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                  "business_id": "250",
     *                    "member_id": "12",
     *                    "member_name": "Blake Dummy Gaines",
     *                    "business_name": "Dummy Business",
     *                    "owner_name": "Dummy Owner",
     *                    "address": "A-301, A Wing, Hetal Arch, Opp. Natraj Market,\nS.V. Road, Malad West, Mumbai.",
     *                    "address_geo": "Babulal Avasti Compound, Opp. Arun Mill, Wallbhat Road, Goregaon (East), Mumbai, Maharashtra 400063, India",
     *                    "description": "<p>ABC</p>",
     *                    "business_type_id": "1",
     *                    "business_type_name": "New Dummy",
     *                    "lat": "19.1581061057055",
     *                    "lng": "72.85170933337395",
     *                    "file": "9cd5b562bdd65f377c100c9d83793746.png",
     *                    "business_image_path": "http://192.168.0.128/samaj/uploads/business_path/9cd5b562bdd65f377c100c9d83793746.png"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getBusinessList
     */
    public function getBusinessList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'MemberId', 'required');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_rules('business_id', 'Business Id', 'integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be numeric');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $businessId = $this->input->post('business_id');
        if ($businessId != '' && is_numeric($businessId)) {
            $existence = $this->Mdl_business->checkBusinessId($businessId, 'tbl_business');
            if ($existence['count'] == 0) {
                $this->response(array(
                    "status"        => FALSE,
                    "message"       => "No Such Business Exists",
                    'responseCode'  => self::HTTP_BAD_REQUEST,
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        $start          = $this->input->post('start');
        $search         = $this->input->post('search');
        $languageId     = $this->input->post('language_id');
        $memberId       = $this->input->post('member_detail_id');
        $businessString = explode(',', $this->input->post('business_string'));
        // check start is greater than 1 or not
        $start          = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $businessSearchArray = array(
            'start'             => $start,
            'search'            => $search,
            'language_id'       => $languageId,
            'business_id'       => $businessId,
            'member_id'         => $memberId,
            'business_type_id'  => $businessString,
        );
        $businessData = $this->Mdl_business->getBusinesses($businessSearchArray);
        if (!empty($businessData)) {
            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Business Listing successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $businessData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => $businessData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/addEditBusiness Add/Edit Business
     * @apiName  Add/Edit Business
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Business
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiParam {int} [business_id = "1"]  The Business Id  for Editing Business.
     * @apiUse business_type_id
     * @apiUse member_id
     * @apiUse business_name
     * @apiUse owner_name
     * @apiUse address
     * @apiUse address_geo
     * @apiUse description
     * @apiUse lat
     * @apiUse lng
     * @apiUse business_mobile
     * @apiUse business_email
     * @apiUse business_telephone
     * @apiUse business_image
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json}  Add/Edit Business
     * {
     * "status": true,
     * "responseCode": 200,
     * "message": "Business inserted/updated successfully"
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/addEditBusiness
     */
    public function addEditBusiness_post()
    {

        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'MemberId', 'required');
        $this->form_validation->set_rules('business_type_id', 'Business Type', 'required|integer');
        $this->form_validation->set_rules('member_detail_id', 'Member Detail Id', 'required|integer');
        $this->form_validation->set_rules('business_name', 'Business Name', 'required');
        $this->form_validation->set_rules('owner_name', 'Owner Name', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('address_geo', 'Address Geo', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('lat', 'Latitude', 'required');
        $this->form_validation->set_rules('lng', 'Longitude', 'required');
        $this->form_validation->set_rules('business_mobile[]', 'Business Mobile', 'required|integer');
        $this->form_validation->set_rules('business_email[]', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('business_telephone[]', 'Business Telephone', 'integer');
        $this->form_validation->set_rules('business_pincode', 'Business Pincode', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be Integer');
        $this->form_validation->set_message('number', '%s should be numeric');
        $this->form_validation->set_message('valid_email', 'Enter valid %s ');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status" => FALSE,
                "message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data" => null,
                'responseCode' => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        } else{
            $businessId      = $this->input->post('business_id');
            $languageId      = $this->input->post('language_id', true);
            $usrId           = $this->input->post('user_id');
            $businessName    = $this->input->post('business_name', true);
            $businessNameArray = array(
                'business_name' => $businessName,
                'business_id'   => $businessId
            );
            if (isset($businessName)) {
                $checkBusinessName = $this->Mdl_business->checkExistingBusinessName($businessNameArray);
                if($checkBusinessName){
                    $this->response(array(
                        'status' => FALSE,
                        'responseCode' => self::HTTP_BAD_REQUEST,
                        'message' => 'Duplicate Entry Of Business Name',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                }

            }
            $ownerName       = $this->input->post('owner_name', true);
            if(is_numeric($ownerName)){
                $this->response(array(
                    'status' => FALSE,
                    'responseCode' => self::HTTP_BAD_REQUEST,
                    'message' => 'Please Enter Only Characters in Owner Name',
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
            $description     = $this->input->post('description', true);
            $address         = $this->input->post('address', true);
            $stateId         = $this->input->post('state_id', true);
            $cityId          = $this->input->post('city_id', true);
            $businessPincode = $this->input->post('business_pincode', true);
            if(isset($businessPincode) && !is_numeric($businessPincode)){
                if(!is_numeric($businessPincode) && strlen($businessPincode) != 6){
                    $this->response(array(
                        'status' => FALSE,
                        'responseCode' => self::HTTP_BAD_REQUEST,
                        'message' => 'Invalid Entry Of Business Pincode, Business Pincode Must have only 6 digit',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                }
            }

            $businessMobile = $this->input->post('business_mobile[]', true);
            if (isset($businessMobile) && is_numeric($businessMobile)) {
                foreach ($businessMobile as $key => $value) {
                    if (strlen($businessMobile[$key]) != 10) {
                        $this->response(array(
                            'status' => FALSE,
                            'responseCode' => self::HTTP_BAD_REQUEST,
                            'message' => 'Invalid Entry Of Mobile Number, Mobile number Must have only 10 digit',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    }
                }
                if (isset($businessMobile)) {
                    $checkMobile = $this->Mdl_business->checkExistingMobileNumber($businessMobile,$businessId);
                    if($checkMobile){
                        $this->response(array(
                            'status' => FALSE,
                            'responseCode' => self::HTTP_BAD_REQUEST,
                            'message' => 'Duplicate Entry Of Mobile Number',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    }
                }
            }
            $businessTelephone = $this->input->post('business_telephone[]', true);
            if (isset($businessTelephone) && is_numeric($businessTelephone)) {
                foreach ($businessTelephone as $key => $value) {
                    if (strlen($businessTelephone[$key]) != 12) {
                        $this->response(array(
                            'status'        => FALSE,
                            'responseCode'  => self::HTTP_BAD_REQUEST,
                            'message'       => 'Invalid Entry Of Telephone Number, Telephone number Must have only 12 digit',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    }
                }
                if (isset($businessTelephone)) {
                    $checkTelephone = $this->Mdl_business->checkExistingBusinessTelephone($businessTelephone,$businessId);
                    if($checkTelephone){
                        $this->response(array(
                            'status'        => FALSE,
                            'responseCode'  => self::HTTP_BAD_REQUEST,
                            'message'       => 'Duplicate Entry Of Telephone Number',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    }
                }
            }
            $businessEmail = $this->input->post('business_email[]', true);
            if (isset($businessEmail)) {
                $checkBusinessEmail = $this->Mdl_business->checkExistingBusinessEmail($businessEmail,$businessId);
                if($checkBusinessEmail){
                    $this->response(array(
                        'status' => FALSE,
                        'responseCode' => self::HTTP_BAD_REQUEST,
                        'message' => 'Duplicate Entry Of Business Email',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                }
            }
            $businessArray   = array(
                'business_id'       => $businessId,
                'business_type_id'  => $this->input->post('business_type_id'),
                'member_id'         => $this->input->post('member_detail_id'),
                'address_geo'       => $this->input->post('address_geo'),
                'lat'               => $this->input->post('lat'),
                'lng'               => $this->input->post('lng'),
                'state_id'          => $stateId,
                'city_id'           => $cityId,
                'business_pincode'  => $businessPincode,
            );
            if ((isset($businessId) && $businessId != '')) {
                $successMessage     = 'Business updated successfully';
                $errorMessage       = 'No data found';
            } else {
                unset($businessArray['business_id']);
                $successMessage     = 'Business inserted successfully';
                $errorMessage       = 'Business insert unsuccessful';
            }
            $this->db->trans_begin();
            $businessData = $this->Mdl_samaj_webservice->insertUpdateRecordApi($businessArray, 'business_id', 'tbl_business', 1);
            $lastBusinessId = $businessData['lastInsertedId'];
            $deleteArray = array(
                'business_id' => $lastBusinessId
            );

            $languageData       = $this->Mdl_language->getLanguageListing();

            if(isset($businessId) && !empty($businessId) && isset($languageId) && !empty($languageId)){
                $businessParametersArray = array(
                    'business_id' => $businessId,
                    'language_id' => $languageId
                );
                $businessDescId     = $this->Mdl_business->getBusinessDescId($businessParametersArray);
                if(empty($businessDescId)) {
                    $this->response(array(
                        'status' => FALSE,
                        'responseCode' => self::HTTP_BAD_REQUEST,
                        'message' => 'No Business Found With Intended Language',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $businessDescUpdateArray[] = array(
                        'business_description_id'   => $businessDescId[0]['business_description_id'],
                        'business_id'               => $lastBusinessId,
                        'business_name'             => $businessName,
                        'owner_name'                => $ownerName,
                        'description'               => $description,
                        'address'                   => $address,
                        'language_id'               => $languageId,
                    );
                }
            }
            foreach ($languageData as $key => $value) {
                if ($businessId == '') {
                    $businessDescInsertArray[] = array(
                        'business_id'   => $lastBusinessId,
                        'language_id'   => $value['language_id'],
                        'business_name' => $businessName,
                        'owner_name'    => $ownerName,
                        'address'       => $address,
                        'description'   => $description,
                    );
                }
            }

            if (!empty($businessDescInsertArray)) {
                $this->Mdl_business->batchInsert($businessDescInsertArray, 'tbl_business_description');
            }
            if (!empty($businessDescUpdateArray)) {
                $this->Mdl_business->batchUpdate($businessDescUpdateArray, 'business_description_id', 'tbl_business_description');
            }

            if (isset($lastBusinessId) && !empty($lastBusinessId)) {
                if (isset($businessId) && !empty($businessId)) {

                    $this->Mdl_samaj_webservice->deleteData($deleteArray, 'tbl_business_mobile');
                }
                foreach ($businessMobile as $key => $value) {
                    $dataArray = array(
                        "business_id" => $lastBusinessId,
                        "mobile" => $value
                    );
                    $this->Mdl_samaj_webservice->insertUpdateData($dataArray, 'businessMobile');
                }

                if (isset($businessId) && !empty($businessId)) {

                    $this->Mdl_samaj_webservice->deleteData($deleteArray, 'tbl_business_email');
                }
                foreach ($businessEmail as $key => $value) {
                    $dataArray = array(
                        "business_id" => $lastBusinessId,
                        "email" => $value
                    );
                    $this->Mdl_samaj_webservice->insertUpdateData($dataArray, 'businessEmail');
                }
                if (isset($businessId) && !empty($businessId)) {

                    $this->Mdl_samaj_webservice->deleteData($deleteArray, 'tbl_business_telephone');
                }
                foreach ($businessTelephone as $key => $value) {
                    $dataArray = array(
                        "business_id" => $lastBusinessId,
                        "telephone" => $value
                    );
                    $this->Mdl_samaj_webservice->insertUpdateData($dataArray, 'businessTelephone');
                }
                if (isset($_FILES['business_image'])) {

                    $businessPath = $this->config->item('business_path');
                    $imageResult = $this->dt_ci_file_upload->UploadMultipleFile('business_image', MAX_IMAGE_SIZE_LIMIT, $businessPath, true, true, array('jpeg', 'png', 'jpg', 'JPG'));

                    if ($imageResult['success'] == false) {
                        $this->response(array(
                            'status' => FALSE,
                            'responseCode' => self::HTTP_OK,
                            'message' => strip_tags($imageResult['msg']),
                        ), REST_Controller::HTTP_OK);
                    } else {
                        if (isset($businessId) && !empty($businessId)) {
                            $businessImageResult = $this->Mdl_samaj_webservice->getImage($lastBusinessId);
                            foreach ($businessImageResult as $businessMediaData) {
                                //delete image file from folder
                                if (isset($businessMediaData) && !empty($businessMediaData)) {
                                    if (file_exists($this->config->item('business_path') . $businessMediaData)) {
                                        @unlink($this->config->item('business_path') . $businessMediaData);
                                    }
                                }
                                $this->Mdl_samaj_webservice->deleteData($deleteArray, 'tbl_business_file');
                            }
                        }
                        unset($imageResult['success']);
                        $batchArray = array();
                        foreach ($imageResult as $key => $data) {
                            $batchArray[] = array(
                                'business_file_id' => '',
                                'business_id' => $lastBusinessId,
                                'filename' => $data['file_name'],
                                'file_real_name' => $data['file_realname'],
                            );
                        }
                        $this->db->insert_batch('tbl_business_file', $batchArray);
                    }
                }
                $this->db->trans_commit();
                $this->response(array(
                    'status' => TRUE,
                    'responseCode' => self::HTTP_OK,
                    'message' => $successMessage,
                ), REST_Controller::HTTP_OK);
            } else {
                $this->db->trans_rollback();
                $this->response(array(
                    'status' => FALSE,
                    'responseCode' => self::HTTP_NOT_FOUND,
                    'message' => $errorMessage,
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        }

    }

    /**@api {post} Api/SamajWebService/getLanguageList Language-List
     * @apiName Language List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Language
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getLanguageList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Language Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                       "language_id": "1",
     *                       "language_code": "EN",
     *                       "language_name": "English",
     *                       "is_default": "1",
     *                       "is_active": "1",
     *                       "created_at": "2019-02-12 02:17:16",
     *                       "created_by": "1",
     *                       "updated_at": "2019-09-18 03:05:08",
     *                       "updated_by": "1"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getLanguageList
     */
    public function getLanguageList_post()
    {
        $start = $this->input->post('start');
        $start = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $languageDataArray = array(
            'start' => $start
        );
        $languageData = $this->Mdl_language->getLanguageListing($languageDataArray);
        if (!empty($languageData)) {
            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Language Listing successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $languageData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => $languageData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/getBusinessTypeList BusinessType-List
     * @apiName BusinessType List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Business Type
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getBusinessTypeList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "BusinessType Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                       "business_type_id": "1",
     *                       "parent_business_type_id": "0",
     *                       "business_type_name": "New Dummy",
     *                       "sort_order": "1",
     *                       "is_active": "1",
     *                       "created_at": "2019-02-21 16:46:00",
     *                       "created_by": "1",
     *                       "updated_at": "2019-02-21 16:46:00",
     *                       "updated_by": "1"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getBusinessTypeList
     */
    public function getBusinessTypeList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be enter integer value');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $start      = $this->input->post('start');
        $search     = $this->input->post('search');
        $languageId = $this->input->post('language_id');
        $start      = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $businessTypeData = array(
            'start'         => $start,
            'search'        => $search,
            'language_id'   => $languageId
        );
        $getBusinessTypeData = $this->Mdl_business_type->getBusinessTypeListing($businessTypeData);

        if (!empty($getBusinessTypeData)) {
            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Business Type Listing successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $getBusinessTypeData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => $getBusinessTypeData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }


    }

    /**@api {post} Api/SamajWebService/addEditMember Add/Edit Member
     * @apiName  Add/Edit Member
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Member
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse aadhar_card_no
     * @apiUse member_id
     * @apiUse member_number
     * @apiUse email
     * @apiUse first_name
     * @apiUse middle_name
     * @apiUse surname
     * @apiUse blood_group
     * @apiUse marital_status
     * @apiUse date_of_birth
     * @apiUse language_id
     * @apiUse is_active
     * @apiUse education
     * @apiUse age
     * @apiUse home_town
     * @apiUse current_work
     * @apiUse member_mobile
     * @apiUse member_mobile_type
     * @apiUse samaj_id
     * @apiUse parent_member_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json}  Add/Edit Member
     * {
     * "status": true,
     * "responseCode": 200,
     * "message": "Member inserted/updated successfully"
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/addEditMember
     */
    public function addEditMember_post()
    {
        $this->db->trans_begin();
        $memberId = $this->input->post('member_detail_id', true);
        if (isset($memberId) && $memberId == '') {
//            $this->form_validation->set_rules('aadhar_card_no','Adhar Card Number', 'required|is_unique[tbl_members.aadhar_card_no]');
//            $this->form_validation->set_rules('member_number','Member Number', 'required|is_unique[tbl_members.member_number]');
//            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[tbl_members.email]');
        } else {

            $this->form_validation->set_rules('email', 'Email', 'required|edit_unique[tbl_members.email.' . $memberId . ']');
        }
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('middle_name', 'Middle Name', 'required');
		$this->form_validation->set_rules('surname_id', 'Surname id', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('blood_group_id', 'Blood Group Id', 'required|integer');
        $this->form_validation->set_rules('marital_status_id', 'Marital Status Id', 'required|integer');
        $this->form_validation->set_rules('date_of_birth', 'Date Of Birth', 'required|date');
        $this->form_validation->set_rules('member_mobile[]', 'Member Mobile', 'required|integer');
        $this->form_validation->set_rules('member_pincode', 'Member Pincode', 'required|integer');
        $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card No', 'integer');
        $this->form_validation->set_rules('gender_id', 'Gender Id', 'integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be integer');
        $this->form_validation->set_message('date', '%s ');
        $this->form_validation->set_message('is_unique', 'This %s already exists');
        $this->form_validation->set_message('edit_unique', 'This %s already exists');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status" => FALSE,
                "message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data" => null,
                'responseCode' => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $memberDescId       = $this->input->post('member_description_id', true);
            $languageId         = $this->input->post('language_id', true);
            $samajId            = $this->input->post('samaj_id', true);
            $isActive           = $this->input->post('is_active', true);
            $firstName          = $this->input->post('first_name', true);
            $middleName         = $this->input->post('middle_name', true);
            $surnameId          = $this->input->post('surname_id', true);
            $education          = $this->input->post('education_id', true);
            $currentWork        = $this->input->post('current_work', true);
            $memberNumber      = $this->input->post('member_number');
			$marriageDate      = $this->input->post('marriage_date');
			$relationship      = $this->input->post('relationship_master_id');
            $memberNumberArray = array(
                'member_number' => $memberNumber,
                'member_id'     => $memberId
            );
            if (isset($memberNumber)) {
                $checkMemberNumber = $this->Mdl_member->checkExistingMemberNumber($memberNumberArray);
                if($checkMemberNumber){
                    $this->response(array(
                        "status" => FALSE,
                        'responseCode' => self::HTTP_NOT_FOUND,
                        'message' => "Member Number already exist.",
                        'limit' => DATA_LIMIT,
                        "data" => null
                    ), REST_Controller::HTTP_NOT_FOUND);
                }
            }
            $email              = $this->input->post('email', true);
            $emailCheckArray = array(
                'email'     => $email,
                'member_id' => $memberId
            );
            if (isset($email)) {
                $checkEmail = $this->Mdl_member->checkExistingEmail($emailCheckArray);
                if($checkEmail){
                    $this->response(array(
                        "status" => FALSE,
                        'responseCode' => self::HTTP_NOT_FOUND,
                        'message' => "Member Email already exist.",
                        'limit' => DATA_LIMIT,
                        "data" => null
                    ), REST_Controller::HTTP_NOT_FOUND);
                }
            }



            $memberMobileArray  = $this->input->post('member_mobile[]', true);
            if (isset($memberMobileArray)) {
                foreach ($memberMobileArray as $key => $value) {
                    if (strlen($memberMobileArray[$key]) != 10) {
                        $this->response(array(
                            'status' => FALSE,
                            'responseCode' => self::HTTP_BAD_REQUEST,
                            'message' => 'Invalid Entry Of Mobile Number, Mobile number Must have only 10 digit',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    }
                }
                if (isset($memberMobileArray)) {
                    $checkMobile = $this->Mdl_member->checkExistingMobileNumber($memberMobileArray,$memberId);
                    if($checkMobile){
                        $this->response(array(
                            'status' => FALSE,
                            'responseCode' => self::HTTP_BAD_REQUEST,
                            'message' => 'Duplicate Entry Of Mobile Number',
                        ), REST_Controller::HTTP_BAD_REQUEST);
                    }
                }
            }
            $mobileType         = $this->input->post('member_mobile_type[]', true);
			$cityId             = $this->input->post('city_id');
			$stateId            = $this->input->post('state_id');
			$genderId           = $this->input->post('gender_id');
			$memberAddress      = $this->input->post('member_address');
			$memberPincode      = $this->input->post('member_pincode');
			if(isset($memberPincode) && strlen($memberPincode) != 6){
                $this->response(array(
                    'status' => FALSE,
                    'responseCode' => self::HTTP_BAD_REQUEST,
                    'message' => 'Invalid Entry Of Member Pincode, Member Pincode Must have only 6 digit',
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
            $aadharCardNo       = $this->input->post('aadhar_card_no');
			$aadharCardNoArray = array(
			    'aadhar_card_no' => $aadharCardNo,
                'member_id'     => $memberId
            );
            if (isset($aadharCardNo)) {
                $checkAadharCardNo = $this->Mdl_member->checkExistingAadharCardNo($aadharCardNoArray);
                if(strlen($aadharCardNo) != 12){
                    $this->response(array(
                        'status' => FALSE,
                        'responseCode' => self::HTTP_BAD_REQUEST,
                        'message' => 'Invalid Entry Of Aadhar Card No, Aadhar Card No Must have only 12 digit',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                }
                if($checkAadharCardNo){
                    $this->response(array(
                        'status' => FALSE,
                        'responseCode' => self::HTTP_BAD_REQUEST,
                        'message' => 'Duplicate Entry Of Aadhar card No',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                }
            }

            $education    = (isset($education)) ? $education : 0;

            $memberArray = array(
                'member_id'         	 => $memberId,
                'samaj_id'          	 => $samajId,
                'surname_id'        	 => $surnameId,
                'parent_member_id'  	 => $this->input->post('parent_member_id'),
                'member_number'      	 => $memberNumber,
                'education_id'      	 => $education,
                'current_work'       	 => $currentWork,
                'email'             	 => $email,
                'blood_group_id'   		 => $this->input->post('blood_group_id', TRUE),
                'marital_status_id'		 => $this->input->post('marital_status_id', TRUE),
                'date_of_birth'     	 => DMYToYMD($this->input->post('date_of_birth', TRUE)),
				'marriage_date'    		 => isset($marriageDate) && $marriageDate != null ? DMYToYMD($marriageDate) : 0000-00-00,
                'city_id'          		 => $cityId,
                'state_id'         		 => $stateId,
                'gender_id'       		 => $genderId,
                'member_pincode'   		 => $memberPincode,
                'aadhar_card_no'    	 => $aadharCardNo,
                'relationship_master_id' => isset($relationship) ? $relationship : 0,
                'is_active'         	 => isset($isActive) ? 1 : 0,
            );

            if ((isset($memberId) && $memberId != '')) {
                $successMessage     = 'Member updated successfully';
                $errorMessage       = 'No data found';
            } else {
                unset($memberArray['member_id']);
                $successMessage     = 'Member inserted successfully';
                $errorMessage       = 'Member insert unsuccessful';
            }

            $memberData     = $this->Mdl_samaj_webservice->insertUpdateRecordApi($memberArray, 'member_id', 'tbl_members', 1);
            $lastMemberId   = $memberData['lastInsertedId'];
            $deleteMemberArray = array(
                'member_id' => $lastMemberId
            );

            $languageData       = $this->Mdl_language->getLanguageListing();
            if(isset($memberId) && !empty($memberId) && isset($languageId) && !empty($languageId)){
                $memberParametersArray = array(
                    'member_id'   => $memberId,
                    'language_id' => $languageId,
                );
                $memberDescId       = $this->Mdl_member->getMemberDescriptionId($memberParametersArray);
                if(empty($memberDescId)) {
                    $this->response(array(
                        'status' => FALSE,
                        'responseCode' => self::HTTP_BAD_REQUEST,
                        'message' => 'No Member Found With Intended Language',
                    ), REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $memberDescUpdateArray[] = array(
                        'member_description_id'     => $memberDescId[0]['member_description_id'],
                        'member_id'                 => $lastMemberId,
                        'first_name'                => $firstName,
                        'middle_name'               => $middleName,
                        'member_address'            => $memberAddress,
                        'language_id'               => $languageId,
                        'updated_at'                => date('Y-m-d  h:i:s'),
                    );
                }
            }

            foreach ($languageData as $key => $value){
                 if($memberId == '') {
                     $memberDescInsertArray[] = array(
                        'member_id'         => $lastMemberId,
                        'first_name'        => $firstName,
                        'middle_name'       => $middleName,
                        'member_address'    => $memberAddress,
                        'language_id'       => $value['language_id'],
                        'created_at'        => date('Y-m-d h:i:s'),
                        'updated_at'        => date('Y-m-d  h:i:s'),
                    );
                }
            }

            if (!empty($memberDescInsertArray)) {
                $this->Mdl_member->batchInsert($memberDescInsertArray, 'tbl_member_description');
            }
            if (!empty($memberDescUpdateArray)) {
                $this->Mdl_member->batchUpdate($memberDescUpdateArray, 'member_description_id', 'tbl_member_description');
            }

            if (isset($lastMemberId) && !empty($lastMemberId)) {

                if (isset($memberId) && !empty($memberId)) {

                    $this->Mdl_samaj_webservice->deleteData($deleteMemberArray, 'tbl_member_mobile');
                }
                foreach ($memberMobileArray as $key => $value) {
                    $dataArray = array(
                        "member_id"     => $lastMemberId,
                        "mobile"        => $memberMobileArray[$key],
                        "mobile_type"   => $mobileType[$key],

                    );
                }
                $this->Mdl_samaj_webservice->insertUpdateData($dataArray, 'memberMobiles');

                if (isset($_FILES['member_image'])) {
                    $memberPath     = $this->config->item('member_image_path');
                    $imageResult    = $this->dt_ci_file_upload->UploadMultipleFile('member_image', MAX_IMAGE_SIZE_LIMIT, $memberPath, true, true, array('jpeg', 'png', 'jpg', 'JPG'));

                    if ($imageResult['success'] == false) {
                        $this->response(array(
                            'status'        => FALSE,
                            'responseCode'  => self::HTTP_OK,
                            'message'       => strip_tags($imageResult['msg']),
                        ), REST_Controller::HTTP_OK);
                    } else {
                        if (isset($memberId) && !empty($memberId)) {
                            $memberImageResult = $this->Mdl_member->getImage($lastMemberId);

                            foreach ($memberImageResult as $memberMediaData) {
                                //delete image file from folder
                                if (isset($memberMediaData) && !empty($memberMediaData)) {
                                    if (file_exists($this->config->item('member_image_path') . $memberMediaData['filename'])) {
                                        @unlink($this->config->item('member_image_path') . $memberMediaData['filename']);
                                    }
                                }
                                $this->Mdl_samaj_webservice->deleteMemberFile($deleteMemberArray, 'tbl_member_file');
                            }
                        }
                        unset($imageResult['success']);
                        $batchArray = array();
                        foreach ($imageResult as $key => $data) {
                            $batchArray[] = array(
                                'member_id'   => $lastMemberId,
                                'filename'    => $data['file_name'],
                            );
                        }
                        $this->db->insert_batch('tbl_member_file', $batchArray);
                    }
                }
                if (isset($_FILES['member_biodata'])) {
                    $memberPath     = $this->config->item('member_biodata_path');
                    $imageResult    = $this->dt_ci_file_upload->UploadMultipleFile('member_biodata', MAX_DOCUMENT_SIZE_LIMIT, $memberPath, true, true, array('jpeg', 'png', 'jpg', 'JPG', 'pdf', 'docx'));

                    if ($imageResult['success'] == false) {
                        $this->response(array(
                            'status'        => FALSE,
                            'responseCode'  => self::HTTP_OK,
                            'message'       => strip_tags($imageResult['msg']),
                        ), REST_Controller::HTTP_OK);
                    } else {
                        if (isset($memberId) && !empty($memberId)) {
                            $memberBioDataResult = $this->Mdl_member->getBioData($lastMemberId);

                            foreach ($memberBioDataResult as $memberBioData) {
                                //delete image file from folder
                                if (isset($memberBioData) && !empty($memberBioData)) {
                                    if (file_exists($this->config->item('member_biodata_path') . $memberBioData['filename'])) {
                                        @unlink($this->config->item('member_biodata_path') . $memberBioData['filename']);
                                    }
                                }
                                $this->Mdl_samaj_webservice->deleteMemberFile($deleteMemberArray, 'tbl_member_biodata_file');
                            }
                        }

                        unset($imageResult['success']);
                        $batchArray = array();

                        foreach ($imageResult as $key => $data) {
                            if(trim($data['file_name']) != '') {
                                $batchArray[] = array(
                                    'member_id' => $lastMemberId,
                                    'filename'  => $imageResult[$key]['file_name'],
                                );
                            }
                        }
                        $this->db->insert_batch('tbl_member_biodata_file', $batchArray);
                    }
                }
                if (isset($memberId) && !empty($memberId)) {
                    $data = array('member_id' => $memberId);
                    $memberData     = $this->Mdl_member->getMembers($data);
                    $educationData  = $this->Mdl_member->getEducationName($memberData);
                    $education      = array();
                    foreach ($educationData as $keyEducation => $valueEducation){
                        array_push($education,$valueEducation);
                    }
                    unset($memberData['education_id']);
                    $memberData['education'] = $education;
                    $this->db->trans_commit();
                    $this->response(array(
                        'status'        => TRUE,
                        'responseCode'  => self::HTTP_OK,
                        'message'       => $successMessage,
                        'data'          => $memberData,
                    ), REST_Controller::HTTP_OK);
                } else {
                    $this->db->trans_commit();
                    $this->response(array(
                        'status'        => TRUE,
                        'responseCode'  => self::HTTP_OK,
                        'message'       => $successMessage,
                    ), REST_Controller::HTTP_OK);
                }
            } else {
                $this->db->trans_rollback();
                $this->response(array(
                    'status'        => FALSE,
                    'responseCode'  => self::HTTP_NOT_FOUND,
                    'message'       => $errorMessage,
                ), REST_Controller::HTTP_NOT_FOUND);
            }

        }
    }

    /**@api {post} Api/SamajWebService/getMemberExtraData MemberExtra-Data
     * @apiName MemberExtra Data
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Member
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getMemberExtraData
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Marital Status and Blood Group Listing successfully",
     *           "limit": 10,
     *           "data":
     *                 {
     *                        "marital_status":
     *                        [
     *                        {
     *                        "marital_status_id": "1",
     *                        "member_id": "1",
     *                        "marital_status": "married"
     *                        },
     *                        {
     *                        "marital_status_id": "2",
     *                        "member_id": "0",
     *                        "marital_status": "unmarried"
     *                        }
     *                        ],
     *                        "blood_group": [
     *                        {
     *                        "blood_group_id": "1",
     *                        "member_id": "1",
     *                        "blood_group": "A+"
     *                        },
     *                        {
     *                        "blood_group_id": "2",
     *                        "member_id": "0",
     *                        "blood_group": "O+"
     *                        },
     *                        {
     *                        "blood_group_id": "3",
     *                        "member_id": "0",
     *                        "blood_group": "B+"
     *                        },
     *                        {
     *                        "blood_group_id": "4",
     *                        "member_id": "0",
     *                        "blood_group": "AB+"
     *                        },
     *                        {
     *                        "blood_group_id": "5",
     *                        "member_id": "0",
     *                        "blood_group": "A+"
     *                        },
     *                        {
     *                        "blood_group_id": "6",
     *                        "member_id": "0",
     *                        "blood_group": "O-"
     *                        },
     *                        {
     *                        "blood_group_id": "7",
     *                        "member_id": "0",
     *                        "blood_group": "B-"
     *                        },
     *                        {
     *                        "blood_group_id": "8",
     *                        "member_id": "0",
     *                        "blood_group": "AB-"
     *                        }
     *                    ]
     *                }
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getMemberExtraData
     */
    public function getMemberExtraData_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be enter integer value');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $start      = $this->input->post('start');
        $search      = $this->input->post('search');
        $languageId  = $this->input->post('language_id');

        $start = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $memberExtraData = array(
            'start'         => $start,
            'search'        => $search,
            'language_id'   => $languageId,

        );
        $memberData['marital_status']   = $this->Mdl_member->getMemberExtraData($memberExtraData, 0);

        $memberData['blood_group']      = $this->Mdl_member->getMemberExtraData($memberExtraData, 1);

//        $memberData['surname']          = $this->Mdl_member->getMemberExtraData($memberExtraData, 2);

//        $memberData['education']        = $this->Mdl_member->getMemberExtraData($memberExtraData, 3);

        $memberData['gender_name']      = $this->Mdl_member->getMemberExtraData($memberExtraData, 4);

//		$memberData['state']            = $this->Mdl_member->getMemberExtraData($memberExtraData, 5);

//		$memberData['city']             = $this->Mdl_member->getMemberExtraData($memberExtraData, 6);

		$memberData['relationship']      = $this->Mdl_member->getMemberExtraData($memberExtraData, 7);
        if (!empty($memberData)) {
            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Marital Status and Blood Group Listing successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $memberData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => $memberData,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/addEventRsvp Add Event Rsvp
     * @apiName Add Event Rsvp
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Event
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse event_id
     * @apiUse member_id
     * @apiUse is_interested
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} addEventRsvp
     *            {
     *            "status": true,
     *                "responseCode": 200,
     *                "message": "Event Rsvp inserted successfully"
     *            }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/addEventRsvp
     */
    public function addEventRsvp_post()
    {
        $this->form_validation->set_rules('event_id', 'Event Id', 'required|integer');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required');
        $this->form_validation->set_rules('member_detail_id', 'Member Detail Id', 'required');
        $this->form_validation->set_rules('is_interested', 'Interest', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s is required');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        } else {


            $memberId       = $this->input->post('member_detail_id');
            $eventId        = $this->input->post('event_id');
            $isInterested   = $this->input->post('is_interested');
            $successMessage = 'Event Rsvp inserted successfully';
            $errorMessage   = 'Event Rsvp insert unsuccessful';

            if (isset($eventId) && !empty($eventId) && isset($memberId) && !empty($memberId)) {
                $checkExistArray = array(
                    'event_id' => $eventId,
                    'member_id' => $memberId,
                    'flag' => 1
                );
                $checkExistEventId = $this->Mdl_samaj_webservice->checkDuplicate($checkExistArray,'tbl_event_rsvp');
                if($checkExistEventId){
                    $this->response(array(
                        "status" => FALSE,
                        'responseCode' => self::HTTP_NOT_FOUND,
                        'message' => "Duplicate Entry of Event Id",
                        'limit' => DATA_LIMIT,
                        "data" => null
                    ), REST_Controller::HTTP_NOT_FOUND);
                }
            }

            $eventRsvpArray = array(
                'member_id'     => $memberId,
                'event_id'      => $eventId,
                'is_interested' => $isInterested,
                'created_at'    => date('Y-m-d h:i:s'),
            );
            $eventRsvpResult = $this->Mdl_samaj_webservice->insertUpdateRecordApi($eventRsvpArray, 'event_rsvp_id', 'tbl_event_rsvp');
            if ($eventRsvpResult) {
                $this->response(array(
                    'status'        => TRUE,
                    'responseCode'  => self::HTTP_OK,
                    'message'       => $successMessage,
                ), REST_Controller::HTTP_OK);
            } else {
                $this->response(array(
                    'status'        => FALSE,
                    'responseCode'  => self::HTTP_NOT_FOUND,
                    'message'       => $errorMessage,
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /**@api {post} Api/SamajWebService/getSurnameList Surname-List
     * @apiName Surname List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Member
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     * @apiUse language_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getSurnameList
     * {
     *
     *           "status": true,
     *           "responseCode": 200,
     *           "message": "Surname Listing successfully",
     *           "limit": 10,
     *           "data":
     *                  [{
     *                      "surname_id": "1",
     *                        "samaj_id": "32",
     *                        "surname": "Digitattva",
     *                        "created_by": "1",
     *                        "created_at": "2019-03-08 12:56:00",
     *                        "updated_by": "1",
     *                        "updated_at": "2019-03-08 12:56:00",
     *                        "surname_description_id": "1",
     *                        "language_id": "1"
     *                  }
     *               ]
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getSurnameList
     */
    public function getSurnameList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s should be enter integer value');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status" => FALSE,
                "message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data" => null,
                'responseCode' => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $start      = $this->input->post('start');
        $search     = $this->input->post('search');
        $samajId    = $this->input->post('samaj_id');
        $languageId = $this->input->post('language_id');
        $start      = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $memberSurnameData = array(
            'start'         => $start,
            'search'        => $search,
            'language_id'   => $languageId,
            'samaj_id'      => $samajId
        );
        $memberSurnameResult = $this->Mdl_member->getSurname($memberSurnameData);

        if (!empty($memberSurnameResult)) {

            $this->response(array(
                'status'        => TRUE,
                'responseCode'  => self::HTTP_OK,
                "message"       => 'Surname Listing successfully',
                'limit'         => DATA_LIMIT,
                'data'          => $memberSurnameResult,
            ), REST_Controller::HTTP_OK);
        } else {

            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "No Data Found",
                'limit'         => DATA_LIMIT,
                "data"          => $memberSurnameResult,
            ), REST_Controller::HTTP_NOT_FOUND);
        }


    }

    /**@api {post} Api/SamajWebService/changeStatus Change-Status
     * @apiName Change Status
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Member
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse is_active
     * @apiUse member_detail_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} changeStatus
     * {
     *
     *                "status": true,
     *                "responseCode": 200,
     *                "message": "Member Status Changed successfully",
     *                "limit": 10,
     *                "data": true
     *
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/changeStatus
     */
    public function changeStatus_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'required');
        $this->form_validation->set_message('required', '%s is required');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $memberId   = $this->input->post('member_detail_id', TRUE);
        $status     = $this->input->post('is_active', TRUE);

        $memberStatusArray = array(
            'is_active' => isset($status) && $status != 0 ? 1 : 0,
            'member_id' => $memberId
        );

        $memberStatusResult = $this->Mdl_member->changeStatus($memberStatusArray);
        if (isset($memberStatusResult)) {
            $this->response(array(
                'status' => TRUE,
                'responseCode' => self::HTTP_OK,
                "message" => 'Member Status Changed successfully',
                'limit' => DATA_LIMIT,
                'data' => $memberStatusResult,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => FALSE,
                'responseCode' => self::HTTP_NOT_FOUND,
                'message' => "Member Status Change Failed",
                'limit' => DATA_LIMIT,
                "data" => $memberStatusResult,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function changeNumber_post()
    {
        $this->form_validation->set_rules('member_id', 'Member Id', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        $this->form_validation->set_rules('old_mobile', 'Old Mobile Number', 'required');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $mobileNumber   = $this->input->post('mobile');
        $memberId       = $this->input->post('member_id');
        $checkStatus    = $this->Mdl_member->checkExistingNumber($mobileNumber);
        if ($checkStatus == 0) {
            $this->response(array(
                "status"        => FALSE,
                'responseCode'  => self::HTTP_NOT_FOUND,
                'message'       => "Mobile Number already exist.",
                'limit'         => DATA_LIMIT,
                "data"          => null
            ), REST_Controller::HTTP_NOT_FOUND);
        }
        $loginData = $this->Mdl_member->checkValidUser($memberId);
        if (isset($loginData) && count($loginData) != 0) {
            if ($loginData['is_active'] == 1) {
                $generateOtp            = GenRandomNumber(6);
                $endTime                = strtotime("+15 minutes", strtotime(date('Y-m-d H:i:s')));
                $generateOtpValidity    = date('Y-m-d H:i:s', $endTime);
                $OtpData = array(
                    'otp'           => $generateOtp,
                    'otp_validity'  => $generateOtpValidity,
                    'member_id'     => $loginData['member_id']
                );
                $otpResultData = $this->Mdl_member->updateOtp($OtpData, $loginData['member_id']);
                $updateRecordData = array(
                    "mobile_number"     => $mobileNumber,
                    "member_id"         => $memberId,
                    "old_mobile"        => $this->input->post("old_mobile")
                );
                $tempNumberUpdateStatus = $this->Mdl_member->addTemporaryNumber($updateRecordData);





                #wolfie baki




                if ($otpResultData == 1) {
                    sendSms($mobileNumber, $OtpData['otp']);
                    unset($OtpData['otp']);
                    unset($OtpData['otp_validity']);
                    $this->response(array(
                        'status'        => TRUE,
                        'responseCode'  => self::HTTP_OK,
                        "message"       => 'OTP Sent Successfully',
                        'limit'         => DATA_LIMIT,
                        'data'          => $OtpData,
                    ), REST_Controller::HTTP_OK);
                } else {
                    $this->response(array(
                        "status"        => FALSE,
                        'responseCode'  => self::HTTP_NOT_FOUND,
                        'message'       => "OTP Not Sent",
                        'limit'         => DATA_LIMIT,
                        "data"          => null,
                    ), REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response(array(
                    "status" => FALSE,
                    'responseCode' => self::HTTP_NOT_FOUND,
                    'message' => "Your account is inactive",
                    'limit' => DATA_LIMIT,
                    "data" => $loginData,
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        }


    }

    /**@api {post} Api/SamajWebService/getPachkhanList Pachkhan-List
     * @apiName Pachkhan List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Pachkhan
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     * @apiUse pachkhan_id
     * @apiUse language_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getPachkhanList
     * {
     *      "status": true,
     *       "responseCode": 200,
     *       "message": "Pachkhan Listing successfully",
     *       "limit": 10,
     *       "data": [{
     *                   "pachkhan_id": "2",
     *                   "pachkhan_name": "Pachkhan ",
     *                   "short_description": " Pachkhan ",
     *                   "long_description": "<p>Pachkhan </p>\n",
     *                   "pachkhan_audio_path": "http://localhost:7777/samaj/uploads/pachkhan_audio/5929a474ea8eb709a5feafffc52a2599.mp3"
     *       }]
     * }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getPachkhanList
     */
    public function getPachkhanList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'MemberId', 'required');
        $this->form_validation->set_rules('pachkhan_id', 'Pachkhan Id', 'integer');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s Must be Enter Only Integer Value');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status"        => FALSE,
                "message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data"          => null,
                'responseCode'  => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $start          = $this->input->post('start');
        $start          = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $search         = $this->input->post('search');
        $languageId     = $this->input->post('language_id');
        $pachkhanId     = $this->input->post('pachkhan_id');

        $pachkhanArray = array(
            'start'         => $start,
            'search'        => $search,
            'language_id'   => $languageId,
            'pachkhan_id'   => $pachkhanId
        );
        $pachkhanData = $this->Mdl_pachkhan->getPachkhanDataListing($pachkhanArray);
        if (!empty($pachkhanData)) {
            $this->response(array(
                'status'       => TRUE,
                'responseCode' => self::HTTP_OK,
                "message"      => 'Pachkhan Listing successfully',
                'limit'        => DATA_LIMIT,
                'data'         => $pachkhanData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"       => FALSE,
                'responseCode' => self::HTTP_NOT_FOUND,
                'message'      => "No Data Found",
                'limit'        => DATA_LIMIT,
                "data"         => null,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**@api {post} Api/SamajWebService/getEducationList Education-List
     * @apiName Education List
     * @apiHeader {string} API-KEY=123456 API-KEY For User
     * @apiGroup Education
     * @apiVersion 0.1.0
     *
     * @apiUse auth_token
     * @apiUse member_id
     * @apiUse start
     * @apiUse search
     * @apiUse language_id
     *
     * @apiUse SuccessDetails
     *
     * @apiSuccessExample {json} getEducationList
     * {
     *       "status": true,
     *       "responseCode": 200,
     *       "message": "Education Listing successfully",
     *       "limit": 10,
     *       "data": {
     *           "education": [
     *               {
     *                   "education_id": "1",
     *                   "education_name": "MscIt"
     *               },
     *               {
     *                   "education_id": "2",
     *                   "education_name": "Mca"
     *               },
     *               {
     *                   "education_id": "3",
     *                   "education_name": "Pharmacy"
     *               }
     *               ]
     *           }
     *   }
     *
     * @apiUse RequiredParameter
     * @apiUse InvalidAuthToken
     * @apiUse AuthTokenExpire
     * @apiUse InvalidApiKey
     * @apiUse NoDataFound
     * @apiUse UnknownMethod
     *
     * @apiSampleRequest Api/SamajWebService/getEducationList
     */
    public function getEducationList_post()
    {
        $this->form_validation->set_rules('auth_token', 'Token', 'required');
        $this->form_validation->set_rules('member_id', 'MemberId', 'required');
        $this->form_validation->set_rules('start', 'Start', 'required|integer');
        $this->form_validation->set_rules('language_id', 'Language Id', 'required|integer');
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s Enter Digit Only');
        if ($this->form_validation->run() === FALSE) {
            $strip_message = strip_tags(validation_errors(""));
            $this->response(array(
                "status" => FALSE,
                "message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
                "data" => null,
                'responseCode' => self::HTTP_BAD_REQUEST,
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
        $start         = $this->input->post('start');
        $start         = ($start <= 0) ? 1 : ($start == 1) ? 0 : $start - 1;
        $search        = $this->input->post('search');
        $languageId    = $this->input->post('language_id');

        $EducationArray = array(
            'start'          => $start,
            'search'         => $search,
            'language_id'    => $languageId
        );
        $educationData['education'] = $this->Mdl_samaj_webservice->getEducationListing($EducationArray);
        if (!empty($educationData)) {
            $this->response(array(
                'status'          => TRUE,
                'responseCode'    => self::HTTP_OK,
                "message"         => 'Education Listing successfully',
                'limit'           => DATA_LIMIT,
                'data'            => $educationData,
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status"       => FALSE,
                'responseCode' => self::HTTP_NOT_FOUND,
                'message'      => "No Data Found",
                'limit'        => DATA_LIMIT,
                "data"         => null,
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
