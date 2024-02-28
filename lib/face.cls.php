<?php
 namespace PHPEMS;

class face
{

	public $G;
	public $token;

	public function __construct()
	{
		
		$this->token = $this->gettoken();
	}

	public function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        // 初始化curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $postUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // post提交方式
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        // 运行curl
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    public function request_json_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        // 初始化curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $postUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length:' . strlen($curlPost),
            'Cache-Control: no-cache',
            'Pragma: no-cache'
        ));
        curl_setopt($curl, CURLOPT_POST, 1);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        // 运行curl
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    public function gettoken()
    {
        $tokenjson = json_decode(file_get_contents('baidutoken.json'),true);
        if($tokenjson['expires_in'] > TIME)
        {
            return $tokenjson['access_token'];
        }
        else
        {
            $url = 'https://aip.baidubce.com/oauth/2.0/token';
            $post_data['grant_type']       = 'client_credentials';
            $post_data['client_id']      = 'toGBdtmhcbEoTPgYp17U9ZMb';
            $post_data['client_secret'] = 'tQh2FG5rUD1UXpCI4fn0Rao0GicbgPwQ';
            $o = "";
            foreach ( $post_data as $k => $v )
            {
                $o.= "$k=" . urlencode( $v ). "&" ;
            }
            $post_data = substr($o,0,-1);
            $res = $this->request_post($url, $post_data);
            $tokenjson = json_decode($res,true);
            $tokenjson['expires_in'] = $tokenjson['expires_in'] + TIME - 10;
            file_put_contents('baidutoken.json',json_encode($tokenjson));
            return $tokenjson['access_token'];
        }
    }

    public function verifyface($data)
    {
        $url = 'https://aip.baidubce.com/rest/2.0/face/v3/match?access_token=' . $this->token;
        $data = stripslashes(json_encode($data));
        $res = $this->request_json_post($url, $data);
        $tokenjson = json_decode($res,true);
        return $tokenjson['result']['score'];
    }

}