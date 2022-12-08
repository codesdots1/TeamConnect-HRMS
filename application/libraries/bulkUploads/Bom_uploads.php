<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bom_uploads
{
    function __construct()
    {
        $this->CI =& get_instance();
    }

    public function uploadBom($userId, $return = false, $file = array()){

        $startTime = date('Y-m-d H:i:s');
        $dirAbsPath = BOM_BU_ABS_PATH;
        $dirRelPath = BOM_BU_RELATIVE_PATH;
        $masterType = "tbl_bom_material";
//        $userId = $this->CI->ion_auth->get_user_id();
        $this->CI->load->model('mdl_fileuploads');
        $this->CI->load->model('Mdl_bom');
        $productTypes = array();
        $customErrors = array(
            "101" => "Main SKU Code required",
            "102" => "Main SKU Code Inactive",
            "103" => "Main SKU Code Invalid",
            "201" => "Material Code required",
            "202" => "Material Code Inactive",
            "203" => "Material Code Invalid",
            "204" => "Material Code Duplicate",
            "301" => "Material Type required",
            "302" => "Material Type should be one of the master",
            "401" => "Material level required",
            "402" => "Material level should be digit",
            "501" => "Batch quantity required",
            "502" => "Batch quantity should be digit",
            "601" => "Block quantity required",
            "602" => "Block quantity should be digit",
        );
        $response = $this->CI->dt_ci_common->uploadMasterFile($userId,$_FILES, $dirAbsPath, $file);
        if($response['result'] == 'ok'){
            $dataExcel = $response['unset_data']['data_excel'];
            $uploadData = $response['unset_data']['upload_data'];
            $filePrefix = $response['unset_data']['file_prefix'];
            unset($response['unset_data']);
            $successEntries = 0;
            $failedEntries = 0;
            $errorRowArr = array();
            $dataSaveBatch = array();
            $dataResponse = array();
            $dataFresh = array();

            foreach ($customErrors as $cErrKey => $cErrMessage){
                $errorRowArr[$cErrKey] = array();
            }
            foreach($dataExcel['values'] as $rowNumber => $row) {

                $dataSave = array();
                $rowErrorArr = array();

                $columnMap = array(
                    'A'	 => 'main_sku_id',
                    'B'  => 'material_code',
                    'C'  => 'material_type_id',
                    'D'  => 'bom_level',
                    'E'  => 'batch_quantity',
                    'F'  => 'bom_quantity',
                );
                //load dataSave with data in db columns
                foreach($columnMap as $excelCol => $colMKey){
                    $dataSave[$colMKey] = isset($row[$excelCol])?$row[$excelCol]:"";
                }
                $dataRowResponse = $dataSave;
                $dataSave = array_map('trim', $dataSave);
                if(empty($rowErrorArr)){
                    // Main SKU Code
                    if($dataSave['main_sku_id'] == "" || $dataSave['main_sku_id'] == NULL ){
                        array_push($errorRowArr[101], $rowNumber);
                        array_push($rowErrorArr, 101);
                    } else {
                        $bomId = 0;
                        $mainSkuId = $this->CI->dt_ci_common->validateSku($dataSave['main_sku_id'], array());
                        if(isset($mainSkuId) && !empty($mainSkuId)){
                            if($mainSkuId['sku_id'] != 0 && $mainSkuId['status'] == 1) {
                                $bomId = $this->CI->Mdl_bom->checkBOMExist($mainSkuId['sku_id']);
                                if($bomId == ''){
                                    $bomData = array(
                                        "sku_id" => $mainSkuId['sku_id'],
                                        "status" => 1,
                                    );
                                    created_info_merge($bomData, $userId);
                                    $bomId   = $this->CI->Mdl_bom->insertBom($bomData);
                                }
                                $dataSave['bom_id'] = isset($bomId) ? $bomId  : "";
                                $bomId = $dataSave['bom_id'];
                            }
                            else{
                                array_push($errorRowArr[102], $rowNumber);
                                array_push($rowErrorArr, 102);
                            }
                        }
                        else {
                            array_push($errorRowArr[103], $rowNumber);
                            array_push($rowErrorArr, 103);
                        }
                    }
                    // material_code
                    if($dataSave['material_code'] == "" || $dataSave['material_code'] == NULL ){
                        array_push($errorRowArr[201], $rowNumber);
                        array_push($rowErrorArr, 201);
                    } else {
                        $skuId = $this->CI->dt_ci_common->validateSku($dataSave['material_code'], array());
                        if(isset($skuId) && !empty($skuId)){
                            if($skuId['sku_id'] != 0 && $skuId['status'] == 1){
                                $dataSave['sku_id'] = $skuId['sku_id'];
                            }
                            else {
                                array_push($errorRowArr[202], $rowNumber);
                                array_push($rowErrorArr, 202);
                            }
                        }
                        else{
                            array_push($errorRowArr[203], $rowNumber);
                            array_push($rowErrorArr, 203);
                        }
                    }
//                     Material Type
                    if($dataSave['material_type_id'] == "" || $dataSave['material_type_id'] == NULL ){
                        array_push($errorRowArr[301], $rowNumber);
                        array_push($rowErrorArr, 301);
                    }  else {
                        $materialTypeId = $this->CI->dt_ci_common->validateMaterialType($dataSave['material_type_id'], array());

                        if ($materialTypeId != 0) {
                            $dataSave['material_type_id'] = $materialTypeId;
                            //  unset($dataSave['product_type_id']);
                        } else {
                            array_push($errorRowArr[302], $rowNumber);
                            array_push($rowErrorArr, 302);
                        }
                    }

                    // Bom Level
                    if($dataSave['bom_level'] == "" || $dataSave['bom_level'] == NULL ){
                        array_push($errorRowArr[401], $rowNumber);
                        array_push($rowErrorArr, 401);
                    }
                    elseif(!is_numeric($dataSave['bom_level'])) {
                        array_push($errorRowArr[402], $rowNumber);
                        array_push($rowErrorArr, 402);
                    }

                    // Batch Quantity
                    if($dataSave['batch_quantity'] == "" || $dataSave['batch_quantity'] == NULL ){
                        array_push($errorRowArr[501], $rowNumber);
                        array_push($rowErrorArr, 501);
                    }
                    elseif(!is_numeric($dataSave['batch_quantity'])) {
                        array_push($errorRowArr[502], $rowNumber);
                        array_push($rowErrorArr, 502);
                    }

                    // Bom Quantity
                    if($dataSave['bom_quantity'] == "" || $dataSave['bom_quantity'] == NULL ){
                        array_push($errorRowArr[601], $rowNumber);
                        array_push($rowErrorArr, 601);
                    }
                    elseif(!is_numeric($dataSave['bom_quantity'])) {
                        array_push($errorRowArr[602], $rowNumber);
                        array_push($rowErrorArr, 602);
                    }
                    //combination check
//                    printArray($dataSave);
                    $dupCheck = $this->CI->Mdl_bom->checkDuplicate($dataSave);
                    if($dupCheck){
                        array_push($errorRowArr[204], $rowNumber);
                        array_push($rowErrorArr, 204);
                    }
                }
                if(empty($rowErrorArr)){
                    $successEntries++;
                    $dataSave['bom_id'] = $bomId;
                    unset($dataSave['main_sku_id']);
                    unset($dataSave['material_code']);
                    $this->CI->Mdl_bom->saveCsv($dataSave, $userId);
                    $dataSaveBatch[] = $dataSave;
                    $dataFresh[] = $dataRowResponse;
                } else {
                    $errorPrintExcel = array();
                    foreach ($rowErrorArr as $eachError){
                        $errorPrintExcel[] = $customErrors[$eachError];
                    }
                    $errorPrintExcel = implode("\n\n", $errorPrintExcel);
                    $dataRowResponse['remarks'] = $errorPrintExcel;
                    $dataResponse[] = $dataRowResponse;
                    $failedEntries++;
                }

            }

            $reponsePath = $dirAbsPath.$filePrefix.'_RESPONSE.csv';
            $freshUploadPath = $dirAbsPath.$filePrefix.'_FRESH.csv';
            $reponseRelativePath = $dirRelPath.$filePrefix.'_RESPONSE.csv';
            $freshUploadRelativePath = $dirRelPath.$filePrefix.'_FRESH.csv';

            $errorMessageDb = '';
            if(!$this->CI->dt_ci_common->checkError($errorRowArr)){
                $response['result'] = "data_error";
                $errorMessage = "<a class='alert-link' href='javascript:void(0)'>There was an error while uploading file!</a><a href='".base_url($reponseRelativePath)."' class='pull-xs-right btn btn-danger' style='color: white;' target='_blank'>Download Response File</a><br></b>";

                foreach($errorRowArr as $key => $value){
                    if(count($value) > 0){
                        if(SHOW_UPLOAD_ERROR){
                            $errorMessage.= '<a href="javascript:void(0)">'.$customErrors[$key].'</a> in <a class="alert-link" href="javascript:void(0)">ROW# '.implode(", ",$value)."</a><br><br>";
                        }
//                        $errorMessageDb.="<br>".$customErrors[$key].' in ROW# '.implode(", ",$value);
                    }
                }

                $response['errorMessage'] = $errorMessage;

            }else{
                $response['result'] = $errorMessageDb = "ok";
            }


            $this->CI->dt_ci_common->printResponseCsv($reponsePath, $dataResponse);
            $this->CI->dt_ci_common->printResponseCsv($freshUploadPath, $dataFresh);

            $fileDbResponse = array(
                "text" => $errorMessageDb,
                "success" => $successEntries,
                "failed" => $failedEntries,
                //"duplicates" => count($errorRowArr[102]),
//                "custom_errors" => $customErrors,
//                "error_array" => $errorRowArr,
            );
            $fileUploadData = array(
                "file_type" => $masterType,
                "filepath" => $dirRelPath.$uploadData['file_name'],
                "user_id" => $userId,
                "start_time" => $startTime,
                "end_time" => date('Y-m-d H:i:s'),
                "response_path" => $reponseRelativePath,
                "response" => json_encode($fileDbResponse),
                "auto_upload" => !is_array($file) && $file != "" ? 1 : 0,
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