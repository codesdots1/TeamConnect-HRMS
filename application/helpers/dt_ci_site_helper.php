<?php
/**
 * Created by PhpStorm.
 * User: vashi
 * Date: 28-02-2017
 * Time: 12:09 AM
 */

if (!function_exists('printArray')) {
    function printArray($array, $die = false)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
        if ($die) {
            die();
        }
    }
}

if (!function_exists('printArrayTable')) {
    function printArrayTable($array, $die = false)
    {
//        echo "<pre>";
//        print_r($array);
//        echo "</pre>";

//        printArray($array, $die);
//        return;

        echo '<table id="dtReport" class="table table-striped w-full">
  <thead>
    <tr>
      <th>' . implode('</th><th>', array_keys(current($array))) . '</th>
</tr>
</thead>
<tbody>';
        foreach ($array as $row) {
            if (is_array($row)) {
                array_map('htmlentities', $row);
                echo '<tr>
        <td>' . implode('</td><td>', $row) . '</td>
</tr>';
            } else {
            }
        }
        echo '</tbody></table>';


        if ($die) {
            die();
        }
    }
}

if (!function_exists('insert_batch_string')) {
    function insert_batch_string($table, $data, $ignore = false)
    {
        $CI = &get_instance();
        $sql = '';

        if ($table && !empty($data)) {
            $rows = [];

            foreach ($data as $row) {
//                printArray($table);
//                printArray($row);
                $insert_string = $CI->db->insert_string($table, $row);
                if (empty($rows) && $sql == '') {
                    $sql = substr($insert_string, 0, stripos($insert_string, 'VALUES'));
                }
                $rows[] = trim(substr($insert_string, stripos($insert_string, 'VALUES') + 6));
            }

            $sql .= ' VALUES ' . implode(',', $rows);

            if ($ignore) $sql = str_ireplace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
        }
        return $sql;
    }
}

if (!function_exists('created_info_merge')) {
    function created_info_merge(&$data, $userId, $escape = false)
    {
        $data = array_map('trim', $data);
        $data = array_map('trim', $data);
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;
        if ($escape == true) {
            $data['created_at'] = "'" . date('Y-m-d H:i:s') . "'";
            $data['updated_at'] = "'" . date('Y-m-d H:i:s') . "'";
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
    }
}

if (!function_exists('updated_info_merge')) {
    function updated_info_merge(&$data, $userId, $escape = false)
    {
        $data = array_map('trim', $data);
        $data['updated_by'] = $userId;
        if ($escape == true) {
            $data['updated_at'] = "'" . date('Y-m-d H:i:s') . "'";
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
    }
}

if (!function_exists('get_log4php_configuration')) {
    function get_log4php_configuration($path = '')
    {

        return array(
            'rootLogger' => array(
                'appenders' => array('default'),
            ),
            'appenders' => array(
                'default' => array(
                    'class' => 'LoggerAppenderDailyFile',
                    'layout' => array(
                        'class' => 'LoggerLayoutPattern',
                        'params' => array(
                            'conversionPattern' => '%d{H:i:s,u} ET:%r Server:%server{REMOTE_ADDR}:%server{REMOTE_PORT} [%p] %m%n',
                        )
                    ),
                    'params' => array(
                        'file' => APPPATH . '/DT_logs/' . $path . '/log_%s.log',
                        'append' => true,
                        'datePattern' => 'Y-m-d',
                    )
                )
            )
        );

    }
}

if (!function_exists('DMYToYMD')) {
    function DMYToYMD($date, $show_his = false, $hour24format = false)
    {
        if ($hour24format) {
            return ($show_his) ? date('Y-m-d H:i:s', strtotime($date)) : date('Y-m-d', strtotime($date));
        } else {
            return ($show_his) ? date('Y-m-d h:i:s A', strtotime($date)) : date('Y-m-d', strtotime($date));
        }
    }
}

if (!function_exists('YMDToDMY')) {
    function YMDToDMY($date, $show_his = false, $hour24format = false)
    {
        if ($hour24format) {
            return ($show_his) ? date('d-m-Y H:i:s', strtotime($date)) : date('d-m-Y', strtotime($date));
        } else {
            return ($show_his) ? date('d-m-Y h:i:s A', strtotime($date)) : date('d-m-Y', strtotime($date));
        }
    }
}

if (!function_exists('character_limiter')) {
    function character_limiter($string, $end)
    {
        return substr($string, 0, $end);
    }
}

if (!function_exists('numberDropDown')) {
    function numberDropDown($number = 10, $selected = "")
    {
        $options = '';
        for ($i = 1; $i <= $number; $i++) {
            if (is_array($selected) and count($selected) > 0) {
                $optionSelected = (in_array($i, $selected)) ? "selected" : "";
            } else {
                $optionSelected = ($i == $selected) ? "selected" : "";
            }
            $options .= '<option ' . $optionSelected . ' value="' . $i . '">' . $i . '</option>';
        }
        return $options;
    }
}

if (!function_exists('CreateOptionFromEnumValues')) {
    function CreateOptionFromEnumValues($optionArray, $selected = "")
    {
        $options = '';
        foreach ($optionArray as $key => $value) {
            if (is_array($selected) and count($selected) > 0) {
                $optionSelected = (in_array($value, $selected)) ? "selected" : "";
            } else {
                $optionSelected = ($value == $selected) ? "selected" : "";
            }
            $options .= '<option value="' . $value . '" ' . $optionSelected . ' >' . ucwords($value) . '</option>';
        }
        return $options;
    }
}

if (!function_exists('time_elapsed_string')) {
//times now
    function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

if (!function_exists('get_enum_values')) {
    function get_enum_values($table, $field)
    {
        $CI =& get_instance();
        $type = $CI->db->query("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'")->row(0)->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }

}

if (!function_exists('rssToTime')) {

    function rssToTime($rss_time)
    {
        $char = substr($rss_time, 6, 1);
        $rss_time = ($char == ' ') ? substr_replace($rss_time, "0", 5, 0) : $rss_time;
        $day = substr($rss_time, 5, 2);
        $month = substr($rss_time, 8, 3);
        $month = date('m', strtotime("$month 1 2011"));
        $year = substr($rss_time, 12, 4);
        $hour = substr($rss_time, 17, 2);
        $min = substr($rss_time, 20, 2);
        $second = substr($rss_time, 23, 2);
        $timezone = substr($rss_time, 26);
        $timestamp = @mktime($hour, $min, $second, $month, $day, $year);
        return $timestamp;
    }
}

if (!function_exists('checkAndCreatePath')) {

    function checkAndCreatePath($path)
    {
        $dirs = null;
        if (strpos($path, '/')) {
            $dirs = explode("/", $path);
        }

        if (strpos($path, '\\')) {
            $dirs = explode("\\", $path);
        }

        $new_path = '';
        if (isset($dirs) && $dirs != null) {
            for ($i = 0; $i < count($dirs); $i++) {
                $new_path .= ($dirs[$i] . "/");
                if (!file_exists($new_path)) {
                    if (!mkdir($new_path, 0777, true)) {
                        die('Failed to create folders...');
                    }
                }
            }
        }
    }
}

if (!function_exists('viewCounter')) {

    function viewCounter($type, $type_id)
    {

        $CI =& get_instance();
        $CI->load->database();
        $CI->load->library('DT_ci_browser_os');
        $os_br = new $CI->dt_ci_browser_os();
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $platform = $os_br->showInfo('os');
        $browser = $os_br->showInfo('browser');
        $device_type = '';


        $CI->db->where('type_for', $type);
        $CI->db->where('type_id', $type_id);
        $query = $CI->db->get('tbl_total_view');
        $data = $query->result_array();
        $cookie_name = "user_view_count_data5";
        $type_array['type_for'] = array();
        $type_array['type_id'] = array();
        // 86400 = 1 day
        if (!isset($_COOKIE[$cookie_name])) {
            //echo "Cookie named '" . $cookie_name . "' is not set!";
            $type_array['type_for'][] = $type;
            $type_array['type_id'][] = $type_id;
            $cookie_value = json_encode($type_array);
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            if (count($data) == 0) {
                $data1 = array('type_for' => $type, 'type_id' => $type_id, 'total_unique_visit' => 1, 'total_visit' => 1);
                $CI->db->insert('tbl_total_view', $data1);
            } else {
                //print_r($data);
                $totalUniqueVisit = $data[0]['total_unique_visit'] + 1;
                $totalVisit = $data[0]['total_visit'] + 1;
                $data2 = array('total_unique_visit' => $totalUniqueVisit, 'total_visit' => $totalVisit);
                $CI->db->where('type_for', $type);
                $CI->db->where('type_id', $type_id);
                $CI->db->update('tbl_total_view', $data2);
            }

        } else {
            //echo "Cookie '" . $cookie_name . "' is set!<br>";
            //echo "Value is: " . $_COOKIE[$cookie_name];
            //$type_array=array();
            $type_array = json_decode($_COOKIE[$cookie_name], true);
            $flag = true;
            for ($i = 0; $i < count($type_array['type_for']); $i++) {
                if ($type_array['type_for'][$i] == $type && $type_array['type_id'][$i] == $type_id) {
                    $flag = false;
                }
            }

            if ($flag) {
                $type_array['type_for'][$i] = $type;
                $type_array['type_id'][$i] = $type_id;
                $cookie_value = json_encode($type_array);
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
                if (count($data) == 0) {
                    $data1 = array('type_for' => $type, 'type_id' => $type_id, 'total_unique_visit' => 1, 'total_visit' => 1);
                    $CI->db->insert('tbl_total_view', $data1);
                } else {
                    //print_r($data);
                    $totalUniqueVisit = $data[0]['total_unique_visit'] + 1;
                    $totalVisit = $data[0]['total_visit'] + 1;
                    $data2 = array('total_unique_visit' => $totalUniqueVisit, 'total_visit' => $totalVisit);
                    $CI->db->where('type_for', $type);
                    $CI->db->where('type_id', $type_id);
                    $CI->db->update('tbl_total_view', $data2);
                }
            }
        }

        if (count($data) > 0) {
            $totalVisit = $data[0]['total_visit'] + 1;
            $data2 = array('total_visit' => $totalVisit);
            $CI->db->where('type_for', $type);
            $CI->db->where('type_id', $type_id);
            $CI->db->update('tbl_total_view', $data2);
        }

        $data5 = array('type_for' => $type, 'type_id' => $type_id, 'user_ip' => $user_ip, 'platform' => $platform, 'browser' => $browser, 'device_type' => $device_type, 'created_by' => $CI->data['userId'], 'created_at' => date('Y-m-d h:i:s', time()));

        $CI->db->insert('tbl_page_view', $data5);
        //print_r($data);
        //print_r($type_array);
    }
}

if (!function_exists('create_slug')) {
    function create_slug($id, $title, $id_name, $table_name)
    {
        $CI =& get_instance();
        $config = array(
            'table' => $table_name,
            'id' => $id_name,
            'field' => 'slug',
            'title' => 'slug',
            'replacement' => 'dash');
        $CI->load->library('slug', $config);

        $data = array(
            'slug' => $title,
        );
        $data['slug'] = $CI->slug->create_uri($data, $id);
        $CI->db->where($id_name, $id);
        $CI->db->update($table_name, $data);

    }
}

//if (!function_exists('sendNotificationAndroid')) {
//    function sendNotificationAndroid($dataArr, $data)
//    {
//
//        $fcmApiKey = 'AIzaSyClesktPp5vuIaU4BKMBSZo_bX9_-R9ti4';//App API Key (This is google cloud messaging api key not web api key)
//
//        $url = 'https://fcm.googleapis.com/fcm/send';//Google URL
//
//        $registrationIds = is_array($dataArr['device_id']) ? $dataArr['device_id'] : 0;//Fcm Device ids array
//
//        $message = $data;//Message which you want to send
//        $title = $data;
//
//
//        ///echo "<pre>"; print_r($message);
//
//        // prepare the bundle
//        $msg = array('message' => $message, 'title' => $title);
//        $fields = array('registration_ids' => $registrationIds, 'data' => $msg);
//
//        $headers = array(
//            'Authorization: key=' . $fcmApiKey,
//            'Content-Type: application/json'
//        );
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//        $result = curl_exec($ch);
//        // Execute post
//        //$result = curl_exec($ch);
//
//        //  print_r($result);
//        if ($result === FALSE) {
//            die('Curl failed: ' . curl_error($ch));
//        }
//        // Close connection
//        curl_close($ch);
//
//        return $result;
//    }
//
//}

//START: Send notification using one signal
function sendNotificationAndroid($data){
    if($data['notification_type'] == 'general') {

        $content = array(
            "en" => $data['notification_title']
        );
        $fields = array(
            'app_id' => "d5be69c3-6a4d-454e-81ba-5348d5a352e5",
            'included_segments' => array('All'),
            'data' => array(
				'notification_id'=> $data['notification_id'],
                'notification_type'=> $data['notification_type']
			),
			'contents' => $content
        );


    } else if ($data['notification_type'] == 'event') {
        $content = array(
            "en" => $data['notification_message']
        );
        $fields = array(
            'app_id' => "d5be69c3-6a4d-454e-81ba-5348d5a352e5",
            'included_segments' => array('All'),
            'data' => array(
                'event_id' => $data['event_id'],
                'notification_type'=>$data['notification_type']
            ),
            'contents' => $content
        );
    } else if ($data['notification_type'] == 'post') {
		$content = array(
			"en" => $data['notification_message']
		);
		$fields = array(
			'app_id' => "d5be69c3-6a4d-454e-81ba-5348d5a352e5",
			'included_segments' => array('All'),
			'data' => array(
				'post_id' => $data['post_id'],
				'notification_type' => $data['notification_type']
			),
			'contents' => $content
		);
	} else if ($data['notification_type'] == 'poll') {

		$content = array(
			"en" => $data['notification_message']
		);
		$fields = array(
			'app_id' => "d5be69c3-6a4d-454e-81ba-5348d5a352e5",
			'included_segments' => array('All'),
			'data' => array(
				'poll_id' => $data['poll_id'],
				'notification_type' => $data['notification_type']
			),
			'contents' => $content
		);
	}
	else {

        exit();
    }

    $fields = json_encode($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic NDJjNDMxNWYtN2I0NC00NTI1LThjMjMtYjA0YWZjZTQ3NjM1'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
//END: Send notification using one signal

//if (!function_exists('sendNotificationIos')) {
//    function sendNotificationIos($dataArr, $data)
//    {
//        include_once APPPATH . "ApnsPHP/Autoload.php";
//
//        $deviceToken = is_array($dataArr['device_id']) ? $dataArr['device_id'] : 0;
//        $setMessage = isset($data) ? $data : 'Default Message Here';
//
//        $apnsFile = FCPATH . 'uploads/Apns/UserPush1.pem';
//
//        $push = new ApnsPHP_Push(
//            ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
//            $apnsFile
//        );
//
//
//        $push->connect();
//
//        $message = new ApnsPHP_Message();
//
//        $message->addRecipient($deviceToken);
//        $message->setText($setMessage);
//        $message->setCustomProperty("data", $dataArr['message']);
//
//        $message->setSound();
//
//        $push->add($message);
//        $push->send();
//        $push->disconnect();
//        $aErrorQueue = $push->getErrors();
//        //Log::info('provider Details:', $aErrorQueue);
//
//        //    print_r($aErrorQueue);
//        return $aErrorQueue;
//    }
//}

if (!function_exists('CreateOptions')) {
    /**
     * @param $format
     * @param $table
     * @param $columns
     * @param null $selected
     * @param null $sort_by insert array
     * @param null $whereCondition
     * @param null $whereInCondition
     * @param null $limit
     * @param null $group_by
     * @return mixed
     */

    function CreateOptions($format, $table, $columns, $selected = null, $sort_by = null, $whereCondition = null, $limit = null, $group_by = null)
    {
//        printArray($whereCondition);
        $columsArray = $columns;
        $CI = &get_instance();

        $columns = is_array($columns) ? implode(", ", $columns) : $columns;

        if ($whereCondition == null && $whereCondition == '' && !is_array($whereCondition)) {
            $whereCondition = "1=1";
        }


        if ($sort_by != null && $sort_by != '' && is_array($sort_by)) {
            if (count($sort_by) > 0) {
                foreach ($sort_by as $key => $value) {
                    $CI->db->order_by($key, $value);
                }
            }
        }


        if ($group_by != '' && $group_by != null) {

            $CI->db->group_by($group_by);
        }

        if (is_array($limit) && $limit != null && $limit != '') {
            var_dump($limit);
            $CI->db->limit($limit[0], $limit[1]);
        }

        $CI->db->select($columns);
        $CI->db->from($table);
        $CI->db->where($whereCondition);
      //  $CI->db->or_where_in($columsArray[0], $selected);

        //  if(!empty($orWhereCondition)) {
        //   $CI->db->or_where_in($columsArray[0],$orWhereCondition[$columsArray[0]]);
        // }


        $option_result = $CI->db->get();
        echo $CI->db->last_query();

        $options = CreateOptionsFromResultSet($option_result, $format, $selected);
        return $options;
    }
}

if (!function_exists('ClearTextAreaBreaks')) {
    /**
     * @param $message
     * @return mixed
     */
    function ClearTextAreaBreaks($message)
    {
        $message = preg_replace('#<br\s*/?>#i', "\n", $message);
        return $message;
    }
}

if (!function_exists('CreateOptionsFromResultSet')) {
    /**
     * @param $option_result
     * @param string $format
     * @param null $selected
     * @return array|string
     */
    function CreateOptionsFromResultSet($option_result, $format = 'html', $selected = null)
    {

        $options = array();
        $columns = array();

//        printArray($options,1);

        $fields = $option_result->field_data();

        foreach ($fields as $key => $fieldData) {
            $columns[$key] = $fieldData->name;
        }

        $optionArray = $option_result->result_array();
        foreach ($optionArray as $key => $option_data) {
            $option_data[$columns[1]] = stripslashes($option_data[$columns[1]]);

            switch ($format) {
                case "array":
                    $options[$option_data[$columns[0]]] = $option_data[$columns[1]];
                    break;
                case "json":
                    $options[] = array(
                        "$columns[0]" => $option_data[$columns[0]],
                        "$columns[1]" => $option_data[$columns[1]],
                    );
                    break;
                default:
                case "html":
                    if (!is_null($selected)) {

                        if (is_array($selected)) {
                            $options[] = (in_array($option_data[$columns[0]], $selected)) ? "<option value='{$option_data[$columns[0]]}' selected='selected'>{$option_data[$columns[1]]}</option>" : "<option value='{$option_data[$columns[0]]}'>{$option_data[$columns[1]]}</option>";
                        } else {
                            $options[] = ($option_data[$columns[0]] == $selected) ? "<option value='{$option_data[$columns[0]]}' selected='selected'>{$option_data[$columns[1]]}</option>" : "<option value='{$option_data[$columns[0]]}'>{$option_data[$columns[1]]}</option>";
                        }
                    } else {
                        $options[] = "<option value='{$option_data[$columns[0]]}'>{$option_data[$columns[1]]}</option>";
                    }
                    break;
            }
        }
        switch ($format) {
            case "array":
                $options = $options;
                break;
            case "json":
                $options = json_encode($options);
                break;
            default:
            case "html":
                $options = implode("", $options);
                break;
        }
        return $options;

    }
}

if (!function_exists('enumValues')) {
    /**
     * @param string $table
     * @param string $field
     * @return array
     */
    function enumValues($table = '', $field = '')
    {
        $enums = array();
        if ($table == '' || $field == '') return $enums;
        $CI =& get_instance();
        preg_match_all("/'(.*?)'/", $CI->db->query("SHOW COLUMNS FROM {$table} LIKE '{$field}'")->row()->Type, $matches);
        foreach ($matches[1] as $key => $value) {
            $enums[$value] = $value;
        }
        return $enums;
    }
}

if (!function_exists('DisplayMessage')) {
    function DisplayMessage($title, $message, $class = 'danger')
    {
        $errorHtml = '<div class="panel panel-white"><div class="alert alert-' . $class . ' alert-styled-left">
									<button type="button" class="close" data-dismiss="alert"></button>
									<span class="text-semibold">' . $title . '</span>' . $message . '
							    </div></div>';
        return $errorHtml;
    }
}

if (!function_exists('googleRecaptcha')) {
    function googleRecaptcha($secret, $userIp, $recaptcha)
    {
        $data = array(
            'secret' => $secret,
            'response' => $recaptcha,
            'remoteip' => $userIp
        );

        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($verify);
        $status = json_decode($responseData, true);
        return $status;
    }
}

if (!function_exists('GenRandomNumber')) {
    function GenRandomNumber($length)
    {
        $characters = "123456789";
        $string = '';
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }
}
if (!function_exists('GenRandomAlphaNumeric')) {
    function GenRandomAlphaNumeric($length)
    {
        $characters = "123456789abcdefghijklmnopqrstuvwxyz";
        $string = '';
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }
}

if (!function_exists('DateRangeOverlapCheck')) {
    function DateRangeOverlapCheck($array)
    {
        $html = "given is not array";
        if (is_array($array)) {
            foreach ($array as $firstArrayKey => $firstArrayValue) {
                $firstCount = 0;
                $firstDateExplode = explode(" - ", $firstArrayValue);
//                printArray("1 : ".$firstDateExplode[0]." --> ".$firstDateExplode[1]);
                $firstStartDate = date('Y-m-d', strtotime($firstDateExplode[0]));
                $firstEndDate = date('Y-m-d', strtotime($firstDateExplode[1] . " + 1 day"));
                while ($firstStartDate != $firstEndDate) {
//                    printArray("1 : ".$firstStartDate);
                    $secondArray = array_except($array, $firstArrayKey);
//                    printArray($secondArray);
                    foreach ($secondArray as $secondArrayKey => $secondArrayValue) {
                        $secondCount = 0;
//                        if ($firstArrayKey != $secondArrayKey) {
                        $secondDateExplode = explode(" - ", $secondArrayValue);
//                        printArray("2 : ".$secondDateExplode[0]." --> ".$secondDateExplode[1]);
                        $secondStartDate = date('Y-m-d', strtotime($secondDateExplode[0]));
                        $secondEndDate = date('Y-m-d', strtotime($secondDateExplode[1] . " + 1 day"));
                        while ($secondStartDate != $secondEndDate) {
                            if ($firstStartDate == $secondStartDate) {
                                $html = "Range is overlapping.";
                                return $html;
                                die();
                            } else {
//                                    printArray("2 : " . $secondStartDate);
                            }
                            $secondCount++;
                            $secondStartDate = date('Y-m-d', strtotime($secondDateExplode[0] . "+ " . $secondCount . " days"));
                        }
//                        }
                    }
                    $firstCount++;
                    $firstStartDate = date('Y-m-d', strtotime($firstDateExplode[0] . "+ " . $firstCount . " days"));
                }
            }
            return true;
        } else {
            return $html;
        }
    }
}

if (!function_exists('array_except')) {
    function array_except($array, $keys)
    {
        return array_diff_key($array, array_flip((array)$keys));
    }
}


/*
 *
 * @Create an HTML drop down menu
 *
 * @param string $name The element name and ID
 *
 * @param int $selected The day to be selected
 *
 * @return string
 *
 */
if (!function_exists('dayDropdown')) {
    function dayDropdown($selected = null)
    {
        $wd = '';
        $days = array(
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday');

        for ($i = 1; $i <= count($days); $i++) {
            if (is_array($selected) and count($selected) > 0) {
                $optionSelected = (in_array($i, $selected)) ? "selected" : "";
            } else {
                $optionSelected = ($i == $selected) ? "selected" : "";
            }
            $wd .= '<option ' . $optionSelected . ' value="' . $i . '">' . $days[$i] . '</option>';
        }

        return $wd;
    }
}
if (!function_exists('pdf_generate')) {

    function pdf_generate($filePath, $type, $content)
    {
        $CI =& get_instance();

        $CI->load->library('DT_ci_Pdf');

        $pdf = new DT_ci_Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetTitle($type);

        $pdf->SetHeaderMargin(30);

        $pdf->SetTopMargin(10);

        $pdf->setFooterMargin(20);

        $pdf->SetAutoPageBreak(true);

        $pdf->SetAuthor('DigiTattva Technolabs');

        $pdf->SetDisplayMode('real', 'default');

        $pdf->AddPage();

        ob_clean();

        $pdf->writeHTML($content, true, false, true, false, '');

        $pdf->Output($filePath, 'F');
    }
}

if (!function_exists('logActivity')) {
    /**
     * Log Activity for everything
     * @param  string $description Activity Description
     * @param  integer $staffid Who done this activity
     */
    function logActivity($description, $staffid = null, $logModule = null)
    {
        $CI =& get_instance();
        $CI->load->library('DT_ci_browser_os');
        $os_br = new $CI->dt_ci_browser_os();
        $user_ip = $CI->input->ip_address();
        $platform = $os_br->showInfo('os');
        $browser = $os_br->showInfo('browser');
        $device_type = '';

        $sessionSamajLogId = $CI->session->userdata('samaj_id');

        $log = array(
            'description'   => $description,
            'date'          => date('Y-m-d H:i:s'),
            'user_browser'  => $browser,
            'user_platform' => $platform,
            'user_ip'       => $user_ip,
            'Module'        => $logModule,
            'samaj_id'      => $sessionSamajLogId
        );

        if (!DEFINED('CRON')) {
            if ($staffid != null && is_numeric($staffid)) {
                $log['staff_id']  = $staffid ;
            } else {

                $log['staff_id'] = '[CRON]';

            }
        } else {
            // manually invoked cron
            if (isset($staffid)) {
                $log['staff_id'] = $staffid;
            } else {
                $log['staff_id'] = '[CRON]';
            }
        }

        $CI->db->insert('tbl_activity_log', $log);
    }
}

if (!function_exists('generate_pdf')) {
    /**
     * Log Activity for everything
     * @param  string $description Activity Description
     * @param  integer $staffid Who done this activity
     */
    function generate_pdf($content, $name = 'download.pdf', $output_type = null, $footer = null, $margin_bottom = null, $header = null, $margin_top = null, $orientation = 'P')
    {
        $CI =& get_instance();
        $CI->load->library('dt_mpdf', '', 'pdf');

        return $CI->pdf->generate($content, $name, $output_type, $footer, $margin_bottom, $header, $margin_top, $orientation);
    }
}

if (!function_exists('decode_html')) {

    function decode_html($str)
    {
        return html_entity_decode($str, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }
}

if (!function_exists('checkSamajIdInSession')) {
    function checkSamajIdInSession($samajId){
        $CI =& get_instance();
        if(!$CI->ion_auth->is_admin()) {
            $sessionSamajId = $CI->session->userdata('samaj_id');
            $sessionSamajIdArray = explode(',', $sessionSamajId);
            if (in_array($samajId, $sessionSamajIdArray)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}

if (!function_exists('sendSms')) {
	 function sendSms($recipients, $message){
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
					'authkey' => "2992AOWGIwKQUbe573db995",
					'mobiles' => $mobileNo,
					'message' => "Your Verification Code Is ".$message . " For Your Community Application Login.",
					'sender' => "DTATVA",
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

		} catch (Exception $e) {

		}
	}
}


if (!function_exists('writeCsvZipExport')) {
    function writeCsvZipExport($name, $headerArr, $dataRows){
        $zipname = $name . '.zip';
        list($fileName, $file) = explode("_", $name);
        $fileName = "uploads/" . $fileName;
        if (!file_exists($fileName)) {
            mkdir($fileName, 0, 777);
        }
        $zippath = $fileName . "/" . $zipname;
        $zip = new ZipArchive;
        $zip->open($zippath, ZipArchive::CREATE);

        $fd = fopen('php://temp/maxmemory:1048576', 'w');
        if (false === $fd) {
            die('Failed to create temporary file');
        }

        //$out = fopen('php://output', 'w');
        fwrite($fd, "\xEF\xBB\xBF");
        fputcsv($fd, $headerArr);

        foreach ($dataRows as $rowElem) {
            fputcsv($fd, $rowElem);
        }
        rewind($fd);
        $zip->addFromString($name . '.csv', stream_get_contents($fd));
        //close the file
        fclose($fd);
        $zip->close();
        return $file = site_url() . $zippath;

//        header('Content-Type: application/zip');
//        header('Content-disposition: attachment; filename='.$zipname);
//        header('Content-Length: ' . filesize($zippath));
//        return readfile($zippath);

        // remove the zip archive
        // you could also use the temp file method above for this.
        //unlink($zipname);
    }
}


