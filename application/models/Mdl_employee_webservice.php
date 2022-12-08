<?php


class Mdl_employee_webservice extends DT_CI_Model
{
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
			$this->db->where($columnName, $dataArray[$columnName]);
			$this->db->update($tableName, $dataArray);
			$result = $response['lastInsertedId'] = $dataArray[$columnName];
		} else {
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

	public function getImage($employeeId)
	{
		$this->db->select('filename');
		$this->db->from('tbl_employee_file');
		$this->db->where('emp_id', $employeeId);
		$employeeImageResult = $this->db->get()->row_array();
		return $employeeImageResult;
	}

	
}

