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
        $this->ask = \PHPEMS\ginkgo::make('ask','ask');
        $this->user = \PHPEMS\ginkgo::make('user','user');
        $this->_user = $_user = $this->session->getSessionUser();
        $this->tpl->assign('_user',$this->user->getUserById($this->_user['sessionuserid']));
	}
}

?>