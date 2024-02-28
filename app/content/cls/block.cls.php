<?php
 namespace PHPEMS;

class block_content
{
	public $G;

	public function __construct()
	{
		
	}

	public function _init()
	{
		$this->categories = NULL;
		$this->tidycategories = NULL;
		$this->sql = \PHPEMS\ginkgo::make('sql');
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->pg = \PHPEMS\ginkgo::make('pg');
		$this->ev = \PHPEMS\ginkgo::make('ev');
	}

	public function getBlockList($args,$page,$number = 20)
	{
		$data = array(
			'select' => false,
			'table' => 'block',
			'query' => $args,
			'orderby' => 'blockid DESC'
		);
		return $this->db->listElements($page,$number,$data);
	}

	public function addBlock($args)
	{
		$data = array('block',$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	public function delBlock($id)
	{
		return $this->db->delElement(array('table' => 'block','query' => array(array("AND","blockid = :blockid",'blockid',$id))));
	}

	public function getBlockById($id)
	{
		$data = array(false,'block',array(array("AND","blockid = :blockid",'blockid',$id)));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,'blockcontent');
	}

	public function modifyBlock($id,$args)
	{
		$data = array('block',$args,array(array("AND","blockid = :blockid",'blockid',$id)));
		$sql = $this->pdosql->makeUpdate($data);
		return $this->db->exec($sql);
	}
}

?>
