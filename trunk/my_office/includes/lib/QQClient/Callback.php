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
//file_put_contents($sLogFolder.date('Y-n-j G.i.s').".txt",var_export($_REQUEST,1)) ;


//echo $msg[IsGroup];
//echo $msg[fromame].'您好,我是北洋贱队机器人小小狗,请您不要骚扰我';

//$qqrobot=new qqrobot($_POST["msg"]);


class qqrobot
{
	public $help="您需要输入命令来使用机器人 例(@ip，后面接空格再接ip地址或是域名)\r\n@ip 网址或IP   <IP地址信息>\r\n@mobile 手机号  <查询手机号归属地>\r\n@weather 城市 <查询本城市三天天气预报>\r\n@md5 字符  <对字符进行md5加密>\r\n@cfs 字符  <cfs不可逆加密>\r\n@enbase64 代码  <对代码进行base64加密>\r\n@debase64 代码  <base64解密>\r\n@whois 域名  <查询域名whois信息>\r\n@ping ip地址或网址  <查看ip地址的连接时间>\r\n@alexa 域名  <查看域名的alexa排名>\r\n@pr 域名  <查询域名的pr值>\r\n@cn2en 字符  <把中文字符翻译成英文字符>\r\n@en2cn 字符  <把英文翻译成中文>";
	public $msg = '';
	function __construct($msg){
		global $sLogFolder;
		file_put_contents($sLogFolder.date('Y-n-j G.i.s').".txt",var_export($_REQUEST,1)) ;
		
		if(substr($msg,0,1)=='@'){
			$msg=substr($msg,1,strlen($msg));
			$arr=explode(' ',$msg);
			$func=$arr[0];
			if(!method_exists($this,$func)){
				$this->msg = '命令出错';
				//echo "命令出错！\r\n".$this->help;
			}else{
				
				//$this->msg = iconv('gb2312','utf-8',$this->$func(trim(str_replace($func.' ','',$msg))));
				$this->msg = $this->$func(trim(str_replace($func.' ','',$msg)));
			}
		}else{
			$this->msg = urlencode($this->help);
		}
		$this->msg =  strip_tags($this->msg);
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
		if(!$ip=$this->checkurlip($ip)) return '出错，IP或网址不正确！';
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
		if(!$ip=$this->checkurlip($ip)) return '出错，IP或网址不正确！';
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
	{
		//return file_get_contents("http://api.liqwei.com/translate/?language=zh-CN|en&content=".iconv('utf-8','gb2312',$content));
		$msg =  file_get_contents("http://api.liqwei.com/translate/?language=zh-CN|en&content=".iconv('GBK','gb2312',$content));
		$msg =str_replace('+',' ',$msg);
		return $msg;
		
	}

	function en2cn($content)
	{
		$msg = file_get_contents("http://api.liqwei.com/translate/?language=en|zh-CN&content=".iconv('GBK','gb2312',$content));
		$msg =str_replace('+',' ',$msg);
		return $msg;
	}

	function translate($t1,$t2,$content)
	{
		return file_get_contents("http://api.liqwei.com/translate/?language={$t1}|{$t2}&content=".iconv('GBK','gb2312',$content));
	}
	function check($content){
		return strlen(file_get_contents($content));
	}
}


?>