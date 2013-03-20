<?
/*
QQ挂机程序
使用Hackfan的QQ Client

作者：Hackfan
来源：http://blog.hackfan.net/
2005.8.19
*/
require_once("qq.php");
$qqs = array
	(
		"350891991"=>"052310110129"
	);

foreach($qqs as $k=>$v)
{
	$qq[$k] = new QQClient($k,$v);
	//登陆
	echo "号码".$k."正在登陆...";
	switch($qq[$k] -> login())
	{
		case QQ_LOGIN_SUCCESS:
			echo "号码".$k."登陆成功，即将进入挂机状态...";
			break;
		case QQ_RETURN_FAILED:
			echo "号码".$k."在登陆时，服务器返回错误，将不再挂机";
			unset($qq[$k]);
			break;
		default:
			echo "号码".$k."登陆失败，原因：".$QQ_ERROR_MSG."，将不再挂机";
			unset($qq[$k]);
			break;
	}
	echo "\n";
}

while(1)
{
	sleep(30); //每30秒刷新一次挂机号码
	foreach($qqs as $k=>$v)
	{
		$qq[$k]->changeStatus(QQ_STATUS_ONLINE);
		echo "刷新了".$k."\n";
	}
	echo "工作完成，休息30秒\n\n";
}

?>