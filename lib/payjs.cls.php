<?php
 namespace PHPEMS;

class payjs
{
	public $G;

	public $config = array();

    public function __construct($G)
    {
    	
        $this->ev = \PHPEMS\ginkgo::make('ev');
    }

    public function post($url,$data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'HTTP CLIENT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, true);
    }

    public function sign($data, $key)
    {
        $data = array_filter($data);
        ksort($data);
        $sign = strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
        return $sign;
    }

    public function outQrcodePay($order)
    {
    	$data = array(
    	    'mchid' => PAYJSMCHID,
            'total_fee' => $order['orderprice']*100,
            'out_trade_no' => $order['ordersn'],
            'type' => '',
            'body' => $order['ordertitle'],
            'attach' => 'phpems',
            'notify_url' => WP."api/payjsnotify.php"
        );
    	$data['sign'] = $this->sign($data,PAYJSKEY);
    	$r = $this->post("https://payjz.cn/api/native",$data);
        return $r;
    }

    public function outJsApiPay($order,$openid)
    {
        $data = array(
            'mchid' => PAYJSMCHID,
            'total_fee' => $order['orderprice']*100,
            'out_trade_no' => $order['ordersn'],
            'type' => '',
            'body' => $order['ordertitle'],
            'attach' => 'phpems',
            'notify_url' => WP."api/payjsnotify.php",
            'openid' => $openid
        );
        $data['sign'] = $this->sign($data,PAYJSKEY);
        $r = $this->post("https://payjz.cn/api/jsapi",$data);
        return $r;
    }

    public function notify()
    {
        $post = $this->ev->post;
        $data = array(
            'return_code' => $post['return_code'],
            'total_fee' => $post['total_fee'],
            'out_trade_no' => $post['out_trade_no'],
            'payjs_order_id' => $post['payjs_order_id'],
            'transaction_id' => $post['transaction_id'],
            'time_end' => $post['time_end'],
            'openid' => $post['openid'],
            'attach' => $post['attach'],
            'mchid' => $post['mchid'],
            'type' => $post['type']
        );
        $sign = $this->sign($data,PAYJSKEY);
        if($data['return_code'] == 1 && $sign == $post['sign'])
        {
            $ordersn = $data['out_trade_no'];
            $this->order->payforOrder($ordersn,'payjs');
            echo 'success';
        }
        else
        {
            echo 'fail';
        }
    }
}

?>