<?php
/*

QQ Client Sample
作者：Hackfan
来源：http://blog.hackfan.net/
2005.8.19

本程序是QQ Client的一个例子，并没有使用全部功能。

请在Console下面运行本程序，并把程序超时适当修改的大一点
*/
date_default_timezone_set("PRC");
set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE ^E_DEPRECATED);
require_once("qq.php");
require_once("Callback.php");

//初始化
//$qq = new QQClient('593795966','19830812ll');
$qq = new QQClient('453196649','lxqzyy2008@');
//登陆
echo "<pre>正在登陆...";
switch($qq -> login())
{
	case QQ_LOGIN_SUCCESS:
		echo "登陆成功";
		break;
	case QQ_RETURN_FAILED:
		echo "服务器返回错误";
		exit;
		break;
	default:
		echo "登陆失败，原因：".$QQ_ERROR_MSG;
		exit;
		break;
}
echo "\r\n";


echo "正在获得你的好友列表...\r\n";
echo "你有 ".count($qq -> getFriendsList())." 个好友！\r\n\r\n";
echo "看看谁在线上：\r\n";
$list = $qq -> getOnlineList();

$onlinefriend = array();

switch($list)
{
	case QQ_RETURN_FAILED:
		echo "服务器返回错误";
		break;
	case QQ_LIST_NONE:
		echo "没有在线好友";
		break;
	case QQ_LIST_ERROR:
		echo "在线好友列表非法！！！";
		break;
	default:
		$online="在线好友：";
		$busy="忙碌好友：";
		for($i=0;$i<count($list);$i++)
		{
			switch($list[$i]['ST'])
			{
				case QQ_STATUS_ONLINE:
					$online .= $list[$i]['NK']."(".$list[$i]['UN'].")  ";
					$onlinefriend[] = $list[$i]['UN'];
					break;
				case QQ_STATUS_BUSY:
					$busy .= $list[$i]['NK']."(".$list[$i]['UN'].")  ";
					break;
				default:
			}
		}
		echo $online."\r\n".$busy."\r\n";
		break;
}

//echo "\r\n查看106814的信息：\r\n";
//print_r($qq -> getInfo('106814'));

/*

$uin = "239845259";
echo "加$uin...\r\n";
switch($qq -> addFriend( $uin ))
{
	case QQ_ADDTOLIST_SUCCESS :
		echo "已经把 $uin 加为好友";
		break;
	case QQ_ADDTOLIST_NEEDAUTH :
		echo "对方需要验证...发送验证请求...";
	//这里有一点小问题。有时候成功，有时候失败。不知道原因，请高手指点。
		$qq -> replyAdd ($uin,'2','TEST');
		echo "发送完毕";
		break;
	case QQ_ADDTOLIST_REFUSE :
		echo "对方拒绝被加为好友";
		break;
	case QQ_RETURN_FAILED:
		echo "服务器返回错误";
		break;
}
*/
echo "\r\n变成忙碌状态...\r\n\r\n";
flush();
$qq->changeStatus(QQ_STATUS_BUSY);
$close_msg='';
$status=true;
while($status)
{
//echo "看看有没有消息：\r\n";
$msg = $qq -> getMsg();
switch($msg)
{
	case QQ_GETMSG_NONE:
		//echo "没有消息\r\n";
		break;
	case QQ_RETURN_FAILED:
		//echo "返回错误\r\n";
		break;
	default:
		for($i=0;$i<count($msg);$i++)
		{
			$msg[$i]['MG'] = chop($msg[$i]['MG']);
			echo "来自：".$msg[$i]['UN'];
			echo "\r\n类型：".$msg[$i]['MT'];
			echo "\r\n内容：".$msg[$i]['MG'];

			switch($msg[$i]['MT'])
			{
				case 9:
					//用户信息
					switch($msg[$i]['MG'])
					{
						case '上线':case '上来':$qq->changeStatus(QQ_STATUS_ONLINE);$reply = '我上了。';break;
						case '离开':case '你忙吧':$qq->changeStatus(QQ_STATUS_BUSY);$reply = '我离开咯。';break;
						case '你好':$reply = '你好，我是机器人，你的号码是'.$msg[$i]['UN'];break;
						case 'logout':$reply='好啦,今天就聊天这里吧.88';break;
						case 'restart':$reply='好啦,一会回来.88';break;
						
						//case '帮助':
						//case 'help':$reply="";break;
						
						//default:$reply ='我是机器人，我收到你的信息了，内容为：'."\r\n".$msg[$i]['MG'];break;
						default:
						$qqrobot = new qqrobot($msg[$i]['MG']);
						$reply=$qqrobot->msg;
						echo '<br>$replay:<pre>';
						var_dump($reply);
						echo '</pre>';
						
					}
					break;
				case 99:
					//系统信息
					switch($msg[$i]['MG'])
					{
						//case QQ_STATUS_ONLINE:$reply = '你来啦';break;
						//case QQ_STATUS_OFFLINE:$reply = '再见';break;
						//case QQ_STATUS_BUSY:$reply = '忙啊，那我不打扰你了。';break;
						//default:$reply = '你给我发了一条系统信息，内容是'.$msg[$i]['MG'];break;
					}
					break;
				case 2:
					//有人加我
					$qq -> replyAdd ($msg[$i]['UN'],'0','');//通过验证
					$reply = "你好，很高兴能够认识你，我是青剑的QQ机器人。";
					sleep(10);
					break;
			}
			if($reply != "" and $lastreply[$msg[$i]['UN']] != $reply and $lastuin !=$msg[$i]['UN']){
				echo "\r\n回复...";
				switch($qq -> sendMsg($msg[$i]['UN'],$reply))
				{
					case QQ_RETURN_SUCCESS :
						echo "发送成功";
						break;
					case QQ_RETURN_FAILED :
						echo "发送失败";
						break;
				}
				$lastreply[$msg[$i]['UN']] = $reply;
				$lastuin = $msg['$i']['UN'];
				if($msg[$i]['MG']=='logout'){
					$qq->logout();
					$status=false;
				}elseif($msg[$i]['MG']=='restart'){
					$qq->logout();
					$status=false;
					$close_msg='<meta http-equiv="refresh" content="1;URL=?" />';
					//header("Location:?");
					
				}
				
			}
			$reply = "";
			echo "\r\n";
		}
}
flush();
sleep (2);
}
echo $close_msg;

?>