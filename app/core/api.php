<?php
namespace PHPEMS;
class app
{
	public $G;

	public function __construct()
	{
		
		$this->files = \PHPEMS\ginkgo::make('files');
		$this->session = \PHPEMS\ginkgo::make('session');
		$this->tpl = \PHPEMS\ginkgo::make('tpl');
		$this->ev = \PHPEMS\ginkgo::make('ev');
	}

}

?>