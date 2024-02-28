<?php
 namespace PHPEMS;

class category
{
	public $G;
	public $tidyCategory;

	public function __construct()
	{

	}

	public function _init($parm)
	{
		if($parm == 'default')
		{
			$this->app = \PHPEMS\ginkgo::$app;
		}
		else
		{
			$this->app = $parm;
		}
		$this->categories = NULL;
		$this->tidycategories = NULL;
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->tidyCategory();
	}

	public function addCategory($args)
	{
		if(!$args['catapp'])$args['catapp'] = $this->app;
		return $this->db->insertElement(array('table' => 'category','query' => $args));
	}

	public function getCategoryById($id)
	{
		$data = array(false,'category',array(array('AND',"catid = :catid",'catid',$id)));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,'catmanager');
	}

	public function getCategoryList($args,$page,$number = 20)
	{
		if(!is_array($args))
		$args = array(array('AND',"catapp = :catapp",'catapp',$this->app));
		else
		$args[] = array('AND',"catapp = :catapp",'catapp',$this->app);
		$data = array(
			'select' => false,
			'table' => 'category',
			'index' => 'catid',
			'query' => $args,
			'orderby' => 'catlite DESC,catid DESC',
			'serial' => 'catmanager'
		);
		return $this->db->listElements($page,$number,$data);
	}

	public function getCategoriesByArgs($args = array())
	{
		if(!is_array($args))
		$args = array(array('AND',"catapp = :catapp",'catapp',$this->app));
		$data = array(false,'category',$args,false,"catlite DESC,catid DESC",false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'catid','catmanager');
	}

	public function delCategory($id)
	{
		return $this->db->delElement(array('table' => 'category','query' => array(array('AND',"catid = :catid",'catid',$id),array('AND',"catapp = :catapp",'catapp',$this->app))));
	}

	public function modifyCategory($id,$args)
	{
		unset($args['catapp']);
		$data = array(
			'table' => 'category',
			'value' => $args,
			'query' => array(array('AND',"catid = :catid",'catid',$id)),
			'orderby' => 'catlite DESC,catid DESC'
		);
		return $this->db->updateElement($data);
	}

	public function getAllCategory($app = false)
	{
		if(!$app)$app = $this->app;
		if($this->categories === NULL)
		{
			$data = array(false,'category',array(array('AND',"catapp = :catapp",'catapp',$app)),false,"catlite DESC,catid DESC",false);
			$sql = $this->pdosql->makeSelect($data);
			$this->categories = $this->db->fetchAll($sql,'catid','catmanager');
			$this->tidyCategory();
		}
		return $this->categories;
	}

	public function getAllCategoryByApp($app)
	{
		$data = array(false,'category',array(array('AND',"catapp = :catapp",'catapp',$app)),false,"catlite DESC,catid DESC",false);
		$sql = $this->sql->makeSelect($data);
		return $this->db->fetchAll($sql,'catid','catmanager');
	}

	private function tidyCategory()
	{
		if($this->tidyCategory === NULL)
		{
			$this->getAllCategory();
			$categories = array();
			foreach($this->categories as $p)
			{
				$categories[$p['catparent']][] = $p;
			}
			$this->tidycategories = $categories;
		}
		return $this->tidycategories;
	}

	public function getChildCategory($id)
	{
		if(!$id)return false;
		$categories = $this->tidyCategory();
		$child = array();
		$parent = array($id);
		$i = 0;
		while($parent[$i])
		{
			if($categories[$parent[$i]])
			{
				foreach($categories[$parent[$i]] as $n)
				{
					$child[] = $n['catid'];
					$parent[] = $n['catid'];
				}
			}
			$i++;
		}
		return $child;
	}

	public function getChildCategoryString($id,$withself = 1)
	{
		$s = implode(',',$this->getChildCategory($id));
		if($withself)
		{
			if($s)$s = $id.','.$s;
			else $s = $id;
		}
		return $s;
	}

	public function getCategoryPos($id)
	{
		$this->tidyCategory();
		if($this->categories[$id])
		{
			$categories = array();
			while($this->categories[$id]['catparent'])
			{
				$categories[] = $this->categories[$this->categories[$id]['catparent']];
				$id = $this->categories[$id]['catparent'];
			}
			krsort($categories);
			return $categories;
		}
		else return false;
	}

	public function levelCategory(&$t,$index,$allcats)
	{
		if(is_array($allcats[$index]))
		{
			foreach($allcats[$index] as $p)
			{
                if($this->selected && $this->selected == $p['catid'])
                $t[$p['catid']] = array('text' => $p['catname'],'href' => $this->hrefpre.$p['catid'],'color' => '#FFFFFF',"backColor" => '#374850');
                else
				$t[$p['catid']] = array('text' => $p['catname'],'href' => $this->hrefpre.$p['catid']);
				$this->levelCategory($t[$p['catid']]['nodes'],$p['catid'],$allcats);
			}
		}
	}

	public function resetCategoryIndex(&$t)
	{
        $t = array_values($t);
		foreach($t as $key => $p)
		{
			if($p['nodes'])
			{
				$this->resetCategoryIndex($t[$key]['nodes']);
			}
		}
	}
}

?>
