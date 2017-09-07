<?php

class DatetimeUtil {
    public static function getDateTime() {
        $day = date("Y-m-d");
        $time = date("H:i:s");
        return $day . " " . $time;
    }
    
    public static function getDateTimeStamp() {
        $day = date("Y-m-d");
        $time = date("H:i:s");
        return $day . "T" . $time;
    }
    
    public static function getTime($name="") {
        return "[".date('H:i:s').".".self::microtimeFloat()."]".$name.PHP_EOL;
    }
    
    public static function microtimeFloat(){      
        list($usec, $sec) = explode(" ", microtime());      
        return ((float)$usec + (float)$sec);   
    }
    
    public static function timediff($time1, $time2){
        $begin_time = strtotime($time1);
        $end_time = strtotime($time2);
        if ( $begin_time < $end_time ) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        $timediff = $endtime - $starttime;
        $days = intval( $timediff / 86400 );
        $remain = $timediff % 86400;
        $hours = intval( $remain / 3600 );
        $remain = $remain % 3600;
        $mins = intval( $remain / 60 );
        $secs = $remain % 60;
        $res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
        return $res;
    }
}
