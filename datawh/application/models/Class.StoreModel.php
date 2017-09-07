<?php
/**
 * Description of StoreModel
 *
 * @author icm
 */
class StoreModel extends Model {
    public function getStore(){
        try{
            //$this->fields;
            $sql = "select * from $this->table;";
            $store = $this->db->query($sql);
            return $store;
        } catch(Exception $e){ 
            echo $e->getMessage(); //输出异常信息。 
        }
    }
}
