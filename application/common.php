<?php
// 应用公共文件
function formatDate($time, $long = true)
{
    $format = "Y-m-d H:i:s";
    if(!$long){
        $format = "Y-m-d";
    }
    return date($format, $time);
}

function output_data($datas, $code = 200,$extend_data = array()) {
    $data = array();
    $data['code'] = $code;

    if(!empty($extend_data)) {
        $data = array_merge($data, $extend_data);
    }
    if($datas)
        $data['data'] = $datas;

    return $data;
}

function output_error($message, $code=-1,$extend_data = array()) {
    $datas = array();
    $extend_data['msg'] = $message;
    return output_data($datas,$code, $extend_data);
}

function my_file_get_contents($url)
{
    $option = array(
        'http' => array(
            'method' => 'GET', 
            'header' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3000.4 Safari/537.36'
        ),
    );
    return file_get_contents($url, false , stream_context_create($option));
}

function charsetUtf8($data)
{
    if($data){
        $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5'));
        if( $fileType != 'UTF-8'){
            //return mb_convert_encoding($data ,'utf-8' , $fileType);
        }

        //return iconv("UTF-8", "GBK//ignore", $data);
    }
    return $data;
}