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
		$this->files = \PHPEMS\ginkgo::make('files');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$_user = $this->session->getSessionUser();

		$this->_user = $this->user->getUserById($_user['sessionuserid']);
		$this->_user['teacher_subjects'] = unserialize($this->_user['teacher_subjects']);
		$group = $this->user->getGroupById($_user['sessiongroupid']);
		if(!$this->_user['teacher_subjects'])
		{
			if($this->ev->get('userhash'))
			exit(json_encode(array(
				'statusCode' => 300,
				"message" => "您不具备管理权限",
			    "callbackType" => 'forward',
			    "forwardUrl" => "index.php?exam-app"
			)));
			else
			{
				header("location:index.php?exam-app");
				exit;
			}
		}
		//生产一个对象
		$this->teachsubjects = implode(',',$this->_user['teacher_subjects']);
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->db = \PHPEMS\ginkgo::make('pepdo');

		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->html = \PHPEMS\ginkgo::make('html');
		$this->apps = \PHPEMS\ginkgo::make('apps','core');
		$this->basic = \PHPEMS\ginkgo::make('basic','exam');
		$this->area = \PHPEMS\ginkgo::make('area','exam');

		$this->section = \PHPEMS\ginkgo::make('section','exam');
		$this->favor = \PHPEMS\ginkgo::make('favor','exam');
		$this->exam = \PHPEMS\ginkgo::make('exam','exam');

		$this->tpl->assign('ols',array(1=>'一','二','三','四','五','六','七','八','九','十','十一','十二','十三','十四','十五','十六','十七','十八','十九','二十'));
		$this->tpl->assign('action',$this->ev->url(2)?$this->ev->url(2):'exams');
		$this->tpl->assign('_user',$this->_user);
		$this->tpl->assign('userhash',$this->ev->get('userhash'));
		$apps = $this->apps->getAppList();
		$this->tpl->assign('apps',$apps);
	}
}

?>