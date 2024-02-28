<?php
namespace PHPEMS;
class app
{
	public $G;

	//初始化信息
	public function __construct()
	{
		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->_user = $_user = $this->session->getSessionUser();
		$group = $this->user->getGroupById($_user['sessiongroupid']);
		if($group['groupmoduleid'] != 1)
		{
			if($this->ev->get('userhash'))
			exit(json_encode(array(
				'statusCode' => 301,
				"message" => "请您重新登录",
			    "callbackType" => 'forward',
			    "forwardUrl" => "index.php?core-master-login"
			)));
			else
			{
				header("location:?core-master-login");
				exit;
			}
		}
		//生产一个对象
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->sql = \PHPEMS\ginkgo::make('sql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->html = \PHPEMS\ginkgo::make('html');
		$this->files = \PHPEMS\ginkgo::make('files');
		$this->apps = \PHPEMS\ginkgo::make('apps','core');
		$this->ce = \PHPEMS\ginkgo::make('ce','certificate');
		$user = $this->user->getUserById($_user['sessionuserid']);
		$user['manager_apps'] = unserialize($user['manager_apps']);
		$this->tpl->assign('_user',$user);
		$localapps = $this->apps->getLocalAppList();
		$apps = $this->apps->getAppList();
		$this->tpl->assign('localapps',$localapps);
		$this->tpl->assign('apps',$apps);
		if(!in_array(\PHPEMS\ginkgo::$app,$user['manager_apps']) && $apps['user']['appsetting']['managemodel'])
		{
			header("location:index.php?core-master");
			exit();
		}
		$this->tpl->assign('userhash',$this->ev->get('userhash'));
	}
}

?>