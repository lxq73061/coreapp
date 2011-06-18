<?php
/**
 * 安装模块
 * 
 * @version 1.2.1
 * @author 青剑 <lxq73061@gmail.com>
 */

/**
 * 导入(import)
 */

error_reporting(E_ALL ^E_NOTICE);
class_exists('core') or require_once 'core.php';

define('LOCK_FILE', dirname(__FILE__) . '/configs/install.lock');
define('CHARSET','UTF-8');
define('ROOT_PATH', dirname(__FILE__));   //该常量是ECCore要求的

/**
 * 定义(define)
 */

class installd extends core {
	/**
	 * 配置文件或参数
	 */
	private static $_done = '';
	private static $_doing = '';
	private static $_hiddens = array();
	
	
		
	/**
	 * 默认动作
	 */
	final static public function main() {
		if (file_exists(LOCK_FILE))
        {
            header('Content-Type:text/html;charset=' . CHARSET);
            die(get_lang('install_locked'));
        }
		
		//self::view ( __FUNCTION__.'.tpl');
		$done  = empty($_GET['do']) ? '' : trim($_GET['do']);
        self::$_done = $done;
        $doing = self::_get_doing();
		self::$_doing = $doing;
		$ondone = $done . '_done';


        if (method_exists(installd, $ondone))
        {

            /* 有结果检测 */
            if (self::$ondone())
            {
                /* 检测通过，进行下一步 */
                self::$doing();
            }
        }
        else
        {
            /* 无结果检测，直接进行下一步 */
            self::$doing();
        }
		
	}
	
    /**
     *    用户协议，该步骤提交两个POST变量，lang和accept
     *
     *    @author    Garbin
     *    @return    void
     */
    function eula()
    {
        $eula = file_get_contents(dirname(__FILE__).'/install/eula.html');
        //$this->assign('eula', $eula);
        //$this->display('eula.html');
		
		self::view ('eula.tpl', compact ('eula', 'error'));
    }
	public  static function view($_view_file,$_view_vars=array(),$_view_type=null,$_view_show=null){
        $map = self::_get_map();
		
       $_view_vars['lang']= get_lang();
       $_view_vars['charset']=  CHARSET;
       $_view_vars['done']=   self::$_done;
       $_view_vars['doing']=  self::$_doing;
       $_view_vars['step_num']=  array_search(self::$_doing,  $map) + 1;
       $_view_vars['step_name']=  get_lang(self::$_doing."_title");
       $_view_vars['step_desc']=  get_lang(self::$_doing."_desc");
       $_view_vars['hiddens']=  self::$_hiddens;
       $_view_vars['map']=  $map;		
	
		parent::view($_view_file,$_view_vars,$_view_type,$_view_show);
	}

    /**
     *    获取当前步骤
     *
     *    @author    Garbin
     *    @return    string
     */
    function _get_doing()
    {
        $map = self::_get_map();
        if (!self::$_done)
        {
            return current($map);
        }

        $key = array_search(self::$_done, self::_get_map());
        $nkey = $key + 1;
        if (isset($map[$nkey]))
        {
            return $map[$nkey];
        }
        else
        {
            return '_install_finished';
        }
    }
    function _get_map()
    {
        return array(
            'eula',     //用户协议
            'check',    //环境检测
            'config',   //用户配置
            'install',  //完成安装
        );
    }	
    /**
     *    配置表单，该步骤提交一个配置数组
     *
     *    @author    Garbin
     *    @return    void
     */
    function config($errvar=array())
    {
        self::$_hiddens['accept']   = $_POST['accept'];
        self::$_hiddens['compatible']   = $_POST['compatible'];
        //$site_url = dirname(site_url());
		 $site_url = (site_url());
       // $this->display('config.html');
	   $vars=compact ('site_url');
	   $vars+=$errvar;
	   
	   self::view ('config.tpl', $vars);
    }

    /**
     *    配置表单的处理脚本
     *
     *    @author    Garbin
     *    @return    void
     */
    function config_done()
    {

        $missing_items = array();
        foreach ($_POST as $key => $value)
        {
            if (empty($value) && $key != 'db_pass')
            {
                $missing_items[] = $key;
            }
        }
        if (!empty($missing_items))
        {
			//pecho($_POST);
			//pecho($missing_items);
            self::$_doing = self::$_done;
            //$this->assign('missing_items', $missing_items);
            self::config(compact ('missing_items'));

            return false;
        }
        extract($_POST);
        if (!preg_match("/^http(s?):\/\//i", $site_url))
        {
            self::$_doing = self::$_done;
            //$this->assign('site_url_error', true);
            self::config(array('site_url_error'=>true));
            return false;
        }
        if (!is_email($admin_email))
        {
            self::$_doing = self::$_done;
            //$this->assign('admin_email_error', true);
            self::config(array('admin_email_error'=>true));
            return false;
        }
        if ($admin_pass != $pass_confirm)
        {
            self::$_doing = self::$_done;
            //$this->assign('pass_error', true);
            self::config(array('pass_error'=>true));
            return false;
        }
		
        /* 检查输入的数据库配置信息*/
        /* 检查是否能连上数据库 */
        $con = @mysql_connect($db_host . ':' . $db_port, $db_user, $db_pass);
        if (!$con)
        {
            self::$_doing = self::$_done;
            //$this->assign('mysql_error', mysql_error());
            self::config(array('mysql_error'=>mysql_error()));
            return false;
        }
        /* 检查数据库是否存在 */
        $selected_db = @mysql_select_db($db_name);
        if (!$selected_db)
        {
			
            /* 如果不存在，尝试创建该数据库 */
            $created_db = create_db($db_name, $con);

            /* 创建不成功，则显示错误 */
            if (!$created_db)
            {
                self::$_doing = self::$_done;
                //$this->assign('create_db_error', mysql_error());
                self::config(array('create_db_error'=>mysql_error()));

                return false;
            }
        }
        else
        {
            /* 如果存在，检查是否已安装过MyOffice */
            $query = @mysql_query("SHOW TABLES LIKE '{$db_prefix}%'");
            /* 如果安装过，检查是否同意强制安装 */
            $has_myoffice = false;
            while ($row = mysql_fetch_assoc($query))
            {
                $has_myoffice = true;
                break;
            }

            /* 有MyOffice，但不同意强制安装，则显示错误 */
            if ($has_myoffice && empty($_POST['force_install']))
            {
                self::$_doing = self::$_done;
                //$this->assign('has_myoffice', true);
                self::config(array('has_myoffice'=>true));

                return false;
            }

            /* 没有装过MyOffice或有MyOffice但同意强制安装，则直接通过 */
        }

        return true;
    }

    /**
     *    安装，根据之前POST过来的配置项组成安装方案并运行
     *
     *    @author    Garbin
     *    @return    void
     */
    function install()
    {
        foreach ($_POST as $key => $value)
        {
            self::$_hiddens[$key] = $value;
        }
        //$this->display('install.html');
		 self::view ('install.tpl');
    }

    /**
     *    完成安装
     *
     *    @author    Garbin
     *    @return    void
     */
    function install_done()
    {

        extract($_POST);

        /* 无实际用途 */
        $_code = rand(10000, 99999);
        setcookie('__INTECODE__', $_code, 0, '/');

        /* 连接数据库 */
        $con = mysql_connect($db_host . ':' . $db_port, $db_user, $db_pass);

        if (!$con)
        {
            show_process(r(get_lang('connect_db'), false), 'parent.show_warning("' . get_lang('connect_db_error') . '")');

            return false;
        }
        show_process(r(get_lang('connect_db'), true));
        $version = mysql_get_server_info();
        $charset = str_replace('-', '', CHARSET);
        if ($version > '4.1')
        {
            if ($charset != 'latin1')
            {
                mysql_query("SET character_set_connection={$charset}, character_set_results={$charset}, character_set_client=binary", $con);
            }
            if ($version > '5.0.1')
            {
                mysql_query("SET sql_mode=''", $con);
            }
        }

        /* 选择数据库 */
        $selected_db = mysql_select_db($db_name, $con);
        if (!$selected_db)
        {
            show_process(r(get_lang('selecte_db'), false), 'parent.show_warning("' . get_lang('selecte_db_error') . '");');

            return false;
        }
        /* 建立数据库结构 */
        show_process(r(get_lang('start_setup_db'), true));

        $sqls = get_sql(version_data('structure.sql'));
        foreach ($sqls as $sql)
        {
            $sql = replace_prefix('ecm_', $db_prefix, $sql);
            if (substr($sql, 0, 12) == 'CREATE TABLE')
            {
                $name = preg_replace("/CREATE TABLE `{$db_prefix}([a-z0-9_]+)` .*/is", "\\1", $sql);
                mysql_query(create_table($sql));				
                show_process(r(sprintf(get_lang('create_table'), $name), true, 1));
            }
            else
            {
                mysql_query($sql, $con);
            }
        }
        /* 安装初始数据 TODO 暂时不完整 */
        $sqls = get_sql(version_data('initdata.sql'));
        //$password = md5($admin_pass);//不加密密码
		 $password = md5($admin_name.md5($admin_pass));
       // $sqls[] = "INSERT INTO `ecm_member`(user_name, email, password, reg_time) VALUES('{$admin_name}', '{$admin_email}', '{$password}', " . gmtime() . ")";
		$sqls[] = "INSERT INTO `ecm_user` (`username`, `password`, `grade`, `name`, `gender`, `mobile`, `email`, `url`, `remark`) VALUES
('{$admin_name}', '{$password}', 1, NULL, NULL, NULL, '{$admin_email}', NULL, NULL)";

        foreach ($sqls as $sql)
        {
            $rzt = mysql_query(replace_prefix('ecm_', $db_prefix, $sql), $con);
            if (!$rzt)
            {
                show_process(r(get_lang('install_initdata'), false), 'parent.show_warning("' . mysql_error() . '");');

                return false;
            }
        }
        if (mysql_errno())
        {
            echo mysql_error();
        }
        show_process(r(get_lang('install_initdata'), true));

        /* 安装初始配置 */
        $db_config = "mysql://{$db_user}:{$db_pass}@{$db_host}:{$db_port}/{$db_name}";
        //$ecm_key   = get_ecm_key();
        //$mall_site_id = product_id();
/*        save_config_file(array(
            'SITE_URL'  => $site_url,
            'DB_CONFIG'  => $db_config,
            'DB_PREFIX'  => $db_prefix,
            'LANG'  => LANG,
            'COOKIE_DOMAIN'  => '',
            'COOKIE_PATH'  => '/',
            'ECM_KEY'  => $ecm_key,
            'MALL_SITE_ID'  => $mall_site_id,
            'ENABLED_GZIP'  => 0,
            'DEBUG_MODE'  => 0,
            'CACHE_SERVER'  => 'default',
            'MEMBER_TYPE'  => 'default',
            'ENABLED_SUBDOMAIN' => 0,
            'SUBDOMAIN_SUFFIX'  => '',
        ));*/
save_config_file(array('autoload_enable' => true,
  'autoload_path' => '@modules',
  'framework_function' => 'front::main',
  'framework_enable' => true,
  'framework_module' => '[go]!(self)|welcome',
  'framework_action' => '[do]|index',
  'template_path' => '@templates/',
  'connect_server' => $db_host, //数据库连接服务器
  'connect_username'=>$db_user,
  'connect_password'=>$db_pass,
  'connect_dbname'=>$db_name,
  'connect_port' => $db_port, //数据库连接端口号

  'connect_charset' => 'UTF8',
  'prefix_search' => $db_prefix, //表名前缀标识符
  'prefix_replace' => $db_prefix, 
  'extension_path' => '@includes',
  'extension_enable' => 'myfunction',
 'debug_enable'=>false,
 'sql_format' => false,
 'debug_file' => '',  
 'front_action' => '', //前端模块的动作参数，默认同framework_action         
 'front_online' => 'online', //在线用户的全局变量名，默认不使用                   
 'front_class' => 'user', //待验证模块的类名，默认是使用核心类                  
 'front_table' => $db_prefix.'user', //待验证模块的表名，默认是加上前缀的类名(类名为空除外)
 'front_fuzzy' => '', //用户名密码验证的模糊提示                            
 'front_username' => '', //单一的用户名，默认不使用                         
 'front_password' => '', //单一的密码                                       
 'front_redirect' => 'index.php', //表单无跳转参数时登录后的默认跳转地址     
 	));
        /* 写入系统信息 */
       // save_system_info(array(
       //     'version'   => VERSION,
       //     'release'   => RELEASE,
       // ));
        show_process(r(get_lang('setup_config'), true));

        /* 锁定安装程序 */
        touch(LOCK_FILE);
        show_process(r(get_lang('lock_install'), true));
  
		/* 安装完成 */
		show_process(r(get_lang('install_done'), true), 'parent.install_successed();');

		return false;
      
    }
    /**
     *    安装完成提示
     *
     *    @author    Garbin
     *    @return    void
     */
    function _install_finished()
    {
        echo 'Install finished';
    }
    /**
     *    环境检测，该步骤提交一个POST变量compatible,yes:检测通过,n:检测不通过
     *
     *    @author    Garbin
     *    @return    void
     */
    function check()
    {
        //规则,结果,显示
        $check_env = self::_check_env(array(
            'php_version'   =>  array(
                'required'  => '>= 4.3',
                'checker'   => 'php_checker',
            ),
            'gd_version'   =>  array(
                'required'  => '>= 1.0',
                'checker'   => 'gd_checker',
            ),
        ));
        $check_file= self::_check_file(array(
            './configs',
            './_cache',
        ));
        $compatible = false;
        if ($check_env['compatible'] && $check_file['compatible'])
        {
            $compatible = true;
        }
		
        self::$_hiddens['accept']   = $_POST['accept'];

		$messages = array_merge($check_env['msg'], $check_file['msg']);		
		self::view ('check.tpl', compact ('check_env', 'check_file','messages','compatible'));
}
    /**
     *    检查环境
     *
     *    @author    Garbin
     *    @param     array $required
     *    @return    array
     */
    function _check_env($required)
    {
        $return  = array('detail' => array(), 'compatible' => true, 'msg' => array());
        foreach ($required as $key => $value)
        {
            $checker = $value['checker'];
            $result = $checker();
            $return['detail'][$key] = array(
                'required'  => $value['required'],
                'current'   => $result['current'],
                'result'    => $result['result'] ? 'pass' : 'failed',
            );
            if (!$result['result'])
            {
                $return['compatible'] = false;
                $return['msg'][] = get_lang($key . '_error');
            }
        }

        return $return;
    }

    /**
     *    检查文件是否可写
     *
     *    @author    Garbin
     *    @param     array $file_list
     *    @return    array
     */
    function _check_file($file_list)
    {
        $return = array('detail' => array(), 'compatible' => true, 'msg' => array());
        foreach ($file_list as $key => $value)
        {
            $result = check_file(ROOT_PATH . '/' . $value);
            $return['detail'][] = array(
                'file'  =>  $value,
                'result'=>  $result ? 'pass' : 'failed',
                'current'   =>  $result ? get_lang('writable') : get_lang('unwritable'),
            );
            if (!$result)
            {
                $return['compatible'] = false;
                $return['msg'][] = sprintf(get_lang('file_error'), $value);
            }
        }

        return $return;
    }


	
}

function get_lang($str=null){
	$array = include dirname(__FILE__).'/install/common.lang.php';
	
	return $str===null ? $array : $array[$str];
}
/**
 *    检查PHP版本
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function php_checker()
{
    return array(
        'current' => PHP_VERSION,
        'result'  => (PHP_VERSION >= 4.3),
    );
}

/**
 *    检查GD版本
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function gd_checker()
{
    $return = array('current' => null, 'result' => false);
    $gd_info = function_exists('gd_info') ? gd_info() : array();
    $return['current'] = empty($gd_info['GD Version']) ? get_lang('gd_missing') : $gd_info['GD Version'];
    $return['result']  = empty($gd_info['GD Version']) ? false : true;

    return $return;
}

/**
 *    显示进程
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function show_process($msg, $script = '')
{
    !headers_sent() && header('Content-type:text/html;charset=' . CHARSET);

    echo '<script type="text/javascript">parent.show_process(\'' . $msg . '\');' . $script . '</script>';
    flush();
    ob_flush();
}

/**
 *    显示进程结果
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function r($text, $result, $level = 0)
{
    $indent = '';
    for ($i = 0; $i < $level; $i++)
    {
        $indent .= '&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    $result_class = $result ? 'successed' : 'failed';
    $result_text = $result ? get_lang('successed') : get_lang('failed');
    $html = "<p><span class=\"{$result_class}\">{$result_text}</span>{$indent}{$text}</p>";

    return $html;
}

function save_config_file($data)
{
    $contents = file_get_contents(ROOT_PATH.'/install/config.sample.php');
    file_put_contents(ROOT_PATH . '/configs/config.php', str_replace('{%CONFIG_ARRAY%}', var_export($data, true), $contents));
}
function save_system_info($info)
{
    $file = ROOT_PATH . '/data/system.info.php';
    file_put_contents($file, "<?php\r\nreturn " . var_export($info, true) . "; \r\n?>");
}

function get_ecm_key()
{
    return md5(ROOT_PATH . time() . site_url() . rand());
}

function get_sql($file)
{
    $contents = file_get_contents($file);
    $contents = str_replace("\r\n", "\n", $contents);
    $contents = trim(str_replace("\r", "\n", $contents));
    $return_items = $items = array();
    $items = explode(";\n", $contents);
    foreach ($items as $item)
    {
        $return_item = '';
        $item = trim($item);
        $lines = explode("\n", $item);
        foreach ($lines as $line)
        {
            if (isset($line[0]) && $line[0] == '#')
            {
                continue;
            }
            if (isset($line[1]) && $line[0] .  $line[1] == '--')
            {
                continue;
            }

            $return_item .= $line;
        }
        if ($return_item)
        {
            $return_items[] = $return_item;
        }
    }

    return $return_items;
}

function create_table($sql) {
    $type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
    $type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
    return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql) .
    (mysql_get_server_info() > '4.1' ? " ENGINE={$type} DEFAULT CHARSET=" . str_replace('-', '', CHARSET) : " TYPE={$type}");
}

function replace_prefix($orig, $target, $sql)
{
    return str_replace('`' . $orig, '`' . $target, $sql);
}

/**
 *    检查文件或目录是否可写
 *
 *    @author    Garbin
 *    @param     string $file
 *    @return    bool
 */
function check_file($file)
{
    if (!file_exists($file))
    {
        //不存在，则不可写
        return false;
    }
    #TODO 在Windows的服务器上可能会存在问题，待发现
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
    {
        if (is_dir($file))
        {
            /* 如果是目录，则尝试创建文件并修改 */
            $trail = substr($file, -1);
            if ($trail == '/' || $trail == '\\')
            {
                $tmpfile = $file . '___test_dir_file.txt';
            }
            else
            {
                $tmpfile = $file . '/' . '___test_dir_file.txt';
            }
            /* 尝试创建文件 */
            if (false === @touch($tmpfile))
            {
                /* 不可写 */

                return false;
            }
            /* 创建文件成功 */
            /* 尝试修改该文件 */
            if (false === @touch($tmpfile))
            {
                return false;
            }

            /* 修改文件成功 */
            /* 删除文件 */
            @unlink($tmpfile);

            return true;
        }
        else
        {
            /* 如果是文件，则尝试修改文件 */
            if (false === @touch($file))
            {
                /* 修改不成功，不可写 */

                return false;
            }
            else
            {
                /* 修改成功，可写 */

                return true;
            }
        }
    }
    else
    {
        return is_writable($file);
    }
}

/**
 *    创建数据库
 *
 *    @author    Garbin
 *    @param     string $db_name
 *    @return    bool
 */
function create_db($db_name, $con)
{
    if (mysql_get_server_info($con) > '4.1')
    {
        $sql = "CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET " . str_replace('-', '', CHARSET);
    }
    else
    {
        $sql = "CREATE DATABASE IF NOT EXISTS `{$db_name}`";
    }

    return mysql_query($sql, $con);
}
/**
 *    版本数据
 *
 *    @author    Garbin
 *    @param     string $file
 *    @return    string
 */
function version_data($file)
{
    return ROOT_PATH . '/install/' . $file;
}
/**
 * 获得当前格林威治时间的时间戳
 *
 * @return  integer
 */
function gmtime()
{
    return (time() - date('Z'));
}

/**
 * 执行(execute)
 */
//install::init(array('template_path' => '@install\\templates'));
//install::stub () and install::main ();
 
installd::init(array('template_path' => '@install/templates' ,'extension_path' => '@includes',  'extension_enable' => 'myfunction'));
installd::stub () and installd::main ();


exit();
?>