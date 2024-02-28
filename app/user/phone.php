<?php
namespace PHPEMS;
class app
{
	public $G;

	public function __construct()
	{
		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->sql = \PHPEMS\ginkgo::make('sql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->module = \PHPEMS\ginkgo::make('module');
		$this->html = \PHPEMS\ginkgo::make('html');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$groups = $this->user->getUserGroups();
		$this->tpl->assign('groups',$groups);
		$this->tpl->assign('userhash',$this->ev->get('userhash'));
		$this->_user = $_user = $this->session->getSessionUser();
		$this->order = \PHPEMS\ginkgo::make('orders','bank');
		if(!$_user['sessionuserid'] && !in_array($this->ev->url(2),array('register','login')))
		{
			$message = array(
				'statusCode' => 301,
				"message" => "请您重新登录",
			    "callbackType" => 'forward',
			    "forwardUrl" => "index.php?user-phone-login"
			);
			\PHPEMS\ginkgo::R($message);
		}
        $groups = $this->user->getUserGroups();
        $this->tpl->assign('groups',$groups);
        $this->tpl->assign('userhash',$this->ev->get('userhash'));
        $this->orderstatus = array(1=>'待支付',2=>'待发货',3=>'待收货',4 => '已完成',99 =>'已取消');
        $this->status = array('申请中','已受理','已出证','申请被驳回');
        $this->tpl->assign('orderstatus',$this->orderstatus);
        $this->tpl->assign('status',$this->status);
	}
}

?>