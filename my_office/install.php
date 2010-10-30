<?php
/**
 * 安装模块
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
class install extends core {
	
	/**
	 * 默认动作
	 */
	final static public function main() {
		self::view ( __FUNCTION__.'.tpl');
	}
	


	
	/**
	 * 设置数据库参数
	 */
	final static public function setup() {
		$error = array ();
		$post = array();
		#self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 检查程序是否已经安装
	 */
	final static public function check() {
		if(file_exists(dirname(__FILE__).'/configs/install.lock')){
			return true;//已经安装
		}else{
			return false;//未安装
		}
	}

	
}

/**
 * 执行(execute)
 */
install::init(array('template_path' => '@install\\'));
install::stub () and install::main ();


?>