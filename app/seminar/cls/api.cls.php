<?php
 namespace PHPEMS;

class api_seminar
{
	public $G;

	public function __construct()
	{
		
	}

	public function _init()
	{
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
        $this->seminar = \PHPEMS\ginkgo::make('seminar','seminar');
	}

    public function parseSeminar($id)
    {
        $elem = $this->seminar->getSeminarElemById($id);
        $data['id'] = $elem['selid'];
        $data['title'] = $elem['seltitle'];
        $data['data'] = $elem['seldata'];
        if($elem['seldata']['number'])
        {
            $args = array();
            $args[] = array("AND","sctelid = :sctelid","sctelid",$id);
            $r = $this->seminar->getSeminarContentList($args,1,$elem['seldata']['number']);
            $data['list'] = $r['data'];
        }
        return $data;
    }
}

?>
