<?php
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/10/20
 * Time: 15:05
 */

class PromicrovideoModel extends Model
{
    /**
     * 搜索功能
     * @param $fieldList array 查询字段 keyword/state/type/sor/order/pSize/pIndex
     * @return array 返回的数据
     */
    public function search($fieldList=array(),$removeHtml=TRUE)
    {
        $result = array("Count"=>0,"Result"=>array());
        $videoList = (new VideoListModel("VideoList"))->search($fieldList);
		
		foreach ($videoList as $index=>$video){
			$videoList[$index]["PreviewImageUrl"] = self::setImagePrefix($videoList[$index]["PreviewImageUrl"]);
		}
		
        $result["Count"] = (new VideoListModel("VideoList"))->getCount($fieldList);
        $result["Result"] = $videoList;

        return $result;
    }

    /**
     * 更新
     * @param $fieldList array 查询字段
     * @return bool 返回的数据
     */
    public function update($vId, $fieldList=array())
    {
		$fieldList["PreviewImageUrl"] = self::removeImagePrefix($fieldList["PreviewImageUrl"]);
        $videoResult = (new VideoListModel("VideoList"))->update($vId, $fieldList);
        if($videoResult){
            return 1;
        }
        else{
            return 0;
        }
    }

    /**
     * 添加
     * @param $fieldList array 查询字段
     * @return bool 返回的数据
     */
    public function add($fieldList=array())
    {
        $videoResult = (new VideoListModel("VideoList"))->add($fieldList);
        if($videoResult){
            return 1;
        }
        else{
            return 0;
        }
    }
}