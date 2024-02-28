<?php
namespace PHPEMS;
/*
 * Created on 2013-12-26
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

define('PEPATH',dirname(dirname(__FILE__)));
class app
{
	public $G;

	public function __construct()
	{

		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->order = \PHPEMS\ginkgo::make('orders','bank');
	}

	public function run()
	{
		$alipay = \PHPEMS\ginkgo::make('alipay');
		$orderid = $this->ev->get('out_trade_no');
		$order = $this->order->getOrderById($orderid);
		$verify_result = $alipay->alinotify();
		if($verify_result)
		{
			if($this->ev->get('trade_status') == 'TRADE_FINISHED' ||$this->ev->get('trade_status') == 'TRADE_SUCCESS')
			{
				if($order['orderstatus'] != 2)
				{
                    $this->order->payforOrder($orderid,'alipay');
				}
				exit('sucess');
			}
			elseif($_POST['trade_status'] == 'WAIT_BUYER_PAY')
			{
				exit('fail');
			}
			else
			{
				exit('fail');
			}
		}
		else
		{
			exit('fail');
		}
	}
}

include PEPATH.'/lib/init.cls.php';
$app = new app(new ginkgo);
$app->run();


?>