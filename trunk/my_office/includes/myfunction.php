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

?>