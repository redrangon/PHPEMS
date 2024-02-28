<?php
namespace PHPEMS;
class app
{
	public $G;
	private $sc = 'testSys&dongao';

	public function __construct()
	{
		
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->files = \PHPEMS\ginkgo::make('files');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->apps = \PHPEMS\ginkgo::make('apps','core');
		$_user = $this->_user = $this->session->getSessionUser();
		$group = $this->user->getGroupById($_user['sessiongroupid']);
		if(!$_user['sessionuserid'])
		{
            $message = array(
                'statusCode' => 300,
                "message" => "请您重新登录",
                "callbackType" => 'forward',
                "forwardUrl" => "index.php?user-app-login"
            );
            \PHPEMS\ginkgo::R($message);
		}
		$this->attach = \PHPEMS\ginkgo::make('attach','document');
		$this->allowexts = $this->attach->getAllowAttachExts();
        $this->forbidden = array('rpm','exe','hta','php','phpx','asp','aspx','jsp');
		//$this->allowexts = array('zip','jpg','rar','png','gif','mp3','mp4','ogg','webm');
	}
}

?>