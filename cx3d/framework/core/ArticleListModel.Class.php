<?php

class ArticleListModel extends Model
{
    /**
     * 搜索功能
     * @param $fieldList array 查询字段 keyword/type/sor/order/pSize/pIndex/newsIds
     * @return array 返回的数据
     */
    public function search($fieldList=array())
    {
        $keyword = isset($fieldList["keyword"])?$fieldList["keyword"]:"";
        $newsIds = isset($fieldList["newsIds"])?$fieldList["newsIds"]:"";
        $sort = isset($fieldList["sort"])?$fieldList["sort"]:"DESC";
        $order = isset($fieldList["order"])?$fieldList["order"]:"LastUpdate";
        $pSize = isset($fieldList["pSize"])?$fieldList["pSize"]:self::$config["pageSize"];
        $pIndex = isset($fieldList["pIndex"])?$fieldList["pIndex"]:self::$config["pageIndex"];

        $sql_sel = "SELECT al.* ";
        $sql_from = "FROM ArticleList al ";
        $sql_join = "";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = "ORDER BY al.`StickyPost` DESC, al.`".$order."` ".$sort." ";
        $sql_limit = "LIMIT ".($pIndex-1) * $pSize.",".$pSize." ";

        if(isset($fieldList["keyword"]) && strlen($keyword)>0){
            $sql_where.= "AND al.`Title` like '%$keyword%' ";
        }
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
        $keyword = isset($fieldList["keyword"])?$fieldList["keyword"]:"";
        $newsIds = isset($fieldList["newsIds"])?$fieldList["newsIds"]:"";
        $sql_where = "1=1 ";

        if(isset($fieldList["keyword"]) && strlen($keyword)>0){
            $sql_where.= "AND `Title` like '%$keyword%' ";
        }
        if(isset($fieldList["newsIds"]) && strlen($newsIds)>0){
            $sql_where.= "AND `NewsId` in ($newsIds) ";
        }

        $result = $this->db->getCount("ArticleList","ArticleListId",$sql_where);
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
        $where = "ArticleListId=".$id;
        $result = $this->db->update("ArticleList",$data,$where);
        return $result;
    }

    /**
     * 添加数据
     * @param $data array 更新的数据内容
     * @return array 返回的数据
     */
    public function add($data)
    {
        $result = $this->db->insert("ArticleList", $data);
        return $result;
    }

}