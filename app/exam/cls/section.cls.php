<?php
 namespace PHPEMS;
/*
 * Created on 2011-11-21
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 对地区进行操作
 */
class section_exam
{
	public $G;

	public function __construct()
	{
		
		$this->sql = \PHPEMS\ginkgo::make('sql');
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->ev = \PHPEMS\ginkgo::make('ev');
	}

	//根据参数查询某一章节
	public function getSectionByArgs($args)
	{
		$data = array(false,'sections',$args);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

	//根据参数查询章节列表
	public function getSectionListByArgs($args)
	{
		$data = array(false,'sections',$args,false,'sectionsequence desc,sectionid asc');
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'sectionid');
	}

	//根据参数获取章节列表
	public function getSectionList($page,$number = 20,$args = 1)
	{
		$page = $page > 0?$page:1;
		$r = array();
		$data = array(false,'sections',$args,false,'sectionsequence desc,sectionid asc',array(intval($page-1)*$number,$number));
		$sql = $this->pdosql->makeSelect($data);
		$r['data'] = $this->db->fetchAll($sql);
		$data = array('count(*) AS number','sections',$args);
		$sql = $this->pdosql->makeSelect($data);
		$t = $this->db->fetch($sql);
		$pages = $this->pg->outPage($this->pg->getPagesNumber($t['number'],$number),$page);
		$r['pages'] = $pages;
		$r['number'] = $t['number'];
		return $r;
	}

	//添加章节
	public function addSection($args)
	{
		$data = array('sections',$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	//删除章节
	public function delSection($id)
	{
		$data = array('sections',array(array("AND","sectionid = :sectionid",'sectionid',$id)));
		$sql = $this->pdosql->makeDelete($data);
		return $this->db->exec($sql);
	}

	//修改章节信息
	public function modifySection($id,$args)
	{
		$data = array('sections',$args,array(array("AND","sectionid = :sectionid",'sectionid',$id)));
		$sql = $this->pdosql->makeUpdate($data);
		return $this->db->exec($sql);
	}

	//获取所有知识点
	public function getAllKnowsBySubject($subjectid)
	{
		$data = array(false,array('sections','knows'),array(array("AND","sections.sectionsubjectid = :subjectid",'subjectid',$subjectid),array("AND","sections.sectionid = knows.knowssectionid"),array("AND","knows.knowsstatus = 1")),false,'knowssequence desc,knowsid asc',false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'knowsid');
	}

	//获取多科目所有知识点
	public function getAllKnowsBySubjects($subjectids)
	{
		$data = array(false,array('sections','knows'),array(array("AND","find_in_set(sections.sectionsubjectid,:subjectids)",'subjectids',$subjectids),array("AND","sections.sectionid = knows.knowssectionid"),array("AND","knows.knowsstatus = 1")),false,'knowssequence desc,knowsid asc',false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'knowsid');
	}

	//根据章节获取知识点列表
	public function getKnowsList($page,$number = 20,$args = 1)
	{
		$page = $page > 0?$page:1;
		$r = array();
		$data = array(false,'knows',$args,false,'knowssequence desc,knowsid asc',array(intval($page-1)*$number,$number));
		$sql = $this->pdosql->makeSelect($data);
		$r['data'] = $this->db->fetchAll($sql);
		$data = array('count(*) AS number','knows',$args);
		$sql = $this->pdosql->makeSelect($data);
		$t = $this->db->fetch($sql);
		$pages = $this->pg->outPage($this->pg->getPagesNumber($t['number'],$number),$page);
		$r['pages'] = $pages;
		$r['number'] = $t['number'];
		return $r;
	}

	//根据参数获取全部匹配的知识点列表
	public function getKnowsListByArgs($args)
	{
		$data = array(false,'knows',$args,false,'knowssequence desc,knowsid asc',false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'knowsid');
	}

    public function getKnowsById($knowsid)
    {
        $data = array(false,'knows',array(array('AND',"knowsid = :knowsid",'knowsid',$knowsid)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
    }

	//根据参数获取某一知识点
	public function getKnowsByArgs($args)
	{
		$data = array(false,'knows',$args,false,false,false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

	//添加知识点
	public function addKnows($args)
	{
		$args['knowsstatus'] = 1;
		$data = array('knows',$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	//删除知识点
	public function delKnows($knowsid,$bool = false)
	{
		if($bool)
		{
			$data = array('knows',array(array('AND',"knowsid = :knowsid",'knowsid',$knowsid)));
			$sql = $this->pdosql->makeDelete($data);
			return $this->db->exec($sql);
		}
		else
		{
			$data = array('knows',array('knowsstatus'=>0),array(array('AND',"knowsid = :knowsid",'knowsid',$knowsid)));
			$sql = $this->pdosql->makeUpdate($data);
			return $this->db->exec($sql);
		}
	}

	//恢复知识点
	public function backKnows($knowsid)
	{
		$data = array('knows',array('knowsstatus'=>1),array(array('AND',"knowsid = :knowsid",'knowsid',$knowsid)));
		$sql = $this->pdosql->makeUpdate($data);
		return $this->db->exec($sql);
	}

	//修改知识点
	public function modifyKnows($knowsid,$args)
	{
		$data = array('knows',$args,array(array('AND',"knowsid = :knowsid",'knowsid',$knowsid)));
		$sql = $this->pdosql->makeUpdate($data);
		return $this->db->exec($sql);
	}

	//根据知识点获取章节和科目信息
	public function getSubjectAndSectionByKnowsid($knowsid)
	{
		$data = array(false,array('knows','sections'),array(array('AND',"knows.knowsid = :knowsid",'knowsid',$knowsid),array('AND',"knows.knowssectionid = sections.sectionid")));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

    public function getQuestionsByKnows($knowsid)
    {
        $knows = $this->getKnowsById($knowsid);
        $knows['knowsnumber'] = unserialize($knows['knowsnumber']);
        $knows['knowsquestions'] = unserialize($knows['knowsquestions']);
        if(!$knows['knowsquestions'])
		{
			$data = array('DISTINCT questionid',array('questions','quest2knows'),array(array("AND","find_in_set(quest2knows.qkknowsid,:knowsid)",'knowsid',$knowsid),array("AND","quest2knows.qktype = 0"),array("AND","quest2knows.qkquestionid = questions.questionid"),array("AND","questions.questionstatus = 1")),false,false,false);
			$sql1 = $this->pdosql->makeSelect($data);
			$data = array('questionid,questiontype',"questions",array(array("AND","questionid in (%CHILDSQL%)")),false,"questionparent asc,questionsequence asc,questionid asc",false);
			$sql = $this->pdosql->makeSelect($data);
			$sql['sql'] = str_replace("%CHILDSQL%",$sql1['sql'],$sql['sql']);
			$sql['v'] = array_merge($sql1['v'],$sql['v']);
			$r = $this->db->fetchAll($sql);
			$t = array();
			$n = array();
			foreach($r as $p)
			{
				$t[$p['questiontype']][] = $p['questionid'];
			}
            $data = array('DISTINCT questionid',array('questions','questionrows','quest2knows'),array(array("AND","find_in_set(quest2knows.qkknowsid,:knowsid)",'knowsid',$knowsid),array("AND","quest2knows.qktype = 1"),array("AND","quest2knows.qkquestionid = questionrows.qrid"),array("AND","questions.questionparent = questionrows.qrid"),array("AND","questionrows.qrstatus = 1")),false,false,false);
			$sql1 = $this->pdosql->makeSelect($data);
			$data = array('questionid,qrtype',array('questions','questionrows'),array(array("AND","questionid in (%CHILDSQL%)"),array("AND","questionparent = qrid")),false,"questionparent asc,questionsequence asc,questionid asc",false);
			$sql = $this->pdosql->makeSelect($data);
			$sql['sql'] = str_replace("%CHILDSQL%",$sql1['sql'],$sql['sql']);
			$sql['v'] = array_merge($sql1['v'],$sql['v']);
            $r = $this->db->fetchAll($sql);
            foreach($r as $p)
            {
                $t[$p['qrtype']][] = $p['questionid'];
            }
            foreach($t as $key => $v)
            {
                $n[$key] = count($v);
            }
            $knows['knowsquestions'] = $t;
            $knows['knowsnumber'] = $n;
			$args = array(
				'knowsquestions' => $t,
				'knowsnumber' => $n
			);
			$this->modifyKnows($knowsid,$args);
        }
        return $knows;
    }
}

?>
