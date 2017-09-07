<?php
/**
 * Description of ProductModel
 *
 * @author icm
 */
class ProductModel extends Model {
    public function getProduct($params){
        try{
            //$this->fields;
            $sql = "select * from $this->table where FamilyId = ".$params['FamilyId'].";";
            $product = $this->db->query($sql);
            return $product;
        } catch(Exception $e){ 
            echo $e->getMessage(); //输出异常信息。 
        }
    }
}
