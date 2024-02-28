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
		$this->content = \PHPEMS\ginkgo::make('content','content');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->_user = $_user = $this->session->getSessionUser();
		$this->tpl->assign('_user',$this->user->getUserById($this->_user['sessionuserid']));
		$rcats = $this->category->getCategoriesByArgs(array(array("AND","catparent = '0'")));
		$this->tpl->assign('rcats',$rcats);
        $this->seminar = \PHPEMS\ginkgo::make('seminar','seminar');
        $this->tpl->assign('navs',\PHPEMS\ginkgo::make('nav','core')->getWebNavs());
	}
}
?>