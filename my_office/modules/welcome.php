<?php
/**
 * 用户模块
 * 
 * @version 1.2.1
 * @author Z <602000@gmail.com>
 */

/**
 * 导入(import)
 */
class_exists('core') or require_once 'core.php';

/**
 * 定义(define)
 */
class welcome extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		if(IN_WAP)return self::wap();
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	final static public function wap() {
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	final static public function top() {
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	final static public function left() {
		$tree =  channel::tree();
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl',compact('tree'));
	}
	final static public function right() {
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	final static public function middle() {
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	final static public function online() {
		
		echo  'online';
		return;
	}
	
}

?>