<?php

class Mdl_fileuploads extends CI_Model
{
    public function saveData($data){
        $this->db->insert("tbl_file_uploads", $data);
    }

    public function saveAutoUploadData($data){
        $this->db->insert("tbl_auto_uploads", $data);

        return $this->db->insert_id();
    }

    public function getFileUpload($id){
        $this->db->select('*');

        $this->db->where('id', $id);
        $res = $this->db->get('tbl_file_uploads');

        return $res->first_row('array');
    }

    public function getPastRecords($months){
        $this->db->select('*');

        $this->db->where("start_time <= (CURDATE() - interval $months month)");

        $res = $this->db->get('tbl_file_uploads');

        return $res->result_array();
    }

    public function cleanup($months){

        $this->db->delete('tbl_file_uploads', "start_time <= (CURDATE() - interval $months month)");
        $this->db->delete('tbl_replenishment_report', "transaction_date <= (CURDATE() - interval $months month)");
        $this->db->delete('tbl_replenishment_report_csd', "transaction_date <= (CURDATE() - interval $months month)");
        $this->db->delete('tbl_hawk_eye', "transaction_date <= (CURDATE() - interval $months month)");
        $this->db->delete('tbl_transactions', "transaction_date <= (CURDATE() - interval $months month)");
        $this->db->delete('tbl_warehouse_order', "transaction_date <= (CURDATE() - interval $months month)");
        $this->db->delete('tbl_warehouse_order_items', "transaction_date <= (CURDATE() - interval $months month)");
        $this->db->delete('tbl_dashboard', "transaction_date <= (CURDATE() - interval $months month)");

//        echo $this->db->last_query();
//        die();

    }
}

?>