<?php
class Payroll_uploads
{

	function __construct()
	{
		$this->CI =& get_instance();
	}

	public function uploadEmployeeSalarySlip($userId, $return = false, $file = array())
	{
		$startTime 	= date('Y-m-d H:i:s');
		$dirAbsPath = EMP_SALARY_PATH;
		$dirRelPath = EMP_SALARY_RELATIVE_PATH;
//		$masterType = "tbl_employee";

		$this->CI->load->model('mdl_fileuploads');
		$this->CI->load->model('Mdl_payroll');

		$customErrors = array(
			"101" => "Employee Id is Required",
			"201" => "Entry Time is Required",
			"301" => "Exit Time is Required",
			"401" => "Attendance Date is Required",
		);

		$response = $this->CI->dt_ci_common->uploadMasterFile($userId,$_FILES, $dirAbsPath, $file);

		if($response['result'] == 'ok'){
			$dataExcel 		= $response['unset_data']['data_excel'];
			$uploadData 	= $response['unset_data']['upload_data'];
			$filePrefix 	= $response['unset_data']['file_prefix'];
			unset($response['unset_data']);
			$successEntries 	= 0;
			$failedEntries 		= 0;
			$errorRowArr 		= array();
			$dataSaveBatch 		= array();
			$dataResponse 		= array();
			$dataFresh 			= array();

			foreach ($customErrors as $cErrKey => $cErrMessage){
				$errorRowArr[$cErrKey] = array();
			}

			foreach($dataExcel['values'] as $rowNumber => $row) {
				$dataSave 	 = array();
				$rowErrorArr = array();

				$columnMap = array(
					'A'	 => 'emp_id',
					'B'  => 'login_time',
					'C'  => 'logout_time',
					'D'  => 'attendance_date',
				);

				//load dataSave with data in db columns
				foreach($columnMap as $excelCol => $colMKey){
					$dataSave[$colMKey] = isset($row[$excelCol])?$row[$excelCol]:"";
				}

				$dataRowResponse = $dataSave;
				$dataSave 		 = array_map('trim', $dataSave);

				if(empty($rowErrorArr)){
					if($dataSave['emp_id'] == "" || $dataSave['emp_id'] == NULL){
						array_push($errorRowArr[101], $rowNumber);
						array_push($rowErrorArr, 101);
					}
					if($dataSave['login_time'] == "" || $dataSave['login_time'] == NULL){
						array_push($errorRowArr[201], $rowNumber);
						array_push($rowErrorArr, 201);
					}
					if($dataSave['logout_time'] == "" || $dataSave['logout_time'] == NULL){
						array_push($errorRowArr[301], $rowNumber);
						array_push($rowErrorArr, 301);
					}
					if($dataSave['attendance_date'] == "" || $dataSave['attendance_date'] == NULL){
						array_push($errorRowArr[401], $rowNumber);
						array_push($rowErrorArr, 401);
					}
				}
				if(empty($rowErrorArr)){
					$successEntries ++ ;
					$this->CI->Mdl_payroll->GenerateSalarySlip($dataSave, $userId);
					$dataSaveBatch[] = $dataSave;
					$dataFresh[] 	 = $dataRowResponse;
				} else {
					$errorPrintExcel = array();
					foreach ($rowErrorArr as $eachError){
						$errorPrintExcel[] = $customErrors[$eachError];
					}
					$errorPrintExcel = implode("\n\n", $errorPrintExcel);
					$dataRowResponse['remarks'] = $errorPrintExcel;
					$dataResponse[] 			= $dataRowResponse;
					$failedEntries++;
				}

			}

			$responsePath 				= $dirAbsPath.$filePrefix.'_RESPONSE.csv';
			$freshUploadPath 			= $dirAbsPath.$filePrefix.'_FRESH.csv';
			$responseRelativePath 	    = $dirRelPath.$filePrefix.'_RESPONSE.csv';
			$freshUploadRelativePath 	= $dirRelPath.$filePrefix.'_FRESH.csv';

			$errorMessageDb = '';
			if(!$this->CI->dt_ci_common->checkError($errorRowArr)){
				$response['result'] = "data_error";
				$errorMessage 		= "<a class='alert-link' href='javascript:void(0)'>There was an error while uploading file!</a><a href='".base_url($responseRelativePath)."' class='pull-xs-right btn btn-danger' style='color: white;' target='_blank'>Download Response File</a><br></b>";

				foreach($errorRowArr as $key => $value){
					if(count($value) > 0){
						if(SHOW_UPLOAD_ERROR){
							$errorMessage.= '<a href="javascript:void(0)">'. $customErrors[$key].'</a> in <a class="alert-link" href="javascript:void(0)"> ROW# '.implode(", ",$value)."</a><br><br>";
						}
					}
				}
				$response['errorMessage'] = $errorMessage;
			} else{
				$response['result'] = $errorMessageDb = "ok";
			}

			$this->CI->dt_ci_common->printResponseCsv($responsePath, $dataResponse);
			$this->CI->dt_ci_common->printResponseCsv($freshUploadPath, $dataFresh);

			$fileDbResponse = array(
				"text" 		=> $errorMessageDb,
				"success" 	=> $successEntries,
				"failed" 	=> $failedEntries,
			);
			$fileUploadData = array(
				"file_type" 	=> '',
				"filepath" 		=> $dirRelPath.$uploadData['file_name'],
				"user_id" 		=> $userId,
				"start_time" 	=> $startTime,
				"end_time" 		=> date('Y-m-d H:i:s'),
				"response_path" => $responseRelativePath,
				"response" 		=> json_encode($fileDbResponse),
				"auto_upload" 	=> !is_array($file) && $file != "" ? 1 : 0,
			);

			$this->CI->mdl_fileuploads->saveData($fileUploadData);
		}

		if(!$return){
			return $response;
		}else{
			if(isset($fileUploadData)){
				$fileUploadData['fresh_path'] = $freshUploadRelativePath;
				$response['file_upload_data'] = $fileUploadData;
			}
			return $response;
		}
	}

}
