<?php
/**
 * 文章回复模块
 *
 * @version 0.0.1
 * @author Steven.liao <lxq73061@qq.com>
 */

/**
 * 导入(import)
 */
class_exists('core') or require_once 'core.php';

/**
 * 定义(define)
 */
class doc_remark extends core {
    /**
     * 默认动作
     */
    final static public function index() {
        //front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
        return ;
    }
    /**
     * 回复列表
     */
    final static public function get_list($doc_id) {

		$class_arr=array();
		$doc_remarks = self::selects('*', null, array('doc_id'=>$doc_id),array('ORDER BY doc_remark_id ASC'),array('doc_remark_id','assoc|table=doc_remark'=>null));
		if($doc_remarks)
		foreach($doc_remarks as &$v) {
           // $v['addtime'] = date("Y-m-d H:i:s",$v['create_date'].''.$v['create_time']);
            $v['content'] = bbcode($v['content'] ); 
			//$v['content'] = nl2br($v['content']);
			$v['ip2'] = preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/isU','\\1.\\2.*.\\4',$v['ip'] ); 
			         
        }
        return $doc_remarks;
   
    }
  
    /**
     * 添加回复
     */
    final static public function append() {
		$online = front::online();
		if(!$online->user_id) die('Permission Denied!');//需要登录
		
        $time = time();
        while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

            $post = array(
                    'doc_id' => isset($_POST['doc_id']) ? $_POST['doc_id'] : '',
                    //'username' => isset($_POST['username']) ? $_POST['username'] : '',
                    'email' => isset($_POST['email']) ? $_POST['email'] : '',
                    'content' => isset($_POST['content']) ? $_POST['content'] : '',
                    'ip' => get_onlineip(),
                    'create_date' => date('Y-m-d',$time),
                    'create_time' => date('H:i:s',$time),
            );
      

            //$post['content'] = htmlentities($content , ENT_COMPAT ,'utf-8') ;
			//pecho($post);
            if (! empty ($error)) {
                break;
            }
            $doc_remark = new self;
			$doc_remark->doc_remark_id=null;
            $doc_remark->struct ($post);
            $doc_remark->insert ('','doc_remark_id');
			if($doc_remark->doc_remark_id ){
				 $doc = new doc;
				 $doc->doc_id = $doc_remark->doc_id;
				 $doc->last_remark =  date('Y-m-d H:i:s',$time);
				 $doc->update();
			}
			//print_r ( $doc_remark);
			header ('Location: ?'.$_GET['query']);
            return;
        }
    }

    /**
     * 删除回复
     */
    final static public function remove() {
		$online = front::online();
		//if(!parent::init('in_manager') || $online->grade!=1) die('Permission Denied!');
        // 获取数据
        $doc_remark = new self;
        $doc_remark->doc_remark_id = isset($_GET['doc_remark_id']) ? $_GET['doc_remark_id'] : null;
        if(! is_numeric($doc_remark->doc_remark_id) || ! $doc_remark->select()) {
            $error = '该回复不存在';
          
            return;
        }

        // 删除数据
        $doc_remark->delete ();
       header ('Location: ?'.$_GET['query']);
    }
	    //得到回复
    final static public  function  get_comments($doc_id) {
		$comments = self::selects(null, null, array('doc_id'=>$doc_id),array('ORDER BY doc_remark_id DESC'),array(' doc_remark_id','assoc|table=doc_remark'=>null));
		if(!$comments)$comments=array();
        foreach($comments as &$v) {
            $v['addtime'] = date("Y-m-d H:i:s",$v['create_date'].''.$v['create_time']);
            $v['content'] = bbcode($v['content'] );           
        }
        return $comments;
    }
}
?>