<?php

class Subadmin_model extends CI_Model
{

    //check wheater the email is exit in the database or not
    function is_email_available($email) 
    {
        $this->db->where('email', $email);
        $query = $this->db->get("oe_admins");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //fetch the data according to search the data of subadmin according to admin search

    public function getsubadmindata($limit, $offset, $params) 
    {

        $this->db->select('SQL_CALC_FOUND_ROWS a.admin_id,admin_email,admin_name,admin_profile_pic,create_date,status', false);
        $this->db->from('admin as a');
        $this->db->where('role_id', 2);

        if (!empty($params['searchlike'])) {
            $this->db->group_start();
            $this->db->like('a.admin_name', $params['searchlike'], 'after');
            $this->db->or_like('a.admin_name', ' ' . $params['searchlike']);
            $this->db->or_like('a.admin_email', $params['searchlike'], 'after');
            $this->db->group_end();
        }
        
        $this->db->where('status != 3');
        
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        //      echo $this->db->last_query();die;
        $respdata = array();
        $respdata['totalrows'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        $respdata['records'] = $query->result_array();
        return $respdata;
    }

    //delete the records
    public function delete_data($userId) 
    {
        $this->db->where('userId', $userId);
        $this->db->delete('oe_admins');
    }

    //update the records
    public function update($table, $data, $where) 
    {
        $this->db->where($where);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //insert the records if not exit 
    public function insertpermission($updateArr, $where) 
    {
        // print_r($where);die;
        //        $sql = "insert into sub_admin (viewp,blockp,editp,deletep,addp,access_permission,admin_Id)values(" . $updateArr['viewp'] . "," . $updateArr['blockp'] . "," . $updateArr['editp'] . "," . $updateArr['deletep'] . "," . $updateArr['addp'] . "," . $updateArr['access_permission'] . "," . $where['admin_Id'] . ")ON DUPLICATE KEY UPDATE viewp='" . $updateArr['viewp'] . "',blockp='" . $updateArr['blockp'] . "',editp='" . $updateArr['editp'] . "',deletep='" . $updateArr['deletep'] . "',addp='" . $updateArr['addp'] . "' where access_permission = ".$updateArr['access_permission']." AND admin_Id = ".$where['admin_Id']."";
        $sql = "insert into sub_admin (viewp,blockp,editp,deletep,addp,access_permission,admin_Id)values(" . $updateArr['viewp'] . "," . $updateArr['blockp'] . "," . $updateArr['editp'] . "," . $updateArr['deletep'] . "," . $updateArr['addp'] . "," . $updateArr['access_permission'] . "," . $where['admin_Id'] . ")";
        ;
        $query = $this->db->query($sql);
        $afftectedRows = $this->db->affected_rows();
        if ($query->$afftectedRows == 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    //    public function getAdminDetail($params) {
    //        $this->db->select('a.admin_id,a.admin_name,a.status,a.admin_email,a.create_date,s.viewp,s.blockp,s.deletep,s.editp,s.addp,s.admin_id,s.access_permission')
    //                ->from('admin as a');
    //        $this->db->join('sub_admin as s', 'a.admin_id=s.admin_id', 'left');
    //        $this->db->where('a.admin_id = ' . $params['admin_id'] . '');
    //        $query = $this->db->get();
    //        if ($query->num_rows() >= 1) {
    //            return $query->result_array();
    //        } else {
    //            return false;
    //        }
    //    }

}

?>
