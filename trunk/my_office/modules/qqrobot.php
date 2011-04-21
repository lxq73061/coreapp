<?php
/**
 * ��QQ Robot �յ������û�����Ϣ �� ����Ⱥ�ڵ���Ϣ���Ὣ��Ϣ��HTTP POST��ʽ���͵�һ����ַ��
 * ������ַ�� qqconfig.txt�ļ��У��� CallbackUrl ָ����
 * 
 * �����ļ�����Ϊ QQ Robot �� CallbackUrl
 */
//ignore_user_abort(true) ;

// ������ QQ Robot �������Ĳ��� ��¼����־�ļ�
// �������⴦��
$sLogFolder = dirname(__FILE__)."/logs/" ;
if( !is_dir($sLogFolder) )
{
	mkdir($sLogFolder) ;
} 

/**
 * ����(import)
 */
if(!class_exists('core'))  {
	require_once dirname(__FILE__). '/../core.php';
	
}

/**
 * ����(define)
 */
class qqrobot extends core {

	public static $help="����Ҫ����������ʹ�û����� ��(@ip������ӿո��ٽ�ip��ַ��������)\r\n@ip ��ַ��IP   <IP��ַ��Ϣ>\r\n@mobile �ֻ���  <��ѯ�ֻ��Ź�����>\r\n@weather ���� <��ѯ��������������Ԥ��>\r\n@md5 �ַ�  <���ַ�����md5����>\r\n@cfs �ַ�  <cfs���������>\r\n@enbase64 ����  <�Դ������base64����>\r\n@debase64 ����  <base64����>\r\n@whois ����  <��ѯ����whois��Ϣ>\r\n@ping ip��ַ����ַ  <�鿴ip��ַ������ʱ��>\r\n@alexa ����  <�鿴������alexa����>\r\n@pr ����  <��ѯ������prֵ>\r\n@cn2en �ַ�  <�������ַ������Ӣ���ַ�>\r\n@en2cn �ַ�  <��Ӣ�ķ��������>";
	//public static $msg = '';	
	private static $msg = '';	
	/**
	 * Ĭ�϶���
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
				self::$msg = '�������:'.$func;
				//echo "�������\r\n".self::help;
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

		//��ʼ��
		//$qq = new QQClient('593795966','19830812ll');
		$qq = new QQClient('453196649','lxqzyy2008@');
		$qq->server='tqq.tencent.com';
		$qq->server='119.147.10.11';
		$qq->port='8000';
		//��½
		echo "<pre>���ڵ�½...";flush();
		switch($qq -> login())
		{
			case QQ_LOGIN_SUCCESS:
				echo "��½�ɹ�";
				break;
			case QQ_RETURN_FAILED:
				echo "���������ش���";
				exit;
				break;
			default:
				echo "��½ʧ�ܣ�ԭ��".$QQ_ERROR_MSG;
				exit;
				break;
		}
		echo "\r\n";
		
		
		echo "���ڻ����ĺ����б�...\r\n";
		echo "���� ".count($qq -> getFriendsList())." �����ѣ�\r\n\r\n";
		echo "����˭�����ϣ�\r\n";
		$list = $qq -> getOnlineList();
		
		$onlinefriend = array();
		
		switch($list)
		{
			case QQ_RETURN_FAILED:
				echo "���������ش���";
				break;
			case QQ_LIST_NONE:
				echo "û�����ߺ���";
				break;
			case QQ_LIST_ERROR:
				echo "���ߺ����б�Ƿ�������";
				break;
			default:
				$online="���ߺ��ѣ�";
				$busy="æµ���ѣ�";
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
		
		//echo "\r\n�鿴106814����Ϣ��\r\n";
		//print_r($qq -> getInfo('106814'));
		
		/*
		
		$uin = "239845259";
		echo "��$uin...\r\n";
		switch($qq -> addFriend( $uin ))
		{
			case QQ_ADDTOLIST_SUCCESS :
				echo "�Ѿ��� $uin ��Ϊ����";
				break;
			case QQ_ADDTOLIST_NEEDAUTH :
				echo "�Է���Ҫ��֤...������֤����...";
			//������һ��С���⡣��ʱ��ɹ�����ʱ��ʧ�ܡ���֪��ԭ�������ָ�㡣
				$qq -> replyAdd ($uin,'2','TEST');
				echo "�������";
				break;
			case QQ_ADDTOLIST_REFUSE :
				echo "�Է��ܾ�����Ϊ����";
				break;
			case QQ_RETURN_FAILED:
				echo "���������ش���";
				break;
		}
		*/
		echo "\r\n���æµ״̬...\r\n\r\n";
		flush();
		$qq->changeStatus(QQ_STATUS_BUSY);
		$close_msg='';
		$status=true;
		while($status)
		{
		//echo "������û����Ϣ��\r\n";
		$msg = $qq -> getMsg();
		switch($msg)
		{
			case QQ_GETMSG_NONE:
				//echo "û����Ϣ\r\n";
				break;
			case QQ_RETURN_FAILED:
				//echo "���ش���\r\n";
				break;
			default:
				for($i=0;$i<count($msg);$i++)
				{
					$msg[$i]['MG'] = chop($msg[$i]['MG']);
					echo "���ԣ�".$msg[$i]['UN'];
					echo "\r\n���ͣ�".$msg[$i]['MT'];
					echo "\r\n���ݣ�".$msg[$i]['MG'];
		
					switch($msg[$i]['MT'])
					{
						case 9:
							//�û���Ϣ
							switch($msg[$i]['MG'])
							{
								case '����':case '����':$qq->changeStatus(QQ_STATUS_ONLINE);$reply = '�����ˡ�';break;
								case '�뿪':case '��æ��':$qq->changeStatus(QQ_STATUS_BUSY);$reply = '���뿪����';break;
								case '���':$reply = '��ã����ǻ����ˣ���ĺ�����'.$msg[$i]['UN'];break;
								case 'logout':$reply='����,��������������.88';break;
								case 'restart':$reply='����,һ�����.88';break;
								
								//case '����':
								//case 'help':$reply="";break;
								
								//default:$reply ='���ǻ����ˣ����յ������Ϣ�ˣ�����Ϊ��'."\r\n".$msg[$i]['MG'];break;
								default:
								$qqrobot = self::start($msg[$i]['MG']);
								$reply   = self::$msg;
								echo '<br>REPLAY:<pre>';
								echo($reply);
								echo '</pre>';
								
							}
							break;
						case 99:
							//ϵͳ��Ϣ
							switch($msg[$i]['MG'])
							{
								//case QQ_STATUS_ONLINE:$reply = '������';break;
								//case QQ_STATUS_OFFLINE:$reply = '�ټ�';break;
								//case QQ_STATUS_BUSY:$reply = 'æ�������Ҳ��������ˡ�';break;
								//default:$reply = '����ҷ���һ��ϵͳ��Ϣ��������'.$msg[$i]['MG'];break;
							}
							break;
						case 2:
							//���˼���
							$qq -> replyAdd ($msg[$i]['UN'],'0','');//ͨ����֤
							$reply = "��ã��ܸ����ܹ���ʶ�㣬�����ལ��QQ�����ˡ�";
							sleep(10);
							break;
					}
					if($reply != "" and $lastreply[$msg[$i]['UN']] != $reply and $lastuin !=$msg[$i]['UN']){
						echo "\r\n�ظ�...";
						switch($qq -> sendMsg($msg[$i]['UN'],$reply))
						{
							case QQ_RETURN_SUCCESS :
								echo "���ͳɹ�";
								break;
							case QQ_RETURN_FAILED :
								echo "����ʧ��";
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
		if(!$ip=self::checkurlip($ip)) return '����IP����ַ����ȷ��';
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
		if(!$ip=self::checkurlip($ip)) return '����IP����ַ����ȷ��';
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