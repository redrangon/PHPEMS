<?php
/*
 * Created on 2013-12-26
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
namespace PHPEMS;
define('PEPATH',dirname(dirname(__FILE__)));
class app
{
	public $G;

	public function __construct()
	{
		
	}

	public function run()
	{
		//使用通用通知接口
        \PHPEMS\ginkgo::make('payjs')->notify();
	}
}
include PEPATH.'/lib/init.cls.php';
$app = new app(new ginkgo);
$app->run();

?>