<?php
namespace PHPEMS;
class app
{
	public $G;

	public function __construct()
	{
		
		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->sql = \PHPEMS\ginkgo::make('sql');
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pdodb');
		$this->category = \PHPEMS\ginkgo::make('category');
		$this->html = \PHPEMS\ginkgo::make('html');
		$this->module = \PHPEMS\ginkgo::make('module');
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
			{
				header("location:index.php?core-master-login");
				exit;
			}
		}
		$localapps = $this->apps->getLocalAppList();
		$apps = $this->apps->getAppList();
		$this->tpl->assign('localapps',$localapps);
		$this->tpl->assign('apps',$apps);
		$modules = $this->module->getModulesByApp('content');
		$groups = $this->user->getUserGroups();
		$user = $this->user->getUserById($_user['sessionuserid']);
		$user['manager_apps'] = unserialize($user['manager_apps']);
		$this->tpl->assign('_user',$user);
		if(!in_array(\PHPEMS\ginkgo::$app,$user['manager_apps']) && $apps['user']['appsetting']['managemodel'])
		{
			header("location:index.php?core-master");
			exit();
		}
		$this->tpl->assign('modules',$modules);
		$this->tpl->assign('groups',$groups);
		$this->tpl->assign('userhash',$this->ev->get('userhash'));
		$this->content = \PHPEMS\ginkgo::make('content','content');
		$this->block = \PHPEMS\ginkgo::make('block','content');
		$this->position = \PHPEMS\ginkgo::make('position','content');
	}
}

?>