<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_common_model extends DT_CI_Model {

    /**
     * 	function insert(),update(),delete()
     * 	@return Either Array Or Object Or Integer
     * 	@author KAP
     * */
    public function insert($table, $data) {
        $this->db->insert($table, $data);
        $id = $this->db->insert_id(); // fetch last inserted id in table
        // $this->db->close();
        return $id;
    }

    public function update($table, $id = null, $data) {
        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where('id', $id);
        }
        $this->db->update($table, $data);
        $update_id = $this->db->affected_rows(); // fetch affected rown in table.
        // $this->db->close();
        return $update_id;
    }

    /**
     * Common delete function
     * @param type $id
     * @param type $table
     * @return boolean
     * @author : KAP
     */
    public function delete($id, $table) {
        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where(array('id' => $id));
        }
        $delete = $this->db->delete($table);
        // $this->db->close();
        return $delete;
    }

    /**
     * Common Delete_where function
     * @param type $id
     * @param type $table
     * @return boolean
     * @author : KAP
     */
    public function delete_wherein($field, $values, $table) {
        $this->db->where_in($field, $values);
        if ($this->db->delete($table)) {
            // $this->db->close();
            return TRUE;
        } else {
            // $this->db->close();
            return FALSE;
        }
    }

    /**
     * Common update_wherein function
     * @param type $id
     * @param type $table
     * @return boolean
     * @author : KAP
     */
    public function update_wherein($field, $values, $data, $table) {
        $this->db->where_in($field, $values);
        if ($this->db->update($table, $data)) {
            // $this->db->close();
            return TRUE;
        } else {
            // $this->db->close();
            return FALSE;
        }
    }

    /* 	Master function to select required data from DB
     *
     * 	@select =  String select statement e.g user.id or MAX(user.id)
     * 	@table = String name of the table
     * 	@where = mixed optional where condition
     * 			ex :
     * 				array( 
     * 					'where_in' => array('users.id' => 5);
     * 				)
     * 	@options = Array Optional conditions e.g order,join, group, limit, count, single
     * 	@Author - KAP
     */

    public function sql_select($table, $select = null, $where = null, $options = null) {
        if (!empty($select)) {
            $this->db->select($select, FALSE);
        }

        $this->db->from($table);

        /* Check wheather where conditions is required or not. */
        if (!empty($where)) {
            if (is_array($where)) {
                $check_where = array(
                    'where',
                    'or_where',
                    'where_in',
                    'or_where_in',
                    'where_not_in',
                    'or_where_not_in',
                    'like', 'or_like',
                    'not_like',
                    'or_not_like',
                    'having'
                );

                foreach ($where as $key => $value) {
                    if (in_array($key, $check_where)) {
                        foreach ($value as $k => $v) {
                            if (in_array($key, array('like', 'or_like', 'not_like', 'or_not_like'))) {
                                $check = 'both';
                                if ($v[0] == '%') {
                                    $check = 'before';
                                    $v = ltrim($v, '%');
                                } else if ($v[strlen($v) - 1] == '%') {
                                    $check = 'after';
                                    $v = rtrim($v, '%');
                                }
                                $this->db->$key($k, $v, $check, FALSE);
                            } else {
                                if ($key == 'having') {
                                    $this->db->$key($value[0]);
                                } else {
                                    $this->db->$key($k, $v);
                                }
                            }
                        }
                    }
                }
            } else {
                $this->db->where($where, '', FALSE);
            }
        }

        /* Check fourth parameter is passed and process 4th param. */
        if (!empty($options) && is_array($options)) {            
            $check_key = array('group_by', 'order_by');
            foreach ($options as $key => $value) {
                if (in_array($key, $check_key)) {
                    if (is_array($value)) {
                        foreach ($value as $k => $v) {
                            $this->db->$key($v);
                        }
                    } else {
                        $this->db->$key($value);
                    }
                }
            }
        }

        /* Check query needs to limit or not.  */
        if (isset($options['limit']) && !empty($options['limit']) && isset($options['offset']) && !empty($options['offset'])) {
            $this->db->limit($options['limit'], $options['offset']);
        } else if (isset($options['limit'])) {
            $this->db->limit($options['limit']);
        }

        /* Check tables need to join or not */
        if (isset($options['join']) && !empty($options['join'])) {
            foreach ($options['join'] as $join) {
                if (!isset($join['join']))
                    $join['join'] = 'left';
                $this->db->join($join['table'], $join['condition'], $join['join']);
            }
        }


        $method = 'result_array';
        /* Check wheather return only single row or not. */
        if (isset($options) && ((!is_array($options) && $options == true) || (isset($options['single']) && $options['single'] == true ))) {
            $method = 'row_array';
        }

        /* Check to return only count or full data */
        if (isset($options['count']) && $options['count'] == true) {
            $result = $this->db->count_all_results();
        } else {
            $result = $this->db->get()->$method();
        }
        // $this->db->close();
        return $result;
    }

    /**
     * Get Last Inserted Id
     * @param type $table
     * @return type
     * @author : KAP
     */
    public function getLastInsertId($table) {
        $insert_id = $this->db->insert_id($table);
        // $this->db->close();
        return $insert_id;
    }

    /**
     * @method: insert_multiple
     * @param : table, data
     * @uses : insert muliple records
     * @author : KAP
     */
    public function insert_multiple($table, $data) {
        if ($this->db->insert_batch($table, $data)) {
            // $this->db->close();
            return TRUE;
        } else {
            // $this->db->close();
            return FALSE;
        }
    }

    /**
     * Update batch - updates the multiple records
     * @param string $table - Name of the table
     * @param array $data - Data to be updated
     * @param string $field - Field to be used as condition
     * @author : KAP
     */
    public function update_multiple($table, $data, $field) {
        $this->db->update_batch($table, $data, $field);
        // $this->db->close();
    }

    public function custom_query($query) {
        $query = $this->db->query($query);
        // $this->db->close();
        return $query;
    }

}

/* End of file Common_model.php */
/* Location: ./application/models/Common_model.php */
