<?php
/**
* 格式化输出数组等
*/

function pecho($s,$f='print_r') {
	static $i;
	$i++;	
	//echo '<pre>';
	//debug_print_backtrace();
	$str ="<style>#pecho_box ul{padding-left:15px; margin-bottom: 12px;list-style:disc outside none;}#pecho_box ul li{list-style:disc outside none;}</style>";
    $str .="<pre id=\"pecho_box\" style=\"border:#CCC 1px solid; background:#FEFEED; margin:5px;padding:5px\">\r\n";
	$str .="<div style=\"border:#EFEFEF 1px solid; background:#DFDFDF; margin:-4px;padding:3px\"><strong>$i:</strong>";	
	if (function_exists('debug_backtrace')) {
		$traces = debug_backtrace();
		
		//array_shift($traces);
		$trace = array_shift($traces);
		$trace['file'] = str_replace(SITE_ROOT,'',$trace['file']);
		$str .= $trace['file'].':'. $trace['line']."\r\n";
	}	  	
	$str .="</DIV>";

    if($f=='var_export') {
        $str .=var_export($s,true);
	}elseif($f=='var_dump'){
		$str .=var_dump($s,true);
	}elseif($f=='trace'){	
	  //debug_print_backtrace();
	  if (function_exists('debug_backtrace')) {
		$trace = debug_backtrace();
		array_shift($trace);
	  }
	 
	  $str .= pecho_trace($trace);		
//	  foreach($trace as $k=>$v){
//		$str .='#'.$k.'<font color="green" >'.$v['function']. '</font><font color="blue">(</font>'. implode(',',$v['args']) .'<font color="blue">)</font> <strong>called at</strong> ['. $v['file'] .':'. $v['line'].']'."\r\n";
//	  }

    }else {
		
        $str .=print_r($s,true);
    }
    $str .= "\r\n</pre>";
    echo $str;
}
function pecho_trace($traces,$level=0){
	
	static $domain = '';
	if(!is_array($traces[0])) return;
	$trace = array_pop($traces);
	
	if($level==0) {
		$tmp = pathinfo($trace['file']);
		$domain =  dirname($trace['file']);;
		$str = '<ul>';
		$str .= '<li>'.$trace['file'].'</li>';
		$str .= '<li>#'. $level.' {main}';
	}
	if(in_array($trace['function'],array('require','require_once','include','include_once'))){
		
		$fullpath = $trace['args'][0];
		$path = dirname($fullpath);
		$file = basename($fullpath);
		//$trace['args'][0] = str_replace($domain,'',$trace['args'][0]);
		$trace['args'][0] = '<a target="_blank" title="'.$fullpath.'" href="file:///'.str_replace(':',':',$path).'">'.str_replace(array($domain,'\\'),array('','/'),$path).'</a>/<a target="_blank" title="'.$fullpath.'"  href="file:///'.str_replace(':',':',$fullpath).'">'.$file.'</a>';

		
		$str .= '<ul>';
		$str .= '<li>';
		$str .= 'Line:'. $trace['line'].' <font color="green" >'.$trace['function']. '</font><font color="blue">(</font>'.  implode(',',$trace['args']) .'<font color="blue">)</font> ';
		$str .= pecho_trace($traces,++$level);
		$str .=  '</li>';
		$str .=  '</ul>';
	}else{
		
		foreach($trace['args'] as $k=>&$v){
			if(is_array($v)){//如果参数是数组情况.
				 $v=var_export($v,true) ;
				//$v=str_replace("\n",'',$v);
				//$v=str_replace("  ",' ',$v);				
				if(strstr($_SERVER['HTTP_USER_AGENT'],'Firefox')){
					
					$v2=str_replace("\n",'\\n',$v);
					$v2=str_replace("'","\\'",$v2);					
					$v='<a href="javascript:alert(\''.$v2.'\')" title="'.$v.'">Array</a>';
				}else{
					$v=str_replace("\n",'&#10;',$v);
					$v='<a href="javascript:" title="'.$v.'">Array</a>';
					
				}
				
			}
		}
	//	pecho($trace['args']);
		$str .= '<ul>';
		$str .= '<li>';
		$str .= 'Line:'. $trace['line'].' <font color="blue" >PHP::'.$trace['function']. '</font><font color="blue">(</font>'. implode(',',$trace['args']) .'<font color="blue">)</font> ';
		$str .= pecho_trace($traces,++$level);
		$str .=  '</li>';
		$str .=  '</ul>';
	}
	if($level==0) {
		$str .=  '</li>';
		$str .=  '</ul>';
	}
	return $str;
	
}
/**
 * 想尽办法获取在线IP
 * @return <type>
 */

function get_onlineip() {
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}
function cache_delete($file,$path = '') {
    if(!$path) $path = CACHE_PATH;
    $cachefile = $path.$file;
    return @unlink($cachefile);
	}

/**
 * 缓存读取
 */
function cache_read($file, $path = '', $iscachevar = 0) {
    if(!$path) $path = CACHE_PATH;
    $cachefile = $path.$file;
    if($iscachevar) {
        global $TEMP;
        $key = 'cache_'.substr($file, 0, -4);
        return isset($TEMP[$key]) ? $TEMP[$key] : $TEMP[$key] = @include $cachefile;
    }
    return @include $cachefile;
}
/*缓存文件*/
function cache_write($file, $array, $path = '') {
//echo '使用cache';
    if(!is_array($array)) return false;
    $array = "<?php\nreturn ".var_export($array, true).";\n?>";
    $cachefile = chk_dir($path ? $path : CACHE_PATH).$file;
    $strlen = file_put_contents($cachefile, $array);
    //echo $cachefile;

    chmod($cachefile, 0777);
    return $strlen;
}

/*是否存在缓存文件，过期时间默认20秒*/
function cache_isset($file,$expired_time=20, $path = '',$isdel=true) {
    if(!$path) $path = CACHE_PATH;
    $cachefile = $path.$file;
    //1260763476 - 1260761474
    //echo time() .' - '.@filectime($cachefile) ;
    if (time() - @filectime($cachefile) < $expired_time) {
        return true;  //未过期
    }else {
        if($isdel==true)@unlink($cachefile) ;
        return false;
    }

}

//检查一个目录,如果不存在就建立
function chk_dir($dir) {
    if(!is_dir($dir)) {
        //@mkdir($dir,0777);
        directory($dir);
    }
    return $dir;
}
function directory($dir) {

    return is_dir($dir) or (Directory(dirname($dir)) and mkdir($dir, 0777));

} 
/*得到精确时间*/
function getmicrotime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
} 
function get_use_time($min=false) {
    global $time_start;
    $time_end = getmicrotime();
    $times = $time_end - $time_start;
    $times = sprintf('%.5f',$times);
    if($min==false) {
        $use_time =  "用时:". $times ."秒";
    }else {
        $use_time = $times;
    }
    return $use_time;
}

function get_title($get){
	if(isset($get['go'])){
		switch($get['go']){
			case 'user':$title='用户';break;
			case 'doc':$title='文章';break;
			case 'channel':$title='分类';break;
			default:
			
		}
	}
	if(isset($get['do'])){
		switch($get['do']){
			case 'append':$title.='添加';break;
			case 'modify':$title.='修改';break;
			case 'browse':$title.='列表';break;
			case 'detail':$title.='详细';break;
			case 'index':$title.='首页';break;
			
			default:
			
		}
	}
	
	return $title;
}
/**
 * 获得当前的域名
 *
 * @return  string
 */
function get_domain()
{
    /* 协议 */
    $protocol = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';

    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        /* 端口 */
        if (isset($_SERVER['SERVER_PORT']))
        {
            $port = ':' . $_SERVER['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
            {
                $port = '';
            }
        }
        else
        {
            $port = '';
        }

        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'] . $port;
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host;
}

/**
 * 获得网站的URL地址
 *
 * @return  string
 */
function site_url()
{
    return get_domain() . substr(PHP_SELF, 0, strrpos(PHP_SELF, '/'));
}

/**
 * 验证输入的邮件地址是否合法
 *
 * @param   string      $email      需要验证的邮件地址
 *
 * @return bool
 */
function is_email($user_email)
{
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,5}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
    {
        if (preg_match($chars, $user_email))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

/**
 * 检查是否为一个合法的时间格式
 *
 * @param   string  $time
 * @return  void
 */
function is_time($time)
{
    $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';

    return preg_match($pattern, $time);
}
function set_select($v1,$v2){
	//pecho($v1.'=='.$v2.' && '.(strlen($v1)).'=='.(strlen($v2)));
	if($v1==$v2 && strlen($v1)==strlen($v2)){
		return 'selected="selected"';
	}
}
function set_radio($v1,$v2){
	if($v1==$v2){
		return 'checked';
	}
}

/*
函数: Pager
作者: Sam Teng <[email=samteng@live.com]samteng@live.com[/email]>
作用：通过记录总数，每页显示数取得offset，总页数，前一页与后一页以及分页纯数字的页数数组，不带任何链接
返回值: 数组
$aPageDatas['offset']        offset 
$aPageDatas['thispage']      当前页 
$aPageDatas['maxpage']       总页数 
$aPageDatas['prepage']       上一页 
$aPageDatas['nextpage']      下一页
$aPageDatas['pagebar']       分页数组
$aPageDatas['pregroup']      上一组页码
$aPageDatas['nextgroup']     下一组页码
$aPageDatas['querystring']   QUERY_STRING 

参数说明:
$intTotal     记录总数
$intShowNum   每页显示数
$intDistance  分页数组最大最小值与当前页的差值,当0或false的时候，不构建分页数组
$strPageVar   分页变量名，在地址档中如abc.php?page=123，即是page，默认是'page'
$bGetQueryString  是否读取Query String, 默认读取
*/
function Pager ( $intTotal , $intShowNum ,  $intDistance = 5 , $strPageVar = 'page' ,  $bGetQueryString = true ) {
	if(!$strPageVar)$strPageVar='page';   
    $aPageDatas = array() ;
	$aPageDatas['strPageVar']=$strPageVar;
    ( $intThisPage = intval( $_REQUEST[$strPageVar] ) ) > 1 ? $aPageDatas[thispage] = $intThisPage : $aPageDatas['thispage'] = $intThisPage = 1 ;
    if ( $intTotal < 1 || $intShowNum < 1 ) {
        $intOffset   = 0 ;
        $aPageDatas['maxpage']  = $aPageDatas['prepage'] = $aPageDatas['nextpage'] = 1 ;
		$aPageDatas['offset']=0;
        return $aPageDatas ;
    }
    $aPageDatas['total']  =$intTotal;//总
    $aPageDatas['show_num']  =$intShowNum;//每
    $aPageDatas['offset']   = $intShowNum * ( $intThisPage - 1 ) ;
    $aPageDatas['maxpage']  = ceil ( $intTotal / $intShowNum ) ;
    $aPageDatas['prepage']  = $intThisPage < 2 ? 1 : $intThisPage - 1 ;
    $aPageDatas['nextpage'] = $intThisPage == $aPageDatas['maxpage'] ? $aPageDatas['maxpage']  : $intThisPage + 1 ;
    if ( $intDistance ) {
        $arrPageBar = array() ;
        $intSPage = ( $intThisPage - $intDistance ) < 1 ? 1 : ( $intThisPage - $intDistance ) ;
        $intEPage = ( $intThisPage + $intDistance ) > $aPageDatas['maxpage'] ? $aPageDatas['maxpage'] : ( $intThisPage + $intDistance ) ;
        $arrPageBar = array ( ) ;
        for ( $i = $intSPage ; $i <= $intEPage ; $i++ ) {
            $arrPageBar[] = $i ;
        }
        $aPageDatas['pagebar']   = $arrPageBar ;
        $aPageDatas['pregroup']  = $intThisPage > ( $intDistance + 1 ) ? $intThisPage - ( $intDistance + 1 ) : 0 ;
        $aPageDatas['nextgroup'] = $intThisPage < ( $aPageDatas['maxpage'] - 1 - $intDistance ) ? $intThisPage + $intDistance + 1 : $aPageDatas['maxpage'] ;
    }
    if ( $bGetQueryString ) {


		if(isset($_SERVER['REQUEST_URI'])){
		 $tmp  = explode('?',$_SERVER['REQUEST_URI']);
		 $_SERVER["QUERY_STRING"] = $tmp[1] ;
		}


        $strPagepattern = '/('.$strPageVar.'=\d{0,})/' ;
        preg_match_all( $strPagepattern, $_SERVER["QUERY_STRING"] , $arrResult );
        $strQueryString = $arrResult[1][0] ? str_replace( "&".$arrResult[1][0] , "" , $_SERVER["QUERY_STRING"] ) : $_SERVER["QUERY_STRING"];
        $strQueryString = str_replace( $arrResult[1][0] , "" , $strQueryString ) ;
        if ( $strQueryString ) $aPageDatas['querystring'] = $strQueryString . '&';
    }
    return $aPageDatas ;
}

function show_pagenav($aPageDatas) {

    $querystring =$aPageDatas['querystring'];
    $total =$aPageDatas['total'] ;
    $firstcount = 	$aPageDatas['offset'];
    $prepg = $aPageDatas['prepage'];
    $nextpg = $aPageDatas['nextpage'];
    $displaypg = $aPageDatas['show_num'] ;
    $lastpg = $aPageDatas['maxpage'];
    $page = $aPageDatas['thispage'];
	$pagebar = $aPageDatas['pagebar'];

	$maxpage = $aPageDatas['maxpage'];
	$strPageVar = $aPageDatas['strPageVar'];
//开始分页导航条代码：  
//$total总数
//$displaypg每 页显示条
    //$page =$_GET['page'];
  //  $pagenav="显示第 <B>".($total?($firstcount+1):0)."</B>-<B>".min($firstcount+$displaypg,$total)."</B> 条记录，共 $total 条记录<BR>";
    //如果只有一页则跳出函数：
    if($lastpg<=1) {
        return false;
    }
    // $url='';
    $url.= '?'. $querystring . "&".$strPageVar;
    $url = str_replace('&&','&',$url);
    /*  if(strstr($querystring,'?')){
	
  }else {  
  	$url.="?page";  
  }  */

    
    if($prepg && $prepg!=$page) {
        $pagenav.="<div class=\"pagLftNav\"><a class=\"arrowLft\" href=\"$url=$prepg\">Previous</a></div>";
    }else {
        $pagenav.="<div class=\"pagLftNav\"><a class=\"arrowLft disabled\" href=\"#\">Previous</a></div>";
    }

	
    if($nextpg && $nextpg!=$page) {
        $pagenav.=" <div class=\"pagRitNav\"><a class=\"arrowRit\" href=\"$url=$nextpg\">Next</a></div> ";
    }else {
        $pagenav.=" <div class=\"pagRitNav\"><a class=\"arrowRit disabled\" href=\"#\">Next</a></div>";
    }
    //$pagenav.=" <a href=\"$url=$lastpg\">尾页</a> ";
	
	if(sizeof($pagebar)){
		$pagenav.="<div class=\"pagNums\"> Page: ";
		if(!in_array(1,$pagebar)){
			$pagenav.=" <span><a href=\"$url=1\">1</a></span> ";
			if(!in_array(1+1,$pagebar))$pagenav.=" ... ";
		}
		foreach($pagebar as $k=>$v){
			if($v== $page)
			$pagenav.=" <span>$v</span> ";
			else
			$pagenav.=" <span><a href=\"$url=$v\">$v</a></span> ";
		}
		
		if(!in_array($maxpage,$pagebar)){
			if(!in_array($maxpage-1,$pagebar))$pagenav.=" ... ";
			$pagenav.=" <span><a href=\"$url=$maxpage\">$maxpage</a></span> ";
		}
		$pagenav.="</div>";
	}
	
/*    //下拉跳转列表，循环列出所有页码：
    $pagenav.="　转到第 <select name=\"topage\" size=\"1\" onchange=\"window.location='$url='+this.value\">\n";
    for($i=1;$i<=$lastpg;$i++) {
        if($i==$page) {
            $pagenav.="<option value=\"$i\" selected>$i</option>\n";
        }else {
            $pagenav.="<option value=\"$i\">$i</option>\n";
        }
    }
    $pagenav.="</select> 页，共 $lastpg 页";
	*/
    return $pagenav;
}
//检查是否wap访问
function check_wap()
{
	if (strpos(strtoupper($_SERVER['HTTP_ACCEPT']),'VND.WAP.WML') > 0)
	{
		// Check whether the browser/gateway says it accepts WML.
		$br = 'WML';
	}
	else
	{
		$browser=substr(trim($_SERVER['HTTP_USER_AGENT']),0,4);
		if ($browser=='Noki' || // Nokia phones and emulators
		$browser=='Eric' || // Ericsson WAP phones and emulators
		$browser=='WapI' || // Ericsson WapIDE 2.0
		$browser=='MC21' || // Ericsson MC218
		$browser=='AUR'  || // Ericsson R320
		$browser=='R380' || // Ericsson R380
		$browser=='UP.B' || // UP.Browser
		$browser=='WinW' || // WinWAP browser
		$browser=='UPG1' || // UP.SDK 4.0
		$browser=='upsi' || // another kind of UP.Browser ??
		$browser=='QWAP' || // unknown QWAPPER browser
		$browser=='Jigs' || // unknown JigSaw browser
		$browser=='Java' || // unknown Java based browser
		$browser=='Alca' || // unknown Alcatel-BE3 browser (UP based?)
		$browser=='MITS' || // unknown Mitsubishi browser
		$browser=='MOT-' || // unknown browser (UP based?)
		$browser=='My S' ||//  unknown Ericsson devkit browser ?
		$browser=='WAPJ' || //  Virtual WAPJAG www.wapjag.de
		$browser=='fetc' || //  fetchpage.cgi Perl script from www.wapcab.de
		$browser=='ALAV' || //  yet another unknown UP based browser ?
		$browser=='Wapa' || //  another unknown browser (Web based “Wapalyzer'?)
		$browser=='MQQB' || 
		$browser=='Oper') // Opera  
		{
		$br = 'WML';
		}
		else
		{
		$br = 'HTML';
		}
	}

	if($br == 'WML')
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}
/**
 * 加密解密函数
 * @param string $string 要加密的字符串
 * @param string $operation 操作方式，默认为解密
 * @param string $key 加密KEY
 * @param int $expiry 过期时间
 * @link http://www.discuz.net/
 * @return <type>
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

    $ckey_length = 4;
    $key = md5($key ? $key : $GLOBALS['site_auth_key']);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }

}
/**
* 生成option
**/
function make_option($t_lists,$t_id){
			foreach($t_lists as $id=>$name){
				if($t_id==$id){
					$t_list .= "        <option value=\"".$id."\" selected=\"selected\">".$name."</option>\n";
				}else{
					$t_list .= "        <option value=\"".$id."\" >".$name."</option>\n";
				}
			}
			return $t_list;
		}
		
//UBB表情解释
function bbcode($str){
	$text = str_replace(" ","&nbsp;",$text);
	$text = preg_replace("/\[em:(.+?):]/is","<img src=\"/templates/images/face/$1.gif\" class=\"face\">",$str);
	$text = nl2br($text);

	return $text;
}

/**
* 表结构
*/
	function get_table($tablename,$type=null) {
	
        if (!$tablename)
            return;
            $sql = 'DESC `#@__' . $tablename . '`';
		
	
            $table = core::selects($sql, null, true, array(), array('Field', 'assoc' => null));
		if($type){
			$tables = array();
			foreach($table as $k=>$v)foreach($v as $kk=>$vv)if($kk==$type)$tables[]=$vv;
			return $tables;
		}
					
        return $table;
    }	
	function remote_addr(){
		if(!isset($GLOBALS['_REMOTE_ADDR_'])){
			if(isset($_SERVER['HTTP_CLIENT_IP'])){   
			  $GLOBALS['_REMOTE_ADDR_'] = $_SERVER['HTTP_CLIENT_IP'];   
			 //}elseif($_SERVER['HTTP_X_FORWARDED_FOR']){   
			//   $onlineip=$_SERVER['HTTP_X_FORWARDED_FOR'];   
			 }else{   
			  $GLOBALS['_REMOTE_ADDR_'] = $_SERVER['REMOTE_ADDR'];   
			} 
		 
 
			/*$addrs = array();
	
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
				foreach( array_reverse( explode( ',',  $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) as $x_f )
				{
					$x_f = trim($x_f);
	
					if ( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $x_f ) )
					{
						$addrs[] = $x_f;
					}
				}
			}
	
			$GLOBALS['_REMOTE_ADDR_'] = isset($addrs[0])?$addrs[0]:$_SERVER['REMOTE_ADDR'];*/
		}
		return $GLOBALS['_REMOTE_ADDR_'];
	}
/**
* 依次寻找模板路径
*/
function find_tpl($type,$gid,$id=null){
	$game_tpl_dir = 'game_'.str_pad($gid,2,'0',STR_PAD_LEFT);
	$curr_tpl_dir = SUB_SITE_ROOT . '/' . core::init('template_path');
	$defa_tpl_dir = SUB_SITE_ROOT . '/' . core::init('template_default_path');
	$tpls = array();
	if($id!==null)$tpls[]= $curr_tpl_dir.'/'.$game_tpl_dir.'/'.$type.'/'.$id.'.php';
	$tpls[]= $curr_tpl_dir.'/'.$game_tpl_dir.'/'.$type.'/index.php';
	if($id!==null)$tpls[]= $curr_tpl_dir.'/game_default/'.$type.'/'.$id.'.php';
	$tpls[]= $curr_tpl_dir.'/game_default/'.$type.'/index.php';
	
	if($curr_tpl_dir!=$defa_tpl_dir){
		if($id!==null)$tpls[]= $defa_tpl_dir.'/'.$game_tpl_dir.'/'.$type.'/'.$id.'.php';
		$tpls[]= $defa_tpl_dir.'/'.$game_tpl_dir.'/'.$type.'/index.php';
		if($id!==null)$tpls[]= $defa_tpl_dir.'/game_default/'.$type.'/'.$id.'.php';
		$tpls[]= $defa_tpl_dir.'/game_default/'.$type.'/index.php';
	}
	
	foreach($tpls  as $tpl){
		if(file_exists($tpl)){
			break;
		}
	}
	if(!file_exists($tpl)){
		showmsg("模板錯誤：".$type.','.$gid.','.$id,-1,'diemsg');
	}
	return $tpl;
	
}
function vcode2($key,$width=50,$height=22) {
	
    $value = $_SESSION [$key] = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

	$fontY = 20;//顶边
	$fontSize = 20;//文字大小
	$size = 12;//文字（宽） $width/$len; 自动计算
	$left = 15; //文字左边距
	$y = $fontY;
	$x= 20;
	 
	if(!$fontSize ){
		$randsize = rand($size-$size/10, $size+$size/10);
	}else{
		$randsize =$fontSize;
	}	
	ob_end_clean();
    // 生成验证碼图
    header('Content-type: image/png');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache -Control: no-store, no-cache , must-revalidate");
    header("Cache -Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache ");
	$fontFile = SITE_ROOT.'/skin/fonts/Candara.ttf';
	$array = range(1,30);
	
	$bgpic  = SITE_ROOT.'/skin/fonts/code/'.$array[array_rand($array)].'.jpg';
    //$im = imagecreate($width, $height);
	$im        = imagecreatefromjpeg($bgpic);
    $color = imagecolorallocate($im, 35, 40, 20);//背景颜色
    imagefill($im, 0, 0, $color);
    $color = imagecolorallocate($im, 215, 215, 151);//文字颜色

	
	imagettftext($im, $randsize, $fontAngle , $x,$y, $color, $fontFile, $value); 
	//setnoise($im,$width, $height);
    imagepng($im);
    imagedestroy($im);
}
/**
* 显示提示消息
*
* @param array $msgs 消息内容
* @param int $time 页面跳转等待时间（秒） 
* @param string $return 页面跳转目标URL 
* @param string $title 提示信息标题 
*
* @return string 返回替换过后的字段
* @author Steven <Steven@farmer100.com>
*/
function show_msg($msgs, $time='', $return='',$title='',$type='warning',$showtime=false)
{
	$header = '
<script>
function countDown(secs,surl){
	document.getElementById(\'tiao\').innerHTML =secs;	
	if(--secs>0){
		setTimeout("countDown("+secs+",\'"+surl+"\')",1000); 
	}else{
		if (surl == \'back\'){
			window.history.back();
		}else{
		location.href=surl;	
		}		
	}  
}
</script>';
	if($title){
	$title = " <h3> $title \n\t</h3>";
	}
	$msg = $header."<div id=\"msg_info\" class=\"alert alert-{$type}\">\n\t{$title}\n\t <div class=\"\">\n<ul>";

	
	while (list($k,$v)=each($msgs)){
			$msg .= "<li>".$v."</li>";
		}
	if(!$return || $return == -1){
		//$return = 'back';	
		$returnurl = 'javascript:history.back();';	
	}else{
		$returnurl = $return;
	}
	if($time) {
		$msg .= "<li class=\"\"><img src=./skin/images/wait2.gif /></li>";
		if($showtime==false){//不显示时间进度
			$tiaostyle ='style=\'display:none\'';
		}
		$msg .= "<li><span id='tiao' $tiaostyle >$time</span> <a href='$returnurl'>点击这里，如果您的浏览器没有自动跳转</a> <br /><script>countDown($time, '$returnurl');</script></li>";	
	}
	$msg .= "</ul></div>\n</div>";
	
	return $msg;
}	
//添加转义
function addslashes_deep($value)
{
    if (empty($value))
        return $value;
    else
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value); // 只处理了數組的值:)
}

//删除转义
function stripslashes_deep($value)
{
    if (empty($value))
        return $value;
    else
        return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value); // 只处理了數組的值:)
}?>