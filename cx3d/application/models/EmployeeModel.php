<?php

class EmployeeModel extends Model
{
    /**
     * 搜索功能
     * @param $fieldList array 查询字段 keyword/state/type/sor/order/pSize/pIndex
     * @return array 返回的数据
     */
    public function search($fieldList=array())
    {
        $keyword = isset($fieldList["keyword"])?$fieldList["keyword"]:"";
        $state = isset($fieldList["state"])?$fieldList["state"]:"";
        $type = isset($fieldList["type"])?$fieldList["type"]:"";
        $sort = isset($fieldList["sort"])?$fieldList["sort"]:"DESC";
        $order = isset($fieldList["order"])?$fieldList["order"]:"LastUpdate";
        $pSize = isset($fieldList["pSize"])?$fieldList["pSize"]:self::$config["pageSize"];
        $pIndex = isset($fieldList["pIndex"])?$fieldList["pIndex"]:self::$config["pageIndex"];

        $sql_sel = "SELECT e.EmployeeName, eud.*, eudt.`TypeName`, eudt.`DisplayName` ";
        $sql_from = "FROM EmployeeUploadData eud ";
        $sql_join = "LEFT JOIN EmployeeUploadDataType eudt ON eudt.`EmployeeUploadDataTypeId` = eud.`DataTypeId` ";
        $sql_join.= "LEFT JOIN Employee e ON e.`EmployeeId` = eud.`EmployeeId` ";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = "ORDER BY eud.`StickyPost` DESC, eud.`".$order."` ".$sort." ";
        $sql_limit = "LIMIT ".($pIndex-1) * $pSize.",".$pSize." ";

        if(isset($fieldList["keyword"]) && strlen($keyword)>0){
            $sql_where.= "AND eud.`Value` like '%$keyword%' AND eud.`DataTypeId` = 3 ";
        }
        if(isset($fieldList["state"]) && strlen($state)>0){
            $sql_where.= "AND eud.`State` = '$state' ";
        }
        if(isset($fieldList["type"]) && strlen($type)>0){
            $sql_where.= "AND eud.`DataTypeId` = '$type' ";
        }

        $sql = $sql_sel.$sql_from.$sql_join.$sql_where.$sql_orderBy.$sql_limit.";";
        $list = $this->db->query($sql);

        foreach ($list as $index=>$item){
            $list[$index]["RepresentImageUrl"] = self::setImagePrefix($item["RepresentImageUrl"]);
        }

        return $list;
    }

    /**
     * 统计数量
     * @param $fieldList array 查询字段 keyword/state/type/sor/order/pSize/pIndex
     * @return array 返回的数据
     */
    public function getCount($fieldList=array())
    {
        $keyword = isset($fieldList["keyword"])?$fieldList["keyword"]:"";
        $state = isset($fieldList["state"])?$fieldList["state"]:"";
        $type = isset($fieldList["type"])?$fieldList["type"]:"";
        $sql_where = "1=1 ";

        if(isset($fieldList["keyword"]) && strlen($keyword)>0){
            $sql_where.= "AND `Value` like '%$keyword%' AND `DataTypeId` = 3 ";
        }
        if(isset($fieldList["state"]) && strlen($state)>0){
            $sql_where.= "AND `State` = '$state' ";
        }
        if(isset($fieldList["type"]) && strlen($type)>0){
            $sql_where.= "AND `DataTypeId` = '$type' ";
        }

        $result = $this->db->getCount("EmployeeUploadData","EmployeeUploadDataId",$sql_where);
        return $result;
    }

    /**
     * 获取数据类型
     * @param $fieldList array 查询字段 sor/order
     * @return array 返回的数据
     */
    public function getDataType($fieldList=array())
    {
        $sort = isset($fieldList["sort"])?$fieldList["sort"]:"DESC";
        $order = isset($fieldList["order"])?$fieldList["order"]:"CreationTime";

        $sql_sel = "SELECT eudt.* ";
        $sql_from = "FROM EmployeeUploadDataType eudt ";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = " ORDER BY eudt.`".$order."` ".$sort." ";

        $sql = $sql_sel.$sql_from.$sql_where.$sql_orderBy.";";
        $result = $this->db->query($sql);
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
        $where = "EmployeeUploadDataId=".$id;
        $result = $this->db->update("EmployeeUploadData",$data,$where);
        return $result;
    }
}