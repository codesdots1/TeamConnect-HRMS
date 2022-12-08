<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DT_CI_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
	

    }

	public function sql_select($table, $select = null, $where = null, $options = null)
	{
		if (!empty($select)) {
			$this->db->select($select, FALSE);
		}

		$this->db->from($table);

		/* Check wheather where conditions is required or not. */
		if (!empty($where)) {
			if (is_array($where)) {
				$check_where = array(
					'where',
					'or_where',
					'where_in',
					'or_where_in',
					'where_not_in',
					'or_where_not_in',
					'like', 'or_like',
					'not_like',
					'or_not_like',
					'having'
				);

				foreach ($where as $key => $value) {
					if (in_array($key, $check_where)) {
						foreach ($value as $k => $v) {
							if (in_array($key, array('like', 'or_like', 'not_like', 'or_not_like'))) {
								$check = 'both';
								if ($v[0] == '%') {
									$check = 'before';
									$v = ltrim($v, '%');
								} else if ($v[strlen($v) - 1] == '%') {
									$check = 'after';
									$v = rtrim($v, '%');
								}
								$this->db->$key($k, $v, $check, FALSE);
							} else {
								if ($key == 'having') {
									$this->db->$key($value[0]);
								} else {
									$this->db->$key($k, $v);
								}
							}
						}
					}
				}
			} else {
				$this->db->where($where, '', FALSE);
			}
		}
	}


    public function insertUpdateRecord($dataArray, $columnName, $tableName, $returnWithId = 0, $returnCol = "")
    {   

        if ($dataArray[$columnName] != '') {
            updated_info_merge($dataArray, $this->data['userId']);
            $this->db->where($columnName, $dataArray[$columnName]);
            $this->db->update($tableName, $dataArray);
            $response['lastInsertedId'] = $dataArray[$columnName];

            $dataArray = array_filter($dataArray);
//            array_walk($dataArray, create_function('&$i,$k','$i=" $k=\"$i\"";'));
            $logDetails = implode($dataArray,"");
            $logId =  $dataArray[$columnName];
            $logColumn = (isset($columnName)) ? ucwords(str_replace("_","",$columnName)) : "";
            $logModule = (isset($tableName) && $tableName != '') ? ucwords(str_replace("_"," ",str_replace("tbl_","",$tableName))) : "";

            logActivity("".$logModule." Updated [$logColumn: $logId, $logDetails ]",$this->data['userId'],$logModule);
        } else {
            created_info_merge($dataArray, $this->data['userId']);
            $this->db->insert($tableName, $dataArray);
            if ($returnWithId == 1 && $returnCol != '') {
                $response['lastInsertedId'] = $this->db->insert_id();
                $response['DataValue'] = $dataArray[$returnCol];
            } else if ($returnWithId == 1) {
                $response['lastInsertedId'] = $this->db->insert_id();
            }

            $dataArray = array_filter($dataArray);
//            array_walk($dataArray, create_function('&$i,$k','$i=" $k=\"$i\"";'));
            $logDetails = implode($dataArray,"");
            $logId =  $this->db->insert_id();
            $logColumn = (isset($columnName)) ? ucwords(str_replace("_","",$columnName)) : "";
            $logModule = (isset($tableName) && $tableName != '') ? ucwords(str_replace("_"," ",str_replace("tbl_","",$tableName))) : "";

            logActivity("".$logModule." Add [$logColumn: $logId, $logDetails ]",$this->data['userId'],$logModule);

//            if($this->db->insert( $tableName, $dataArray)){
//            if($returnWithId == 1 && $returnCol != '') {
//                $response['lastInsertedId'] = $this->db->insert_id();
//                $response['DataValue'] = $dataArray[$returnCol];
//            }else if($returnWithId == 1){
//                $response['lastInsertedId'] = $this->db->insert_id();
//            }
//            }

//            else{
//                $response['success'] = 'Error';
//                //$response['msg']     = $this->db->error()['message'];
//                $response['msg']     = 'something went wrong';
//               // printArray($response);
//                return $response;
//
//            }

        }


        if ($this->db->affected_rows() > 0) {
            $response['success'] = true;
            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }
    }


    public function updateIsDefault($tableName)
    {
        $this->db->set('is_default', 0);
        $this->db->update($tableName);

    }

    public function updateIsPrimary($tableName, $type, $typeId)
    {
        $this->db->set('is_primary', 0);
        $this->db->where('type', $type);
        $this->db->where('type_id', $typeId);
        $this->db->update($tableName);

    }


    public function getNextAutoIncrementId($tableName)
    {
        $next = $this->db->query("SHOW TABLE STATUS LIKE '$tableName'");
        $next = $next->row(0);
        return $next->Auto_increment;
    }

    public function insertUpdate($dataArray, $columnName, $tableName, $returnWithId = 0)
    {
        if ($dataArray[$columnName] != '') {

            $this->db->where($columnName, $dataArray[$columnName]);
            $this->db->update($tableName, $dataArray);

            $dataArray = array_filter($dataArray);
//            array_walk($dataArray, create_function('&$i,$k','$i=" $k=\"$i\"";'));
            $logDetails = implode($dataArray,"");
            $logId =  $dataArray[$columnName];
            $logColumn = (isset($columnName)) ? ucwords(str_replace("_","",$columnName)) : "";
            $logModule = (isset($tableName) && $tableName != '') ? ucwords(str_replace("_"," ",str_replace("tbl_","",$tableName))) : "";

            logActivity("".$logModule." Updated [$logColumn: $logId, $logDetails ]",$this->data['userId'],$logModule);

            if ($returnWithId == 1) {
                $response['lastInsertedId'] = $dataArray[$columnName];
            }
        } else {
            $this->db->insert($tableName, $dataArray);

            $dataArray = array_filter($dataArray);
//            array_walk($dataArray, create_function('&$i,$k','$i=" $k=\"$i\"";'));
            $logDetails = implode($dataArray,"");
            $logId =  $this->db->insert_id();
            $logColumn = (isset($columnName)) ? ucwords(str_replace("_","",$columnName)) : "";
            $logModule = (isset($tableName) && $tableName != '') ? ucwords(str_replace("_"," ",str_replace("tbl_","",$tableName))) : "";

            logActivity("".$logModule." Add [$logColumn: $logId, $logDetails ]",$this->data['userId'],$logModule);

            if ($returnWithId == 1) {
                $response['lastInsertedId'] = $this->db->insert_id();
            }
        }
        if ($this->db->affected_rows()) {
            $response['success'] = true;
            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }
    }

    // update database and returns true and false
    function update_data($data, $tablename, $columnname, $columnid)
    {
        $this->db->where($columnname, $columnid);
        if ($this->db->update($tablename, $data)) {
            $response['success'] = true;
            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }
    }

    function insert_data($data, $tablename)
    {
        if ($this->db->insert($tablename, $data)) {
            $response['success'] = true;
            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }
    }


    function checkOTPUnique($tablename, $columnname1, $columnameid1_value, $columnname2, $columnameid2_value, $condition_array)
    {
        if ($columnameid2_value != "") {
            $this->db->where($columnname2, $columnameid2_value);
        }
        if (!empty($condition_array)) {
            $this->db->where($condition_array);
        }

        $this->db->where($columnname1, $columnameid1_value);
        $query = $this->db->get($tablename);
        return $query->result();
    }


    public function statusChange($columnId, $status, $columnname, $tablename)
    {

        $data['is_active'] = $status;
        $this->db->where($columnname, $columnId);
        $return = $this->db->update($tablename, $data);

        $logColumn = (isset($columnname)) ? ucwords(str_replace("_","",$columnname)) : "";
        $logModule = (isset($tablename) && $tablename != '') ? ucwords(str_replace("_"," ",str_replace("tbl_","",$tablename))) : "";
        $message = ($status == 1 ? 'Status Change From Inactive To Active' : 'Status Change From Active To Inactive');
        logActivity($logModule." ".$message." [$logColumn: $columnId]",$this->data['userId'],$logModule);

        return $return;

    }

    public function requiredStatusChange($columnId, $status, $columnname, $tablename)
    {

        $data['is_required'] = $status;
        $this->db->where($columnname, $columnId);
        $return = $this->db->update($tablename, $data);

        $logColumn = (isset($columnname)) ? ucwords(str_replace("_","",$columnname)) : "";
        $logModule = (isset($tablename) && $tablename != '') ? ucwords(str_replace("_"," ",str_replace("tbl_","",$tablename))) : "";
        $message = ($status == 1 ? 'Required Status Change From Inactive To Active' : 'Required Status Change From Active To Inactive');
        logActivity($logModule." ".$message." [$logColumn: $columnId]",$this->data['userId'],$logModule);

        return $return;

    }

    public function multipleStatusChange($columnId, $status, $columnname, $tablename)
    {

        $data['is_multiple'] = $status;
        $this->db->where($columnname, $columnId);
        $return = $this->db->update($tablename, $data);

        $logColumn = (isset($columnname)) ? ucwords(str_replace("_","",$columnname)) : "";
        $logModule = (isset($tablename) && $tablename != '') ? ucwords(str_replace("_"," ",str_replace("tbl_","",$tablename))) : "";
        $message = ($status == 1 ? 'Multiple Status Change From Inactive To Active' : 'Multiple Status Change From Active To Inactive');
        logActivity($logModule." ".$message." [$logColumn: $columnId]",$this->data['userId'],$logModule);

        return $return;

    }


    public function defaultChange($columnId, $default, $columnname, $tablename)
    {
        $data['is_default'] = $default;
        $this->db->where($columnname, $columnId);
        $return = $this->db->update($tablename, $data);

        $logColumn = (isset($columnname)) ? ucwords(str_replace("_","",$columnname)) : "";
        $logModule = (isset($tablename) && $tablename != '') ? ucwords(str_replace("_"," ",str_replace("tbl_","",$tablename))) : "";
        $message = ($default == 1 ? 'Status Change Default To Active' : 'Status Change Default To Inactive');
        logActivity($logModule." ".$message." [$logColumn: $columnId]",$this->data['userId'],$logModule);

        return $return;
    }


    //server side country Exist check
    public function NameExist()
    {	
        $columnId = $this->input->post("column_id", TRUE);
        $columnName = $this->input->post("column_name", TRUE);
        $tableName = $this->input->post("table_name", TRUE);

        if (isset($columnId) && $columnId == '') {
            $this->form_validation->set_rules($columnName, $this->lang->line($columnName), 'required|trim|is_unique[' . $tableName . '.' . $columnName . ']');
        } else {
            $this->form_validation->set_rules($columnName, $this->lang->line($columnName), 'required|trim|edit_unique[' . $tableName . '.' . $columnName . '.' . $columnId . ']');
        }


        if ($this->form_validation->run() == false) {
            echo "false";
            die();
        } else {
            echo "true";
            die();
        }
    }


    public function batchInsert($dataArray, $tableName)
    {

        $this->db->insert_batch($tableName, $dataArray);

        if ($this->db->affected_rows()) {
            $response['success'] = true;
            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }
    }

    public function batchUpdate($dataArray, $columnName, $tableName)
    {
        $this->db->update_batch($tableName, $dataArray, $columnName);

        if ($this->db->affected_rows()) {
            $response['success'] = true;
            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }
    }

    /**
     * @param $table
     * @param $moduleName
     * @param $moduleId
     * @return bool
     */
    public function checkDependency($table, $moduleName, $moduleId)
    {
        $moduleData = $this->db->select('*')
            ->where('type', $moduleName)
            ->where('type_id', $moduleId)
            ->get($table)
            ->row();
        if (isset($moduleData->type_id)) {
            return false;
        } else {
            return true;
        }
    }

    public function unlinkFile($path, $fileName)
    {
        if (!is_array($fileName) && !empty($fileName)) {
            if (file_exists($this->config->item($path) . $fileName)) {
                @unlink($this->config->item($path) . $fileName);
            }
        } else {
            foreach ($fileName as $key => $value) {
                if (file_exists($this->config->item($path) . $value)) {
                    @unlink($this->config->item($path) . $value);
                }
            }
        }
    }


}
