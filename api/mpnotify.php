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
		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->sql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->module = \PHPEMS\ginkgo::make('module');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$groups = $this->user->getUserGroups();
		$this->order = \PHPEMS\ginkgo::make('orders','bank');
	}

	public function run()
	{
		//使用通用通知接口
		$notify = \PHPEMS\ginkgo::make('mppay')->pehandle();
		exit;
	}
}
include PEPATH.'/lib/init.cls.php';
$app = new app(new ginkgo);
$app->run();

?>