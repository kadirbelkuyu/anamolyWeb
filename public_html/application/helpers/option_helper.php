<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Count total rows on table based on params
 * @param  string $table Table from where to count
 * @param  array  $where
 * @return mixed  Total rows
 */
function total_rows($table, $where = array())
{
    $CI =& get_instance();
    if (is_array($where)) {
        if (sizeof($where) > 0) {
            $CI->db->where($where);
        }
    } elseif (strlen($where) > 0) {
        $CI->db->where($where);
    }

    return $CI->db->count_all_results($table);
}

/**
 * Add option in table
 * @since  Version 1.0.1
 * @param string $name  option name
 * @param string $value option value
 */
function add_option($name, $value = '',$type='', $autoload = 1,$is_update=false)
{
    $CI =& get_instance();

    $exists = total_rows('options', array(
        'name' => $name,
    ));

    if ($exists == 0) {
        $newData = array(
                'name' => trim($name),
                'value' => trim($value),
                'type' => trim($type)
            );

        if ($CI->db->field_exists('autoload', 'options')) {
            $newData['autoload'] = $autoload;
        }

        $CI->db->insert('options', $newData);
        $insert_id = $CI->db->insert_id();

        if ($insert_id) {
            return true;
        }

        return false;
    }else{
        if($is_update){
            update_option($name,$value,$autoload);
        }
    }

    return false;
}

function add_options($options,$type='', $autoload = 1,$is_update=false)
{
    if(!empty($options)){
        foreach($options as $key=>$val){
            add_option(trim($key),trim($val),trim($type),$autoload,$is_update);
        }
    }
    return true;
}

/**
 * Get option value
 * @param  string $name Option name
 * @return mixed
 */
function get_option($name,$val='')
{
        $name = trim($name);
        $CI =& get_instance();


        //if (!isset($this->options[$name])) {
            // is not auto loaded
            $CI->db->select('value');
            $CI->db->where('name', $name);
            $row = $CI->db->get('options')->row();
            if ($row) {
                $val = $row->value;
            }
        /*} else {
            $val = $this->options[$name];
        }*/
        return $val;
}
/**
 * Get options by array
 * @param  string $name Option name
 * @return mixed
 */
function get_options($options=array())
{
    if(!empty($options)){
        $CI =& get_instance();
            // is not auto loaded
            $CI->db->select('name,value');
            $CI->db->where_in('name', $options);
            $rows = $CI->db->get('options')->result();
            $output = array();
            foreach($options as $op){
                $output[$op] = "";
            }
            foreach($rows as $row){
                    $output[$row->name] = $row->value;
            }
        return $output;
    }
}
/**
 * Get options by type
 * @param  string $name Option name
 * @return mixed
 */
function get_options_by_type($type)
{
    if(!empty($type)){
        $CI =& get_instance();
        $CI->db->select('name,value');
                
        if(is_array($type)){
                // is not auto loaded
            $CI->db->where_in('type', $type);
        }else{
            $CI->db->where('type', $type);    
        }
        $rows = $CI->db->get('options')->result();
        $output = array();
            foreach($rows as $row){
                $output[$row->name] = $row->value;
            }        
        return $output;
    }
}
/**
 * Get option value from database
 * @param  string $name Option name
 * @return mixed
 */
function update_option($name, $value, $autoload = null)
{
    $CI =& get_instance();
    $CI->db->where('name', $name);
    $data = array(
        'value' => $value,
        );
    if ($autoload) {
        $data['autoload'] = $autoload;
    }
    $CI->db->update('options', $data);
    if ($CI->db->affected_rows() > 0) {
        return true;
    }

    return false;
}

function update_options($options=array())
{
    if(!empty($options)){
        foreach($options as $key=>$val){
            update_option(trim($key),trim($val));
        }
    }    

    return true;
}
/**
 * Delete option
 * @since  Version 1.0.4
 * @param  mixed $id option id
 * @return boolean
 */
function delete_option($id)
{
    $CI =& get_instance();
    $CI->db->where('id', $id);
    $CI->db->or_where('name', $id);
    $CI->db->delete('options');
    if ($CI->db->affected_rows() > 0) {
        return true;
    }

    return false;
}
