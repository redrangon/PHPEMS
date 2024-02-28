<?php
 namespace PHPEMS;

class log_course
{
	public $G;

	public function __construct()
	{
		
	}

	public function _init()
	{
		$this->categories = NULL;
		$this->tidycategories = NULL;
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->ev = \PHPEMS\ginkgo::make('ev');
	}

	public function getPassedLogsNumberByCsid($csid,$userid)
	{
        $data = array('count(*) as number',array('log','course'),array(array('AND','loguserid = :loguserid','loguserid',$userid),array('AND','logstatus = 1'),array('AND','courseid = logcourseid'),array('AND','coursecsid = :coursecsid','coursecsid',$csid)));
        $sql = $this->pdosql->makeSelect($data);
        $r = $this->db->fetch($sql);
        return $r['number'];
	}

    public function getLogsByCsid($csid,$userid)
    {
        $data = array('logid,loguserid,logcourseid,logstatus,logtime,logendtime,logprogress,coursetitle',array('log','course'),array(array('AND','loguserid = :loguserid','loguserid',$userid),array('AND','courseid = logcourseid'),array('AND','coursecsid = :coursecsid','coursecsid',$csid)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql,'logcourseid');
    }

	public function getLogByArgs($args)
	{
		$data = array(false,'log',$args);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

	public function addLog($args)
	{
		$args['logtime'] = TIME;
		return $this->db->insertElement(array('table' => 'log','query' => $args));
	}

	public function modifyLog($id,$args)
    {
        $data = array('log',$args,array(array("and","logid = :logid","logid",$id)));
        $sql = $this->pdosql->makeUpdate($data);
        return $this->db->exec($sql);
    }
}

?>
