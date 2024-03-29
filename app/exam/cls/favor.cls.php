<?php
 namespace PHPEMS;
/*
 * Created on 2011-11-21
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 对地区进行操作
 */
class favor_exam
{
	public $G;

	public function __construct()
	{
		
	}

	public function _init()
	{
		$this->sql = \PHPEMS\ginkgo::make('sql');
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->basic = \PHPEMS\ginkgo::make('basic','exam');
		$this->exam = \PHPEMS\ginkgo::make('exam','exam');
		$this->section = \PHPEMS\ginkgo::make('section','exam');
	}

	public function getBestStudentsToday()
	{
		$t = TIME - 24*3600*7;
		$data = array("count(*) AS number,ehusername,max(ehscore) as ehscore",'examhistory',array(array("AND","ehstarttime >= :ehstarttime",'ehstarttime',$t)),"ehuserid","number DESC",10);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql);
	}

	public function getBestStudentsThisMonth()
	{
		$t = TIME - 24*3600*30;
		$data = array("count(*) AS number,ehusername,max(ehscore) as ehscore",'examhistory',array(array("AND","ehstarttime >= :ehstarttime",'ehstarttime',$t)),"ehuserid","number DESC",10);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql);
	}

	//通过用户ID获取收藏试题列表
	//参数：当前页码，单页显示数量，查询参数（数组或字符串）
	//返回值：试题列表数组
	public function getFavorListByUserid($args = array(),$page,$number = 20,$orderby = 'favorid desc')
	{
        $args[] = array("AND","favor.favorquestionid = questions.questionid");
        $data = array(
            'select' => false,
            'table' => array('favor','questions'),
            'query' => $args,
            'orderby' => $orderby
        );
        return $this->db->listElements($page,$number,$data);
	}

	//收藏试题
	//参数：试题ID，用户ID，考试ID
	//返回值：试题收藏后的插入ID
	public function favorQuestion($questionid,$userid,$subjectid)
	{
		$args = array("favorsubjectid"=>$subjectid,"favorquestionid" => $questionid,"favoruserid" => $userid,"favortime" => TIME );
		$data = array("favor",$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	//根据ID获取试题是否收藏
	//本函数暂不用
	public function getFavorById($id)
	{
		$data = array(false,'favor',array(array("AND","favorid = :favorid",'favorid',$id)));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,'favorquestion');
	}

	//根据用户ID和试题ID获取试题是否收藏
	//参数：试题ID，用户ID
	//返回值：试题信息数组（无则为FALSE）
	public function getFavorByQuestionAndUserId($id,$userid)
	{
		$data = array(false,'favor',array(array("AND","favorquestionid = :favorquestionid",'favorquestionid',$id),array("AND","favoruserid = :favoruserid",'favoruserid',$userid)));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,'favorquestion');
	}

	//根据ID删除试题
	public function delFavorById($id)
	{
		$data = array("favor",array(array("AND","favorid = :favorid",'favorid',$id)));
		$sql = $this->pdosql->makeDelete($data);
		$this->db->exec($sql);
		return true;
	}

	//错题记录函数
	//新增错题记录
	public function addRecord($args)
	{
		$args['recordtime'] = TIME;
		$data = array("record",$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	public function addRecords($userid,$ids,$subjectid)
	{
		foreach($ids as $id)
		{
			if(!$this->getRecordByQuestionAndUserId($id,$userid))
			{
				$args = array(
					'recordquestionid' => $id,
					'recorduserid' => $userid,
					'recordsubjectid' => $subjectid
				);
				$this->addRecord($args);
			}
		}
		return true;
	}

	//根据用户ID和试题ID获取试题是否被收入错题库
	public function getRecordByQuestionAndUserId($id,$userid)
	{
		$data = array(false,'record',array(array("AND","recordquestionid = :recordquestionid",'recordquestionid',$id),array("AND","recorduserid = :recorduserid",'recorduserid',$userid)));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,'recordquestion');
	}

	//删除错误记录
	public function delRecord($recordid)
	{
		$data = array("record",array(array("AND","recordid = :recordid",'recordid',$recordid)));
		$sql = $this->pdosql->makeDelete($data);
		$this->db->exec($sql);
		return true;
	}

	public function delRecordByArgs($args)
	{
		$data = array("record",$args);
		$sql = $this->pdosql->makeDelete($data);
		$this->db->exec($sql);
		return true;
	}

	//通过用户ID获取错误试题列表
	public function getRecordList($args = array(),$page,$number = 20,$order = 'recordid desc')
	{
        $data = array(
            'select' => false,
            'table' => array('record','questions'),
            'query' => $args,
            'orderby' => $order
        );
        return $this->db->listElements($page,$number,$data);
	}

	public function recordTask()
	{
		$time = strtotime(date('Y-m-d',TIME - 3*24*3600));
		$data = array(false,'record',array(array('AND','recordtime >= :recordtime','recordtime',$time)),false,'recordid desc',false);
		$sql = $this->pdosql->makeSelect($data);
		$rs = $this->db->fetchAll($sql);
		$log = array();
		foreach($rs as $r)
		{
			if(!$log[$r['recorduserid']][$r['recordsubjectid']])
			{
				$log[$r['recorduserid']][$r['recordsubjectid']] = 1;
				$this->setRecordData($r['recorduserid'],$r['recordsubjectid']);
			}
		}
	}

	public function setRecordData($userid,$subjectid)
	{
		$page = 1;
        $questions = array();
        $rs = array();
		while($page == 1 || $questions['data'])
		{
            $args = array();
            $args[] = array('AND','recorduserid = :recorduserid',"recorduserid",$userid);
            $args[] = array('AND','recordsubjectid = :recordsubjectid',"recordsubjectid",$subjectid);
            $args[] = array('AND','recordquestionid = questionid');
            $args[] = array('AND','questionstatus = 1');
			$questions = $this->getRecordList($args,$page);
			foreach($questions['data'] as $question)
			{
				if($question['questionparent'])
				{
					$data = array(false,'questionrows',array(array("AND","qrid = :qrid","qrid",$question['questionparent']),array('AND','qrstatus = 1')));
                    $sql = $this->pdosql->makeSelect($data);
                    $parent = $this->db->fetch($sql);
                    if($parent)
					{
						$data = array(false,'quest2knows',array(array("AND","qkquestionid = :qkquestionid","qkquestionid",$question['questionparent']),array("AND","qktype = 1")),false,false,false);
						$sql = $this->pdosql->makeSelect($data);
						$r = $this->db->fetchAll($sql);
						foreach($r as $tmp)
						{
							//$rs[$tmp['qkknowsid']][$parent['qrtype']][$parent['qrlevel']]['questionrows'][$parent['qrid']] = $parent['qrid'];
							$rs[$tmp['qkknowsid']][$parent['qrtype']]['questionrows'][$parent['qrid']] = $parent['qrid'];
						}
                    }
				}
				else
				{
					$data = array(false,'quest2knows',array(array("AND","qkquestionid = :qkquestionid","qkquestionid",$question['questionid']),array("AND","qktype = 0")),false,false,false);
                    $sql = $this->pdosql->makeSelect($data);
                    $r = $this->db->fetchAll($sql);
                    foreach($r as $tmp)
					{
                        //$rs[$tmp['qkknowsid']][$question['questiontype']][$question['questionlevel']]['question'][$question['questionid']] = $question['questionid'];
                        $rs[$tmp['qkknowsid']][$question['questiontype']]['question'][$question['questionid']] = $question['questionid'];
					}
				}
			}
			$page++;
		}
		$rd = $this->getRecordDataByUseridAndSubjectid($userid,$subjectid);
		if($rd)
		{
			$this->modifyRecordData($rd['rdid'],array('rddata' => $rs));
		}
		else
		{
            $args = array(
            	'rddata' => $rs,
				'rduserid' => $userid,
				'rdsubjectid' => $subjectid
			);
			$this->addRecordData($args);
		}
	}

    public function getRecordDataByUseridAndSubjectid($userid,$subjectid)
    {
        $data = array(false,'recorddata',array(array("AND","rduserid = :rduserid",'rduserid',$userid),array("AND","rdsubjectid = :rdsubjectid",'rdsubjectid',$subjectid)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql,'rddata');
    }

    public function getRecordData($id)
    {
        $data = array(false,'recorddata',array(array("AND","rdid = :rdid",'rdid',$id)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql,'rddata');
    }

    public function addRecordData($args)
    {
		$args['rdtime'] = TIME;
    	$data = array("recorddata",$args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
        return $this->db->lastInsertId();
    }

    public function delRecordData($rdid)
    {
        $data = array("recorddata",array(array("AND","rdid = :rdid",'rdid',$rdid)));
        $sql = $this->pdosql->makeDelete($data);
        $this->db->exec($sql);
        return true;
    }

    public function modifyRecordData($rdid,$args)
    {
        $data = array("recorddata",$args,array(array("AND","rdid = :rdid",'rdid',$rdid)));
        $sql = $this->pdosql->makeUpdate($data);
        $this->db->exec($sql);
        return true;
    }

	//根据用户和科目获取考试记录列表
	public function getExamHistoryListByArgs($args = array(),$page,$number = 20,$fields = false,$orderby = "ehscore DESC,ehid DESC")
	{
		$page = $page > 0?$page:1;
		$r = array();
		$args[] = array("AND","examhistory.ehuserid = user.userid");
		$data = array($fields,array('examhistory','user'),$args,false,$orderby,array(intval($page-1)*$number,$number));
		$sql = $this->pdosql->makeSelect($data);
		$r['data'] = $this->db->fetchAll($sql,'ehid');
		$data = array('count(*) AS number',array('examhistory','user'),$args);
		$sql = $this->pdosql->makeSelect($data);
		$t = $this->db->fetch($sql);
		$pages = $this->pg->outPage($this->pg->getPagesNumber($t['number'],$number),$page);
		$r['pages'] = $pages;
		$r['number'] = $t['number'];
		return $r;
	}

	//根据用户和科目获取考试记录列表
	public function getAllExamHistoryByArgs($args = array(),$fields = false)
	{
		$args[] = array("AND","examhistory.ehuserid = user.userid");
		$data = array($fields,array('examhistory','user'),$args,false,"ehscore DESC",false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql);
	}

	public function getStatsAllExamHistoryByArgs($args = array())
	{
		$args[] = array("AND","examhistory.ehuserid = user.userid");
		$data = array(false,array('examhistory','user'),$args,false,"ehscore DESC",false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,false,array('ehscorelist','ehuseranswer','ehtimelist'));
	}

	//根据用户和ID获取一个考试记录
	public function getExamHistoryById($id)
	{
		$data = array(false,'examhistory',array(array("AND","ehid = :ehid",'ehid',$id)));
		$sql = $this->pdosql->makeSelect($data);
		$r = $this->db->fetch($sql,array('ehscorelist','ehuseranswer','ehtimelist'));
		$r['ehquestion'] = unserialize(gzuncompress(base64_decode($r['ehquestion'])));
		$r['ehsetting'] = unserialize(gzuncompress(base64_decode($r['ehsetting'])));
		return $r;
	}

    public function getExamHistoryByArgs($args)
    {
        $data = array(false,'examhistory',$args);
        $sql = $this->pdosql->makeSelect($data);
        $r = $this->db->fetch($sql,array('ehscorelist','ehuseranswer','ehtimelist'));
        $r['ehquestion'] = unserialize(gzuncompress(base64_decode($r['ehquestion'])));
        $r['ehsetting'] = unserialize(gzuncompress(base64_decode($r['ehsetting'])));
        return $r;
    }

	//根据ID修改一个考试记录
	public function modifyExamHistory($ehid,$args)
	{
		$data = array("examhistory",$args,array(array("AND","ehid = :ehid",'ehid',$ehid)));
		$sql = $this->pdosql->makeUpdate($data);
		$this->db->exec($sql);
		return true;
	}

	//根据ID删除一个考试记录
	public function delExamHistory($ehid,$userid = false)
	{
		if($userid)
		{
			$data = array("examhistory",array(array("AND","ehid = :ehid",'ehid',$ehid),array("AND","ehuserid = :ehuserid",'ehuserid',$userid)));
		}
		else
		{
			$data = array("examhistory",array(array("AND","ehid = :ehid",'ehid',$ehid)));
		}
		$sql = $this->pdosql->makeDelete($data);
		$this->db->exec($sql);
		return true;
	}

	//批量清理考试记录
	public function clearExamHistory($args)
	{
		$data = array("examhistory",$args);
		$sql = $this->pdosql->makeDelete($data);
		$this->db->exec($sql);
		return true;
	}

	//获取记录数量
	public function getExamHistoryNumber($userid,$basicid,$type = 0)
	{
		$data = array('count(*) AS number',"examhistory",array(array("AND","ehuserid = :ehuserid",'ehuserid',$userid),array("AND","ehbasicid = :ehbasicid",'ehbasicid',$basicid),array("AND","ehtype = :ehtype",'ehtype',$type)));
		$sql = $this->pdosql->makeSelect($data);
		$r = $this->db->fetch($sql);
		return $r['number'];
	}

	//删除最后一个考试记录
	public function delLastExamHistory($userid,$subjectid,$type = 0)
	{
		$data = array(false,"examhistory",array(array("AND","ehuserid = :ehuserid",'ehuserid',$userid),array("AND","ehsubjectid = :ehsubjectid",'ehsubjectid',$subjectid),array("AND","ehtype = :ehtype",'ehtype',$type)),false,"ehid ASC",1);
		$sql = $this->pdosql->makeSelect($data);
		$r = $this->db->fetch($sql);
		$data = array("examhistory","ehid = '{$r['ehid']}'");
		$sql = $this->pdosql->makeDelete($data);
		$this->db->exec($sql);
		return true;
	}

	//添加一个考试记录
	public function addExamHistory($exam,$status = 1)
	{
		if(!$exam)return false;
		$t = TIME - $exam['examsessionstarttime'];
		if($t >= $exam['examsessiontime']*60)$t = $exam['examsessiontime']*60;
		$user = \PHPEMS\ginkgo::make('user','user')->getUserById($exam['examsessionuserid']);
		$args = array(
			'ehtype'=>$exam['examsessiontype'],
			'ehtimelist'=>$exam['examsessiontimelist'],
			'ehexam'=>$exam['examsession'],
			'ehexamid'=>$exam['examsessionkey'],
			'ehbasicid'=>$exam['examsessionbasic'],
			'ehquestion'=>base64_encode(gzcompress(serialize($exam['examsessionquestion']),9)),
			'ehsetting'=>base64_encode(gzcompress(serialize($exam['examsessionsetting']),9)),
			'ehuseranswer'=>$exam['examsessionuseranswer'],
			'ehstarttime'=>$exam['examsessionstarttime'],
			'ehtime'=>$t,
			'ehscore'=>$exam['examsessionscore'],
			'ehscorelist'=>$exam['examsessionscorelist'],
			'ehuserid'=>$exam['examsessionuserid'],
			'ehusername'=>$user['username'],
			'ehdecide' => intval($exam['examsessionsetting']['examdecide']),
			'ehstatus' => $status,
			'ehispass' => $exam['examsessionscore'] >= $exam['examsessionsetting']['examsetting']['passscore']?1:0,
			'ehbatch' => $exam['batch']
		);
		$data = array('examhistory',$args);
		$ehid = 0;
		$errnum = 0;
		while(!$ehid && $errnum < 3)
		{
			$sql = $this->pdosql->makeInsert($data);
			$aff = $this->db->exec($sql);
			$ehid = $this->db->lastInsertId();
			$errnum++;
		}
		if(!$ehid)
		{
			file_put_contents('data/save/'.$args['ehuserid'].'-'.$args['ehbasicid'].'-'.TIME.rand(100,999).'.txt',serialize($args));
		}
//		if($args['ehtype'] == 2 && $args['ehispass'])
//		{
//			\PHPEMS\ginkgo::make('progress','user')->modifyProgressByArgs(array(array("AND","prsexamid = :prsexamid","prsexamid",$args['ehbasicid']),array("AND","prsexamstatus = 0")),array('prsexamstatus' => 1));
//			\PHPEMS\ginkgo::make('progress','user')->modifyProgressByArgs(array(array("AND","prscoursestatus = 1"),array("AND","prsexamstatus = 1"),array("AND","prstatus = 0"),array("AND","prsexamid = :prsexamid","prsexamid",$args['ehbasicid'])),array('prstatus' => 1,'prsendtime' => TIME));
//		}
		return $ehid;
	}

	public function getAvgScore($args)
	{
		$data = array("avg(ehscore) as avgscore",'examhistory',$args,false,false,false);
		$sql = $this->pdosql->makeSelect($data);
		$r = $this->db->fetch($sql);
		return $r['avgscore'];
	}

	public function getExamUseNumber($userid,$examid,$basicid,$batch = 1,$type = 2)
	{
		$data = array('count(*) AS number',"examhistory",array(array("AND","ehuserid = :ehuserid",'ehuserid',$userid),array("AND","ehexamid = :ehexamid",'ehexamid',$examid),array("AND","ehbasicid = :ehbasicid",'ehbasicid',$basicid),array("AND","ehtype = :ehtype",'ehtype',$type),array("AND","ehneedresit = 0"),array("AND","ehbatch = :ehbatch","ehbatch",$batch)));
		$sql = $this->pdosql->makeSelect($data);
		$r = $this->db->fetch($sql);
		return $r['number'];
	}

	public function getExamScoreListByBasicId($basicid,$page,$number = 10)
	{
		$data = array(
			'select' => false,
			'table' => array('examhistory','user'),
			'query' => array(array("AND","ehbasicid = :ehbasicid",'ehbasicid',$basicid),array("AND","ehtype = 2"),array("AND","ehuserid = userid"),array("AND","ehstatus = 1")),
			'orderby' => 'ehscore DESC,ehid DESC'
		);
		return $this->db->listElements($page,$number,$data);
	}

	public function getUserScoreIndex($basicid,$userid,$score)
	{
		$data = array("count(*) as number",'examhistory',array(array("AND","ehbasicid = :ehbasicid",'ehbasicid',$basicid),array("AND","ehscore > :ehscore",'ehscore',$score)),false,false,false);
		$sql = $this->pdosql->makeSelect($data);
		$r = $this->db->fetch($sql);
		return $r['number'] + 1;
	}

	public function getUserTopScore($basicid,$userid)
	{
		$data = array("max(ehscore) as ehscore",'examhistory',array(array("AND","ehbasicid = :ehbasicid",'ehbasicid',$basicid),array("AND","ehuserid = :ehuserid",'ehuserid',$userid),array("AND","ehtype = 2")),false,false,false);
		$sql = $this->pdosql->makeSelect($data);
		$r = $this->db->fetch($sql);
		if($r['ehscore'] > 0)$s = array('score' => $r['ehscore']);
		else
		$s = array('score' => 0);
		$data = array("count(*) as number",'examhistory',array(array("AND","ehbasicid = :ehbasicid",'ehbasicid',$basicid),array("AND","ehscore > :ehscore",'ehscore',$s['score']),array("AND","ehtype = 2")),false,false,false);
		$sql = $this->pdosql->makeSelect($data);
		$r = $this->db->fetch($sql);
		$s['index'] = $r['number'] + 1;
		return $s;
	}

}

?>
