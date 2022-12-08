<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DT_ci_common
{
    public function __construct()
    {
        $this->CI =& get_instance();
    }


    public function getParamFromArray($param, $data = array())
    {
        return $param = isset($data[$param]) ? $data[$param] : NULL;
    }

    public function cleanArray($param, &$data = array())
    {

        foreach ($data as $key => $value) {
            if ($value == $param) {
                unset($data[$key]);
            }
        }

    }

    public function getTableArray($dataParams, $array)
    {
        $resultParams = array();
        foreach ($dataParams as $key => $value) {
            if ($value == NULL) {
                $resultParams[$key] = $this->getParamFromArray($key, $array);
            } else {
                $resultParams[$value] = $this->getParamFromArray($key, $array);
            }
        }
        return $resultParams;
    }

    public function getActiveMenu($path, $class = 'active')
    {
        if ($path == uri_string()) {
            return "class='$class'";
        }
    }

    public function printArray($array, $die = false)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
        if ($die) {
            die();
        }
    }

    public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public function printOptions($options, $selected, $keyName, $valueName, $zeroValue = '', $valueName2 = '')
    {
        if($zeroValue != ''){
            echo "<option value='0'>--Select {$zeroValue}--</option>";
        }
        foreach ($options as $opt){
            echo (!is_null($selected) && $opt[$keyName] == $selected) ? "<option value='".$opt[$keyName]."' selected='selected'>".$opt[$valueName].($valueName2 != ''?' - '.$opt[$valueName2]:'')."</option>" : "<option value='".$opt[$keyName]."'>".$opt[$valueName].($valueName2 != ''?' - '.$opt[$valueName2]:'')."</option>";
        }
    }

    public function getColorPHP($zone){

        $colors = json_decode(BUFFER_ZONE_COLORS, true);

        foreach($colors as $key => $value){
            if($value['color'] == $zone){
                return $value['color_code'];
            }
        }

//        if($zone == "blue"){
//            return BLUE_ZONE;
//        }else if($zone == "green"){
//            return GREEN_ZONE;
//        }else if($zone == "yellow"){
//            return YELLOW_ZONE;
//        }else if($zone == "red"){
//            return RED_ZONE;
//        }else if($zone == "black"){
//            return BLACK_ZONE;
//        }else{
//            return $zone;
//        }

        return false;
    }

    public function checkOverLap($minValues,$maxValues){
        $x_array = $minValues;
        $y_array = $maxValues;

        $overlap = array();
        asort($x_array); // sort only the first x range array values and maintain the index
        $max_val = -99999999999999999999999999999;
        $last_index = 0;
        foreach($x_array as $each_index => $each_x){
            // get the y corresponding value
            $this_y = $y_array[$each_index];
            //echo "$this_y < $max_val";
            if($each_x > $max_val && $this_y > $max_val){
                $max_val = $this_y;
            } else{
                $last_x = $x_array[$last_index];
                $last_y = $y_array[$last_index];
                $overlap[] = array(
                   "index1" => $each_index,
                   "index2" => $last_index,
                   "display" => "($each_x,$this_y) and ($last_x,$last_y)",
                );
            }
            $last_index = $each_index;
        }
        return $overlap;
    }
    public function checkError($errorRowArr){
        foreach($errorRowArr as $key => $value){
            if(count($value) > 0){

                return false;
            }
        }
        return true;
    }

    public function duplicateCount($errorRowArr, $errorCode){
        $duplicateCount = 0;
        foreach($errorRowArr as $key => $value){
            if(count($value) > 0){
                return false;
            }
        }
        return true;
    }

    public function checkDateFormat($date){
        return date('Y',strtotime($date))=='1970' && date('m',strtotime($date))=='01' && date('d',strtotime($date))=='01';
    }

    public function validateUnit($unit_symbol, $units = array()){

        if(empty($units)){
            $this->CI->load->model('mdl_units');
            $units = $this->CI->mdl_units->getUnits();
        }

        $unitId = 0;

        if($unit_symbol == ""){
            return $unitId;
        }

        foreach($units as $unit){
            if($unit['unit_symbol'] == $unit_symbol){
                $unitId = $unit['unit_id'];
                break;
            }
        }

        return $unitId;
    }

    function siteDateFormat($date,$show_time = '',$hour24format = ''){
        if ($hour24format) {
            return ($show_time) ? date('d-m-Y H:i:s', strtotime($date)) : date('d-m-Y', strtotime($date));
        } else {
            return ($show_time) ? date('d-m-Y h:i:s A', strtotime($date)) : date('d-m-Y', strtotime($date));
        }
    }

    public function validateProductType($productType, $productTypes = array()){

        if(empty($productTypes)){
            $this->CI->load->model('mdl_master');
            $productTypes = $this->CI->mdl_master->getProductType();
            //$this->printArray($productTypes,1);
        }

        $productTypeId = 0;
        if($productType == ""){
            return $productTypeId;
        }

        foreach($productTypes as $productTypeData){

            if($productTypeData['product_type'] == $productType){
                $productTypeId = $productTypeData['product_type_id'];
                break;
            }
        }

        return $productTypeId;
    }

    public function validateMaterialType($materialType, $materialTypes = array()){

        if(empty($materialTypes)){
            $this->CI->load->model('mdl_master');
            $materialTypes = $this->CI->mdl_master->getMaterialType();
        }

        $materialTypeId = 0;
        if($materialType == ""){
            return $materialTypeId;
        }

        foreach($materialTypes as $materialTypeData){

            if($materialTypeData['material_type'] == $materialType){
                $materialTypeId = $materialTypeData['material_type_id'];
                break;
            }
        }

        return $materialTypeId;
    }
	public function validateLocation($location, $locations = array()){

		if(empty($locations)){
			$this->CI->load->model('mdl_master');
			$locations = $this->CI->mdl_master->getLocation();

		}

		$locationId = 0;
		if($location == ""){
			return $locationId;
		}

		foreach($locations as $locationData){

			if($locationData['location'] == $location){
				$locationId = $locationData['location_id'];
				break;
			}
		}

		return $locationId;
	}
	public function validateAssemblyLine($assemblyLine, $assemblyLines = array()){

		if(empty($assemblyLines)){
			$this->CI->load->model('mdl_master');
			$assemblyLines = $this->CI->mdl_master->getAssemblyLine();

		}

		$assemblyLineId = 0;
		if($assemblyLine == ""){
			return $assemblyLineId;
		}

		foreach($assemblyLines as $assemblyLineData){

			if($assemblyLineData['assembly_line'] == $assemblyLine){
				$assemblyLineId = $assemblyLineData['assembly_line_id'];
				break;
			}
		}
		return $assemblyLineId;
	}
    public function validateMaterialCategory($materialCategory, $materialCategories = array()){

        if(empty($materialCategories)){
            $this->CI->load->model('mdl_master');
            $materialCategories = $this->CI->mdl_master->getMaterialCategory();
            //$this->printArray($materialCategories,1);
        }

        $materialCategoryId = 0;
        if($materialCategory == ""){
            return $materialCategoryId;
        }

        foreach($materialCategories as $materialCategoryData){

            if($materialCategoryData['material_category_code'] == $materialCategory){
                $materialCategoryId = $materialCategoryData['material_category_id'];
                break;
            }
        }

        return $materialCategoryId;
    }

    public function validateLineName($lineName, $lineNames = array()){

        if(empty($lineNames)){
            $this->CI->load->model('mdl_master');
            $lineNames = $this->CI->mdl_master->getLines();
            //$this->printArray($materialCategories,1);
        }

        $lineNameId = 0;
        if($lineName == ""){
            return $lineNameId;
        }

        foreach($lineNames as $lineNameData){

            if($lineNameData['line_code'] == $lineName){
                $lineNameId = $lineNameData['line_name_id'];
                break;
            }
        }

        return $lineNameId;
    }

    public function validateRegion($region_code, $regions = array(),$condition = ''){

        if(empty($regions)){
            $this->CI->load->model('mdl_region');
            $regions = $this->CI->mdl_region->getRegion(0,0,'',true,$condition);
        }

        $regionId = 0;

        if($region_code == ""){
            return $regionId;
        }

        foreach($regions as $region){
            if($region['region_code'] == $region_code){
                $regionId = $region['region_id'];
                break;
            }
        }

        return $regionId;
    }

    public function validateCat($category_code, $cats = array()){

        if(empty($cats)){
            $this->CI->load->model('mdl_category');
            $cats = $this->CI->mdl_category->getCategories();
        }

        $catId = 0;

        if($category_code == ""){
            return $catId;
        }

        foreach($cats as $cat){
            if($cat['category_code'] == $category_code){
                $catId = $cat['category_id'];
                break;
            }
        }

        return $catId;
    }

    public function validateNode($node_code, $nodes = array(),$condition = ''){


        if(empty($nodes)) {
            $this->CI->load->model('mdl_node');
            $nodes = $this->CI->mdl_node->getNodes(0,0,'', false,$condition);

        }

        $nodeId = 0;

        if($node_code == ""){
            return $nodeId;
        }
        foreach($nodes as $node){

            if($node['node_code'] == $node_code){
                $nodeId = $node['node_id'];
                break;
            }
        }


        return $nodeId;
    }


    public function validateSku($sku_code, $skus = array()){

        $skuData = array();
        if(empty($skus)) {
            $this->CI->load->model('mdl_sku');
            $skus = $this->CI->mdl_sku->getSkuData();

        }
        $skuId = 0;
        if($sku_code == ""){
            return $skuId;
        }
        foreach($skus as $sku){
            if($sku['sku_code'] == $sku_code){
                $skuData = array(
                    'sku_id' => $sku['sku_id'],
                    'status' => $sku['status']
                );
                break;
            }
        }
        return $skuData;
    }

    public function validateSkuSpecial($sku_code, $skus = array()){

        if(empty($skus)) {
            $this->CI->load->model('mdl_sku');
            $skus = $this->CI->mdl_sku->getSkus();
        }

        $skuDetails = false;

        if($sku_code != ""){
            foreach($skus as $sku){
                if($sku['sku_code'] == $sku_code){
                    $skuDetails = $sku;
                    break;
                }
            }
        }

        return $skuDetails;
    }

    public function validateNodeType($node_type, $nodeTypes = array()){

        if(empty($nodeTypes)) {
            $this->CI->load->model('mdl_node');
            $nodeTypes = $this->CI->mdl_node->getNodeTypes();
        }

        $nodeTypeId = 0;

        if($node_type == ""){
            return $nodeTypeId;
        }

        foreach($nodeTypes as $nodeType){
//            $this->printArray($node);
            if($nodeType['node_type_name'] == $node_type){
                $nodeTypeId = $nodeType['id'];
                break;
            }
        }

        return $nodeTypeId;
    }

    function is_digits($element) {
        return !preg_match ("/[^0-9]/", $element);
    }

    public function validateIndentType($indent_type, $nodeTypes = array()){

        if(empty($nodeTypes)) {
            $this->CI->load->model('mdl_additional_indent');
            $indentTypes = $this->CI->mdl_additional_indent->getIndentTypes();
        }

        $indentTypeId = 0;

        if($indent_type == ""){
            return $indentTypeId;
        }

        foreach($indentTypes as $indentType){
//            $this->printArray($node);
            if($indentType['indent_type_name'] == $indent_type){
                $indentTypeId = $indentType['id'];
                break;
            }
        }

        return $indentTypeId;
    }

    public function getSymbols($type, $returnType = 'symbol', $showInactive = true){

        $dataArr = array();
        $symbolArr = array();
        $symbolName = '';

        if($type == 'unit'){
            $this->CI->load->model('mdl_units');
            $dataArr = $this->CI->mdl_units->getUnits(0,0,$showInactive);
            $symbolName = 'unit_symbol';
        }
        else if($type == 'category'){
            $this->CI->load->model('mdl_category');
            $dataArr = $this->CI->mdl_category->getCategories(0,0,'', $showInactive);
            $symbolName = 'category_code';
        }
        else if($type == 'node'){
            $this->CI->load->model('mdl_node');
            $dataArr = $this->CI->mdl_node->getNodes(0,0,'', $showInactive);
            $symbolName = 'node_code';
        }
        else if($type == 'region'){
            $this->CI->load->model('mdl_region');
            $dataArr = $this->CI->mdl_region->getRegion(0,0,'', $showInactive);
            $symbolName = 'region_code';
        }
        else if($type == 'sku'){
            $this->CI->load->model('mdl_sku');
            $dataArr = $this->CI->mdl_sku->getSkus(0,0,'', $showInactive);
            $symbolName = 'sku_code';
        }

        foreach ($dataArr as $indData){
            array_push($symbolArr, $indData[$symbolName]);
        }

        $returnArr = array(
            'data' => $dataArr,
            'symbols' => $symbolArr
        );

        if($returnType == 'symbol'){
            return $symbolArr;
        }
        else if($returnType == 'data'){
            return $dataArr;
        }
        else if($returnType == 'both'){
            return $returnArr;
        }
    }

    public function printResponseCsv($path, $data, $hasHeaderAsRow = false){
        if(empty($data)){
            $this->printCsv($path, array(0=> array("Result" => "Success!")));
        }else{
            $this->printCsv($path, $data, $hasHeaderAsRow);
        }
    }

    public function printCsv($path, $data, $hasHeaderAsRow = false){

        $out = fopen($path, 'w');


        $headerArr = array();

        foreach($data as $row){
            foreach ($row as $rowKey => $rowValue){
                $headerArr[] = $rowKey;
            }
            break;
        }

        fwrite($out, "\xEF\xBB\xBF");

        if(!$hasHeaderAsRow){
            fputcsv($out, $headerArr);
        }

        foreach ($data as $row){
            fputcsv($out, $row);

        }

        fclose($out);
    }

    public function uploadMasterFile($userId,$files, $path, $file = array()){

//        $this->printArray($userId);
//        $this->printArray($file);
//        $this->printArray($path);
//        $this->printArray($files,1);

        $response = array();
        $directFile = false;
        $fileUploadSuccess = false;

        if(!is_array($file)){
            $directPathInfo = pathinfo($file);
            $directFileName = $directPathInfo['basename'];
            $directFile = true;
            $user_id = $userId;

        }else{
            $user_id = $userId;
        }

        if(isset($_FILES['file']['name'])){
            $original_name_array = explode(".", $_FILES['file']['name']);
        }else{
            $original_name_array = explode(".", $directFileName);
        }

        $dateTimeObj = new DateTime();
        $fileDateSuffix = $dateTimeObj->format('mdY_His');
        $fileDateSuffix.=substr((string)microtime(), 2, 8);

        $response = array();
        $fileRealName = reset($original_name_array);
        $ext = end($original_name_array);

        $file_name = $user_id.'_'.$fileDateSuffix.'.'.$ext;

        if($directFile){

            copy($file, $path.$file_name);
//            $this->printArray(copy($file, $path.$file_name),1);
            $response['unset_data']['upload_data']['file_name'] = $file_name;
            $fileUploadSuccess = true;
        } else {

            $config = array();
            $config['upload_path'] = $path;
            $config['allowed_types'] = array('csv');
            $config['file_name'] = $file_name;
            $this->CI->load->library('upload', $config);
            $this->CI->upload->initialize($config);

            if ( ! $this->CI->upload->do_upload('file')){
                $error = array('error' => $this->CI->upload->display_errors());
                $response['result'] = 'error';
                $response['error_desc'] = $error;
                $response['more_data'] = $files;
                $fileUploadSuccess = false;
            }
            else{
                $response['unset_data']['upload_data'] = $this->CI->upload->data();
                $fileUploadSuccess = true;
            }
        }
        if($fileUploadSuccess){
            $response['result'] = 'ok';

            $filePath = $path.$file_name;

            $this->CI->load->library('DT_ci_phpcsv');
            $DT_ci_phpexcel = new DT_ci_phpcsv();

            $response['unset_data']['data_excel'] = $DT_ci_phpexcel->getFileData($filePath);

            $response['unset_data']['file_prefix'] = $user_id.'_'.$fileDateSuffix;
        }

        return $response;
    }
    
    public function getFileData($filePath){

        $this->CI->load->library('DT_ci_phpcsv');
        $DT_ci_phpexcel = new DT_ci_phpcsv();

        $response = [];

        $response['data_excel'] = $DT_ci_phpexcel->getFileData($filePath);

        return $response;
    }


    public function uploadFile($files, $path, $file = array()){

        $response = array();
        $directFile = false;
        $fileUploadSuccess = false;

        if(!is_array($file)){
            $directPathInfo = pathinfo($file);
            $directFileName = $directPathInfo['basename'];
            $directFile = true;
            $user_id = $this->CI->ion_auth->get_user_id();
        }else{
            $user_id = $this->CI->ion_auth->get_user_id();
        }

        if(isset($_FILES['file']['name'])){
            $original_name_array = explode(".", $_FILES['file']['name']);
        }else{
            $original_name_array = explode(".", $directFileName);
        }

        $dateTimeObj = new DateTime();
        $fileDateSuffix = $dateTimeObj->format('mdY_His');
        $fileDateSuffix.=substr((string)microtime(), 2, 8);

        $response = array();
        $fileRealName = reset($original_name_array);
        $ext = end($original_name_array);

        $file_name = $user_id.'_'.$fileDateSuffix.'.'.$ext;

        if($directFile){
//          $this->printArray(copy($file, $path.$file_name),1);
            $response['unset_data']['upload_data']['file_name'] = $file_name;
            $fileUploadSuccess = true;
        }else{
            $config = array();
            $config['upload_path'] = $path;
            $config['allowed_types'] = array('csv');
            $config['file_name'] = $file_name;
            $this->CI->load->library('upload', $config);
            $this->CI->upload->initialize($config);
            if ( ! $this->CI->upload->do_upload('file')){
                $error = array('error' => $this->CI->upload->display_errors());
                $response['result'] = 'error';
                $response['error_desc'] = $error;
                $response['more_data'] = $files;
                $response['success'] = false;
                $fileUploadSuccess = false;
            }
            else{
                $response['unset_data']['upload_data'] = $this->CI->upload->data();
                $response['success'] = true;
                $fileUploadSuccess = true;
            }
        }

        return $response;
    }

    public function getSettingsArray($code){

        $this->CI->load->model('mdl_settings');

        $settingsArray = array();
        $settings = $this->CI->mdl_settings->getSettings($code);

        foreach ($settings as $setting){
            if($setting['serialized'] == 1){
                $settingsArray[$setting['key']] = json_decode($setting['value'], true);
            }else{
                $settingsArray[$setting['key']] = $setting['value'];
            }
        }

        return $settingsArray;
    }

    public function getColorZone($settingsArray, $percentage){
        $color = '';

        foreach ($settingsArray as $settings){
            if($settings['onOff'] == 1){
                $percentage = (double) $percentage;
                $settings['from'] = (double) $settings['from'];
                $settings['to'] = (double) $settings['to'];
                if(
                    $percentage >= $settings['from'] &&
                    $percentage <= $settings['to']
                ){
                    $color = $settings['color']['color'];
                    break;
                }
            }
        }

        return $color;
    }

    public function getColorSettings($settingsArray, $color){
        $returnValue = 1;

        foreach ($settingsArray as $settings){
            if($settings['onOff'] == 1 && $settings['color']['color'] == $color){

                if($settings['type'] == 1){
                    $returnValue = (double) $settings['to'];
                }
                else if($settings['type'] == 2){
                    $returnValue = (double) $settings['from'];
                }
                else if($settings['type'] == 3){
                    $returnValue = (double) $settings['from'];
                }
                else if($settings['type'] == 4){
                    $returnValue = (double) $settings['to'];
                }
            }
        }

        return $returnValue;
    }

    public function array_sort($array, $on, $maintainIndex = true, $order=SORT_ASC){

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    if($maintainIndex){
                        asort($sortable_array);
                    }else{
                        sort($sortable_array);
                    }
                    break;
                case SORT_DESC:
                    if($maintainIndex){
                        arsort($sortable_array);
                    }else{
                        rsort($sortable_array);
                    }
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k]
                    = $array[$k];
            }
        }

        return $new_array;
    }

    public function writeCsvExport($name, $headerArr, $dataRows){

        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$name.'.csv";');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, $headerArr);

        foreach ($dataRows as $rowElem){
            fputcsv($out, $rowElem);
        }

        fclose($out);
    }

    public function writeCsvZipExport($name, $headerArr, $dataRows, $skipHeader = false){

        // create your zip file
        $zipname = $name.time().'.zip';
        $zippath = TEMP_EXPORT_PATH.$zipname;
        $zip = new ZipArchive;
        $zip->open($zippath, ZipArchive::CREATE);
        $fd = fopen('php://temp/maxmemory:1048576', 'w');
        if (false === $fd) {
            die('Failed to create temporary file');
        }
//        $out = fopen('php://output', 'w');
        fwrite($fd, "\xEF\xBB\xBF");
        if(!$skipHeader){
            fputcsv($fd, $headerArr);
        }

        foreach ($dataRows as $rowElem){
            fputcsv($fd, $rowElem);
        }

        rewind($fd);

        $zip->addFromString($name.'.csv', stream_get_contents($fd) );
        $this->printArray($zippath,1);
        //close the file
        fclose($fd);
        $zip->close();
//        return $zipname;
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zippath));
        readfile($zippath);

//         remove the zip archive
//         you could also use the temp file method above for this.
        unlink($zipname);
    }
    public function getDataFromDatatable($jsonString){
        $dataReport = json_decode($jsonString, true);
        $rows = $dataReport['data'];

        return $rows;
    }

    public function checkAndMergePercentage(&$elemInner, $index, $precision = 2, $max = ""){
        if(isset($elemInner[$index]) && $elemInner[$index] != ""){
            $elemInner[$index] = round($elemInner[$index] * 100, $precision);

            if($max != ""){
                $elemInner[$index] = min($elemInner[$index], $max);
            }

            $elemInner[$index] = $elemInner[$index]." %";
        }else{
            $elemInner[$index] = "0 %";
        }
    }

    public function checkAndMergeNOS(&$elemInner, $index){
        if(isset($elemInner[$index]) && $elemInner[$index] != ""){
            $elemInner[$index] = round($elemInner[$index], 2);
        }else{
            $elemInner[$index] = 0;
        }
    }

    public function stringEndsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    public function getCatOrWhereClause($catFilter){
        $catOr = "";
//        $this->printArray($catFilter, true);
        $catFilter = isset($catFilter) && is_array($catFilter)?array_filter($catFilter):array();
        if(isset($catFilter) && $catFilter != ""  && count($catFilter) > 0){
            foreach($catFilter as $key => $arr){
                $innerArr = explode("_", $arr);
                foreach($innerArr as $iKey => $categoryId){
                    if($iKey == 0){
                        $catAnd = "FIND_IN_SET('$categoryId', cat_ids)";
                    } else {
                        $catAnd .= " AND FIND_IN_SET('$categoryId', cat_ids)";
                    }
                }
                $catOr .= ($key == 0) ? "(".$catAnd.")" : " or (".$catAnd.")";
            }
        }

        return $catOr;
    }

    public function sendSms($recipients, $message){
        $CI =& get_instance();

        if(is_array($recipients))
        {
            $mobileNo = implode(",",$recipients);
        } else {
            $mobileNo = $recipients;
        }

        $CI->load->library('Guzzle');

        $client = new GuzzleHttp\Client();

        try {
            $requestTime = date('Y-m-d H:i:s');
            $response = $client->request('GET', 'http://globesms.in/api/sendhttp.php', [
                'query' => [
                    'authkey' => "5280Aowu9kqqXa5ac62c0e",
                    'mobiles' => $mobileNo,
                    'message' => $message,
                    'sender' => "INFISC",
                    'route' => 4,
                    'country' => 91,
                ],
            ]);

            $responseTime = date('Y-m-d H:i:s');

            $returnStatus = $response->getBody()->getContents();

            $logEmailData = array(
                "response" => $returnStatus,
                "request_time" => $requestTime,
                "response_time" => $responseTime,
                "status" => 1,
                "recipient" => json_encode($recipients),
                "email_type" => 0,
                "email_content" => $message,
                "contact_type" => 'sms',
            );

            $CI->db->insert('tbl_log_email', $logEmailData);

//            $data = $response->getBody();

        }catch (Exception $e) {

        }
    }


    public function validateWIPType($type, $wipType = array()){

        if(count($wipType) == 0){
            $wipType = array(
                "WIP",
                "EXPORT",
                "AI",
            );
        }

       if(in_array($type,$wipType)){
           return true;
       } else {
           return false;
       }


    }
}
