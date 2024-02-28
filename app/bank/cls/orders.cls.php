<?php
 namespace PHPEMS;

class orders_bank
{
	public $G;

	public function __construct()
	{
		
	}

	public function _init()
	{
		$this->pdosql = \PHPEMS\ginkgo::make('pdosql');
		$this->db = \PHPEMS\ginkgo::make('pepdo');
		$this->user = \PHPEMS\ginkgo::make('user','user');
		$this->module = \PHPEMS\ginkgo::make('module');
		$this->item = \PHPEMS\ginkgo::make('item','item');
	}

	public function payforOrder($ordersn,$paytype = 'handle')
	{
		$order = $this->getOrderById($ordersn);
		if($order['orderstatus'] == 1)
		{
			$modules = $this->module->getModulesByApp('item');
			if($order['orderapp'] == 'item')
			{
				$unvirtual = 0;
				$describe = array();
				foreach($order['orderitems'] as $item)
				{
					if($item['goodsinkind'])
					{
						for($i = 0;$i< $item['number'];$i++)
						{
							$sn = '';
							$args = array(
								array("AND","gcgoodsid = :gcgoodsid","gcgoodsid",$item['itemgoodsid']),
								array("AND","gcstatus = 0")
							);
							$code = $this->item->getGoodsCodeByArgs($args);
							if($code)
							{
								$this->item->modifyGoodsCode($code['gcid'],array(
									'gcordersn' => $order['ordersn'],
									'gcstatus' => 1
								));
								$sn .= $code['gcsn'].';';
							}							
							$args = array(
								array("AND","icitemid = :icitemid","icitemid",$item['itemid']),
								array("AND","icstatus = 0")
							);
							$code = $this->item->getItemCodeByArgs($args);
							if($code)
							{
								$this->item->modifyItemCode($code['icid'],array(
									'icordersn' => $order['ordersn'],
									'icstatus' => 1
								));
								$sn .= $code['icsn'];
							}
							$describe[$item['itemid']][] = $sn;
						}
					}
					else
					{
						$unvirtual = 1;
					}
				}
				if($unvirtual)
				{
					$this->modifyOrderById($ordersn,array('orderstatus' => 2,'orderpaytime' => TIME,'orderpaytype' => $paytype,'orderdescribe' => $describe));
				}
				else
				{
					$this->modifyOrderById($ordersn,array('orderstatus' => 4,'orderpaytime' => TIME,'orderfinishtime' => TIME,'orderpaytype' => $paytype,'orderdescribe' => $describe));
				}
			}
			else
			{
				$this->modifyOrderById($ordersn,array('orderstatus' => 4,'orderfinishtime' => TIME,'orderpaytype' => $paytype));
				$user = $this->user->getUserById($order['orderuserid']);
				$args['usercoin'] = $user['usercoin']+$order['orderprice']*10;
				$this->user->modifyUserInfo($order['orderuserid'],$args);
			}
		}
		return true;
	}

	public function getOrderList($args,$page,$number = 20,$order = 'ordercreatetime DESC')
	{
		$data = array(
			'select' => false,
			'table' => 'orders',
			'query' => $args,
			'orderby' => $order,
			'serial' => array('orderitems','orderpost','orderaddress','orderuserinfo','orderdescribe','orderfaq')
		);
		return $this->db->listElements($page,$number,$data);
	}

	public function delOrder($id)
	{
		return $this->db->delElement(array('table' => 'orders','query' => array(array("AND","ordersn = :ordersn",'ordersn',$id))));
	}

	public function modifyOrder($id,$args)
	{
		$data = array(
			'table' => 'orders',
			'value' => $args,
			'query' => array(array("AND","ordersn = :ordersn",'ordersn',$id))
		);
		return $this->db->updateElement($data);
	}

	public function addOrder($args)
	{
		return $this->db->insertElement(array('table' => 'orders','query' => $args));
	}

	public function getOrderById($id,$userid = null)
	{
		if($userid)
		$data = array(false,'orders',array(array("AND","ordersn = :ordersn",'ordersn',$id),array("AND","orderuserid = :orderuserid",'orderuserid',$userid)));
		else
		$data = array(false,'orders',array(array("AND","ordersn = :ordersn",'ordersn',$id)));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,array('orderitems','orderaddress','orderdescribe','orderpost','orderuserinfo','orderfaq'));
	}

	public function modifyOrderById($id,$args)
	{
		$data = array('orders',$args,array(array("AND","ordersn = :ordersn",'ordersn',$id)));
		$sql = $this->pdosql->makeUpdate($data);
		return $this->db->exec($sql);
	}
}

?>
