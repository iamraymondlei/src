<?php

/* ========================================================

  Author:			Raymond Lui
  Last Modified:	2014-02-20

  ========================================================== */

class XMLUtil {
    /*
      XmlUtil::EscapeXml()
      replace & ' " < > to &amp; &apos; &quot; &lt; &gt;

      param:
      $pStr   string

      return:
      string
     */

    public static function escapeXml($pStr) {
        $result = $pStr;
        if ($pStr != "") {
            $result = str_replace("&amp;", "&", $result);
            $result = str_replace("&apos;", "'", $result);
            $result = str_replace("&quot;", '"', $result);
            $result = str_replace("&lt;", "<", $result);
            $result = str_replace("&gt;", ">", $result);

            $result = str_replace("&", "&amp;", $result);
            $result = str_replace("'", "&apos;", $result);
            $result = str_replace('"', "&quot;", $result);
            $result = str_replace("<", "&lt;", $result);
            $result = str_replace(">", "&gt;", $result);
        }
        return $result;
    }

    /**
      XmlUtil::UnescapeXml()
      replace &amp; &apos; &quot; &lt; &gt to & ' " < > ;

      param
      $pStr string

      return string
     */
    public static function unescapeXml($pStr) {
        $result = $pStr;
        if ($pStr != "") {
            $result = str_replace("&amp;", "&", $result);
            $result = str_replace("&apos;", "'", $result);
            $result = str_replace("&quot;", '"', $result);
            $result = str_replace("&lt;", "<", $result);
            $result = str_replace("&gt;", ">", $result);
        }
        return $result;
    }

    /*
      XmlUtil::PrettifyXml()

      param:
      $pXmlStr string

      return:
      string
     */

    public static function prettifyXml($pXmlStr) {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($pXmlStr);
        return $dom->saveXML();
    }

    public static function xmlToArray($xmlContents) {
        if (!empty($xmlContents) && self::isXML($xmlContents)) {
            $simpleXmlObj = simplexml_load_string($xmlContents, 'SimpleXMLElement', LIBXML_NOCDATA);
            $result[] = self::simplexmlObjToArray($simpleXmlObj);
            return $result;
        } else
            return false;
    }

    public static function isXML($xmlContent) {
        if ($xmlContent != "null") {
            try {
                @$xmlContent = new SimpleXMLElement($xmlContent);
                return 1;
            } catch (Exception $e) {
                return 0;
            }
        }
    }

    public static function simplexmlObjXMLString($simpleXmlObj) {
        return $simpleXmlObj->asXML();
    }

    public static function simplexmlObjJson($simpleXmlObj) {
        return json_encode($simpleXmlObj);  //php5，以及以上，如果是更早版本，請下載JSON.php
    }

    public static function xmlToJson($xmlContents) {
        $simpleXmlObj = simplexml_load_string($xmlContents, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_encode($simpleXmlObj);
    }

    public static function simplexmlObjToArray($simpleXmlObj) {
        $xmlAry = array();
        $xmlAry['name'] = $simpleXmlObj->getName();
        $xmlAry['value'] = (string) $simpleXmlObj;

        $attrAry = array();
        foreach ($simpleXmlObj->attributes() as $name => $value) {
            $attrAry[$name] = $value;
        }
        $xmlAry['attr'] = $attrAry;

        $childAry = array();
        foreach ($simpleXmlObj->children() as $name => $xmlchild) {
            $childAry[] = self::simplexmlObjToArray($xmlchild); //FIX : For multivalued node 
        }
        $xmlAry['child'] = $childAry;
        return($xmlAry);
    }

    public static function arrayToXML($array, $level = 1) {
        $result = "";
        $tab = "";
        $converAry = $array;

        if (is_array($converAry) && count($converAry) > 0) {
            for ($i = 0; $i < $level; $i++) {
                $tab.="\t";
            }

            foreach ($converAry as $item) {
                $nodeName = $item["name"];
                $value = $item["value"];
                $attr = $item["attr"];
                $childrenAry = $item["child"];

                if ($nodeName != "")
                    $result.=$tab . "<" . $nodeName;

                if (is_array($attr) && count($attr) > 0) {
                    foreach ($attr as $name => $attrValue) {
                        $result.= " " . $name . '="' . $attrValue . '"';
                    }
                }

                $result.=">";

                if (count($childrenAry) > 0) {
                    $nextLevel = $level + 1;
                    $result.="\r\n";
                    $result.=self::arrayToXML($childrenAry, $nextLevel);
                    $result.=$tab . "</" . $nodeName . ">\r\n";
                } else {
                    $result.=self::escapeXml($value);
                    $result.="</" . $nodeName . ">\r\n";
                }
            }
        }

        return $result;
    }

    public static function getNodeByXpath($xpath, $xmlContents) {
        $result = false;
        if (!empty($xmlContents) && !empty($xpath) && self::isXML($xmlContents)) {
            $xmlObj = simplexml_load_string($xmlContents, 'SimpleXMLElement', LIBXML_NOCDATA);
            $result = $xmlObj->xpath($xpath);
        }
        return $result;
    }

    public static function replaceNodeValueByXpath($xmlContents, $nodeXpath, $newValue) {
        $xml = new DOMDocument();
        if ($xml->loadXML($xmlContents) === FALSE) {
            die('Cannot load XML document ' . $xmlContents);
        }
        $xpath = new DOMXPath($xml);
        foreach ($xpath->query($nodeXpath) as $node) {
            $node->nodeValue = $newValue;
        }
        return $xml->saveXml();
    }

}

?>