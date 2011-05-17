<?php
/**
 * 主从模块
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
class sync extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	final static public function sql_insert() {
	}
	final static public function sql_update() {
	}
	final static public function sql_delete() {
	}


	/**
	* 本地同步到服务器
	*/
	final static public function start_sync(){
		$sync_id=self::down_syncid();//日期时分秒;取服务器最后更新日期
		$local_sync_id=self::get_syncid();//日期时分秒;取上次同步日期
	
		$syncs = self::_get_sync();//取本地需要更新的内容
		$status = self::upload_sync($syncs);//上传本地更新内容到服务器并返回处理结果
	
		$syncs= self::down_sync(local_sync_id);//到服务器取上次同步后更新的内容
		self::_update_sync($syncs);//更新服务器数据到本地
	
	}
	final static public function down_syncid(){
		
	}
	final static public function get_syncid(){
		
	}
	
	final static public function upload_sync($syncs){
		
		//发送内容到远程服务器
		//远程服务器返回处理结果
		return ;
	}
	final static public function get_sync($sync_id=null){
		//读取本地insert to id...
		//读取本地update id...(如果此ID是在本地的 insert to id范围,则附加到insert to语句中)
		//读取本地delete id...
		return;
	}
	//更新sync内容
	final static public function _update_sync($syncs){
	//分析内容
	//执行SQL在
	}
	final static public function down_sync($sync_id){
	  //读取远程的同步数据
	  
	
	}
	
	/**
	* 服务器处理本地递交数据
	*/
	final static public function sync_update(){
	$updateids;//更新数组(table,id,content...)
	$deleteids;//删除数组(table,id...)
	$insertids;//添加数组(table,id,content...)id将忽略
	//返回sync_status=1;
	//更新条数,删除条数,插入条数(插入前台ID对比)
	
	}
	}
?>