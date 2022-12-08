<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/14/2018
 * Time: 11:51 AM
 */
class Mdl_samaj_webservice extends DT_CI_Model{
	function insertData($data, $tablename)
	{
		if ($this->db->insert($tablename, $data)) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	// update database and returns true and false
	function updateData($data, $tablename, $columnname, $columnid)
	{
		$this->db->where($columnname, $columnid);
		if ($this->db->update($tablename, $data)) {
			return $columnid;
		} else {
			return false;
		}
	}

	// delete data
	function delete_data($tablename, $columnname, $columnid)
	{
		$this->db->where($columnname, $columnid);
		if ($this->db->delete($tablename)) {
			return true;
		} else {
			return false;
		}
	}

	public function insertUpdateRecordApi($dataArray, $columnName, $tableName, $returnWithId = 0, $returnCol = "")
	{
		if (isset($dataArray[$columnName]) && $dataArray[$columnName] != '') {
//			updated_info_merge($dataArray, $dataArray['user_id']);
//			unset($dataArray['user_id']);
			$this->db->where($columnName, $dataArray[$columnName]);
			$this->db->update($tableName, $dataArray);
			$response['lastInsertedId'] = $dataArray[$columnName];
		} else {
//			created_info_merge($dataArray, $dataArray['user_id']);
//			unset($dataArray['user_id']);
			$this->db->insert($tableName, $dataArray);
			if ($returnWithId == 1 && $returnCol != '') {
				$response['lastInsertedId'] = $this->db->insert_id();
				$response['DataValue'] = $dataArray[$returnCol];
			} else if ($returnWithId == 1) {
				$response['lastInsertedId'] = $this->db->insert_id();
			}
		}
		if ($this->db->affected_rows() > 0) {
			$response['success'] = true;
			return $response;
		} else {
			$response['success'] = false;
			return $response;
		}
	}

	public function insertUpdateData($dataArray,$field)
	{
		if($field == 'businessMobile')
		{
			$this->db->insert("tbl_business_mobile",$dataArray);
		}
		if ($field == 'businessEmail') {
			$this->db->insert("tbl_business_email",$dataArray);
		}
		if ($field == 'businessTelephone') {
			$this->db->insert("tbl_business_telephone",$dataArray);
		}
		if ($field == 'memberMobiles') {
			$this->db->insert("tbl_member_mobile",$dataArray);
		}
		if ($field == 'postCategory') {
			$this->db->insert("tbl_post_category",$dataArray);
		}
		if ($field == 'eventRsvp') {
			$this->db->insert("tbl_event_rsvp",$dataArray);
		}

	}

	public function deleteData($data = '',$table)
	{
		if(isset($data['business_id']) && $data['business_id'] != '' && $table == 'tbl_business_mobile') {
			$this->db->where_in('business_id',$data['business_id']);
		}
		if(isset($data['business_id']) && $data['business_id'] != ''  && $table == 'tbl_business_email') {
			$this->db->where_in('business_id',$data['business_id']);
		}
		if(isset($data['business_id']) && $data['business_id'] != '' && $table == 'tbl_business_telephone') {
			$this->db->where_in('business_id',$data['business_id']);
		}
		if(isset($data['business_id']) && $data['business_id'] != '' && $table == 'tbl_business_file') {
			$this->db->where_in('business_id',$data['business_id']);
		}
		if(isset($data['member_id']) && $data['member_id'] != '' && $table == 'tbl_member_mobile') {
			$this->db->where_in('member_id',$data['member_id']);
		}
		if(isset($data['member_id']) && $data['member_id'] != '' && $table == 'tbl_member_file') {
			$this->db->where_in('member_id',$data['member_id']);
		}
		$this->db->delete($table);
		if ($this->db->affected_rows()) {
			$response['success'] = true;
			return $response;
		} else {
			$response['success'] = false;
			return $response;
		}

	}

    public function deleteBusinessImage($businessId)
    {
        $tables = array('tbl_business_file');
        $this->db->where_in('business_id',$businessId);
        $this->db->delete($tables);

        $ids = is_array($businessId) ? implode(',',$businessId) : $businessId;

        if ($this->db->affected_rows()) {
            $response['success'] = true;


            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }

    }

    public function deleteMemberFile($memberId,$tables)
    {
        $this->db->where_in('member_id',$memberId);
        $this->db->delete($tables);

        $ids = is_array($memberId) ? implode(',',$memberId) : $memberId;

        if ($this->db->affected_rows()) {
            $response['success'] = true;
            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }

    }

	public function getImage($businessId)
    {
        $this->db->select('filename');
        $this->db->from('tbl_business_file');
        $this->db->where('business_id', $businessId);
        $businessImageResult = $this->db->get()->row_array();
        return $businessImageResult;
    }

	public function checkExistId($data = '')
	{
		$this->db->select('count(*) as count');
		$this->db->from($data['table_name']);
		if(isset($data['event_id']) && $data['event_id'] != '') {
			$this->db->where("event_id", $data['event_id']);
		}
		if(isset($data['member_id']) && $data['member_id'] != '') {
			$this->db->where("member_id", $data['member_id']);
		}
		if(isset($data['parent_member_id']) && $data['parent_member_id'] != '') {
			$this->db->where("parent_member_id", $data['parent_member_id']);
		}
		if(isset($data['language_id']) && $data['language_id'] != '') {
			$this->db->where("language_id", $data['language_id']);
		}

		$data = $this->db->get()->row_array();
		return $data;
	}

	public function checkDuplicate($data,$table = '')
	{
		if(!isset($data['member_id']) || !isset($data['event_id'])) {
			return false;
		}
		if($table == 'tbl_event_rsvp') {
			$whereArr = array(
				"member_id" => $data['member_id'],
				"event_id" => $data['event_id'],
			);
		}
		$this->db->where($whereArr);
		$res = $this->db->get($table)->row_array();

		if(isset($data['flag']) && !empty($data['flag']) && $data['flag'] == 1){
		    return isset($res) ?  true : false;
        } else {
            return isset($res) ?  $res['is_interested'] : false;
        }
	}

	public function getEducationListing($data = '')
    {
        $limit = DATA_LIMIT;
        $this->db->select('te.education_id,ted.education_name');
        $this->db->from('tbl_education as te');
        $this->db->join('tbl_education_description as ted','te.education_id = ted.education_id','left');
        $this->db->join('tbl_language_master as tlm','tlm.language_id = ted.language_id','left');
        if (isset($data['search']) && $data['search'] != '') {
            $this->db->like('ted.education_name', $data['search'],'both');
        }
        $this->db->where('ted.language_id',$data['language_id']);
        $this->db->group_by('te.education_id');
        if (isset($data['start'])) {
            $this->db->limit($limit, $data['start'] * $limit);
        }
        return $this->db->get()->result_array();
    }





}
