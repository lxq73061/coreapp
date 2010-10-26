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
		$str .= $trace['file'].':'. $trace['line']."</div>\r\n";
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
?>