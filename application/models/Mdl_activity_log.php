<?php
/**
 * Created by PhpStorm.
 * User: dt-user09
 * Date: 7/27/2018
 * Time: 10:29 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_activity_log extends DT_CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function deleteRecord($activityLogId)
    {
        $tables = array('tbl_activity_log');
        $this->db->where_in('activity_log_id', $activityLogId);
        $this->db->delete($tables);

        if ($this->db->affected_rows()) {
            $response['success'] = true;
            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }

    }

    public function writeCsvZipExport($name, $headerArr, $dataRows)
    {
//        printArray($dataRows,1);

        // create your zip file
        $zipname = $name.'.zip';
        $zippath = $zipname;
        $zip = new ZipArchive;
        $zip->open($zippath, ZipArchive::CREATE);

        $fd = fopen('php://temp/maxmemory:1048576', 'w');
        if (false === $fd) {
            die('Failed to create temporary file');
        }


        //$out = fopen('php://output', 'w');
        fwrite($fd, "\xEF\xBB\xBF");
        fputcsv($fd, $headerArr);

        foreach ($dataRows as $rowElem){
            fputcsv($fd, $rowElem);
        }

        rewind($fd);

        $zip->addFromString($name.'.csv', stream_get_contents($fd));
        //close the file
        fclose($fd);
        $zip->close();
        return $file = $zipname;

//        header('Content-Type: application/zip');
//        header('Content-disposition: attachment; filename='.$zipname);
//        header('Content-Length: ' . filesize($zippath));
//        return readfile($zippath);

        // remove the zip archive
        // you could also use the temp file method above for this.
        //unlink($zipname);
    }
}

?>