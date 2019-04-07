<?php
namespace common\components;
 
use yii\base\Component;
use yii;

class map extends Component {
	/*
	*	状态码
		0为正常,
		310请求参数信息有误，
		311Key格式错误,
		306请求有护持信息请检查字符串,
		110请求来源未被授权
	*
	*/
 
    //定义get函数
    private static function curl_get($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $dom = curl_exec($ch);
        curl_close($ch);
        return $dom;
    }
    //腾讯逆地址转换
    public function getmap($location) {

        $key = "CQWBZ-BCKW6-QBCS7-MEMFN-64PPF-LKF4O";

        $url = "https://apis.map.qq.com/ws/geocoder/v1/?location={$location}&key={$key}";

        $json = self::curl_get($url);

        return json_decode($json);
    }

}
