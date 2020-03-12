<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_akun extends CI_Model
{
    public $table = "users";
    public $select_column = array('id_user','username','password','email','full_name','phone','role');
    public $order_column = array(null,'id_user','username','password','email','full_name','phone','role',null);

    private function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);

        if(isset($_POST['search']['value']))
        {
            $this->db->like('username',$_POST['search']['value']);
            $this->db->or_like('full_name',$_POST['search']['value']);
            $this->db->or_like('email',$_POST['search']['value']);
        }

        if(isset($_POST['order']))
        {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
        }else
        {
            $this->db->order_by('id_user');
        }
    }

    function make_datatables()
    {
        $this->make_query();
        if($_POST['length'] != -1)
        {
            $this->db->limit($_POST['length'],$_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_all_data()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}