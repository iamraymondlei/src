<?php

class ImageListModel extends Model
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

        $sql_sel = "SELECT il.* ";
        $sql_from = "FROM ImageList il ";
        $sql_join = "";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = "ORDER BY il.`StickyPost` DESC, il.`".$order."` ".$sort." ";
        $sql_limit = "LIMIT ".($pIndex-1) * $pSize.",".$pSize." ";

        if(isset($fieldList["newsIds"]) && strlen($newsIds)>0){
            $sql_where.= "AND il.`NewsId` in ($newsIds) ";
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

        $result = $this->db->getCount("ImageList","ImageListId",$sql_where);
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
        $where = "ImageListId=".$id;
        $result = $this->db->update("ImageList",$data,$where);
        return $result;
    }

    /**
     * 添加数据
     * @param $data array 更新的数据内容
     * @return array 返回的数据
     */
    public function add($data)
    {
        $result = $this->db->insert("ImageList", $data);
        return $result;
    }

    /**
     * 删除数据
     * @param $id int 更新的数据Id
     * @return array 返回的数据
     */
    public function del($id)
    {
        $where = "ImageListId=".$id;
        $result = $this->db->delete("ImageList",$where);
        return $result;
    }

}