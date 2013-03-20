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
//file_put_contents($sLogFolder.date('Y-n-j G.i.s').".txt",var_export($_REQUEST,1)) ;


//echo $msg[IsGroup];
//echo $msg[fromame].'����,���Ǳ�����ӻ�����СС��,������Ҫɧ����';

//$qqrobot=new qqrobot($_POST["msg"]);


class qqrobot
{
	public $help="����Ҫ����������ʹ�û����� ��(@ip������ӿո��ٽ�ip��ַ��������)\r\n@ip ��ַ��IP   <IP��ַ��Ϣ>\r\n@mobile �ֻ���  <��ѯ�ֻ��Ź�����>\r\n@weather ���� <��ѯ��������������Ԥ��>\r\n@md5 �ַ�  <���ַ�����md5����>\r\n@cfs �ַ�  <cfs���������>\r\n@enbase64 ����  <�Դ������base64����>\r\n@debase64 ����  <base64����>\r\n@whois ����  <��ѯ����whois��Ϣ>\r\n@ping ip��ַ����ַ  <�鿴ip��ַ������ʱ��>\r\n@alexa ����  <�鿴������alexa����>\r\n@pr ����  <��ѯ������prֵ>\r\n@cn2en �ַ�  <�������ַ������Ӣ���ַ�>\r\n@en2cn �ַ�  <��Ӣ�ķ��������>";
	public $msg = '';
	function __construct($msg){
		global $sLogFolder;
		file_put_contents($sLogFolder.date('Y-n-j G.i.s').".txt",var_export($_REQUEST,1)) ;
		
		if(substr($msg,0,1)=='@'){
			$msg=substr($msg,1,strlen($msg));
			$arr=explode(' ',$msg);
			$func=$arr[0];
			if(!method_exists($this,$func)){
				$this->msg = '�������';
				//echo "�������\r\n".$this->help;
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
		if(!$ip=$this->checkurlip($ip)) return '����IP����ַ����ȷ��';
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
		if(!$ip=$this->checkurlip($ip)) return '����IP����ַ����ȷ��';
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