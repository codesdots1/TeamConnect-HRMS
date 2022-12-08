<?php
/**
 * Created by PhpStorm.
 * User: Digitattva
 * Date: 30-5-2018
 * Time: 12:09 AM
 */

    /**
     * Check if field is used in table
     * @param  string  $field column
     * @param  string  $table table name to check
     * @param  integer  $id   ID used
     * @return boolean
     */
if (!function_exists('is_reference_in_table')) {
    function is_reference_in_table($field, $table, $id)
    {
        $CI =& get_instance();
        $CI->db->where_in($field, $id);
        $row = $CI->db->get($table)->row();
        if ($row) {

            $logColumn = (isset($field)) && is_array($field) ? ucwords(str_replace("_","",$field)) : "";
            $logModule = (isset($table) && $table != '') ? ucwords(str_replace("_"," ",str_replace("tbl_","",$table))) : "";

            $ids = is_array($id) ? implode(',',$id) : $id;
            logActivity("Tried to delete ".$logModule."[".$logColumn.": " . $ids . "]",$CI->data['userId'],$logModule);
            return true;
        }

        return false;
    }
}


if(!function_exists('accessFilter')){
    function accessFilter($dbObjRef, $moduleFieldArray){
        $CI = &get_instance();
        if (!$CI->ion_auth->is_admin()) {
            $samajId = $CI->session->userdata('samaj_id');
            if (is_array($moduleFieldArray) && count($moduleFieldArray) > 0) {
                foreach ($moduleFieldArray as $module => $fieldId) {
                    if ($module == 'samaj') {
                        $dbObjRef->where_in($fieldId, explode(",", $samajId));
                    }
                }
            }
        }
    }
}

/**
 * Function that add views tracking for proposals,estimates,invoices,knowledgebase article in database.
 * This function tracks activity only per hour
 * Eq customer viewed invoice at 15:00 and then 15:05 the activity will be tracked only once.
 * If customer view the invoice again in 16:01 there will be activity tracked.
 * @param string $rel_type
 * @param mixed $rel_id
 */
if(!function_exists('addViewsTracking')){
    function addViewsTracking($rel_type, $rel_id)
    {
        $CI =& get_instance();
        if (!is_staff_logged_in()) {
            $CI->db->where('rel_id', $rel_id);
            $CI->db->where('rel_type', $rel_type);
            $CI->db->order_by('id', 'DESC');
            $CI->db->limit(1);
            $row = $CI->db->get('tblviewstracking')->row();
            if ($row) {
                $dateFromDatabase = strtotime($row->date);
                $date1HourAgo     = strtotime("-1 hours");
                if ($dateFromDatabase >= $date1HourAgo) {
                    return false;
                }
            }
        } else {
            // Staff logged in, nothing to do here
            return false;
        }

        do_action('before_insert_views_tracking', array(
            'rel_id' => $rel_id,
            'rel_type' => $rel_type,
        ));

        $notifiedUsers = array();
        $members = array();
        $notification_data = array();
        if ($rel_type == 'invoice' || $rel_type == 'proposal' || $rel_type == 'estimate') {
            $responsible_column = 'sale_agent';

            if ($rel_type == 'invoice') {
                $table = 'tblinvoices';
                $notification_link = 'invoices/list_invoices/' . $rel_id;
                $notification_description = 'not_customer_viewed_invoice';
                array_push($notification_data, format_invoice_number($rel_id));
            } elseif ($rel_type == 'estimate') {
                $table = 'tblestimates';
                $notification_link = 'estimates/list_estimates/' . $rel_id;
                $notification_description = 'not_customer_viewed_estimate';
                array_push($notification_data, format_estimate_number($rel_id));
            } else {
                $responsible_column = 'assigned';
                $table = 'tblproposals';
                $notification_description = 'not_customer_viewed_proposal';
                $notification_link = 'proposals/list_proposals/' . $rel_id;
                array_push($notification_data, format_proposal_number($rel_id));
            }

            $notification_data = serialize($notification_data);

            $CI->db->select('addedfrom,'.$responsible_column)
                ->where('id', $rel_id);

            $rel = $CI->db->get($table)->row();

            $CI->db->select('staffid')
                ->where('staffid', $rel->addedfrom)
                ->or_where('staffid', $rel->{$responsible_column});

            $members = $CI->db->get('tblstaff')->result_array();
        }

        $CI->db->insert('tblviewstracking', array(
            'rel_id' => $rel_id,
            'rel_type' => $rel_type,
            'date' => date('Y-m-d H:i:s'),
            'view_ip' => $CI->input->ip_address(),
        ));

        $view_id = $CI->db->insert_id();
        if ($view_id) {
            foreach ($members as $member) {
                $notification = array(
                    'fromcompany' => true,
                    'touserid' => $member['staffid'],
                    'description' => $notification_description,
                    'link' => $notification_link,
                    'additional_data' => $notification_data,
                );
                if (is_client_logged_in()) {
                    unset($notification['fromcompany']);
                }
                $notified = add_notification($notification);
                if ($notified) {
                    array_push($notifiedUsers, $member['staffid']);
                }
            }
            pusher_trigger_notification($notifiedUsers);
        }
    }
}

/**
 * Helper function to get all announcements for user
 * @param  boolean $staff Is this client or staff
 * @return array
 */
function get_announcements_for_user($staff = true)
{
    if (!is_logged_in()) {
        return array();
    }

    $CI =& get_instance();
    $CI->db->select();

    if ($staff == true) {
        $CI->db->where('announcementid NOT IN (SELECT announcementid FROM tbldismissedannouncements WHERE staff=1 AND userid = ' . get_staff_user_id() . ') AND showtostaff = 1');
    } else {
        $contact_id = get_contact_user_id();
        if (!is_client_logged_in()) {
            return array();
        }

        if ($contact_id) {
            $CI->db->where('announcementid NOT IN (SELECT announcementid FROM tbldismissedannouncements WHERE staff=0 AND userid = ' . $contact_id . ') AND showtousers = 1');
        } else {
            return array();
        }
    }
    $CI->db->order_by('dateadded','desc');
    $announcements = $CI->db->get('tblannouncements');
    if ($announcements) {
        return $announcements->result_array();
    } else {
        return array();
    }
}


