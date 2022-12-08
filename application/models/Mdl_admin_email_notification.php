<?php
/**
 * Created by PhpStorm.
 * User: dt-user09
 * Date: 3/19/2019
 * Time: 12:36 PM
 */

class Mdl_admin_email_notification extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function saveNotification($notificationData = array(),$notificationId = 0){
		if($notificationId == 0){
			$status = $this->db->insert('tbl_admin_email_notification',$notificationData);
		}else{
			$this->db->where('notification_id', $notificationId);
			$status = $this->db->update('tbl_admin_email_notification',$notificationData);
		}
		return $status;
	}
	
	public function checkTodayEntry(){
		$this->db->select("*");
		$this->db->from("tbl_admin_email_notification");
		$this->db->where('date',date('Y-m-d')); 
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
}
