<?php
/**
 * 当QQ Robot 收到来自用户的消息 和 来自群内的消息，会将消息以HTTP POST方式发送到一个网址。
 * 发送网址在 qqconfig.txt文件中，由 CallbackUrl 指定。
 * 
 * 将此文件设置为 QQ Robot 的 CallbackUrl
 */
//ignore_user_abort(true) ;

// 仅仅将 QQ Robot 传递来的参数 记录到日志文件
// 不做额外处理
$sLogFolder = dirname(__FILE__)."/logs/" ;
if( !is_dir($sLogFolder) )
{
	mkdir($sLogFolder) ;
} 

/**
 * 导入(import)
 */
if(!class_exists('core'))  {
	require_once dirname(__FILE__). '/../core.php';
	
}

/**
 * 定义(define)
 */
class qqrobot extends core {

	public static $help="您需要输入命令来使用机器人 例(@ip，后面接空格再接ip地址或是域名)\r\n@ip 网址或IP   <IP地址信息>\r\n@mobile 手机号  <查询手机号归属地>\r\n@weather 城市 <查询本城市三天天气预报>\r\n@md5 字符  <对字符进行md5加密>\r\n@cfs 字符  <cfs不可逆加密>\r\n@enbase64 代码  <对代码进行base64加密>\r\n@debase64 代码  <base64解密>\r\n@whois 域名  <查询域名whois信息>\r\n@ping ip地址或网址  <查看ip地址的连接时间>\r\n@alexa 域名  <查看域名的alexa排名>\r\n@pr 域名  <查询域名的pr值>\r\n@cn2en 字符  <把中文字符翻译成英文字符>\r\n@en2cn 字符  <把英文翻译成中文>";
	//public static $msg = '';	
	private static $msg = '';	
	/**
	 * 默认动作
	 */
	final static public function index() {
		//address::browse();
		//echo 1;return;
		//self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
		//echo self::$help;
		self::login();
	}

	final static public function start($msg){
		global $sLogFolder;
		#file_put_contents($sLogFolder.date('Y-n-j G.i.s').".txt",var_export($_REQUEST,1)) ;
		
		if(substr($msg,0,1)=='@'){
			$msg=substr($msg,1,strlen($msg));
			$arr=explode(' ',$msg);
			$func=$arr[0];
			if(!method_exists(new self,$func)){
				self::$msg = '命令出错:'.$func;
				//echo "命令出错！\r\n".self::help;
			}else{
				
				//self::$msg = iconv('gb2312','utf-8',self::$func(trim(str_replace($func.' ','',$msg))));
				self::$msg = self::$func(trim(str_replace($func.' ','',$msg)));
			}
			
		}else{
			self::$msg = (self::$help);//urlencode
		}
		//self::$msg =  strip_tags(self::$msg);
		//parent::__construct();
	}
	
	final static public function login(){
		date_default_timezone_set("PRC");
		//set_time_limit(0);
		error_reporting(E_ALL & ~E_NOTICE ^E_DEPRECATED);
		require_once(dirname(__FILE__)."/../includes/lib/QQClient/qq.php");

		//初始化
		//$qq = new QQClient('593795966','19830812ll');
		$qq = new QQClient('453196649','lxqzyy2008@');
		$qq->server='tqq.tencent.com';
		$qq->server='119.147.10.11';
		$qq->port='8000';
		//登陆
		echo "<pre>正在登陆...";flush();
		switch($qq -> login())
		{
			case QQ_LOGIN_SUCCESS:
				echo "登陆成功";
				break;
			case QQ_RETURN_FAILED:
				echo "服务器返回错误";
				exit;
				break;
			default:
				echo "登陆失败，原因：".$QQ_ERROR_MSG;
				exit;
				break;
		}
		echo "\r\n";
		
		
		echo "正在获得你的好友列表...\r\n";
		echo "你有 ".count($qq -> getFriendsList())." 个好友！\r\n\r\n";
		echo "看看谁在线上：\r\n";
		$list = $qq -> getOnlineList();
		
		$onlinefriend = array();
		
		switch($list)
		{
			case QQ_RETURN_FAILED:
				echo "服务器返回错误";
				break;
			case QQ_LIST_NONE:
				echo "没有在线好友";
				break;
			case QQ_LIST_ERROR:
				echo "在线好友列表非法！！！";
				break;
			default:
				$online="在线好友：";
				$busy="忙碌好友：";
				for($i=0;$i<count($list);$i++)
				{
					switch($list[$i]['ST'])
					{
						case QQ_STATUS_ONLINE:
							$online .= $list[$i]['NK']."(".$list[$i]['UN'].")  ";
							$onlinefriend[] = $list[$i]['UN'];
							break;
						case QQ_STATUS_BUSY:
							$busy .= $list[$i]['NK']."(".$list[$i]['UN'].")  ";
							break;
						default:
					}
				}
				echo $online."\r\n".$busy."\r\n";
				break;
		}
		
		//echo "\r\n查看106814的信息：\r\n";
		//print_r($qq -> getInfo('106814'));
		
		/*
		
		$uin = "239845259";
		echo "加$uin...\r\n";
		switch($qq -> addFriend( $uin ))
		{
			case QQ_ADDTOLIST_SUCCESS :
				echo "已经把 $uin 加为好友";
				break;
			case QQ_ADDTOLIST_NEEDAUTH :
				echo "对方需要验证...发送验证请求...";
			//这里有一点小问题。有时候成功，有时候失败。不知道原因，请高手指点。
				$qq -> replyAdd ($uin,'2','TEST');
				echo "发送完毕";
				break;
			case QQ_ADDTOLIST_REFUSE :
				echo "对方拒绝被加为好友";
				break;
			case QQ_RETURN_FAILED:
				echo "服务器返回错误";
				break;
		}
		*/
		echo "\r\n变成忙碌状态...\r\n\r\n";
		flush();
		$qq->changeStatus(QQ_STATUS_BUSY);
		$close_msg='';
		$status=true;
		while($status)
		{
		//echo "看看有没有消息：\r\n";
		$msg = $qq -> getMsg();
		switch($msg)
		{
			case QQ_GETMSG_NONE:
				//echo "没有消息\r\n";
				break;
			case QQ_RETURN_FAILED:
				//echo "返回错误\r\n";
				break;
			default:
				for($i=0;$i<count($msg);$i++)
				{
					$msg[$i]['MG'] = chop($msg[$i]['MG']);
					echo "来自：".$msg[$i]['UN'];
					echo "\r\n类型：".$msg[$i]['MT'];
					echo "\r\n内容：".$msg[$i]['MG'];
		
					switch($msg[$i]['MT'])
					{
						case 9:
							//用户信息
							switch($msg[$i]['MG'])
							{
								case '上线':case '上来':$qq->changeStatus(QQ_STATUS_ONLINE);$reply = '我上了。';break;
								case '离开':case '你忙吧':$qq->changeStatus(QQ_STATUS_BUSY);$reply = '我离开咯。';break;
								case '你好':$reply = '你好，我是机器人，你的号码是'.$msg[$i]['UN'];break;
								case 'logout':$reply='好啦,今天就聊天这里吧.88';break;
								case 'restart':$reply='好啦,一会回来.88';break;
								
								//case '帮助':
								//case 'help':$reply="";break;
								
								//default:$reply ='我是机器人，我收到你的信息了，内容为：'."\r\n".$msg[$i]['MG'];break;
								default:
								$qqrobot = self::start($msg[$i]['MG']);
								$reply   = self::$msg;
								echo '<br>REPLAY:<pre>';
								echo($reply);
								echo '</pre>';
								
							}
							break;
						case 99:
							//系统信息
							switch($msg[$i]['MG'])
							{
								//case QQ_STATUS_ONLINE:$reply = '你来啦';break;
								//case QQ_STATUS_OFFLINE:$reply = '再见';break;
								//case QQ_STATUS_BUSY:$reply = '忙啊，那我不打扰你了。';break;
								//default:$reply = '你给我发了一条系统信息，内容是'.$msg[$i]['MG'];break;
							}
							break;
						case 2:
							//有人加我
							$qq -> replyAdd ($msg[$i]['UN'],'0','');//通过验证
							$reply = "你好，很高兴能够认识你，我是青剑的QQ机器人。";
							sleep(10);
							break;
					}
					if($reply != "" and $lastreply[$msg[$i]['UN']] != $reply and $lastuin !=$msg[$i]['UN']){
						echo "\r\n回复...";
						switch($qq -> sendMsg($msg[$i]['UN'],$reply))
						{
							case QQ_RETURN_SUCCESS :
								echo "发送成功";
								break;
							case QQ_RETURN_FAILED :
								echo "发送失败";
								break;
						}
						$lastreply[$msg[$i]['UN']] = $reply;
						$lastuin = $msg['$i']['UN'];
						if($msg[$i]['MG']=='logout'){
							$qq->logout();
							$status=false;
						}elseif($msg[$i]['MG']=='restart'){
							$qq->logout();
							$status=false;
							//IF NOT errorlevel 1 echo ok
							$close_msg='<meta http-equiv="refresh" content="1;URL=?" />';
							//header("Location:?");
							
						}
						
					}
					$reply = "";
		
					echo "\r\n";
				}
		}
		//var_dump($status);
		flush();
		sleep (2);
		}
		echo $close_msg;
	}
	function checkurlip($str){
		$ip=gethostbyname($str);
		if(count(explode('.',$ip))==4){
			return $ip;
		}else{
			return false;
		}
	}

	function ip($ip){
		if(!$ip=self::checkurlip($ip)) return '出错，IP或网址不正确！';
		return file_get_contents("http://api.liqwei.com/location/?ip=".$ip);
	}

	function mobile($tel){
		return file_get_contents("http://api.liqwei.com/location/?mobile=".$tel);
	}

	function weather($city){
		return str_replace('<br/>',"\r\n",file_get_contents("http://api.liqwei.com/weather/?city=".iconv('GBK','gb2312',$city)));
	}

	function md5($str){
		return md5($str);
	}

	function cfs($str){
		return file_get_contents("http://api.liqwei.com/security/?cfs=".$str);
	}

	function enbase64($code){
		return file_get_contents("http://api.liqwei.com/security/?base64encode=".$code);
	}

	function debase64($code){
		return file_get_contents("http://api.liqwei.com/security/?base64decode=".$code);
	}

	function whois($domain){
		return file_get_contents("http://api.liqwei.com/whois/?domain=".$domain);
	}

	function ping($ip)
	{
		if(!$ip=self::checkurlip($ip)) return '出错，IP或网址不正确！';
		return strip_tags(str_replace("<br />","\r\n",str_replace("\r\n","",file_get_contents("http://api.liqwei.com/ping/?ip=".$ip))));
	}

	function alexa($domain)
	{
		return file_get_contents("http://api.liqwei.com/alexa/?domain=".$domain);
	}

	function pr($domain)
	{
		return file_get_contents("http://api.liqwei.com/pr/?domain=".$domain);
	}

	function cn2en($content)
	{	return self::google_translage('cn','en',$content);
		//return file_get_contents("http://api.liqwei.com/translate/?language=zh-CN|en&content=".iconv('utf-8','gb2312',$content));
		$msg =  file_get_contents("http://api.liqwei.com/translate/?language=zh-CN|en&content=".iconv('GBK','gb2312',$content));
		$msg =str_replace('+',' ',$msg);
		return $msg;
		
	}

	function en2cn($content)
	{
		return self::google_translage('en','zh-CN',$content);
		$msg = file_get_contents("http://api.liqwei.com/translate/?language=en|zh-CN&content=".iconv('GBK','gb2312',$content));
		$msg =str_replace('+',' ',$msg);
		return $msg;
	}

	function translate($t1,$t2,$content)
	{
		return file_get_contents("http://api.liqwei.com/translate/?language={$t1}|{$t2}&content=".iconv('GBK','gb2312',$content));
	}
	function google_translage($code1,$code2,$content){
		require_once(dirname(__FILE__).'/../includes/lib/GTranslate/GTranslate.php');	
		
		try{
			
			$code1 = strtolower($code1);
			$code2 = strtolower($code2);
			if($code1=='cn')$code1='zh-cn';
			if($code2=='cn')$code2='zh-cn';
			
			
			 
			$language_list = parse_ini_file(dirname(__FILE__).'/../includes/lib/GTranslate/languages.ini');
			$language_list 		= 	array_map( "strtolower", $language_list );
			$language_list2 		= 	array_map( "strtolower", array_flip ($language_list) );//
			
			$language_list_v  	= 	array_map( "strtolower", array_values($language_list) );
			$language_list_k 	= 	array_map( "strtolower", array_keys($language_list) );
			
			if(!isset($language_list2[$code1])){
				return ('Error language code:'.$code1);
			}
			if(!isset($language_list2[$code2])){
				return ('Error language code:'.$code2);
			}
			
			
			$fromlanguage = $language_list2[$code1];
			$targetlanguage = $language_list2[$code2];
			
			//print_r($targetlanguage);
			
			 
			 $gt = new Gtranslate;
		
			$gt->setRequestType('curl');
			$function  = $fromlanguage.'_to_'.$targetlanguage;
			$text = $gt->$function($content);	
			echo $function."\r\n";
			$text = iconv('UTF-8','GBK',$text);
			return $text;
			
		
		} catch (GTranslateException $ge){
		   return $ge->getMessage();
		}
	}
	function check($content){
		return strlen(file_get_contents($content));
	}
}
$config = array (
  'autoload_enable' => true,
  'autoload_path' => '@',
 // 'framework_function' => 'front::main',
  'framework_enable' => true,
 // 'framework_module' => '[go]!(self)|welcome',
 // 'framework_action' => '[do]|index',
  'template_path' => '@templates\\',
  'connect_server' => 'localhost',
  'connect_username' => 'root',
  'connect_password' => '123456',
  'connect_dbname' => 'my_office',
  'connect_port' => '3306',
  'connect_charset' => 'UTF8',
  'prefix_search' => 'mdb_',
  'prefix_replace' => 'mdb_',
  'extension_path' => '@includes',
  'extension_enable' => 'myfunction',
  'debug_enable' => false,
  'sql_format' => false,
  'debug_file' => '',
//  'front_action' => '',
//  'front_online' => 'online',
//  'front_class' => 'user',
//  'front_table' => 'mdb_user',
//  'front_fuzzy' => '',
//  'front_username' => '',
//  'front_password' => '',
//  'front_redirect' => 'index.php',

);
	qqrobot::init($config);
	
	qqrobot::stub () and qqrobot::main ();
?>