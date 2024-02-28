<?php
 namespace PHPEMS;
/*
 * Created on 2015-10-29
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class exercise_exam
{
	public $G;

	public function __construct()
	{
		
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->ev = \PHPEMS\ginkgo::make('ev');
	}

	//根据参数查询
	public function getExerciseProcessByUser($userid,$basicid,$knowsid = null)
	{
		if($knowsid)
		{
			$data = array(false,'exercise',array(array("AND","exeruserid = :exeruserid",'exeruserid',$userid),array("AND","exerbasicid = :exerbasicid",'exerbasicid',$basicid),array("AND","exerknowsid = :exerknowsid",'exerknowsid',$knowsid)));
			$sql = $this->pdosql->makeSelect($data);
			return $this->db->fetch($sql);
        }
        else
		{
            $data = array(false,'exercise',array(array("AND","exeruserid = :exeruserid",'exeruserid',$userid),array("AND","exerbasicid = :exerbasicid",'exerbasicid',$basicid)),false,false,false);
            $sql = $this->pdosql->makeSelect($data);
            return $this->db->fetchAll($sql,'exerknowsid');
		}
	}

	public function setExercise($args)
	{
		$userid = $args['exeruserid'];
		$basicid = $args['exerbasicid'];
        $knowsid = $args['exerknowsid'];
		$r = $this->getExerciseProcessByUser($userid,$basicid,$knowsid);
		if($r)
		{
			$data = array('exercise',$args,array(array("AND","exerid = :exerid",'exerid',$r['exerid'])));
			$sql = $this->pdosql->makeUpdate($data);
			$this->db->exec($sql);
		}
		else
		{
			$data = array('exercise',$args);
			$sql = $this->pdosql->makeInsert($data);
			$this->db->exec($sql);
		}
		return true;
	}
}


?>
