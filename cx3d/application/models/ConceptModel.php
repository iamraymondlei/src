<?php
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/10/20
 * Time: 15:05
 */

class ConceptModel extends Model
{
    /**
     * 搜索功能
     * @param $fieldList array 查询字段 keyword/state/type/sor/order/pSize/pIndex
     * @return array 返回的数据
     */
    public function search($fieldList=array(),$removeHtml=TRUE)
    {
        $result = array("Count"=>0,"Result"=>array());
        $newsList = (new CatNodeListModel("CatNodeList"))->search($fieldList);
        $result["Count"] = (new CatNodeListModel("CatNodeList"))->getCount($fieldList);

        foreach ($newsList as $news){
            $fieldList["newsIds"] = $news["NewsId"];
            $articleList = (new ArticleListModel("ArticleList"))->search($fieldList);
            if(count($articleList) == 1){
                $news["ArticleId"] = $articleList[0]["ArticleListId"];
                $news["ArticleTitle"] = $articleList[0]["Title"];
                $news["ArticleSubTitle"] = $articleList[0]["SubTitle"];
                $news["ArticleRepresentImageUrl"] = self::setImagePrefix($articleList[0]["RepresentImageUrl"]);
                $news["ArticleContent"] =  ($removeHtml)?$this->removeHtmlTag($articleList[0]["Content"]):$articleList[0]["Content"];
                $news["ArticleLastUpdate"] = $articleList[0]["LastUpdate"];
            }

            $videoList = (new VideoListModel("VideoList"))->search($fieldList);
            if(count($videoList) == 1){
                $news["VideoId"] = $videoList[0]["VideoListId"];
                $news["VideoUrl"] = $videoList[0]["VideoUrl"];
                $news["VideoPreviewImageUrl"] = self::setImagePrefix($videoList[0]["PreviewImageUrl"]);
                $news["VideoDescription"] = $videoList[0]["Description"];
            }

            $result["Result"][] = $news;
        }
        return $result;
    }

    /**
     * 更新
     * @param $fieldList array 查询字段
     * @return bool 返回的数据
     */
    public function update($id, $fieldList=array())
    {
        $newsResult = (new NewsModel("News"))->update($fieldList["NewsId"],array("LastUpdate"=>date("Y-m-d h:i:s")));

        $fieldList["Article"]["RepresentImageUrl"] = self::removeImagePrefix($fieldList["Article"]["RepresentImageUrl"]);
        $artResult = (new ArticleListModel("ArticleList"))->update($fieldList["Article"]["ArticleListId"],$fieldList["Article"]);

        $fieldList["Video"]["VideoUrl"] = self::removeImagePrefix($fieldList["Video"]["VideoUrl"]);
        $videoResult = (new VideoListModel("VideoList"))->update($fieldList["Video"]["VideoListId"],$fieldList["Video"]);

        if($artResult && $videoResult){
            return 1;
        }
        else{
            return 0;
        }
    }
}