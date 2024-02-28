<?php

/** 常规常量设置 */
define('DOMAINTYPE','off');
define('CH','exam_');
define('CDO','');
define('CP','/');
define('CRT',180);
define('CS','1hqfx6ticwRxtfviTp940vng!yC^QK^6');//请随机生成32位字符串修改此处值
define('HE','utf-8');
define('PN',10);
define('TIME',time());
if(dirname($_SERVER['SCRIPT_NAME']))
{
    define('WP','http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/');
}
else
{
    define('WP','http://'.$_SERVER['SERVER_NAME'].'/');
}
define('OPENOSS',false);

/**接口加密设置**/
define('APIKEY','35abcdec2532ceb0945b615a922c3370');
define('APIIV','#phpems90iv*');
/**composer开关**/
define('COMPOSER',0);
/** 数据库设置 */
define('SQLDEBUG',0);
define('DB','pe9');//MYSQL数据库名
define('DH','127.0.0.1');//MYSQL主机名，不用改
define('DU','root');//MYSQL数据库用户名
define('DP','root');//MYSQL数据库用户密码
define('DTH','x2_');//系统表前缀，不用改

/** 微信相关设置 */
define('USEWX',false);//微信使用开关，绑定用户，false时不启用
define('WXAUTOREG',false);//微信开启自动注册,设置为false时转向登录和注册页面，绑定openid
define('WXPAY',false);
//define('FOCUSWX',true);//强制引导关注微信
//define('WXQRCODE','qrcode.png');//微信公众号二维码地址
define('EP','@phpems.net');//微信开启自动注册时注册邮箱后缀
define('OPENAPPID','wx7703aa61284598ea');//开放平台账号
define('OPENAPPSECRET','36745ed4d4ea9da9f25e7b3ccd06d5ef');
define('MPAPPID','wx7703aa61284598ea');//小程序账号
define('MPAPPSECRET','36745ed4d4ea9da9f25e7b3ccd06d5ef');
define('WXAPPID','wx6967d8319bfeea19');//公众号账号
define('WXAPPSECRET','4d462a770aebb7460e2284cbd448cee0');
define('WXMCHID','1414206302');//MCHID
define('WXKEY','72653616204d16975931a46f9296092e');

/** 支付宝相关设置 */
define('ALIPAY',false);
/**MD5**/
define('ALIPART','111171660');
define('ALIKEY','os11111117fmljymmcau');
define('ALIACC','111111@qq.com');
/**RAS2**/
define('ALIAPPID','2016072738');
define('ALIPRIKEY','MIIEogIBAMXsHR2vhl3BqWBbXfDaIcrJgNssIUddVCX6fT9YWiPXCiCeu5M=');
define('ALIPUBKEY','MIIBIjANXRkaDw6wIDAQAB');




?>