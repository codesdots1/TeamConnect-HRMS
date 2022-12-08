<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Dt_ci_file_upload
{
    public function UploadMultipleFile($fieldname, $maxsize, $uploadpath, $get_real_name = true, $ref_name = true, $extensions = false,$isUpload = true){
        $uploadData = array();
        $CI =& get_instance();
        $filesCount = count($_FILES[$fieldname]['name']);
        if ($filesCount == 0) {
            return array('success' => false, 'title' => 'unsuccessful', 'msg' => 'Please upload a file');
        }
        for ($i = 0; $i < $filesCount; $i++) {
            $_FILES['userFile']['name'] = $_FILES[$fieldname]['name'][$i];
            $_FILES['userFile']['type'] = $_FILES[$fieldname]['type'][$i];
            $_FILES['userFile']['tmp_name'] = $_FILES[$fieldname]['tmp_name'][$i];
            $_FILES['userFile']['error'] = $_FILES[$fieldname]['error'][$i];
            $_FILES['userFile']['size'] = $_FILES[$fieldname]['size'][$i];
//           File Extension Checking
            $file_extension = strtolower(pathinfo($_FILES['userFile']['name'], PATHINFO_EXTENSION));
//            echo $file_extension;
            if ($extensions !== false && is_array($extensions)) {
                if (!in_array($file_extension, $extensions)) {
                    return array('title' => $_FILES['userFile']['name'], 'success' => false, 'msg' => 'Please upload valid file');
                }
            }

//           File Size Checking
            $file_size = $_FILES['userFile']['size'];
            if ($file_size > $maxsize) {
                $uploadData['success'] = false;
                $uploadData['title'] = 'Error';
                $uploadData['msg'] = 'File Exceeds maximum limit';
                return $uploadData;

            }
        }

        if($isUpload == true) {
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['userFile']['name'] = $_FILES[$fieldname]['name'][$i];
                $_FILES['userFile']['type'] = $_FILES[$fieldname]['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES[$fieldname]['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES[$fieldname]['error'][$i];
                $_FILES['userFile']['size'] = $_FILES[$fieldname]['size'][$i];

//          If Real Path
                if ($get_real_name) {
                    $uploadData[$i]['file_realname'] = $_FILES['userFile']['name'];
                }
                $config['upload_path'] = $uploadpath;
                $config['allowed_types'] = $extensions;
                $config['encrypt_name'] = $ref_name;
                $config['max_size'] = $maxsize;

                $CI->load->library('upload', $config);
                $CI->upload->initialize($config);
                if ($CI->upload->do_upload('userFile')) {
                    $fileData = $CI->upload->data();
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    $uploadData[$i]['file_size'] = $_FILES['userFile']['size'];
                }
                $CI->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $uploadpath.$fileData['file_name'];
                $config['maintain_ratio'] = TRUE;
                $config['width']    = 600;
                $config['height']   = 600;
                $CI->image_lib->clear();
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();


            }
            $uploadData['success'] = true;
        }
        return $uploadData;
    }

    public function UploadFile($fieldname, $maxsize, $uploadpath, $get_real_name = true, $ref_name = true, $extensions=false,$isUpload= true){
        $CI =& get_instance();
        $filesCount = count($_FILES);
        if ($filesCount == 0) {
            return array(
                'success'   => false,
                'title'     => 'unsuccessful',
                'msg'       => 'Please upload a file');
        }

        $_FILES['userFile']['name'] = $_FILES[$fieldname]['name'];
        $_FILES['userFile']['type'] = $_FILES[$fieldname]['type'];
        $_FILES['userFile']['tmp_name'] = $_FILES[$fieldname]['tmp_name'];
        $_FILES['userFile']['error'] = $_FILES[$fieldname]['error'];
        $_FILES['userFile']['size'] = $_FILES[$fieldname]['size'];

        $file_extension = strtolower(pathinfo($_FILES['userFile']['name'], PATHINFO_EXTENSION));

        if ($extensions !== false && is_array($extensions)) {
            if (!in_array($file_extension, $extensions)) {
                return array('title' => $_FILES['userFile']['name'], 'success' => false, 'msg' => 'Please upload valid file');
            }
//           File Size Checking
            $file_size = $_FILES['userFile']['size'];
            if ($file_size > $maxsize) {
                return array('success' => false, 'title' => 'Error', 'msg' => 'File Exceeds maximum limit');
            }
        }
        $uploadData = array();
//          If Real Path
        if($isUpload == true) {
            if ($get_real_name) {
                $uploadData['file_realname'] = $_FILES['userFile']['name'];
            }
            $config['upload_path'] = $uploadpath;
            $config['allowed_types'] = $extensions;
            $config['encrypt_name'] = $ref_name;
            $config['max_size'] = $maxsize;

            $CI->load->library('upload', $config);
            $CI->upload->initialize($config);
            if ($CI->upload->do_upload('userFile')) {
                $fileData = $CI->upload->data();
                $uploadData['file_name'] = $fileData['file_name'];
                $uploadData['file_size'] = $_FILES['userFile']['size'];
            } else{
                $error = array('error' => $CI->upload->display_errors());
//                printArray($error,1);
            }
            $uploadData['success'] = true;
        }
        return $uploadData;
    }
}
