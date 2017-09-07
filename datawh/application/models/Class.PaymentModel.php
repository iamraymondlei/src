<?php
/**
 * Description of PaymentModel
 *
 * @author icm
 */
class PaymentModel extends Model {
    public function getPayment(){
        try{
            //$this->fields;
            $sql = "select * from $this->table;";
            $payment = $this->db->query($sql);
            return $payment;
        } catch(Exception $e){ 
            echo $e->getMessage(); //输出异常信息。 
        }
    }
}
