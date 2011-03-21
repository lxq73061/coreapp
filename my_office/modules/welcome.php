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
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	final static public function top() {
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	final static public function left() {
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	final static public function right() {
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	final static public function middle() {
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
}

?>