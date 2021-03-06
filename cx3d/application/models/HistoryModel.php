<?php
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/10/20
 * Time: 15:05
 */

class HistoryModel extends Model
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
            }

            $imageList = (new ImageListModel("ImageList"))->search($fieldList);
            foreach ($imageList as $imgIndex => $img){
                foreach ($img as $imgAttr => $imgVale){
                    if($imgAttr === "ImageUrl" || $imgAttr === "ThumbImageUrl"){
                        $imageList[$imgIndex][$imgAttr] = self::setImagePrefix($imgVale);
                    }
                }
            }
            $news["ImageList"] = $imageList;
            $news["ImageCount"] = (new ImageListModel("ImageList"))->getCount($fieldList);
            $result["Result"][] = $news;
        }
        return $result;
    }

    /**
     * 更新
     * @param $fieldList array 查询字段
     * @return bool 返回的数据
     */
    public function update($newsId, $fieldList=array())
    {
        //update news title
        $newsResult = (new NewsModel("News"))->update($newsId,$fieldList["News"]);
        //update article
        $artResult = (new ArticleListModel("ArticleList"))->update($fieldList["Article"]["ArticleListId"],$fieldList["Article"]);
        //get org images ids
        $orgImageIds = [];
        $newImageIds = [];
        $orgImageList = (new ImageListModel("ImageList"))->search(array("newsIds"=>$newsId,"pSize"=>"999","pIndex"=>"1"));
        foreach ($orgImageList as $imgIndex => $img){
            foreach ($img as $imgAttr => $imgVale){
                if($imgAttr === "ImageListId"){
                    $orgImageIds[] = $imgVale;
                }
            }
        }
        //add new images
        $imageResult = 1;
        foreach($fieldList["ImageList"] as $item){
            if(isset($item["ImageListId"]) && !empty($item["ImageListId"])){
                $newImageIds[] = $item["ImageListId"];
            }
            else{
                unset($item["ImageListId"]);
                $item["NewsId"] = $newsId;
                $item["ImageUrl"] = self::removeImagePrefix($item["ImageUrl"]);
                $item["ThumbImageUrl"] = self::removeImagePrefix($item["ThumbImageUrl"]);
                $item["StickyPost"] = empty($item["StickyPost"])?"0":$item["StickyPost"];
                $imageResult = (new ImageListModel("ImageList"))->add($item);
            }
        }
        //del images
        foreach ($orgImageIds as $id) {
            if(!in_array($id,$newImageIds)){
                $imageResult = (new ImageListModel("ImageList"))->del($id);
            }
        }

        if($newsResult && $imageResult && $artResult){
            return 1;
        }
        else{
            return 0;
        }
    }
}