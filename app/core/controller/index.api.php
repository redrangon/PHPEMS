<?php
/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
namespace PHPEMS; 
use thiagoalessio\TesseractOCR\TesseractOCR;

set_time_limit(0);
class action extends app
{
	public function display()
	{
        $this->pdo = \PHPEMS\ginkgo::make('pepdo');
	    $action = $this->ev->url(3);
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}

    public function phpml()
    {
        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];
        $classifier = new \Phpml\Classification\KNearestNeighbors();
        $classifier->train($samples,$labels);
        echo $classifier->predict([3, 9]);
    }

    public function recog()
    {
        ini_set('memory_limit', '2048M');
        $recog = \PHPEMS\ginkgo::make('recog');
        $recog->loadImg('sfz.jpg');
        try {
            $id_no = $recog->recognize_id_number();
            echo "sfz is {$id_no}";
        } catch (exception $e) {
            print_r($e);
        }
    }

    public function ocr()
    {
        $path = "./3.jpg";
        $text = new TesseractOCR($path);
        $t = $text->lang('chi_sim')->run();
        echo $t;
    }

	public function sendmail()
	{
        $this->user = \PHPEMS\ginkgo::make('user','user');
	    $email = $this->ev->get('email');
        if(!\PHPEMS\ginkgo::make('strings')->isEmail($email))
        {
            $message = array(
                'statusCode' => 300,
                'message' => '错误的邮箱'
            );
            exit(json_encode($message));
        }
        $action = $this->ev->get('action');
        $user = $this->user->getUserByEmail($email);
        if(!$user && $action != 'reg')
        {
            $message = array(
                'statusCode' => 300,
                'message' => '该邮箱未注册'
            );
            exit(json_encode($message));
        }
        if($user && $action == 'reg')
        {
            $message = array(
                'statusCode' => 300,
                'message' => '该邮箱已注册'
            );
            exit(json_encode($message));
        }
        if(!$action)$action = 'findpassword';
        $randcode = rand(1000,9999);
        $_SESSION['phonerandcode'] = array(
            $action => $randcode,
			'email' => $email
        );
        $app = \PHPEMS\ginkgo::make('apps','core')->getApp('user');
        if(!$app['appsetting']['emailverify'])
		{
            $message = array(
                'statusCode' => 300,
                'message' => '管理员未开启邮箱验证'
            );
            exit(json_encode($message));
		}
        if($app['appsetting']['emailaccount'] && $app['appsetting']['emailpassword'])
		{
            $smtp = \PHPEMS\ginkgo::make('sendmail');
            //$smtp->setServer('smtp.qq.com','278768688@qq.com','thjmltwesfcgcbef','465',true);
            $smtp->setServer('smtp.qq.com',$app['appsetting']['emailaccount'],$app['appsetting']['emailpassword'],'465',true);
            $smtp->setFrom($app['appsetting']['emailaccount']);
            $smtp->setReceiver($email);
        }
		else
        {
            $message = array(
                'statusCode' => 300,
                'message' => '管理邮箱设置错误，请联系管理员'
            );
            exit(json_encode($message));
        }
        if($user)
        {
            if($action == 'findpassword')
            {
                $smtp->setMail('找回密码验证码','您的找回密码验证码是'.$randcode);
                $smtp->sendMails();
            }
            $message = array(
                'statusCode' => 200
            );
        }
        else
        {
            if($action == 'reg')
            {
                $smtp->setMail('注册验证码','您的注册验证码是'.$randcode);
                $smtp->sendMails();
            }
            $message = array(
                'statusCode' => 200
            );
        }
        exit(json_encode($message));

	}

	private function qrcode()
	{
		header("Content-type: image/png");
		require_once("lib/include/phpqrcode.php");
		$data = urldecode($this->ev->get('data'));
		QRcode::png($data);
	}
	
	public function csp()
	{
		$this->excel = \PHPEMS\ginkgo::make('excel');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$data = $this->excel->getExcelContent('x1.xlsx');
		foreach($data as $data)
		{
			$username = $data[0];
			$clsname = $data[1];
			$user = $this->user->getUserByUserName($username);
			$this->user->modifyUserInfo($user['userid'],array("normal_classs" => $clsname));
			echo "{$username}<br />";
		}
	}

	public function index()
	{
		exit;
	}
}


?>
