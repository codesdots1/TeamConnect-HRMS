<?php
/**
 * Created by PhpStorm.
 * User: dt-user04
 * Date: 18-07-2018
 * Time: 10:50
 */

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * General merge fields not linked to any features
 * @return array
 */
function getOtherMergeFields()
{
    $CI =& get_instance();
    $fields = array();
    $fields['{logo_url}'] = base_url('uploads/company/' . get_option('company_logo'));

    $logo_width = do_action('merge_field_logo_img_width', '');
    $fields['{logo_image_with_url}'] = '<a href="' . site_url() . '" target="_blank"><img src="' . base_url('uploads/company/' . get_option('company_logo')) . '"' . ($logo_width != '' ? ' width="' . $logo_width . '"' : '') . '></a>';

    $fields['{crm_url}'] = site_url();
    $fields['{admin_url}'] = admin_url();
    $fields['{main_domain}'] = get_option('main_domain');
    $fields['{companyname}'] = get_option('companyname');

    if (!is_staff_logged_in() || is_client_logged_in()) {
        $fields['{email_signature}'] = get_option('email_signature');
    } else {
        $CI->db->select('email_signature')->from('tblstaff')->where('staffid', get_staff_user_id());
        $signature = $CI->db->get()->row()->email_signature;
        if (empty($signature)) {
            $fields['{email_signature}'] = get_option('email_signature');
        } else {
            $fields['{email_signature}'] = $signature;
        }
    }

    $hook_data['merge_fields'] = $fields;
    $hook_data['fields_to'] = 'other';
    $hook_data['id'] = '';

    $hook_data = do_action('other_merge_fields', $hook_data);
    $fields = $hook_data['merge_fields'];

    return $fields;
}


/**
 * Merge field for customer members
 * @param  mixed $customer_id customer id
 * @return array
 */
function getCustomerMergeFields($customer_id)
{
    $fields = array();

    $CI =& get_instance();
    $CI->db->where('customer_id', $customer_id);
    $customer = $CI->db->get('tbl_customer')->row();

    $fields['{customer_fullname}'] = '';
    $fields['{customer_details}'] = '';
    $fields['{customer_type}'] = '';
    $fields['{customer_datecreated}'] = '';

    if (!$customer) {
        return $fields;
    }

    $fields['{customer_fullname}'] = $customer->full_name;
    $fields['{customer_details}'] = $customer->customer_details;
    $fields['{customer_type}'] = $customer->type;
    $fields['{customer_datecreated}'] = $customer->created_at;


//    $custom_fields = get_custom_fields('staff');
//    foreach ($custom_fields as $field) {
//        $fields['{' . $field['slug'] . '}'] = get_custom_field_value($customer_id, $field['id'], 'customer');
//    }

    return $fields;
}

/**
 * Merge field for quotation
 * @param  mixed $quotation_id quotation id
 * @return array
 */
function getQuotationMergeFields($quotation_id)
{
    $fields = array();

    $CI =& get_instance();
    $CI->load->model('Mdl_quotation');

    $quotation = $CI->Mdl_quotation->getQuotationData($quotation_id);

    $fields['{customer_fullname}'] = '';
    $fields['{customer_type}'] = '';
    $fields['{quotation_id}'] = '';
    $fields['{quotation_date}'] = '';
    $fields['{quotation_due_date}'] = '';
    $fields['{order_type}'] = '';
    $fields['{base_total}'] = '';
    $fields['{base_grand_total}'] = '';
    $fields['{base_discount_amount}'] = '';
    $fields['{quotation_datecreated}'] = '';
    $fields['{term_details}'] = '';

    if (!$quotation) {
        return $fields;
    }

    $fields['{customer_fullname}'] = $quotation['full_name'];
    $fields['{customer_type}'] = $quotation['customer_type'];
    $fields['{quotation_id}'] = $quotation['quotation_id'];
    $fields['{quotation_date}'] = date('d F, Y', strtotime($quotation['quotation_date']));
    $fields['{quotation_due_date}'] = date('d F, Y', strtotime($quotation['due_date']));
    $fields['{order_type}'] = $quotation['order_type'];
    $fields['{base_total}'] = $quotation['base_total'];
    $fields['{base_grand_total}'] = $quotation['base_grand_total'];
    $fields['{base_discount_amount}'] = $quotation['base_discount_amount'];
    $fields['{quotation_datecreated}'] = date('d F, Y', strtotime($quotation['created_at']));
    $fields['{term_details}'] = $quotation['term_details'];

    return $fields;
}


function getSalesOrderMergeFields($sales_order_id)
{
    $fields = array();

    $CI =& get_instance();
    $CI->load->model('Mdl_sales_order');

    $sales_order = $CI->Mdl_sales_order->getSalesOrderData($sales_order_id);

    $fields['{series}'] = '';
    $fields['{company_name}'] = '';
    $fields['{company_id}'] = '';
    $fields['{customer_fullname}'] = '';
//    $fields['{quotation_due_date}'] = '';
//    $fields['{order_type}'] = '';
//    $fields['{base_total}'] = '';
//    $fields['{base_grand_total}'] = '';
//    $fields['{base_discount_amount}'] = '';
//    $fields['{quotation_datecreated}'] = '';
//    $fields['{term_details}'] = '';

    if (!$sales_order) {
        return $fields;
    }

//    printArray($sales_order,1);

    $fields['{series}'] = $sales_order['series'];
    $fields['{company_name}'] = $sales_order['company_name'];
    $fields['{company_id}'] = $sales_order['company_id'];
    $fields['{customer_fullname}'] = $sales_order['full_name'];
//    $fields['{quotation_due_date}'] = date('d F, Y', strtotime($quotation['due_date']));
//    $fields['{order_type}'] = $quotation['order_type'];
//    $fields['{base_total}'] = $quotation['base_total'];
//    $fields['{base_grand_total}'] = $quotation['base_grand_total'];
//    $fields['{base_discount_amount}'] = $quotation['base_discount_amount'];
//    $fields['{quotation_datecreated}'] = date('d F, Y', strtotime($quotation['created_at']));
//    $fields['{term_details}'] = $quotation['term_details'];

    return $fields;
}
/**
 * @return array
 * All available merge fields for templates are defined here
 */
function getAvailableMergeFields()
{
    $available_merge_fields = array(
        'customer' => array(
            array(
                'name' => 'Customer Fullname',
                'key' => '{customer_fullname}',
                'available' => array(
                    'customer',
                    'quotation',
                    'sales_order',
                ),
            ),
            array(
                'name' => 'Customer Type',
                'key' => '{customer_type}',
                'available' => array(
                    'customer',
                    'quotation'
                ),
            ),
            array(
                'name' => 'Customer Details',
                'key' => '{customer_details}',
                'available' => array(
                    'customer',
                    'quotation'
                ),
            ),
            array(
                'name' => 'Customer Date Created',
                'key' => '{customer_datecreated}',
                'available' => array(
                    'customer',
                )
            )
        ),
        'quotation' => array(
            array(
                'name' => 'Quotation Id',
                'key' => '{quotation_id}',
                'available' => array(
                    'quotation',
                ),
            ),
            array(
                'name' => 'Quotation Date',
                'key' => '{quotation_date}',
                'available' => array(
                    'quotation',
                ),
            ),
            array(
                'name' => 'Quotation Due Date',
                'key' => '{quotation_due_date}',
                'available' => array(
                    'quotation',
                ),
            ),
            array(
                'name' => 'Order Type',
                'key' => '{order_type}',
                'available' => array(
                    'quotation'
                ),
            ),
            array(
                'name' => 'Base Total',
                'key' => '{base_total}',
                'available' => array(
                    'quotation',
                ),
            ),
            array(
                'name' => 'Base Discount Amount',
                'key' => '{base_discount_amount}',
                'available' => array(
                    'quotation',
                ),
            ),
            array(
                'name' => 'Base Grand Total',
                'key' => '{base_grand_total}',
                'available' => array(
                    'quotation',
                ),
            ),
            array(
                'name' => 'Term Details',
                'key' => '{term_details}',
                'available' => array(
                    'quotation',
                ),
            ),
            array(
                'name' => 'Quotation Date Created',
                'key' => '{quotation_datecreated}',
                'available' => array(
                    'quotation',
                )
            )
        ),
        'sales_order' => array(
            array(
                'name' => 'Series',
                'key' => '{series}',
                'available' => array(
                    'sales_order',
                ),
            ),
            array(
                'name' => 'Company Id',
                'key' => '{company_id}',
                'available' => array(
                    'sales_order',
                ),
            ),
            array(
                'name' => 'Company Name',
                'key' => '{company_name}',
                'available' => array(
                    'sales_order',
                ),
            )
        ),
    );
    return $available_merge_fields;
}

/**
 * Based on the template slug and email the function will fetch a template from database
 * The template will be fetched on the language that should be sent
 * @param  string $template_id
 * @return object
 */
function getEmailTemplateForSending($slug)
{
    $CI =& get_instance();
    $CI->load->model('Mdl_email_template');
    $template = $CI->Mdl_email_template->getEmailTemplateByType($slug);
    return $template;
}
/**
 * Based on the template slug and email the function will fetch a template from database
 * The template will be fetched on the language that should be sent
 * @param  string $template_id
 * @return object
 */
function getDocumentTemplateForSending($slug)
{
    $CI =& get_instance();
    $CI->load->model('Mdl_document');
    $template = $CI->Mdl_document->getDocumentTemplateByType($slug);
    return $template;
}

/**
 * This function will parse email template merge fields and replace with the corresponding merge fields passed before sending email
 * @param  object $template template from database
 * @param  array $mergeFields available merge fields
 * @return object
 */
function parseEmailTemplateMergeFields($template, $mergeFields)
{
//    printArray($mergeFields,1);
    foreach ($mergeFields as $key => $val) {
        if (stripos($template->email_message, $key) !== false) {
            $template->email_message = str_ireplace($key, $val, $template->email_message);
//            printArray($template,1);
        } else {

            $template->email_message = str_ireplace($key, '', $template->email_message);
        }
        if (stripos($template->subject_name, $key) !== false) {
            $template->subject_name = str_ireplace($key, $val, $template->subject_name);
        } else {
            $template->subject_name = str_ireplace($key, '', $template->subject_name);
        }
        if (stripos($template->from_name, $key) !== false) {
            $template->from_name = str_ireplace($key, $val, $template->from_name);
        } else {
            $template->from_name = str_ireplace($key, '', $template->from_name);
        }
    }

    return $template;
}