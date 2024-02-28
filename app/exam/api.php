<?php

class app
{
	public $G;
	//联系密钥
	private $sc = '';

	public function __construct()
	{
		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->sql = \PHPEMS\ginkgo::make('sql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->html = \PHPEMS\ginkgo::make('html');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->exam = \PHPEMS\ginkgo::make('exam','exam');
		$this->user = \PHPEMS\ginkgo::make('user','user');
	}
}

?>