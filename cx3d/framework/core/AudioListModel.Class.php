<?php

class AudioListModel extends Model
{
    /**
     * 搜索功能
     * @param $fieldList array 查询字段 keyword/state/type/sor/order/pSize/pIndex
     * @return array 返回的数据
     */
    public function search($fieldList=array())
    {
        $newsIds = isset($fieldList["newsIds"])?$fieldList["newsIds"]:"";
        $sort = isset($fieldList["sort"])?$fieldList["sort"]:"DESC";
        $order = isset($fieldList["order"])?$fieldList["order"]:"LastUpdate";
        $pSize = isset($fieldList["pSize"])?$fieldList["pSize"]:self::$config["pageSize"];
        $pIndex = isset($fieldList["pIndex"])?$fieldList["pIndex"]:self::$config["pageIndex"];

        $sql_sel = "SELECT al.* ";
        $sql_from = "FROM AudioList al ";
        $sql_join = "";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = "ORDER BY al.`StickyPost` DESC, al.`".$order."` ".$sort." ";
        $sql_limit = "LIMIT ".($pIndex-1) * $pSize.",".$pSize." ";

        if(isset($fieldList["newsIds"]) && strlen($newsIds)>0){
            $sql_where.= "AND al.`NewsId` in ($newsIds) ";
        }

        $sql = $sql_sel.$sql_from.$sql_join.$sql_where.$sql_orderBy.$sql_limit.";";
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * 统计数量
     * @param $fieldList array 查询字段 keyword/state/type/sor/order/pSize/pIndex
     * @return array 返回的数据
     */
    public function getCount($fieldList=array())
    {
        $newsIds = isset($fieldList["newsIds"])?$fieldList["newsIds"]:"";
        $sql_where = "1=1 ";

        if(isset($fieldList["newsIds"]) && strlen($newsIds)>0){
            $sql_where.= "AND `NewsId` in ($newsIds) ";
        }

        $result = $this->db->getCount("AudioList","AudioListId",$sql_where);
        return $result;
    }

    /**
     * 更新数据
     * @param $id int 更新的数据Id
     * @param $data array 更新的数据内容
     * @return array 返回的数据
     */
    public function update($id,$data)
    {
        $where = "AudioListId=".$id;
        $result = $this->db->update("AudioList",$data,$where);
        return $result;
    }
}