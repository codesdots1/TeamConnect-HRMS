<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Export_uploads
{
    function __construct()
    {
        $this->CI =& get_instance();
    }

    public function uploadExport($userId, $return = false, $file = array()){
        $startTime  = date('Y-m-d H:i:s');
        $dirAbsPath = EXPORT_BU_ABS_PATH;
        $dirRelPath = EXPORT_BU_RELATIVE_PATH;
        $masterType = "export_master";
        $this->CI->load->model('mdl_fileuploads');
        $this->CI->load->model('mdl_export');
        $bomSymbols = $this->CI->dt_ci_common->getSymbols('export');
        $customErrors = array(
            "101" => "To Node node code required",
            "102" => "To Node must be From existing Export Node",
            "201" => "From Node Require",
            "202" => "From Node must be from existing Plant Node",
            "301" => "Zone is required",
            "302" => "Zone must be From existing Zone",
            "401" => "Country is required",
            "402" => "Country must be From existing Country",
            "501" => "Po Number required",
            "502" => "Po Number Should be numeric",
            "503" => "Po Number already exist",
            "504" => "Po Number should be positive number",
            "505" => "Po Number should not have decimal value",
            "601" => "Date of PO required",
            "602" => "Po date should be in format(DD-MM-YYYY)",
            "701" => "Planing Remark required",
            "702" => "Production remark required",
            "801" => "Date Of Delivery required",
            "802" => "Date Of Delivery should be in format(DD-MM-YYYY)",
            "803" => "Date Of Delivery should be more then Date Of PO.",
        );
        $response = $this->CI->dt_ci_common->uploadMasterFile($userId,$_FILES, $dirAbsPath, $file);
        if($response['result'] == 'ok'){
            $dataExcel  = $response['unset_data']['data_excel'];
            $uploadData = $response['unset_data']['upload_data'];
            $filePrefix = $response['unset_data']['file_prefix'];
            unset($response['unset_data']);
            $successEntries = 0;
            $failedEntries  = 0;
            $errorRowArr    = array();
            $dataSaveBatch  = array();
            $dataResponse   = array();
            foreach ($customErrors as $cErrKey => $cErrMessage){
                $errorRowArr[$cErrKey] = array();
            }
            foreach($dataExcel['values'] as $rowNumber => $row) {
                $dataSave       = array();
                $rowErrorArr    = array();
                $toNodeId = 0;
                $zoneRegionId = 0;
                $columnMap = array(
                    'A'	=> 'to_node_id',
                    'B' => 'zone_region_id',
                    'C' => 'country_region_id',
                    'D' => 'from_node_id',
                    'E' => 'so_number',
                    'F' => 'date_of_so',
                    'G' => 'date_of_delivery',
                    'H' => 'planning_remark',
                    'I' => 'production_remark',
                );
                //load dataSave with data in db columns
                foreach($columnMap as $excelCol => $colMKey){
                    $dataSave[$colMKey] = isset($row[$excelCol])?$row[$excelCol]:"";
                }
                $dataRowResponse        = $dataSave;
                $dataSave['is_delete']  = 0;
                $dataSave               = array_map('trim', $dataSave);
                if(empty($rowErrorArr)){
                    //to_node_id
                    if($dataSave['to_node_id'] == "" || $dataSave['to_node_id'] == NULL ){
                        array_push($errorRowArr[101], $rowNumber);
                        array_push($rowErrorArr, 101);
                    } else {
                        $nodeId = $this->CI->dt_ci_common->validateNode($dataSave['to_node_id'], array(),'export');
                        if($nodeId != 0){
                            $toNodeId = $nodeId;
                            $dataSave['to_node_id'] = $nodeId;
                        }else{
                            array_push($errorRowArr[102], $rowNumber);
                            array_push($rowErrorArr, 102);
                        }
                    }
                    //from_node_id
                    if($dataSave['from_node_id'] == "" || $dataSave['from_node_id'] == NULL){
                        array_push($errorRowArr[201], $rowNumber);
                        array_push($rowErrorArr, 201);
                    } else {
                        $nodeId = $this->CI->dt_ci_common->validateNode($dataSave['from_node_id'], array(),'plant');
                        if($nodeId != 0){
                            $dataSave['from_node_id'] = $nodeId;
                        } else {
                            array_push($errorRowArr[202], $rowNumber);
                            array_push($rowErrorArr, 202);
                        }
                    }
                    //zone_region_id
                    if($dataSave['zone_region_id'] == "" || $dataSave['zone_region_id'] == NULL){
                        array_push($errorRowArr[301], $rowNumber);
                        array_push($rowErrorArr, 301);
                    }
                    else {
                        $zoneId = $this->CI->dt_ci_common->validateRegion($dataSave['zone_region_id'], array(),array("node_id" => $toNodeId,"region_level" => 2));
                        if($zoneId != 0){
                            $zoneRegionId = $zoneId;
                            $dataSave['zone_region_id'] = $zoneId;
                        }else{
                            array_push($errorRowArr[302], $rowNumber);
                            array_push($rowErrorArr, 302);
                        }
                    }
                    //country_region_id
                    if($dataSave['country_region_id'] == "" || $dataSave['country_region_id'] == NULL){
                        array_push($errorRowArr[401], $rowNumber);
                        array_push($rowErrorArr, 401);
                    }
                    else {
                        $nodeId = $this->CI->dt_ci_common->validateRegion($dataSave['country_region_id'], array(),array("parent_region_id" => $zoneRegionId,"region_level" => 3));
                        if($nodeId != 0){
                            $dataSave['country_region_id'] = $nodeId;
                        }else{
                            array_push($errorRowArr[402], $rowNumber);
                            array_push($rowErrorArr, 402);
                        }
                    }
                    //so_number
                    if($dataSave['so_number'] == "" || $dataSave['so_number'] == NULL){
                        array_push($errorRowArr[501], $rowNumber);
                        array_push($rowErrorArr, 501);
                    }
                    if (!is_numeric($dataSave['so_number'])) {
                        array_push($errorRowArr[502], $rowNumber);
                        array_push($rowErrorArr, 502);
                    }
                    $dupCheck = $this->CI->mdl_export->checkDuplicateSoNumber($dataSave);
                    if($dupCheck){
                        array_push($errorRowArr[503], $rowNumber);
                        array_push($rowErrorArr, 503);
                    }
                    if($dataSave['so_number'] <  0) {
                        array_push($errorRowArr[504], $rowNumber);
                        array_push($rowErrorArr, 504);
                    }
                    if($dataSave['so_number'] != round($dataSave['so_number'], 0)){
                        array_push($errorRowArr[505], $rowNumber);
                        array_push($rowErrorArr, 505);
                    }
                    //date_of_so
                    if($dataSave['date_of_so'] == "" || $dataSave['date_of_so'] == NULL){
                        array_push($errorRowArr[601], $rowNumber);
                        array_push($rowErrorArr, 601);
                    }
                    $date = date('Y-m-d',strtotime($dataSave['date_of_so']));
                    if ($this->CI->dt_ci_common->checkDateFormat($date)) {
                        $errorArrayLocal[] = 'Invalid SO date: '.$dataSave['date_of_so'].' Needs to be: Date - DD-MM-YYYY!';
                        array_push($errorRowArr[602], $rowNumber);
                        array_push($rowErrorArr, 602);
                    }
                    else {
                        $dataSave['date_of_so'] = $date;
                    }
                    //date_of_delivery
                    $date = date('Y-m-d',strtotime($dataSave['date_of_delivery']));
                    if ($this->CI->dt_ci_common->checkDateFormat($date)) {
                        $errorArrayLocal[] = 'Invalid Date of Delivery: '.$dataSave['date_of_delivery'].' Needs to be: Date - DD-MM-YYYY!';
                        array_push($errorRowArr[802], $rowNumber);
                        array_push($rowErrorArr, 802);
                    }
                    else {
                        $dataSave['date_of_delivery'] = $date;
                    }
                    if($dataSave['date_of_delivery'] == "" || $dataSave['date_of_delivery'] == NULL){
                        array_push($errorRowArr[801], $rowNumber);
                        array_push($rowErrorArr, 801);
                    }
                    else {
                        if ($dataSave['date_of_so'] != "" && $dataSave['date_of_so'] != null) {
                            if ($dataSave['date_of_delivery'] < $dataSave['date_of_so']) {
                                array_push($errorRowArr[803], $rowNumber);
                                array_push($rowErrorArr, 803);
                            } else {
                                $dataSave['date_of_delivery'] = $date;
                            }
                        }
                    }
                    //planning_remark
                    /*if($dataSave['planning_remark'] == "" || $dataSave['planning_remark'] == NULL){
                        array_push($errorRowArr[701], $rowNumber);
                        array_push($rowErrorArr, 701);
                    }*/

                    //production_remark
                    /*if($dataSave['production_remark'] == "" || $dataSave['production_remark'] == NULL){
                        array_push($errorRowArr[702], $rowNumber);
                        array_push($rowErrorArr, 702);
                    }*/
                }
                if(empty($rowErrorArr)){
                    $successEntries++;
                    $this->CI->mdl_export->saveExportCsv($dataSave, $userId);
                    $dataSaveBatch[] = $dataSave;
                }else{
                    $errorPrintExcel = array();
                    foreach ($rowErrorArr as $eachError){
                        $errorPrintExcel[] = $customErrors[$eachError];
                    }
                    $errorPrintExcel            = implode("\n\n", $errorPrintExcel);
                    $dataRowResponse['remarks'] = $errorPrintExcel;
                    $dataResponse[]             = $dataRowResponse;
                    $failedEntries++;
                }
            }
            $reponsePath            = $dirAbsPath.$filePrefix.'_RESPONSE.csv';
            $reponseRelativePath    = $dirRelPath.$filePrefix.'_RESPONSE.csv';
            $errorMessageDb         = '';
            if(!$this->CI->dt_ci_common->checkError($errorRowArr)){
                $response['result'] = "data_error";
                $errorMessage       = "<a class='alert-link' href='javascript:void(0)'>There was an error while uploading file!</a><a href='".base_url($reponseRelativePath)."' class='pull-xs-right btn btn-danger' style='color: white;' target='_blank'>Download Response File</a><br></b>";
                foreach($errorRowArr as $key => $value){
                    if(count($value) > 0){
                        if(SHOW_UPLOAD_ERROR){
                            $errorMessage.= '<a href="javascript:void(0)">'.$customErrors[$key].'</a> in <a class="alert-link" href="javascript:void(0)">ROW# '.implode(", ",$value)."</a><br><br>";
                        }
                    }
                }
                $response['errorMessage'] = $errorMessage;
            }else{
                $response['result'] = $errorMessageDb = "ok";
            }
            $this->CI->dt_ci_common->printResponseCsv($reponsePath, $dataResponse);
            $fileDbResponse = array(
                "text"          => $errorMessageDb,
                "success"       => $successEntries,
                "failed"        => $failedEntries,
                "duplicates"    => count($errorRowArr[102]),
            );
            $fileUploadData = array(
                "file_type"     => $masterType,
                "filepath"      => $dirRelPath.$uploadData['file_name'],
                "user_id"       => $userId,
                "start_time"    => $startTime,
                "end_time"      => date('Y-m-d H:i:s'),
                "response_path" => $reponseRelativePath,
                "response"      => json_encode($fileDbResponse),
                "auto_upload"   => !is_array($file) && $file != ""?1:0,
            );
            $this->CI->mdl_fileuploads->saveData($fileUploadData);
        }
        if(!$return){
            return json_encode($response);
        }else{
            if(isset($fileUploadData)) $response['file_upload_data'] = $fileUploadData;
            return $response;
        }
    }

    public function uploadExportSku($userId, $return = false, $file = array()){
        $startTime  = date('Y-m-d H:i:s');
        $dirAbsPath = EXPORT_SKU_BU_ABS_PATH;
        $dirRelPath = EXPORT_SKU_BU_RELATIVE_PATH;
        $masterType = "export_master";
        $this->CI->load->model('mdl_fileuploads');
        $this->CI->load->model('mdl_export');
        $bomSymbols = $this->CI->dt_ci_common->getSymbols('export');
        $customErrors = array(
            "101" => "PO number Required",
            "102" => "PO number does not exist",
            "103" => "Sku Code is already exist with Entered PO Number",
            "104" => "PO number should be positive value",
            "105" => "PO number should not have decimal value",
            "106" => "PO number should be numeric",
            "201" => "Sku code required",
            "202" => "Sku is inactive",
            "203" => "Sku is invalid",
            "501" => "Order quantity require",
            "502" => "Order quantity should be numeric",
            "503" => "Order quantity should be positive number",
            "504" => "Order quantity should not have decimal value",
        );
        $response = $this->CI->dt_ci_common->uploadMasterFile($userId,$_FILES, $dirAbsPath, $file);
        if($response['result'] == 'ok'){
            $dataExcel  = $response['unset_data']['data_excel'];
            $uploadData = $response['unset_data']['upload_data'];
            $filePrefix = $response['unset_data']['file_prefix'];
            unset($response['unset_data']);
            $successEntries = 0;
            $failedEntries  = 0;
            $errorRowArr    = array();
            $dataSaveBatch  = array();
            $dataResponse   = array();
            foreach ($customErrors as $cErrKey => $cErrMessage){
                $errorRowArr[$cErrKey] = array();
            }
            foreach($dataExcel['values'] as $rowNumber => $row) {
                $dataSave       = array();
                $rowErrorArr    = array();
                $columnMap = array(
                    'A' => 'so_number',
                    'B'	=> 'sku_id',
                    'C' => 'order_quantity',
                    'D' => 'sku_planning_remark',
                    'E' => 'sku_production_remark',
                );
                //load dataSave with data in db columns
                foreach($columnMap as $excelCol => $colMKey){
                    $dataSave[$colMKey] = isset($row[$excelCol])?$row[$excelCol]:"";
                }
                $dataRowResponse = $dataSave;
                unset($dataSave['is_delete']);
                $dataSave = array_map('trim', $dataSave);
                if(empty($rowErrorArr)){
                    if($dataSave['so_number'] == "" || $dataSave['so_number'] == NULL ){
                        array_push($errorRowArr[101], $rowNumber);
                        array_push($rowErrorArr, 101);
                    }
                    elseif (!is_numeric($dataSave['so_number'])) {
                        array_push($errorRowArr[106], $rowNumber);
                        array_push($rowErrorArr, 106);
                    }
                   elseif($dataSave['so_number'] <  0) {
                        array_push($errorRowArr[104], $rowNumber);
                        array_push($rowErrorArr, 104);
                    }
                   elseif($dataSave['so_number'] != round($dataSave['so_number'], 0)){
                        array_push($errorRowArr[105], $rowNumber);
                        array_push($rowErrorArr, 105);
                    }
                    else {
                        $result = $this->CI->mdl_export->checkExisting($dataSave);
                        $skuId = $this->CI->dt_ci_common->validateSku($dataSave['sku_id'], array());
                        $dataArray = array('sku_id' => $skuId['sku_id']);
                        if($result) {
                            $dupCheck = $this->CI->mdl_export->checkCombination($dataArray,$result['export_id']);
                            $dataSave['export_id'] = $result['export_id'];
                            if($dupCheck){
                                array_push($errorRowArr[103], $rowNumber);
                                array_push($rowErrorArr, 103);
                            }
                        } else {
                            array_push($errorRowArr[102], $rowNumber);
                            array_push($rowErrorArr, 102);
                        }
                    }
                    if($dataSave['sku_id'] == "" || $dataSave['sku_id'] == NULL ){
                        array_push($errorRowArr[201], $rowNumber);
                        array_push($rowErrorArr, 201);
                    }
                    else {
                        $sku = $this->CI->dt_ci_common->validateSku($dataSave['sku_id'], array());
                        if (isset($sku) && !empty($sku)) {
                            if ($sku['sku_id'] != 0 && $sku['status'] == 1) {
                                $dataSave['sku_id'] = $sku['sku_id'];
                            } else {
                                array_push($errorRowArr[202], $rowNumber);
                                array_push($rowErrorArr, 202);
                            }
                        } else {
                            array_push($errorRowArr[203], $rowNumber);
                            array_push($rowErrorArr, 203);
                        }
                    }
                    if($dataSave['order_quantity'] == "" || $dataSave['order_quantity'] == NULL){
                        array_push($errorRowArr[501], $rowNumber);
                        array_push($rowErrorArr, 501);
                    }
                    if (!is_numeric($dataSave['so_number'])) {
                        array_push($errorRowArr[502], $rowNumber);
                        array_push($rowErrorArr, 502);
                    }
                    if($dataSave['order_quantity'] <  0) {
                        array_push($errorRowArr[503], $rowNumber);
                        array_push($rowErrorArr, 503);
                    }
                    if($dataSave['order_quantity'] != round($dataSave['order_quantity'], 0)){
                        array_push($errorRowArr[504], $rowNumber);
                        array_push($rowErrorArr, 504);
                    }
                }
                if(empty($rowErrorArr)){
                    unset($dataSave['so_number']);
                    $successEntries++;
                    $exportId                   = $dataSave['export_id'];
                    $availableCount             = $this->CI->mdl_export->getCountByExportId($exportId);
                    $serial_number              = ($availableCount * 10) + 10;
                    $dataSave['serial_number']  = customString($serial_number);
                    $dataSave['balance_quantity']  = $dataSave['order_quantity'];
                    $this->CI->mdl_export->saveExportSkuCsv($dataSave, $userId);
                    $dataSaveBatch[] = $dataSave;
                } else {
                    $errorPrintExcel = array();
                    foreach ($rowErrorArr as $eachError){
                        $errorPrintExcel[] = $customErrors[$eachError];
                    }
                    $errorPrintExcel            = implode("\n\n", $errorPrintExcel);
                    $dataRowResponse['remarks'] = $errorPrintExcel;
                    $dataResponse[]             = $dataRowResponse;
                    $failedEntries++;
                }
            }
            $reponsePath            = $dirAbsPath.$filePrefix.'_RESPONSE.csv';
            $reponseRelativePath    = $dirRelPath.$filePrefix.'_RESPONSE.csv';
            $errorMessageDb         = '';
            if(!$this->CI->dt_ci_common->checkError($errorRowArr)){
                $response['result'] = "data_error";
                $errorMessage       = "<a class='alert-link' href='javascript:void(0)'>There was an error while uploading file!</a><a href='".base_url($reponseRelativePath)."' class='pull-xs-right btn btn-danger' style='color: white;' target='_blank'>Download Response File</a><br></b>";
                foreach($errorRowArr as $key => $value){
                    if(count($value) > 0){
                        if(SHOW_UPLOAD_ERROR){
                            $errorMessage.= '<a href="javascript:void(0)">'.$customErrors[$key].'</a> in <a class="alert-link" href="javascript:void(0)">ROW# '.implode(", ",$value)."</a><br><br>";
                        }
                    }
                }
                $response['errorMessage'] = $errorMessage;
            }else{
                $response['result'] = $errorMessageDb = "ok";
            }
            $this->CI->dt_ci_common->printResponseCsv($reponsePath, $dataResponse);
            $fileDbResponse = array(
                "text"      => $errorMessageDb,
                "success"   => $successEntries,
                "failed"    => $failedEntries,
            );
            $fileUploadData = array(
                "file_type"     => $masterType,
                "filepath"      => $dirRelPath.$uploadData['file_name'],
                "user_id"       => $userId,
                "start_time"    => $startTime,
                "end_time"      => date('Y-m-d H:i:s'),
                "response_path" => $reponseRelativePath,
                "response"      => json_encode($fileDbResponse),
                "auto_upload"   => !is_array($file) && $file != ""?1:0,
            );
            $this->CI->mdl_fileuploads->saveData($fileUploadData);
        }
        if(!$return){
            return json_encode($response);
        }else{
            if(isset($fileUploadData)) $response['file_upload_data'] = $fileUploadData;
            return $response;
        }
    }
}
