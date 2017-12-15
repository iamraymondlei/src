<?php

class SortCatNode {

    /**
     * Common::RearrangeAry()
     * @param array $pDataAry
     * @return array
     */
    public static function RearrangeAry($pDataAry, $nodeNameId, $nodeNameParetnId, $nodeNameList, $nodeName, $parentId = 0) {
        $result = array();
        if (count($pDataAry) > 0) {
            $finialAry = array();
            $surplusAry = array();

            foreach ($pDataAry as $item) {
                if ( $parentId === 0 && $item[$nodeNameParetnId] == $parentId) {
                    $finialAry[] = $item;
                } elseif ( $parentId > 0 && $item[$nodeNameId] === $parentId) {
                    $finialAry[] = $item;
                } else {
                    $surplusAry[] = $item;
                }
            }

            for ($i = 0; $i < count($finialAry); $i++) {
                $finialAry[$i] = self::BuildLayer($finialAry[$i], $surplusAry, $nodeNameId, $nodeNameParetnId, $nodeNameList, $nodeName);
            }
            
            $result = $finialAry;
        }
        return $result;
    }

    /**
     * Common::BuildLayer()
     * @param array $parentAry
     * @param array $surplusAry
     * @return array
     */
    public static function BuildLayer($parentAry, $surplusAry, $nodeNameId, $nodeNameParetnId, $nodeNameList, $nodeName) {
        $result = $parentAry;
        for ($i = 0; $i < count($surplusAry); $i++) {
            if ($surplusAry[$i][$nodeNameParetnId] == $parentAry[$nodeNameId]) {
                $tempAry = $surplusAry[$i];
                //unset($surplusAry[$i]);
                $result[$nodeNameList][$nodeName][] = self::BuildLayer($tempAry, $surplusAry, $nodeNameId, $nodeNameParetnId, $nodeNameList, $nodeName);
            }
        }
        return $result;
    }

}
