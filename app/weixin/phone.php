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
		$this->category = \PHPEMS\ginkgo::make('category');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->_user = $this->session->getSessionUser();
		$user = $this->user->getUserById($this->_user['sessionuserid']);
		$this->tpl->assign('_user',$user);
		$this->tpl->assign('userhash',$this->ev->get('userhash'));
	}
}

?>