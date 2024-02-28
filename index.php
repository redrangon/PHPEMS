<?php

session_start();
define("PE_VERSION",'9.0');
define("PEPATH",dirname(__FILE__));
require PEPATH."/lib/init.cls.php";
$ginkgo = new \PHPEMS\ginkgo;
$ginkgo->run();