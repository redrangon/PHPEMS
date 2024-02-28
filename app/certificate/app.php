<?php

namespace PHPEMS;

class app
{
	public $G;
	public $data = array();
	public $sessionvars;

	public function __construct()
	{
		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->sql = \PHPEMS\ginkgo::make('sql');
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->html = \PHPEMS\ginkgo::make('html');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->_user = $this->session->getSessionUser();
		if(!$this->_user['sessionuserid'])
		{
			if($this->ev->get('userhash'))
			exit(json_encode(array(
				'statusCode' => 301,
				"message" => "请您重新登录",
			    "callbackType" => 'forward',
			    "forwardUrl" => "index.php?user-app-login"
			)));
			else
			{
				header("location:index.php?user-app-login");
				exit;
			}
		}
		$this->ce = \PHPEMS\ginkgo::make('ce','certificate');
		$this->tpl->assign('_user',$this->user->getUserById($this->_user['sessionuserid']));
		$this->tpl->assign('userhash',$this->ev->get('userhash'));
        $this->tpl->assign('navs',\PHPEMS\ginkgo::make('nav','core')->getWebNavs());
	}
}

?>