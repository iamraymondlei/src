<?php

class HttpError {
    public static function GetErrorMessage($errCode) {
        // WS 状态码控制
        $errMsg = "";
        if (empty($errCode)) {
            switch ($errCode) {
                case 200: $errMsg = "";
                    break;
                case 400: $errMsg = "缺少参数或不完整的请求";
                    break;
                case 401: $errMsg = "未授权：登录失败";
                    break;
                case 403: $errMsg = "Forbidden";
                    break;
                case 404: $errMsg = "无法找到文件";
                    break;
                case 405: $errMsg = "Method Not Allowed";
                    break;
                case 406: $errMsg = "Not Acceptable";
                    break;
                case 408: $errMsg = "请求超时";
                    break;
                case 409: $errMsg = "Conflict";
                    break;
                case 415: $errMsg = "不支援的格式";
                    break;
                case 500: $errMsg = "服务器内部错误如连接数据库失败";
                    break;
                default: $errMsg = "服务器内部错误如连接数据库失败";
                    break;
            }
        }
        return $errMsg;
    }
}

