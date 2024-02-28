<?php

namespace PHPEMS;

ini_set("display_errors","on");
error_reporting(E_ERROR || E_PARSE);

class ginkgo
{
    static public $G = array();
    static public $L = array();
    static public $app;
    static public $module;
    static public $method;
    static public $defaultApp = 'core';
	
	public function __construct()
	{
		self::loadMoudle();
	}

    static function loadMoudle()
    {
        include PEPATH.'/lib/config.inc.php';
        header('P3P: CP=CAO PSA OUR');
        header('Content-Type: text/html; charset='.HE);
        ini_set('date.timezone','Asia/Shanghai');
        date_default_timezone_set("Etc/GMT-8");
        $path = PEPATH."/vendor/vendor/autoload.php";
        if(file_exists($path) && COMPOSER)
        {
            include_once $path;
        }
    }
	
    /**
     * @param $G
     * @param null $app
     * @return static
     */
	static public function make($G,$app = NULL,$parm = 'default')
	{
		if($app)return self::load($G,$app);
		else
		{
			if(!isset(self::$G[$G][$parm]))
			{
				if(file_exists(PEPATH.'/lib/'.$G.'.cls.php'))
				{
					include_once PEPATH.'/lib/'.$G.'.cls.php';
				}
				else return false;
				$clsname = '\\PHPEMS\\'.$G;
                self::$G[$G][$parm] = new $clsname();
				if(method_exists(self::$G[$G][$parm],'_init'))self::$G[$G][$parm]->_init($parm);
			}
			return self::$G[$G][$parm];
		}

	}

	//加载对象类文件并生成对象
    /**
     * @param $G
     * @param null $app
     * @return static
     */
    static public function load($G,$app)
	{
		if(!$app)return false;
		$o = $G.'_'.$app;
		if(!isset(self::$L[$app][$o]))
		{
			$fl = PEPATH.'/app/'.$app.'/cls/'.$G.'.cls.php';
			if(file_exists($fl))
			{
				include $fl;
			}
			else return false;
            $clsname = '\\PHPEMS\\'.$o;
            self::$L[$app][$o] = new $clsname();
			if(method_exists(self::$L[$app][$o],'_init'))self::$L[$app][$o]->_init();
		}
		return self::$L[$app][$o];
	}

	//执行页面
	public function run()
	{        
        self::$app = self::$defaultApp;
        $ev = self::make('ev');
        if($ev->url(0))
        {
            self::$app = $ev->url(0);
        }
        self::$module = $ev->url(1);
        self::$method = $ev->url(2);
		if(!self::$module)self::$module = 'app';
		if(!self::$method)self::$method = 'index';
		include PEPATH.'/app/'.self::$app.'/'.self::$module.'.php';
		
		$modulefile = PEPATH.'/app/'.self::$app.'/controller/'.self::$method.'.'.self::$module.'.php';
		if(file_exists($modulefile))
		{			
			include $modulefile;			
			$tpl = self::make('tpl');
			$tpl->assign('_app',self::$app);
			$tpl->assign('method',self::$method);
			$run = new action();
			$run->display();
		}
		else die('error:Unknown app to load, the app is '.self::$app);
	}

	static public function R($message)
	{
		$ev = self::make('ev');
		if($ev->get('userhash'))
		{
			exit(json_encode($message));
		}
		else
		{
			if($message['callbackType'] == 'forward')
			{
				if($message['forwardUrl'])
				exit("<script>window.location = '{$message['forwardUrl']}';</script>");
				else
				exit("<script>window.location = document.referrer+'&'+Math.random();</script>");
			}
			else
			{
				exit("<script>window.location = document.referrer+'&'+Math.random();</script>");
			}
		}
	}
}

?>