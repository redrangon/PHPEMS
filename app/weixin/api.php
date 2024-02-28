<?php

namespace PHPEMS;

class app
{
	public $G;

	public function __construct()
	{
				
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->_user = $_user = $this->session->getSessionUser();
		if($this->_user['sessionuserid'])
		$this->tpl->assign('_user',$this->user->getUserById($this->_user['sessionuserid']));
        $this->tpl->assign('navs',\PHPEMS\ginkgo::make('nav','core')->getWebNavs());
	}
}
?>