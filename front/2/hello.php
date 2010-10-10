<?php
/**
 * 导入(import)
 */
class_exists ('core') or require_once 'core.php';

/**
 * 定义(define)
 */
class hello extends core {

	/**
	 * 默认首页
	 */
	final public static function index () {
		core::view (__CLASS__ . '/' . __FUNCTION__ . '.tpl');
	}

	/**
	 * hello world
	 */
	final public static function world () {
		echo 'hello world!';
	}

}

/**
 * 执行(execute)
 */
hello::stub () and hello::main ();
?>