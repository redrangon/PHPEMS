<?php
 namespace PHPEMS;

class consume_bank
{
	public $G;

	public function __construct()
	{
		
	}

	public function _init()
	{
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->ev = \PHPEMS\ginkgo::make('ev');
		$this->files = \PHPEMS\ginkgo::make('files');
	}

	public function getConsumeList($args,$page,$number = 10)
	{
		$args[] = array("AND","conluserid = userid");
		$data = array(
			'select' => false,
			'table' => array('consumelog','user'),
			'query' => $args,
			'orderby' => 'conltime DESC'
		);
		return $this->db->listElements($page,$number,$data);
	}

	public function getConsumeStats($args)
	{
		$args[] = array("AND","conluserid = userid");
		$data = array('sum(conlcost) as consume',array('consumelog','user'),$args);
		$sql = $this->pdosql->makeSelect($data);
		$r = $this->db->fetch($sql);
		return $r['consume'];
	}

	public function getConsumesByArgs($args,$fields)
	{
		$args[] = array("AND","conluserid = userid");
	}

	public function addConsumeLog($args)
	{
		return $this->db->insertElement(array('table' => 'consumelog','query' => $args));
	}

	public function statsConsume($args,$fields = false)
	{
		$args[] = array("AND","conluserid = userid");
		$data = array($fields,array('consumelog','user'),$args,false,false,false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql);
	}
}

?>
