<?php
/**
 * 前端模块
 * 
 * @version 1.3.0
 * @author Z <602000@gmail.com>
 */

/**
 * 导入(import)
 */
class_exists('core') or require_once 'core.php';

/**
 * 定义(define)
 */
class front extends core {

	/**
	 * 配置文件或参数
	 */
	private static $config = '';

	/**
	 * 入口函数(继承)
	 */
	public static function main($framework_enable = null, $framework_require = null, $framework_module = null, $framework_action = null, $framework_parameter = null){

		// 防止运行多次
		static $static_repeat = false;
		if ($static_repeat) {
			return parent::main (false);
		}
		$static_repeat = true;
		define('IN_WAP',check_wap()); 
			
		// 载入配置参数
		list ($front_action,$front_online) = self::init(array('front_action','front_online'));
		list ($front_action2,$front_online2) = parent::init(array('front_action','front_online'));
		$front_action === '' and $front_action = $front_action2;
		$front_action === '' and $front_action = parent::init('framework_action');
		$front_online === '' and $front_online = $front_online2;

		// 执行前端模块
		$online = parent::main ('final,return', null, __CLASS__, $front_action);

		// 执行后续模块
		if ($online) {
			// 视图全局变量
			if ($front_online) {
				front::view2 (array ($front_online=>$online));
			}
			return parent::main ($framework_enable, $framework_require, $framework_module, $framework_action, $framework_parameter);
		} else {
			return false;
		}
	}

	/**
	 * 配置函数(继承)
	 *
	 * @param mixed $config
	 * @param mixed &$variable
	 * @return mixed
	 */
	public static function init($config = null, &$variable = null) {
		if ($variable === null) {
			// 默认本类配置
			if (self::$config === '') {
				$file = self::path (__CLASS__ . '/config.php', 'config');
				if (is_file ($file)) {
					parent::init ($file, self::$config);;
				}
			}
			return parent::init ($config, self::$config);
		} else {
			// 调用继承函数
			return parent::init ($config, $variable);
		}
	}

	/**
	 * 在线函数
	 *
	 * @param object/bool $online
	 * @return object/null
	 */
	public static function online($online = null) {

		// 处理在线状态
		static $key = null;
		if ( $key === null ) {
			$key = 'online_' . md5(__FILE__) . '_' . __CLASS__;
		}
		static $value = null;
		if ( $online === null && $value !== null ) {
			return $value;
		}

		// 处理会话过程
		headers_sent () or session_start ();
		if ( $online === false ) {
			unset ( $_SESSION [$key] );
			$value = null;
		} else {
			$front_class = self::init('front_class');
			$front_class === '' and $front_class = parent::init('front_class');
			if (empty ($front_class) || ! class_exists ($front_class)) {
				$front_class = get_parent_class ();
			}
			if ($online instanceof $front_class) {
				$_SESSION [$key] = serialize($online);
				$value = $online;
			} else {
				$value = isset($_SESSION [$key]) ? unserialize($_SESSION [$key]) : null;
			}
		}
		headers_sent () or session_write_close ();
		return $value;
	}

	/**
	 * 默认动作
	 */
	final public static function index() {

		// 第二次运行
		static $static_repeat = false;
		if ($static_repeat) {
			$front_redirect = self::init('front_redirect');
			$front_redirect === '' and $front_redirect = parent::init('front_redirect');
			if ($front_redirect !== '') {
				header ('Location: ' . $front_redirect);
			} else {
				echo 'Require front_redirect.';
			}
			return false;
		}
		$static_repeat = true;

		// 第一次运行
		$online = self::online ();
		if ($online) {
			// 前端已登录
			return $online;
		} else {
			// 前端未登录
			return self::login ($_SERVER['REQUEST_URI']);
		}

	}

	/**
	 * 登录函数
	 *
	 * @param bool $login
	 * @return self
	 */
	final public static function login($redirect = null) {
		$error = array();
//print_r($_SERVER);
//print_r($_POST);
		// 数据消毒
		$post = array(
			'username' => isset ($_POST ['username']) ? $_POST ['username'] : '',
			'password' => isset ($_POST ['password']) ? $_POST ['password'] : '',
			'authcode' => isset ($_POST ['authcode']) ? $_POST ['authcode'] : '',
			'redirect' => isset ($_POST ['redirect']) ? $_POST ['redirect'] : '',
		);
		if (get_magic_quotes_gpc()) {
			$post = array_map ('stripslashes', $post);
		}

		// 表单处理
		while ($redirect === null && isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST'){

			// 配置处理
			$attribute_array = array ('front_class','front_table','front_fuzzy','front_username','front_password','front_redirect');
			list ($front_class,$front_table,$front_fuzzy,$front_username,$front_password,$front_redirect) = self::init($attribute_array);
			list ($front_class2,$front_table2,$front_fuzzy2,$front_username2,$front_password2,$front_redirect2) = parent::init($attribute_array);
			$front_class === '' and $front_class = $front_class2;
			$front_table === '' and $front_table = $front_table2;
			$front_fuzzy === '' and $front_fuzzy = $front_fuzzy2;
			$front_username === '' and $front_username = $front_username2;
			$front_password === '' and $front_password = $front_password2;
			$front_redirect === '' and $front_redirect = $front_redirect2;

			// 数据验证
			if ($post ['username'] === '') {
				$error ['username'] = '用户名不能为空';
			}
			if ($post ['password'] === '') {
				$error ['password'] = '密码不能为空';
			}
			if(!IN_WAP){
				if ($post ['authcode'] === '') {
					$error ['authcode'] = '验证码不能为空';
				} elseif (! self::authcode( $post ['authcode'] ) ) {
					$error ['authcode'] = '验证码输入不正确';
				}
			}
			if ($error !== array () ) {
				break;
			}
			// 密码加密处理
			//$post ['password'] = md5 ($post ['password']);
			//$front_password = md5 ($front_password);
			if (empty ($front_class)) {
				$front_class = get_parent_class ();
			}
			if ($front_username) {
				// 配置项验证
				if ($front_fuzzy) {
					// 同时验证
					if ($front_username !== $post ['username'] || $front_password !== md5($post ['username'].md5($post ['password']))) {
						$error ['username'] = $error ['password'] = '用户名或密码不正确';
						break;
					}
				} else {
					// 分开验证
					if ($front_username !== $post ['username']) {
						$error ['username'] = '用户名不存在';
						break;
					}
					if ($front_password !== md5($post ['username'].md5($post ['password']))) {
						$error ['password'] = '密码不正确';
						break;
					}
				}
				$online = new $front_class;
				$online->username = $post ['username'];
				$online->password = md5($post ['username'].md5($post ['password']));
			} else {
				// 数据库验证
				if (empty ($front_table)) {
					$front_table = null;
					$class_table = 'class|table=' . $front_class;
				} else {
					$class_table = 'class';
				}
				if ($front_fuzzy) {
					// 同时验证
					$online = self::selects (null, $front_table, array('username'=>$post ['username'], 'password'=>md5($post ['username'].md5($post ['password']))), null, array($class_table=>$front_class));
					if (empty ($online)) {
						$error ['username'] = $error ['password'] = '用户名或密码不正确';
						break;
					}
				} else {
					// 分开验证
					$online = self::selects (null, $front_table, array('username'=>$post ['username']), null, array($class_table=>$front_class));
					if (empty ($online)) {
						$error ['username'] = '用户名不存在';
						break;
					}
					if ($online->password !== md5($post ['username'].md5($post ['password']))) {
						$error ['password'] = '密码不正确';
						break;
					}
				}
			}

			// 页面跳转
			self::online ( $online );
			if ($post ['redirect'] !== '') {
				header ('Location: ' . $post ['redirect']);
			} elseif ($front_redirect !== '') {
				header ('Location: ' . $front_redirect);
			} else {
				echo 'Require front_redirect.';
			}
			return false;
		}

		// 显示模板
		front::view2 ( __CLASS__ . '.' . __FUNCTION__.'.tpl', compact ( 'error', 'redirect' ) );
		return false;
	}

	/**
	 * 登出函数
	 *
	 * @return bool
	 */
	final public static function logout() {
		self::online (false);
		return self::login ('');
	}

	/**
	 * 验证码函数
	 *
	 * @param string $authcode
	 * @return bool
	 */
	final public static function authcode($authcode = null) {
		static $key = null;
		if ( $key === null ) {
			$key = 'authcode_' . md5(__FILE__) . '_' . __CLASS__;
		}
		if ( $authcode === null ) {
			// 生成验证码号
			session_start ();
			$value = $_SESSION [$key] = str_pad(mt_rand(0,9999),4,'0',STR_PAD_LEFT);
			session_write_close ();
			// 生成验证码图
			header('Content-type: image/png');
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
			header("Cache -Control: no-store, no-cache , must-revalidate");
			header("Cache -Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache ");
			$im = imagecreate(47,17);
			$color = imagecolorallocate($im,255,255,255);
			imagefill($im, 0, 0, $color);
			$color = imagecolorallocate($im,255,0,0);
			imagestring($im, 5, 5, 1, $value, $color);
			imagepng($im);
			imagedestroy($im);
		} else {
			// 返回验证码号
			session_start ();
			$value = isset($_SESSION [$key]) ? $_SESSION [$key] : null;
			unset($_SESSION [$key]);
			session_write_close ();
			return $authcode === $value;
		}
	}
	public static function view2($_view_file_global = null, $_view_vars = null, $_view_type = null, $_view_show = null){
		//$config = parent::init('config');
		
		if(is_string($_view_file_global) && strstr($_view_file_global,'.')){
			
			$str =  parent::view($_view_file_global, $_view_vars, $_view_type, false);
			if($config['siteRewrite']==false){
				$array = array(				
					'@([\w\d\-]+)_([\d]+).html@isU'=>'?go=news&do=detail&news_id=$2',
				);
				$a1 =array_keys($array);
				$a2 =array_values($array);	
				if($_view_show===false) return preg_replace($a1,$a2,$str);
				$html =  preg_replace($a1,$a2,$str);				
			}else{
				$array = array(
					'@\?go=user@isU'=>'My-Account.html',
				);
				$a1 =array_keys($array);
				$a2 =array_values($array);	
				if($_view_show===false) return preg_replace($a1,$a2,$str);
				$html =  preg_replace($a1,$a2,$str);	
			}
			//$html =  strip_tags($html,'<p><a><b><html><body><!DOCTYPE><div><table><tr><td><th><input><select><option>');  
			if(IN_WAP){
				$array = array(
					'@<\!DOCTYPE[^>]+>@isU'=>'',
					'@<html[^>]+>@isU'=>'<html>',
					'@<link[^>]+>@isU'=>'',
					'@<script[^>]*?>[^<]+?</script>@isU'=>'',
					'@<script[^>]*?></script>@isU'=>'',
					'@&nbsp;@isU'=>' ',
					
				);
				$a1 =array_keys($array);
				$a2 =array_values($array);				
				$html =  preg_replace($a1,$a2,$html);
			}
			echo $html;
			
		}else{
			return parent::view($_view_file_global, $_view_vars, $_view_type, $_view_show);
		}
		
	
	}
}

/**
 * 执行(execute)
 */
front::stub () and front::main ();
?>