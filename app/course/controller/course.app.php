<?php
 namespace PHPEMS;
/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class action extends app
{
	public function display()
	{
		$action = $this->ev->url(3);
		$this->progress = \PHPEMS\ginkgo::make('progress','user');
		$this->module = \PHPEMS\ginkgo::make('module');
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}

	private function pdfview()
	{
		$this->tpl->display('viewer');
	}

	private function coursejs()
	{
		$contentid = $this->ev->get('contentid');
		$r = $this->log->getLogByArgs(array(array('AND',"loguserid = :loguserid",'loguserid',$this->_user['sessionuserid']),array('AND',"logcourseid = :logcourseid",'logcourseid',$contentid)));
		if((TIME - $r['logtime']) > 10)echo "document.write('[已学习]');";
	}

    private function jumppaper()
    {
        $csid = $this->ev->get('csid');
        $progress = $this->progress->getProgressByArgs(array(array("AND","prscourseid = :prscourseid","prscourseid",$csid),array("and","prsuserid = :prsuserid","prsuserid",$this->_user['sessionuserid'])));
        if(!$progress['prscoursestatus'])
        {
            $message = array(
                'statusCode' => 300,
                "message" => "请先学完课程"
            );
            \PHPEMS\ginkgo::R($message);
        }
        $course = $this->course->getCourseById($csid);
        $basicid = $course['csbasicid'];
        //$examid = $this->ev->get('examid');
        if(!$basicid)
        {
            $message = array(
                'statusCode' => 300,
                "message" => "请联系管理员设置考场"
            );
            \PHPEMS\ginkgo::R($message);
        }
        $this->session->setSessionValue(array('sessioncurrent'=>$basicid));
        header("location:index.php?exam-app-basics-detail&basicid=".$basicid);
        exit;
    }

	private function opencourse()
	{
		$csid = $this->ev->get('csid');
		$course = $this->course->getCourseById($csid);
		if($this->ev->get('opencs'))
		{
			$userid = $this->_user['sessionuserid'];
			if($this->course->getOpenCourseByUseridAndCsid($userid,$csid))
			{
				$message = array(
					'statusCode' => 300,
					"message" => "您已经开通了本课程"
				);
			}
			if($course['csdemo'])
			{
				$time = 365*24*3600;
			}
			else
			{
				$opentype = intval($this->ev->get('opentype'));
				$price = 0;
				if(trim($course['csprice']))
				{
					$price = array();
					$course['csprice'] = explode("\n",$course['csprice']);
					foreach($course['csprice'] as $p)
					{
						if($p)
						{
							$p = explode(":",$p);
							$price[] = array('time'=>intval($p[0]),'price'=>intval($p[1]));
						}
					}
				}
				if(!$price[$opentype])$t = $price[0];
				else
				$t = $price[$opentype];
				$time = $t['time']*24*3600;
				$score = $t['price'];
				$user = $this->user->getUserById($this->_user['sessionuserid']);
				if($user['usercoin'] < $score)
				{
					$message = array(
						'statusCode' => 300,
						"message" => "开通失败，您的积分不够"
					);
					\PHPEMS\ginkgo::R($message);
				}
				else
				{
					$args = array("usercoin" => $user['usercoin'] - $score);
					$this->user->modifyUserInfo($this->_user['sessionuserid'],$args);
				}
			}
			$args = array('ocuserid'=>$userid,'occourseid'=>$csid,'ocendtime'=>TIME + $time);
			$this->course->openCourse($args);
			$message = array(
				'statusCode' => 200,
				"message" => "开通成功!",
				"callbackType" => "forward",
			    "forwardUrl" => "index.php?course-app-course&csid=".$csid
			);
			\PHPEMS\ginkgo::R($message);
		}
		else
		{
			$price = 0;
			if(trim($course['csprice']))
			{
				$price = array();
				$course['csprice'] = explode("\n",$course['csprice']);
				foreach($course['csprice'] as $p)
				{
					if($p)
					{
						$p = explode(":",$p);
						$price[] = array('time'=>$p[0],'price'=>$p[1]);
					}
				}
				$this->tpl->assign('price',$price);
			}
			$isopen = $this->course->getOpenCourseByUseridAndCsid($this->_user['sessionuserid'],$csid);
			$this->tpl->assign('isopen',$isopen);
			$this->tpl->assign('course',$course);
			$this->tpl->display('opencourse');
		}
	}

	private function recordtime()
	{
		$courseid = $this->ev->get('courseid');
		$time = intval($this->ev->get('time'));
		$r = $this->log->getLogByArgs(array(array('AND',"loguserid = :loguserid",'loguserid',$this->_user['sessionuserid']),array('AND',"logcourseid = :logcourseid",'logcourseid',$courseid)));
		if($r)
        {
            $this->log->modifyLog($r['logid'],array('logprogress' => $time));
		}
        exit('1');
	}

	private function endstatus()
    {
        $courseid = $this->ev->get('courseid');
        $r = $this->log->getLogByArgs(array(array('AND',"loguserid = :loguserid",'loguserid',$this->_user['sessionuserid']),array('AND',"logcourseid = :logcourseid",'logcourseid',$courseid)));
        if($r)
        {
            $this->log->modifyLog($r['logid'],array('logstatus' => 1,'logendtime' => TIME, 'logprogress' => 0));
            $content = $this->content->getCourseById($courseid);
            if($content)
			{
				$csid = $content['coursecsid'];
                $course = $this->course->getCourseById($csid);
				$ishave = $this->progress->getProgressByArgs(array(array("AND","prscourseid = :prscourseid","prscourseid",$csid),array("and","prsuserid = :prsuserid","prsuserid",$this->_user['sessionuserid'])));
				if(!$ishave)
				{
					$args = array(
						'prsuserid' => $this->_user['sessionuserid'],
						'prscourseid' => $csid,
						'prstime' => TIME,
						'prsexamid' => $course['csbasicid']
					);
					$id = $this->progress->addProgress($args);
				}
				else
				$id = $ishave['prsid'];
				$allnumber = $this->course->getCourseNumberByCsid($csid);
                $passednumber = $this->log->getPassedLogsNumberByCsid($csid,$this->_user['sessionuserid']);
                if($passednumber >= $allnumber)
				{
					$this->progress->modifyProgress($id,array('prscoursestatus' => 1));
				}
			}
        }
        exit('1');
    }

	private function setcourse()
	{
		$cnoteid = $this->ev->get('cnoteid');
		$note = $this->ev->get('note');
		$this->content->setCourseNote(array('clsnote' => $note),$cnoteid);
		$message = array(
			'statusCode' => 200,
			"message" => "保存成功"
		);
		\PHPEMS\ginkgo::R($message);
	}

	private function index()
	{
		$page = $this->ev->get('page');
		$csid = $this->ev->get('csid');
		$contentid = $this->ev->get('contentid');
		$course = $this->course->getCourseById($csid);
		$oc = $this->course->getOpenCourseByUseridAndCsid($this->_user['sessionuserid'],$csid);
		if(!$oc)
		{
			header("location:index.php?course-app-course-opencourse&csid=".$csid);
			exit;
		}
		else
		{
            $this->tpl->assign('oc',$oc);
		}
		if($course['cstype'])
		{
			$cdata = $this->course->getCourseContentStatus($course['csid'],$this->_user['sessionuserid']);
			if($cdata['lock'][$contentid])
			{
				$message = array(
					'statusCode' => 300,
					"message" => "请先学完上节课程"
				);
				\PHPEMS\ginkgo::R($message);
			}
        }
		$catbread = $this->category->getCategoryPos($course['cscatid']);
		$cat = $this->category->getCategoryById($course['cscatid']);
		$contents = $this->content->getCoursesByCsid($csid);
		if($contentid)$content = $this->content->getCourseById($contentid);
		else
		$content = current($contents['data']);
		if($content['courseid'])
        {
            $r = $this->log->getLogByArgs(array(array('AND',"loguserid = :loguserid",'loguserid',$this->_user['sessionuserid']),array('AND',"logcourseid = :logcourseid",'logcourseid',$content['courseid'])));
            if(!$r)$this->log->addLog(array('loguserid' => $this->_user['sessionuserid'],'logcourseid' => $content['courseid']));
        }
		$module = $this->module->getModuleById($content['coursemoduleid']);
		switch($module['modulecode'])
		{
			case 'pdf':
			$template = 'course_pdf';
			break;

			default:
			$template = 'course_default';
			break;
		}
		$logs = $this->log->getLogsByCsid($course['csid'],$this->_user['sessionuserid']);
		$this->tpl->assign('logs',$logs);
		$this->tpl->assign('cat',$cat);
		$this->tpl->assign('page',$page);
		$this->tpl->assign('catbread',$catbread);
		$this->tpl->assign('course',$course);
		$this->tpl->assign('contents',$contents);
		//$this->tpl->assign('cdata',$cdata);
		$this->tpl->assign('content',$content);
		$this->tpl->display($template);
	}
}


?>
