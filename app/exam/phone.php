<?php
namespace PHPEMS;
class app
{
	public $G;
	public $data = array();
	public $sessionvars;

	public function __construct()
	{
		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->_user = $this->session->getSessionUser();
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
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->exam = \PHPEMS\ginkgo::make('exam','exam');
		$this->basic = \PHPEMS\ginkgo::make('basic','exam');
		$this->section = \PHPEMS\ginkgo::make('section','exam');
		$this->question = \PHPEMS\ginkgo::make('question','exam');
		$this->favor = \PHPEMS\ginkgo::make('favor','exam');
		if(!$this->data['openbasics'])$this->data['openbasics'] = $this->basic->getOpenBasicsByUserid($this->_user['sessionuserid']);
		if(!$this->_user['sessioncurrent'] || !$this->data['openbasics'][$this->_user['sessioncurrent']])
		{
			$this->data['currentbasic'] = current($this->data['openbasics']);
			$this->_user['sessioncurrent'] = $this->data['currentbasic']['basicid'];
			$this->session->setSessionValue(array('sessioncurrent'=>$this->_user['sessioncurrent']));
		}
		else
		$this->data['currentbasic'] = $this->data['openbasics'][$this->_user['sessioncurrent']];
        $app = \PHPEMS\ginkgo::make('apps','core')->getApp('exam');
        $this->setting = $app['appsetting'];
		$this->selectorder = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
		$this->tpl->assign('ols',array(1=>'一','二','三','四','五','六','七','八','九','十','十一','十二','十三','十四','十五','十六','十七','十八','十九','二十'));
		$this->tpl->assign('selectorder',$this->selectorder);
		$this->tpl->assign('data',$this->data);
		$this->tpl->assign('_user',$this->user->getUserById($this->_user['sessionuserid']));
		$this->tpl->assign('userhash',$this->ev->get('userhash'));
		if($this->data['currentbasic']['basicexam']['model'] == 2)
		{
			if($this->ev->url('2') && !in_array($this->ev->url('2'),array('index','basics','exam','recover','history')))
			{
				$message = array(
                    'statusCode' => 200,
                    "callbackType" => 'forward',
                    "forwardUrl" => "index.php?exam-phone-exam"
                );
                \PHPEMS\ginkgo::R($message);
			}
		}
	}
}

?>