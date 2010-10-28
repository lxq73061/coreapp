<?php
return array (
  'autoload_enable' => true,
  'autoload_path' => '@modules',
  'framework_function' => 'front::main',
  'framework_enable' => true,
  'framework_module' => '[go]!(self)|welcome',
  'framework_action' => '[do]|index',
  'template_path' => '@templates\\',
  'connect_username'=>'root',
  'connect_password'=>'admin',
  'connect_dbname'=>'my_office',
  'connect_charset' => 'UTF8',
  'extension_path' => '@includes',
  'extension_enable' => 'myfunction',
//  'debug_enable'=>true,
//  'sql_format' => true,
 // 'debug_file' => 'debug.sql',
  
 'front_action' => '', //前端模块的动作参数，默认同framework_action         
 'front_online' => 'online', //在线用户的全局变量名，默认不使用                   
 'front_class' => 'user', //待验证模块的类名，默认是使用核心类                  
 'front_table' => 'user', //待验证模块的表名，默认是加上前缀的类名(类名为空除外)
 'front_fuzzy' => '', //用户名密码验证的模糊提示                            
 'front_username' => '', //单一的用户名，默认不使用                         
 'front_password' => '', //单一的密码                                       
 'front_redirect' => 'index.php', //表单无跳转参数时登录后的默认跳转地址          
 
  
);
?>