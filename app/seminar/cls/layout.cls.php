<?php
 namespace PHPEMS;

class layout_seminar
{
	public $G;

	public function __construct()
	{
		
	}

	public function _init()
	{
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
	}

	public function getSeminarTplsList($args,$page,$number = 10,$orderby = 'stplid desc')
	{
		$data = array(
			'select' => false,
			'table' => 'seminar_tpls',
			'query' => $args,
			'orderby' => $orderby
		);
		return $this->db->listElements($page,$number,$data);
	}

	public function getSeminarTplsByArgs($args,$orderby = 'stplid desc')
	{
        $data = array(false,'seminar_tpls',$args,false,$orderby,false);
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
	}

	public function addSeminarTpl($args)
	{
		return $this->db->insertElement(array('table' => 'seminar_tpls','query' => $args));
	}

    public function delSeminarTpl($stplid)
    {
        $args = array(
            array("AND","stplid = :stplid","stplid",$stplid)
        );
    	return $this->db->delElement(array('table' => 'seminar_tpls','query' => $args));
    }

    public function modifySeminarTpl($stplid,$args)
    {
        $data = array(
            'table' => 'seminar_tpls',
            'value' => $args,
            'query' => array(array("AND","stplid = :stplid","stplid",$stplid))
        );
        return $this->db->updateElement($data);
    }

    public function getSeminarTplById($stplid)
    {
        $args = array(
        	array("AND","stplid = :stplid","stplid",$stplid)
		);
    	$data = array(false,'seminar_tpls',$args);
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
    }
}

?>
