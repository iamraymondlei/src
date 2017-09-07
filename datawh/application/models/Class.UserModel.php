<?php
/**
 * Description of UserModel
 *
 * @author icm
 */
class UserModel extends Model {
    public function getUsers(){
        try{
            //$this->fields;
            $sql = "select * from $this->table;";
            $users = $this->db->query($sql);
            return $users;
        } catch(Exception $e){ 
            echo $e->getMessage(); //输出异常信息。 
        }
    }
}
