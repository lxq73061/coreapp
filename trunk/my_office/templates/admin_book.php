<?php
include_once('clogin.inc.php');
include_once('admin_head.php');

$uid = $_SESSION["uid"];
$model_name ='帐本';

if ($_POST['action'] == 'edit'){


	$id = $_POST['id'];
	$amount = $_POST['amount'];
	
	if($ccy !='CNY'){
		//showmsg("{$model_name}目前仅支持RMB",'失败',"return",'返回');
		//exit();
	}
	
	if( !in_array($ccy,array('CNY','USD')) ){
		showmsg("{$model_name}目前仅支持CNY,USD",'失败',"return",'返回');
		exit();
	}
	if( !strtotime($update_date)){
		showmsg("{$model_name}日期格式不正确",'失败',"return",'返回');
		exit();
	}
	if($item<1 || $item>3){
		showmsg("{$model_name}请选择正确的类型",'失败',"return",'返回');
	}
	
	if($amount < 0){
		//showmsg("{$model_name}请输入正确的金额，不能为负数",'失败',"return",'返回');
	}
	
	if($ccy2==0){//支出
		$amount= - abs($amount);
		$otype ='OUT';
	}else{
		$amount=  abs($amount);
		$otype ='IN';
	}
	if($item_txt2){
		$item_txt = $item_txt2;
	}
	//更新NET
	update_statement_net($uid,0,$ccy);

	//得到上个帐目小计，用户, 货币
	$sql = "SELECT net FROM {$tablepre}book WHERE update_date<'$update_date' AND uid='$uid' AND ccy='$ccy' ORDER BY update_date DESC,id DESC";
	$net = $db->result_first($sql);
	//pecho($sql);
	//pecho($net);
//update `lxq_book` set date=update_date,time=update_date			
	if (empty($id)) {

		$edit = '添加';		
		
		$dbarr['uid'] = $_SESSION["uid"];
		$dbarr['update_date'] = $update_date;
		$dbarr['item'] = $item;
		$dbarr['item_txt'] = $item_txt;
		
		$dbarr['remark'] = $remark;
		$dbarr['ccy'] = $ccy;		
		$dbarr['amount'] = $amount;
		$dbarr['net'] = $net+$amount;
		$dbarr['otype'] = $otype;
		$dbarr['date'] = date('Y-m-d',strtotime($update_date));
		$dbarr['time'] = date('H:i:s',strtotime($update_date));
		
		
		
		$sql = make_insert_sql("{$tablepre}book",$dbarr);

		
	}else{
		$dbarr['update_date'] = $update_date;
		$dbarr['item'] = $item;
		$dbarr['item_txt'] = $item_txt;
		$dbarr['remark'] = $remark;
		$dbarr['ccy'] = $ccy;		
		$dbarr['amount'] = $amount;
		$dbarr['net'] = $net+$amount;
		$dbarr['otype'] = $otype;
		$dbarr['date'] = date('Y-m-d',strtotime($update_date));
		$dbarr['time'] = date('H:i:s',strtotime($update_date));
		
		$where['id'] = $id;
		$sql = make_update_sql("{$tablepre}book",$dbarr,$where);		
		$edit = '修改';	
	} 

	
	mysql_query($sql) or die(mysql_error()); 	

	if ( mysql_affected_rows() == 1 ) {
		if(mysql_insert_id()) $id = mysql_insert_id();
		 showmsg("{$model_name}{$edit}成功！",'成功',"../view_book.php",'返回');
		
	}else{
		 showmsg("{$model_name}{$edit}失败或没有修改！",'失败',"return",'返回');
	}
	
	exit();
}

$sql = "SELECT item_txt FROM {$tablepre}book WHERE  uid=$uid GROUP BY item_txt";
$item_txts = $db->fetch_all($sql);
if($item_txts){
	foreach($item_txts as $k=>$v){
		$item_txts[$k] = $v['item_txt'];
	}
}else{
	$item_txts=array();
}


if ($_GET['id']) {
	$id = intval($_GET['id']);
	if ($_GET['action'] == 'del'){//删除
		$edit = '删除';	
		$sql = "DELETE FROM {$tablepre}book WHERE id = $id LIMIT 1";
		mysql_query($sql);
		showmsg("{$model_name}{$edit}成功！",'成功',"../view_book.php",'返回');
		exit();
		
	}else{
			$id = intval($_GET['id']);
			$sql = "SELECT * FROM {$tablepre}book WHERE id=$id AND uid=$uid ";
			$book = $db->fetch_first($sql);
	}

}else{
	$book['update_date'] = date('Y-m-d H:i:s');
}


?>


	<form id="form1" name="form1" method="post" action="/admin/admin_book.php">
	    <table width="500" border="0" cellpadding="0" cellspacing="1">
	        <tr>
	            <th colspan="2" scope="col">账目编辑</th>
	            </tr>

	        <tr>
	            <td>日期</td>
	            <td>
	                <input name="update_date" type="text" id="update_date" size="12" value="<?=$book['update_date']?>" />
	                <a href="javascript:pubdate_cal[0].popup()"><img src="./js/class/calendar2/calendar.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date" /></a></td>
	            </tr>
	        <tr>
	            <td>账户</td>
	            <td><select name="item" id="item">
	                <option value="1" <?=set_select($book['item'],1)?>>银行</option>
	                <option value="2" <?=set_select($book['item'],2)?>>支付宝</option>
	                <option value="3" <?=set_select($book['item'],3)?>>现金</option>
	                </select></td>
	            </tr>
	        <tr>
	            <td>用途</td>
	            <td>选择
                <!--//在选择不选中添加字句、不需要在或输入添加-->
	                <select name="item_txt" id="item_txt">
                    <option value="" <?=set_select('',$book['item_txt'])?>>=不选=</option>
	                <?php foreach( $item_txts as $k=>$v ){?>
	                <option value="<?=$v?>" <?=set_select($v,$book['item_txt'])?>><?=$v?></option>
	               <?php }?>
                </select>
                或输入
                <input name="item_txt2" type="text" id="item_txt2" value="" size="8" /></td>
            </tr>
	        <tr>
	            <td>备注</td>
	            <td><input type="text" name="remark" id="remark" value="<?=$book['remark']?>" /></td>
	            </tr>
	        <tr>
	            <td>货币</td>
	            <td><select name="ccy" id="ccy">
	                <option value="CNY" <?=set_select($book['ccy'],'CNY')?>>CNY</option>
	                <option value="USD" <?=set_select($book['ccy'],'USD')?>>USD</option>
	                </select></td>
	            </tr>
	        <tr>
	            <td>金额</td>
	            <td><input name="amount" type="text" id="amount" size="5" value="<?=$book['amount']?>" /></td>
	            </tr>
	        <tr>
	            <td>方式</td>
	            <td><input id="ccy1" name="ccy1" value="0" type="radio" <?=set_radio($book['amount']<=0,true)?> >
                  <label for="ccy1"> 支出 </label>
                  <input id="ccy2" name="ccy2" value="1"  type="radio"  <?=set_radio($book['amount']<0,false)?> >
                  <label for="ccy2"> 收入 </label></td>
	            </tr>
	        <tr>
	            <td>&nbsp;</td>
	            <td>
              
                <?php if(!$book['id']){?>
                <input type="submit" name="button" id="button" value="添加" />
                <?php }else{?>
                <input type="submit" name="button" id="button" value="修改" />
                <?php }?>
	                <input name="action" type="hidden" id="action" value="edit" />
	                <input name="id" type="hidden" id="id" value="<?=$book['id']?>"  /></td>
	            </tr>
	        </table>
	    </form>
		

<script type="text/javascript" language="javascript" src="../js/class/calendar2/calendar.js"></script>
<script>
var pubdate_cal=[];
var i=0;

pubdate_cal[i] = new xar_base_calendar(document.getElementById("update_date"), "../"); 
pubdate_cal[i].year_scroll = true; 
pubdate_cal[i].time_comp = true;
</script>

</body>
</html>