<?php
 namespace PHPEMS;

class area
{
	public $G;
	public $data;

    public function __construct()
    {
    	$this->ca = \PHPEMS\ginkgo::make("ca");
    	$this->pdosql = \PHPEMS\ginkgo::make("pdosql");
    }

	public function _init()
	{
		if($rs = $this->ca->readCache('areas'))
		{
			$this->data = $rs;
		}
		else
		{
			$data = array(false,'province',1,false,false,false);
			$sql = $this->pdosql->makeSelect($data);
			$this->data['province'] = $this->db->fetchAll($sql,'provinceid');
			$data = array(false,'city',1,false,false,false);
			$sql = $this->pdosql->makeSelect($data);
			$this->data['city'] = $this->db->fetchAll($sql);
			$data = array(false,'cityarea',1,false,false,false);
			$sql = $this->pdosql->makeSelect($data);
			$this->data['district'] = $this->db->fetchAll($sql);
			$this->ca->writeCache('areas',$this->data);
		}
	}

    //获取会话ID
    public function getProvinces()
    {
    	return $this->data['province'];
    }
	
	//ios的api所需
	public function getCities()
	{
		return $this->data['city'];
	}
	
	 public function getareas()
	{
		return $this->data['district'];
	}

    //根据省ID获取城市列表
    public function getCitiesByProvince($pid)
    {
    	if($this->data['detail'][$pid])
		{
			return $this->data['detail'][$pid];
		}
    	else
		{
			foreach($this->data['city'] as $city)
			{
				if($city['father'] == $pid)
				{
					$this->data['detail'][$pid][$city['cityid']] = $city;
				}
			}
		}
		return $this->data['detail'][$pid];
    }

    //根据城市ID获取区列表
    public function getAreasByCity($cid)
    {
		if($this->data['detail'][$cid])
		{
			return $this->data['detail'][$cid];
		}
		else
		{
			foreach($this->data['district'] as $area)
			{
				if($area['father'] == $cid)
				{
					$this->data['detail'][$cid][$area['areaid']] = $area;
				}
			}
		}
		return $this->data['detail'][$cid];
    }

    //根据区ID获取区县
    public function getCityAreaById($aid)
    {
		return $this->data['district'][$aid];
    }

    //根据ID获取城市
    public function getCityById($cid)
    {
		return $this->data['city'][$cid];
    }

    //根据ID获取省份
    public function getProvinceById($pid)
    {
		return $this->data['province'][$pid];
    }
}
?>
