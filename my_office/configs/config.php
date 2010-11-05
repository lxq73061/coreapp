<?php

//---修改本文件请务必小心!并做好相应备份---
/*
配置说明:

http://www.coremvc.cn/api/core/config.php
*/

return array (
  'autoload_enable' => true,
  'autoload_path' => '@modules',
  'framework_function' => 'front::main',
  'framework_enable' => true,
  'framework_module' => '[go]!(self)|welcome',
  'framework_action' => '[do]|index',
  'template_path' => '@templates\\',
  'connect_server' => 'localhost',
  'connect_username' => 'root',
  'connect_password' => '123456',
  'connect_dbname' => 'myoff',
  'connect_port' => '3306',
  'connect_charset' => 'UTF8',
  'prefix_search' => 'mdb_',
  'prefix_replace' => 'mdb_',
  'extension_path' => '@includes',
  'extension_enable' => 'myfunction',
  'debug_enable' => false,
  'sql_format' => false,
  'debug_file' => '',
  'front_action' => '',
  'front_online' => 'online',
  'front_class' => 'user',
  'front_table' => 'mdb_user',
  'front_fuzzy' => '',
  'front_username' => '',
  'front_password' => '',
  'front_redirect' => 'index.php',
);

?>
