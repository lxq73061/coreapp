<?
/*
QQ�һ�����
ʹ��Hackfan��QQ Client

���ߣ�Hackfan
��Դ��http://blog.hackfan.net/
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
	//��½
	echo "����".$k."���ڵ�½...";
	switch($qq[$k] -> login())
	{
		case QQ_LOGIN_SUCCESS:
			echo "����".$k."��½�ɹ�����������һ�״̬...";
			break;
		case QQ_RETURN_FAILED:
			echo "����".$k."�ڵ�½ʱ�����������ش��󣬽����ٹһ�";
			unset($qq[$k]);
			break;
		default:
			echo "����".$k."��½ʧ�ܣ�ԭ��".$QQ_ERROR_MSG."�������ٹһ�";
			unset($qq[$k]);
			break;
	}
	echo "\n";
}

while(1)
{
	sleep(30); //ÿ30��ˢ��һ�ιһ�����
	foreach($qqs as $k=>$v)
	{
		$qq[$k]->changeStatus(QQ_STATUS_ONLINE);
		echo "ˢ����".$k."\n";
	}
	echo "������ɣ���Ϣ30��\n\n";
}

?>