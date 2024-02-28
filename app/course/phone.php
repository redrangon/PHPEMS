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
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');

		$this->db = \PHPEMS\ginkgo::make('pdodb');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->html = \PHPEMS\ginkgo::make('html');
		$this->files = \PHPEMS\ginkgo::make('files');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->category = \PHPEMS\ginkgo::make('category');
		$this->course = \PHPEMS\ginkgo::make('course','course');
		$this->content = \PHPEMS\ginkgo::make('content','course');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->_user = $_user = $this->session->getSessionUser();
		if(!$this->_user['sessionuserid'])
		{
			if($this->ev->get('userhash'))
			exit(json_encode(array(
				'statusCode' => 301,
				"message" => "请您重新登录",
			    "callbackType" => 'forward',
			    "forwardUrl" => "index.php?user-phone-login"
			)));
			else
			{
				header("location:index.php?user-phone-login");
				exit;
			}
		}
        $user = $this->user->getUserById($this->_user['sessionuserid']);
        $this->tpl->assign('_user',$user);
		$this->rcats = $rcats = $this->category->getCategoriesByArgs("catparent = '0'");
		$this->tpl->assign('rcats',$rcats);
		$this->tpl->assign('userhash',$this->ev->get('userhash'));
		$this->log = \PHPEMS\ginkgo::make('log','course');
	}
}

?>