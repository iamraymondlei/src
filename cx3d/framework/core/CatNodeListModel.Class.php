<?php

class CatNodeListModel extends Model
{
    /**
     * 搜索功能
     * @param $fieldList array 查询字段 type/sor/order/
     * @return array 返回的数据
     */
    public function search($fieldList=array())
    {
        $newsId = isset($fieldList["newsId"])?$fieldList["newsId"]:"";
        $catId = isset($fieldList["catId"])?$fieldList["catId"]:"";
        $sort = isset($fieldList["sort"])?$fieldList["sort"]:"DESC";
        $order = isset($fieldList["order"])?$fieldList["order"]:"LastUpdate";
        $pSize = isset($fieldList["pSize"])?$fieldList["pSize"]:self::$config["pageSize"];
        $pIndex = isset($fieldList["pIndex"])?$fieldList["pIndex"]:self::$config["pageIndex"];

        $sql_sel = "SELECT n.*, cnl.`CatNodeId` ";
        $sql_from = "FROM CatNodeList cnl ";
        $sql_join = "LEFT JOIN News n ON cnl.`NewsId` = n.`NewsId`";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = "ORDER BY n.`".$order."` ".$sort." ";
        $sql_limit = "LIMIT ".($pIndex-1) * $pSize.",".$pSize." ";

        if(isset($fieldList["catId"]) && strlen($catId)>0){
            $sql_where.= "AND cnl.`CatNodeId` = '$catId' ";
        }
        if(isset($fieldList["newsId"]) && strlen($newsId)>0){
            $sql_where.= "AND n.`NewsId` = '$newsId' ";
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
        $catId = isset($fieldList["catId"])?$fieldList["catId"]:"";
        $newsId = isset($fieldList["newsId"])?$fieldList["newsId"]:"";
        $sql_where = "1=1 ";

        if(isset($fieldList["catId"]) && strlen($catId)>0){
            $sql_where.= "AND `CatNodeId` = '$catId' ";
        }
        if(isset($fieldList["newsId"]) && strlen($newsId)>0){
            $sql_where.= "AND `NewsId` = '$newsId' ";
        }

        $result = $this->db->getCount("CatNodeList","CatNodeListId",$sql_where);
        return $result;
    }
}