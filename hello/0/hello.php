<?php
/**
 * 演示模块
 * 
 * @version 1.0.0
 * @author Z <602000@gmail.com>
 */

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
		core::view (__CLASS__ . '/' . __FUNCTION__ . '.tpl', array ('world' => $_GET ['do']));
	}

	/**
	 * hello coremvc
	 */
	final public static function coremvc () {
		$list = self::selects ('SELECT ? AS c1,? AS c2', array ('hello','CoreMVC'), true);
		echo $list [0]->c1 . ' ' . $list [0]->c2 . '!';

	}

}

/**
 * 执行(execute)
 */
hello::stub () and hello::main ();
?>