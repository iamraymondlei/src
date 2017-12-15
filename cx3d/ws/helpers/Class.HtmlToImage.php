<?php
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/12/4
 * Time: 11:24
 * Issue: 不会另存为图片文件
 *        图片截取范围通过page参数决定，如page=1
 *        图片每页截取大小由size参数决定，如size=10x20
 *        模板为静态文件，通过templateId参数决定，如templateId=1
 */

class HtmlToImage
{
    /**
     * 将html内容转换为image图片
     * @param $htmlcontent
     * @param $toimagepath
     * @author james.ou 2011-11-1
     */
    public static function html2image($htmlcontent, $toimagepath, $toimagewidth = '400', $toimageheight = '300', $toimagetype = 'png')
    {
        $str = $htmlcontent;
        $str = strtolower($str);
        //$str = mb_convert_encoding($str, "html-entities", "utf-8");
        //Get the original HTML string
        //Declare <h1> and </h1> arrays
        $h1_start = array();
        $h1_end = array();
        //Clear <h1> and </h1> attributes
        $str = preg_replace("/<h1[^>]*>/", "<h1>", $str);
        $str = preg_replace("/<\/h1[^>]*>/", "</h1>", $str);
        $str = preg_replace("/<h1>\s*<\/h1>/", "", $str);

        //Declare <img> arrays
        $img_pos = array();
        $imgs = array();
        //If we have images in the HTML
        if (preg_match_all("/<img[^>]*src=\"([^\"]*)\"[^>]*>/", $str, $m)) {
            //Delete the <img> tag from the text
            //since this is not plain text
            //and save the position of the image
            $nstr = $str;
            $nstr = str_replace("\r\n", "", $nstr);
            $nstr = str_replace("<h1>", "", $nstr);
            $nstr = str_replace("</h1>", "", $nstr);
            $nstr = preg_replace("/<br[^>]*>/", str_repeat(chr(1), 2), $nstr);
            $nstr = preg_replace("/<div[^>]*>/", str_repeat(chr(1), 2), $nstr);
            $nstr = preg_replace("/<\/div[^>]*>/", str_repeat(chr(1), 2), $nstr);
            $nstr = preg_replace("/<p[^>]*>/", str_repeat(chr(1), 4), $nstr);
            $nstr = preg_replace("/<\/p[^>]*>/", str_repeat(chr(1), 4), $nstr);
            $nstr = preg_replace("/<hr[^>]*>/", str_repeat(chr(1), 8), $nstr);

            foreach ($m[0] as $i => $full) {
                $img_pos[] = strpos($nstr, $full);
                $str = str_replace($full, chr(1), $str);
            }
            //Save the sources of the images
            foreach ($m[1] as $i => $src) {
                $imgs[] = $src;
            }
            //Get image resource of the source
            //according to its extension and save it in array
            foreach ($imgs as $i => $image) {
                $ext = end(explode(".", $image));
                $im = null;
                switch ($ext) {
                    case "gif":
                        $im = imagecreatefromgif($image);
                        break;
                    case "png":
                        $im = imagecreatefrompng($image);
                        break;
                    case "jpeg":
                        $im = imagecreatefromjpeg($image);
                        break;
                }
                $imgs[$i] = $im;
            }
        }
        //If there is <h1> or </h1>s
        while (strpos($str, "<h1>") != false || strpos($str, "</h1>") != false) {
            while (strpos($str, "<h1>") !== false) {
                $p = strpos($str, "<h1>");
                $h1_start[] = $p;
                $str = substr($str, 0, $p) . substr($str, $p + strlen("<h1>"));
            }
            while (strpos($str, "</h1>") !== false) {
                $p = strpos($str, "</h1>");
                $h1_end[] = $p;
                $str = substr($str, 0, $p) . substr($str, $p + strlen("</h1>"));
            }
        }
    }
}