<?php
/*

QQ Client Sample
���ߣ�Hackfan
��Դ��http://blog.hackfan.net/
2005.8.19

��������QQ Client��һ�����ӣ���û��ʹ��ȫ�����ܡ�

����Console�������б����򣬲��ѳ���ʱ�ʵ��޸ĵĴ�һ��
*/
date_default_timezone_set("PRC");
set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE ^E_DEPRECATED);
require_once("qq.php");
require_once("Callback.php");

//��ʼ��
//$qq = new QQClient('593795966','19830812ll');
$qq = new QQClient('453196649','lxqzyy2008@');
//��½
echo "<pre>���ڵ�½...";
switch($qq -> login())
{
	case QQ_LOGIN_SUCCESS:
		echo "��½�ɹ�";
		break;
	case QQ_RETURN_FAILED:
		echo "���������ش���";
		exit;
		break;
	default:
		echo "��½ʧ�ܣ�ԭ��".$QQ_ERROR_MSG;
		exit;
		break;
}
echo "\r\n";


echo "���ڻ����ĺ����б�...\r\n";
echo "���� ".count($qq -> getFriendsList())." �����ѣ�\r\n\r\n";
echo "����˭�����ϣ�\r\n";
$list = $qq -> getOnlineList();

$onlinefriend = array();

switch($list)
{
	case QQ_RETURN_FAILED:
		echo "���������ش���";
		break;
	case QQ_LIST_NONE:
		echo "û�����ߺ���";
		break;
	case QQ_LIST_ERROR:
		echo "���ߺ����б�Ƿ�������";
		break;
	default:
		$online="���ߺ��ѣ�";
		$busy="æµ���ѣ�";
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

//echo "\r\n�鿴106814����Ϣ��\r\n";
//print_r($qq -> getInfo('106814'));

/*

$uin = "239845259";
echo "��$uin...\r\n";
switch($qq -> addFriend( $uin ))
{
	case QQ_ADDTOLIST_SUCCESS :
		echo "�Ѿ��� $uin ��Ϊ����";
		break;
	case QQ_ADDTOLIST_NEEDAUTH :
		echo "�Է���Ҫ��֤...������֤����...";
	//������һ��С���⡣��ʱ��ɹ�����ʱ��ʧ�ܡ���֪��ԭ�������ָ�㡣
		$qq -> replyAdd ($uin,'2','TEST');
		echo "�������";
		break;
	case QQ_ADDTOLIST_REFUSE :
		echo "�Է��ܾ�����Ϊ����";
		break;
	case QQ_RETURN_FAILED:
		echo "���������ش���";
		break;
}
*/
echo "\r\n���æµ״̬...\r\n\r\n";
flush();
$qq->changeStatus(QQ_STATUS_BUSY);
$close_msg='';
$status=true;
while($status)
{
//echo "������û����Ϣ��\r\n";
$msg = $qq -> getMsg();
switch($msg)
{
	case QQ_GETMSG_NONE:
		//echo "û����Ϣ\r\n";
		break;
	case QQ_RETURN_FAILED:
		//echo "���ش���\r\n";
		break;
	default:
		for($i=0;$i<count($msg);$i++)
		{
			$msg[$i]['MG'] = chop($msg[$i]['MG']);
			echo "���ԣ�".$msg[$i]['UN'];
			echo "\r\n���ͣ�".$msg[$i]['MT'];
			echo "\r\n���ݣ�".$msg[$i]['MG'];

			switch($msg[$i]['MT'])
			{
				case 9:
					//�û���Ϣ
					switch($msg[$i]['MG'])
					{
						case '����':case '����':$qq->changeStatus(QQ_STATUS_ONLINE);$reply = '�����ˡ�';break;
						case '�뿪':case '��æ��':$qq->changeStatus(QQ_STATUS_BUSY);$reply = '���뿪����';break;
						case '���':$reply = '��ã����ǻ����ˣ���ĺ�����'.$msg[$i]['UN'];break;
						case 'logout':$reply='����,��������������.88';break;
						case 'restart':$reply='����,һ�����.88';break;
						
						//case '����':
						//case 'help':$reply="";break;
						
						//default:$reply ='���ǻ����ˣ����յ������Ϣ�ˣ�����Ϊ��'."\r\n".$msg[$i]['MG'];break;
						default:
						$qqrobot = new qqrobot($msg[$i]['MG']);
						$reply=$qqrobot->msg;
						echo '<br>$replay:<pre>';
						var_dump($reply);
						echo '</pre>';
						
					}
					break;
				case 99:
					//ϵͳ��Ϣ
					switch($msg[$i]['MG'])
					{
						//case QQ_STATUS_ONLINE:$reply = '������';break;
						//case QQ_STATUS_OFFLINE:$reply = '�ټ�';break;
						//case QQ_STATUS_BUSY:$reply = 'æ�������Ҳ��������ˡ�';break;
						//default:$reply = '����ҷ���һ��ϵͳ��Ϣ��������'.$msg[$i]['MG'];break;
					}
					break;
				case 2:
					//���˼���
					$qq -> replyAdd ($msg[$i]['UN'],'0','');//ͨ����֤
					$reply = "��ã��ܸ����ܹ���ʶ�㣬�����ལ��QQ�����ˡ�";
					sleep(10);
					break;
			}
			if($reply != "" and $lastreply[$msg[$i]['UN']] != $reply and $lastuin !=$msg[$i]['UN']){
				echo "\r\n�ظ�...";
				switch($qq -> sendMsg($msg[$i]['UN'],$reply))
				{
					case QQ_RETURN_SUCCESS :
						echo "���ͳɹ�";
						break;
					case QQ_RETURN_FAILED :
						echo "����ʧ��";
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