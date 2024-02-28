<?php
namespace PHPEMS;
class app
{
	public $G;
	public $_user;

	public function __construct()
	{
		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->sql = \PHPEMS\ginkgo::make('sql');
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->files = \PHPEMS\ginkgo::make('files');
		$this->apps = \PHPEMS\ginkgo::make('apps','core');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->_user = $_user = $this->session->getSessionUser();
		$group = $this->user->getGroupById($_user['sessiongroupid']);
		if($group['groupid'] != 1)
		{
			if($this->ev->get('userhash'))
			exit(json_encode(array(
				'statusCode' => 300,
				"message" => "请您重新登录",
			    "callbackType" => 'forward',
			    "forwardUrl" => "index.php?core-master-login"
			)));
			else
			header("location:index.php?core-master-login");
		}
		$user = $this->user->getUserById($_user['sessionuserid']);
		$user['manager_apps'] = unserialize($user['manager_apps']);
		$this->tpl->assign('_user',$user);
		$apps = $this->apps->getAppList();
		if(!in_array(\PHPEMS\ginkgo::$app,$user['manager_apps']) && $apps['user']['appsetting']['managemodel'])
		{
			header("location:index.php?core-master");
			exit();
		}
		$this->tpl->assign('action',$this->ev->url(2)?$this->ev->url(2):'user');
		$localapps = $this->apps->getLocalAppList();
		$apps = $this->apps->getAppList();
		$this->tpl->assign('localapps',$localapps);
		$this->tpl->assign('apps',$apps);
		$this->orders = \PHPEMS\ginkgo::make('orders','bank');
        $this->orderstatus = array(1=>'待支付',2=>'待发货',3=>'待收货',4 => '已完成',99 =>'已取消');
		$this->tpl->assign('orderstatus',$this->orderstatus);
        $this->paytypes = array('handle' => '手动','alipay' => '支付宝','wxpay' => '微信','payjs' => 'PayJZ');
        $this->tpl->assign('paytypes',$this->paytypes);
		$this->tpl->assign('userhash',$this->ev->get('userhash'));
	}
}

?>