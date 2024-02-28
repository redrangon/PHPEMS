<?php

namespace PHPEMS;
/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class action extends app
{
	public function display()
	{
		$this->item = ginkgo::make("item","weixin");
		$this->search = $this->ev->get('search');
		$this->u = '';
		if($this->search)
		{
			foreach($this->search as $key => $arg)
			{
				$this->u .= "&search[{$key}]={$arg}";
			}
			$this->tpl->assign('u',$this->u);
		}
		$this->tpl->assign('search',$this->search);
		$action = $this->ev->url(3);
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}


	private function index()
	{
		$page = $this->ev->get('page');
		$args = array();
		if($this->search['itemcode'])
		{
			$args[] = array("AND","itemcode = :itemcode",'itemcode',$this->search['itemcode']);
		}
		if($this->search['keyword'])
		{
			$args[] = array("AND","itemtitle LIKE (:itemtitle)",'itemtitle',"%{$this->search['keyword']}%");
		}
		$items = $this->item->getItemList($args,$page);
		$this->tpl->assign('items',$items);
		$this->tpl->display('item');
	}
}


?>
