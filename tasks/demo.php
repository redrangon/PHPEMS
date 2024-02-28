<?php
namespace PHPEMS;
/*
 * Created on 2013-12-26
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
if(php_sapi_name() != 'cli')exit('Access denied!');
set_time_limit(0);
define('PEPATH',dirname(dirname(__FILE__)));
class app
{
	public $G;

	public function __construct()
	{		
		$this->ev = \PHPEMS\ginkgo::make('ev');
	}

	public function run()
	{
		print_r($this->ev->get);
		$this->favor = \PHPEMS\ginkgo::make('favor','exam');
        $app = \PHPEMS\ginkgo::make('apps','core')->getApp('exam');
        $this->setting = $app['appsetting'];
		print_r($this->setting);
	}
}

include PEPATH.'/lib/init.cls.php';
ginkgo::loadMoudle();
$app = new app();
$app->run();


?>