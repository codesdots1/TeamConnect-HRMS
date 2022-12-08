<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DT_ci_phpcsv {

    public function __construct() {
//    parent::__construct();
    }

    public function getFileData($filePath){
        $arr_data = array();
        $header = array();

        $row = 1;
        $file = fopen($filePath,"r");

        while(! feof($file)) {
            $data = fgetcsv($file);
//            printArray($data,1);
            $num = ($data != '') ? count($data) : "";
//            printArray($num,1);

            if($row == 1){
                for ($c=0; $c < $num; $c++) {
                    $header[$row][$this->getNameFromNumber($c)] = $data[$c];
                }
            }else{
                for ($c=0; $c < $num; $c++) {
                    $arr_data[$row][$this->getNameFromNumber($c)] = $data[$c];
                }
            }
            $row++;
        }
        fclose($file);
        //send the data in an array format
        $data['header'] = $header;
        foreach($arr_data as $dataKey => $dataVal){
            $nonEmpty = false;
            foreach($dataVal as $key => $value){
                if(trim($value) != ""){
                    $nonEmpty = true;
                    break;
                }
            }
            if($nonEmpty){
                $data['values'][$dataKey] = $dataVal;
            }
        }

        return $data;
    }

    private function getNameFromNumber($num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }


}