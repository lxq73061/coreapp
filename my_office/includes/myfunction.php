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
?>