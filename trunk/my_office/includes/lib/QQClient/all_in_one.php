<?
/***************************************
QQ Client

���ߣ�Hackfan
��Դ��http://blog.hackfan.net/
2005.8.18

QQ�ͻ��ˣ�ʹ����Ѷtqq.tencent.com:8000 HTTP�ӿ�

�ο����£�http://spaces.msn.com/members/mprogramer

ʹ�õ����ࣺ
Advanced HTTP Client
���ı��뼯�����

��ӿڣ�

	��ʼ���ࣺ
		$qq = new QQClient('106814','password');

	��½��
		$qq -> login();
		������
			void
		���أ�
			���������سɹ���
				��½�ɹ���QQ_LOGIN_SUCCESS
				��½ʧ�ܣ�QQ_LOGIN_FAILED
					ͬʱ��ȫ�ֱ���$QQ_ERROR_MSG��¼�˷��������صĴ���˵��
			����������ʧ�ܣ�QQ_RETURN_FAILED

	��ú����б�
		$qq -> getFriendsList();
		������
			void
		���أ�
			�ɹ���
				array
				(
					QQ����1,
					QQ����2
				)
			ʧ�ܣ�QQ_RETURN_FAILED

	��������б�:
		$qq -> getOnlineList();
		������
			void
		���أ�
			�ɹ���
				������ > 0
					array
					(
						array
						(
							"UN" => QQ����,
							"NK" => QQ�ǳ�,
							"ST" => QQ״̬,
							"FC" => QQͷ��
						),
					)

					����ST��
						10Ϊ����QQ_STATUS_ONLINE��20Ϊ����QQ_STATUS_OFFLINE��30ΪæµQQ_STATUS_BUSY
					����FC��
						FCΪQQͷ��ĵ�ID�����ͷ��IDΪ270����ô��ͷʹ�õ�ͼƬΪ91.bmp�����㷨ΪFC/3+1

				������ = 0
					QQ_LIST_NONE
			����
				!(���ߺ�����==���ߺ����ǳ���==���ߺ���״̬��==���ߺ���ͷ����)��QQ_LIST_ERROR
			ʧ�ܣ�QQ_RETURN_FAILED

	��ú�����Ϣ��
		$qq -> getInfo('106814');
		������
			string QQ����
		���أ�
			�ɹ���
				array
				(
					'AD' => ,		//��ϵ��ַ
					'AG' => ,		//����
					'BT' => ,		//Ѫ��
					'CO' => ,		//����
					'CT' => ,		//����
					'CY' => ,		//����
					'EM' => ,		//Email
					'FC' => ,		//ͷ��
					'HP' => ,		//��վ
					'JB' => ,		//ְҵ
					'MO' => ,		//�ƶ��绰
					'PC' => ,		//�ʱ�
					'PH' => ,		//��ϵ�绰
					'PR' => ,		//���
					'PV' => ,		//ʡ
					'RN' => ,		//��ʵ����
					'SC' => ,		//��ҵԺУ
					'SX' => ,		//�Ա�
					'UN' => ,		//QQ��
					'NK' => 		//�ǳ�
				)
			ʧ�ܣ�QQ_RETURN_FAILED

	��Ӻ��ѣ�
		$qq -> addFriend( '106814' );
		������
			string QQ����
		���أ�
			�ɹ���
				�Է������κ��˼�Ϊ���ѣ�QQ_ADDTOLIST_SUCCESS;
				��Ҫ��֤��QQ_ADDTOLIST_NEEDAUTH;
				�������κ��˼�Ϊ���ѣ�QQ_ADDTOLIST_REFUSE;
				δ֪�Ĵ��룺QQ_ADDTOLIST_UNKNOWN;
			ʧ�ܣ�QQ_RETURN_FAILED

	��֤��
		$qq -> replyAdd( '106814' , TYPE, MSG );
		������
			string QQ����
			enum(0,1,2) ����
				*0��ʾ��ͨ����֤����1��ʾ���ܾ���Ϊ�Է�Ϊ���ѡ���2��ʾ��Ϊ����Է���Ϊ���ѡ�
			string ����
		���أ�
			�ɹ���QQ_RETURN_SUCCESS
			ʧ�ܣ�QQ_RETURN_FAILED

	ɾ�����ѣ�
		$qq -> delFriend( '106814' );
		������
			string QQ����
		���أ�
			�ɹ���QQ_RETURN_SUCCESS
			ʧ�ܣ�QQ_RETURN_FAILED

	�ı�״̬��
		$qq -> changeStatus( QQ_STATUS );
		������
			enum(QQ_STATUS_ONLINE,QQ_STATUS_OFFLINE,QQ_STATUS_BUSY) ����
		���أ�
			�ɹ���QQ_RETURN_SUCCESS
			ʧ�ܣ�QQ_RETURN_FAILED

	�ǳ���
		$qq -> logout();
		������
			void
		���أ�
			�ɹ���QQ_RETURN_SUCCESS
			ʧ�ܣ�QQ_RETURN_FAILED

	������Ϣ��
		$qq -> getMsg();
		������
			void
		���أ�
				��Ϣ�� > 0
					array
					(
						array
						(
							"MT" => ��Ϣ����,
							"UN" => �����ߺ���,
							"MG" => ��Ϣ����
						),
					)

					����MT��
						9Ϊ�û���Ϣ��99Ϊϵͳ��Ϣ��2Ϊ������Ϣ��3Ϊͨ����֤��4Ϊ�ܾ����Ӻ���
					����MG��
						��MT=9ʱ��MGΪ�û����͵���Ϣ����
						��MT=99ʱ,
							MG=10(QQ_STATUS_ONLINE)��ʾ�Է�����
							MG=20(QQ_STATUS_OFFLINE)��ʾ�Է�����
							MG=30(QQ_STATUS_BUSY)��ʾ�Է�����æµ״̬
						��MT=2ʱ��MGΪ������֤����Ϣ
						��MT=3ʱ��MGΪ?
						��MT=4ʱ��MGΪ�ܾ�����

				������ = 0
					QQ_LIST_NONE
			����
				!(���ߺ�����==���ߺ����ǳ���==���ߺ���״̬��==���ߺ���ͷ����)��QQ_LIST_ERROR
			ʧ�ܣ�QQ_RETURN_FAILED

	������Ϣ��
		$qq -> sendMsg($uin,$msg);
		������
		���أ�
			�ɹ���QQ_RETURN_SUCCESS
			ʧ�ܣ�QQ_RETURN_FAILED

���ͣ�
	QQ_RETURN_SUCCESS��ʾ����������ִ�гɹ�����Ϣ
	QQ_RETURN_FAILED��ʾ������û����ȷ���ػ��߷���û����ȷִ��
		�����봦�ڵ���״̬����������û����ȷ���ص�ʱ�򣬽����ӡ����ϸ����Ϣ

���У��Ƽ���Consoleģʽ�����б����򣬲�����ʹ��WebServer���С�

***************************************/
error_reporting(E_ALL ^ E_NOTICE);

//�ɹ�2xx
	define( 'QQ_RETURN_SUCCESS',	200 );
	define( 'QQ_LOGIN_SUCCESS',	201 );
	define( 'QQ_LIST_NONE',		202 );
	define( 'QQ_ADDTOLIST_SUCCESS',	203 );
	define( 'QQ_REPLYADD_SUCCESS',	204 );
	define( 'QQ_GETMSG_NONE',	205 );

//����3xx
	define( 'QQ_ADDTOLIST_NEEDAUTH',300 );
	define( 'QQ_ADDTOLIST_REFUSE',	301 );
	define( 'QQ_ADDTOLIST_UNKNOWN',	302 );

//ʧ��4xx
	define( 'QQ_RETURN_FAILED',	400 );
	define( 'QQ_LIST_ERROR',	401 );
	define( 'QQ_GETMSG_ERROR',	402 );

//����״̬
	define( 'QQ_STATUS_ONLINE',	10);
	define( 'QQ_STATUS_OFFLINE',	20);
	define( 'QQ_STATUS_BUSY',	30);

//Ѫ��
	$QQ_DATA_BT = array
		(
			0 => '',
			1 => 'A��',
			2 => 'B��',
			3 => 'O��',
			4 => 'AB��',
			5 => '����'
		);

//����
	$QQ_DATA_CO = array
		(
			0 => '',
			1 => 'ˮƿ��',
			2 => '˫����',
			3 => 'ĵ����',
			4 => '��ţ��',
			5 => '˫����',
			6 => '��з��',
			7 => 'ʨ����',
			8 => '��Ů��',
			9 => '�����',
			10 => '��Ы��',
			11 => '������',
			12 => 'Ħ����'
		);

//��Ф
	$QQ_DATA_SH = array
		(
			0 => '',
			1 => '��',
			2 => 'ţ',
			3 => '��',
			4 => '��',
			5 => '��',
			6 => '��',
			7 => '��',
			8 => '��',
			9 => '��',
			10 => '��',
			11 => '��',
			12 => '��'
		);

//�Ա�
	$QQ_DATA_SX = array
		(
			0 => '��',
			1 => 'Ů'
		);

class QQClient
{
	var $uin;
	var $pwd;

	var $server	=	'219.133.51.11';
	var $port	=	8000;

	var $httpclient;
	var $chs	=	NULL;

	function QQClient($uin,$pwd)
	{
		$this->uin = $uin;
		$this->pwd = $pwd;
	}

	function encode($str)
	/*
		˵������KEY1=VAL1&KEY2=VAL2��ʽ��Ϊ����
	*/
	{
		$arr = explode('&' , $str);
		$return = array();
		foreach($arr as $k=>$v)
		{
			list($key,$val) = explode('=',$v);
			$return[$key] = $val;
			$this->chs = NULL;
		}
		return $return;
	}

	function utf8_to_gb2312($str)
	{
		$this->chs = new Chinese("UTF8","GB2312", $str );
		return $this->chs->ConvertIT();
	}

	function gb2312_to_utf8($str)
	{
		$this->chs = new Chinese("GB2312","UTF8", $str );
		return $this->chs->ConvertIT();
	}

	function query($str)
	{
		$this->httpclient = new http( HTTP_V11, true );
		$this->httpclient->host = '219.133.51.11';
		$this->httpcilent->port = '8000';

		$query = $this->encode($str);
		$status = $this->httpclient->post( '', $query, '' );
		if ( $status == HTTP_STATUS_OK ) {
			return $this->httpclient->get_response_body();
		}
		else
		{
			print_r($this->httpclient);
			return false;
		}
		$this->httpclient->disconnect();
		unset($this->httpclient);
	}

	function split_str($str)
	{
		$arr = explode("," , $str);
		if($arr[count($arr)-1] == NULL)
		{
			unset($arr[count($arr)-1]);
		}
		return $arr;
	}

	function login()
	{
		//��½
		//VER=1.1&CMD=Login&SEQ=&UIN=&PS=&M5=1&LC=9326B87B234E7235
		$str = "VER=1.1&CMD=Login&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&PS=".md5($this->pwd)."&M5=1&LC=9326B87B234E7235";
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//���سɹ�
			if($return['RS']==0)
			{
				//��½�ɹ�
				return QQ_LOGIN_SUCCESS;
			}
			else
			{
				//��½ʧ��
				$GLOBALS['QQ_ERROR_MSG'] = $this->utf8_to_gb2312($return['RA']);
				return QQ_LOGIN_FAILED;
			}
		}
		else
		{
			//����ʧ��
			return QQ_RETURN_FAILED;
			
		}
	}

	function getFriendsList()
	{
		//�õ������б�
		//VER=1.1&CMD=List&SEQ=&UIN=&TN=160&UN=0 
		$str = "VER=1.1&CMD=List&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&TN=160&UN=0";
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//���سɹ�
			return $this->split_str($return['UN']);
		}
		else
		{
			//����ʧ��
			return QQ_RETURN_FAILED;
			
		}
	}

	function getOnlineList()
	{
		//�õ����ߺ����б�
		//VER=1.1&CMD=Query_Stat&SEQ=&UIN=&TN=50&UN=0 
		$str = "VER=1.1&CMD=Query_Stat&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&TN=50&UN=0";
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//���سɹ�
			if($return['SN'] > 0)
			{
				//���ߺ�����>0
				$uns = $this->split_str($return['UN']);	//�����б�
				$nks = $this->split_str($return['NK']); //�ǳ��б�
				$sts = $this->split_str($return['ST']); //״̬�б�
				$fcs = $this->split_str($return['FC']); //ͷ���б�
				$error = 0;
				((count($uns)==count($nks))==(count($sts)==count($fcs)))==(count($nks)==count($sts)) ?
					$num = count($uns)
					:
					$error = 1;
				;
				if($error == 1) return QQ_LIST_ERROR;
				$arr = array();
				for($i=0;$i<$num;$i++)
				{
					$arr[] = array(
						"UN" => $uns[$i] ,
						"NK" => $this->utf8_to_gb2312($nks[$i]) ,
						"ST" => $sts[$i] ,
						"FC" => $fcs[$i]
					);
				}
				return ($arr);
			}
			else
			{
				//���ߺ�����<=0
				return QQ_LIST_NONE;
			}
			
		}
		else
		{
			//����ʧ��
			return QQ_RETURN_FAILED;
				
		}
	}

	function getInfo($uin)
	{
		//�õ�������Ϣ
		//ADΪ��ϵ��ַ��AGΪ���䣬EMΪMAIL��FCΪͷ��HPΪ��վ��JBΪְҵ��PCΪ�ʱ࣬PHΪ��ϵ�绰��PRΪ��飬PVΪʡ��RNΪ��ʵ���ƣ�SCΪ��ҵԺУ��SXΪ�Ա�UNΪQQ�ţ�NKΪQQ�ǳ�
		//����ע���о� by Hackfan
		//BTΪѪ�ͣ�COΪ������CTΪ���У�CYΪ���ң�MOΪ�ƶ��绰��SH��Ф
		//LVΪ��ѯ�ĺ���(1Ϊ�����ѯ��2Ϊ��ͨ��ѯ��3Ϊ��ϸ��ѯ)
		//CVδ֪��IDδ֪(���֤?)��MTδ֪��MVδ֪��
		//VER=1.1&CMD=GetInfo&SEQ=&UIN=&LV=3&UN=
		$str = "VER=1.1&CMD=GetInfo&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&LV=3&UN=".$uin;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//���سɹ�
			$arr = array
				(
					'AD' => $this->utf8_to_gb2312($return['AD']),		//��ϵ��ַ
					'AG' => $this->utf8_to_gb2312($return['AG']),		//����
					'BT' => $return['BT'],		//Ѫ��
					'CO' => $return['CO'],		//����
					'CT' => $this->utf8_to_gb2312($return['CT']),		//����
					'CY' => $this->utf8_to_gb2312($return['CY']),		//����
					'EM' => $this->utf8_to_gb2312($return['EM']),		//Email
					'FC' => $return['FC'],		//ͷ��
					'HP' => $this->utf8_to_gb2312($return['HP']),		//��վ
					'JB' => $this->utf8_to_gb2312($return['JB']),		//ְҵ
					'MO' => $return['MO'],		//�ƶ��绰
					'PC' => $this->utf8_to_gb2312($return['PC']),		//�ʱ�
					'PH' => $this->utf8_to_gb2312($return['PH']),		//��ϵ�绰
					'PR' => $this->utf8_to_gb2312($return['PR']),		//���
					'PV' => $this->utf8_to_gb2312($return['PV']),		//ʡ
					'RN' => $this->utf8_to_gb2312($return['RN']),		//��ʵ����
					'SC' => $this->utf8_to_gb2312($return['SC']),		//��ҵԺУ
					'SH' => $return['SH'],		//��Ф
					'SX' => $return['SX'],		//�Ա�
					'UN' => $return['UN'],		//QQ��
					'NK' => $this->utf8_to_gb2312($return['NK'])		//�ǳ�
				);
			return $arr;
		}
		else
		{
			//����ʧ��
			return QQ_RETURN_FAILED;
				
		}

	}

	function addFriend($uin)
	{
		//����º���
		//VER=1.1&CMD=AddToList&SEQ=&UIN=&UN=
		$str = "VER=1.1&CMD=AddToList&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&UN=".$uin;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//���سɹ�
			switch($return['CD'])
			{
				case 0 :
					//�Է������κ��˼�Ϊ����
					return QQ_ADDTOLIST_SUCCESS;
					break;
				case 1 :
					//��Ҫ��֤
					return QQ_ADDTOLIST_NEEDAUTH;
					break;
				case 3 :
					//�������κ��˼�Ϊ����
					return QQ_ADDTOLIST_REFUSE;
					break;
				default :
					//δ֪�Ĵ���
					return QQ_ADDTOLIST_UNKNOWN;
					break;
			}
		}
		else
		{
			//����ʧ��
			return QQ_RETURN_FAILED;
		}
	}

	function replyAdd($uin,$type,$msg)
	{
		//��Ӧ��Ӻ���
		//VER=1.1&CMD=Ack_AddToList&SEQ=&UIN=&UN=&CD=&RS=
		//CDΪ��Ӧ״̬��CDΪ0��ʾ��ͨ����֤����CDΪ1��ʾ���ܾ���Ϊ�Է�Ϊ���ѡ���CDΪ2��ʾ��Ϊ����Է���Ϊ���ѡ���RSΪ��Ҫ���������
		$str = "VER=1.2&CMD=Ack_AddToList&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&UN=".$uin."&CD=".$type."&RS=".$this->gb2312_to_utf8($msg);
		$return = $this->encode($this->query($str));
		
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//�������ɹ��õ���Ϣ
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//ʧ��
			return QQ_RETURN_FAILED;			
		}
	}

	function delFriend($uin)
	{
		//ɾ������
		//VER=1.1&CMD=DelFromList&SEQ=&UIN=&UN=
		$str = "VER=1.1&CMD=DelFromList&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&UN=$uin";
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//�������ɹ��õ���Ϣ
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//ʧ��
			return QQ_RETURN_FAILED;
		}
	}

	function changeStatus($status)
	{
		//�ı�״̬
		//VER=1.1&CMD=Change_Stat&SEQ=&UIN=&ST= 
		//STΪҪ�ı��״̬��10Ϊ���ߣ�20Ϊ���ߣ�30Ϊæµ��
		$str = "VER=1.1&CMD=Change_stat&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&ST=".$status;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//�������ɹ��õ���Ϣ
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//ʧ��
			return QQ_RETURN_FAILED;
		}
	}

	function logout()
	{
		//�˳���½
		//VER=1.1&CMD=Logout&SEQ=&UIN=
		$str = "VER=1.1&CMD=Logout&SEQ=".rand(1000,9000)."&UIN=".$this->uin;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//�������ɹ��õ���Ϣ
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//ʧ��
			return QQ_RETURN_FAILED;
		}
	}

	function getMsg()
	{
		//�����Ϣ
		//VER=1.1&CMD=GetMsgEx&SEQ=&UIN=
		//MT��ʾ��Ϣ���ͣ�99��ʾϵͳ��Ϣ��9��ʾ�û���Ϣ��UN��ʾ��Ϣ������Դ�û���MG��ʾ���͵���Ϣ��MG��Ϣ���Ա�ʾĳЩ�ض���ϵͳ����
		//��MT=99ʱ��MG=10��ʾ�û����ߣ�MG=20��ʾ�û����ߣ�MG=30��ʾ�û�æµ
		$str = "VER=1.1&CMD=GetMsgEx&SEQ=".rand(1000,9000)."&UIN=".$this->uin;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//�������ɹ��õ���Ϣ
			if($return['MN'] > 0)
			{
				//��Ϣ��>0
				$mts = $this->split_str($return['MT']);	//��Ϣ����
				$uns = $this->split_str($return['UN']); //�����ߺ���
				$mgs = $this->split_str($return['MG']); //��Ϣ����
				$error = 0;
				(count($mts)==count($uns))==(count($uns)==count($mgs))?
					$num = count($uns)
					:
					$error = 1;
				;
				if($error == 1) return QQ_GETMSG_ERROR;	//�������
				$arr = array();
				for($i=0;$i<$num;$i++)
				{
					$arr[] = array(
						"MT" => $mts[$i] ,
						"UN" => $uns[$i] ,
						"MG" => $this->utf8_to_gb2312($mgs[$i])
					);
				}
				return ($arr);
			}
			else
			{
				//���ߺ�����<=0
				return QQ_GETMSG_NONE;
			}
		}
		else
		{
			//ʧ��
			return QQ_RETURN_FAILED;
		}
	}

	function sendMsg($uin,$msg)
	{
		//������Ϣ
		//VER=1.1&CMD=CLTMSG&SEQ=&UIN=&UN=&MG= 
		$str = "VER=1.1&CMD=CLTMSG&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&UN=".$uin."&MG=".$this->gb2312_to_utf8($msg);
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//�������ɹ��õ���Ϣ
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//ʧ��
			return QQ_RETURN_FAILED;
		}
	}

}
?>
<?php

/**
 * ���ı��뼯�����
 *
 * Ŀǰ��������ʵ�֣��������� <-> �������ı��뻥�����������ġ��������� -> ƴ������ת����
 * �������ġ��������� <-> UTF8 ����ת�����������ġ��������� -> Unicode����ת��
 *
 * @����         Hessian(solarischan@21cn.com)
 * @�汾         1.5
 * @��Ȩ����     Hessian / NETiS
 * @ʹ����Ȩ     GPL������Ӧ�����κ���ҵ��;�����뾭������ͬ�⼴���޸Ĵ��룬���޸ĺ�Ĵ�����밴��GPLЭ�鷢����
 * @�ر���л     unknow������ת������Ƭ�ϣ�
 * @��ʼ         2003-04-01
 * @����޸�     2003-06-06
 * @����         ����
 *
 * ���¼�¼
 *
 * ver 1.5 2003-06-06
 * ���� UTF8 ת���� GB2312��BIG5�Ĺ��ܡ�
 *
 * ver 1.4 2003-04-07
 * ���� ��ת��HTMLʱ�趨Ϊtrue�����ɸı�charset��ֵ��
 *
 * ver 1.3 2003-04-02
 * ���� ��������ת����ƴ���Ĺ��ܡ�
 *
 * ver 1.2 2003-04-02
 * �ϲ� ���塢��������ת����UTF8�ĺ�����
 * �޸� ��������ת����ƴ���ĺ���������ֵ����Ϊ�ַ�����ÿһ�����ֵ�ƴ���ÿո�ֿ�
 * ���� ��������ת��Ϊ UNICODE �Ĺ��ܡ�
 * ���� ��������ת��Ϊ UNICODE �Ĺ��ܡ�
 *
 * ver 1.1 2003-04-02
 * ���� OpenFile() ������֧�ִ򿪱����ļ���Զ���ļ���
 * ���� ��������ת��Ϊ UTF8 �Ĺ��ܡ�
 * ���� ��������ת��Ϊ UTF8 �Ĺ��ܡ�
 *
 * ver 1.0 2003-04-01
 * һ�����������ļ��壬���ķ����Ӧ���ֱ��뻥��������Ѿ�������ɡ�
 */
class Chinese
{

	/**
	 * ��ż���������ƴ�����ձ�
	 *
	 * @��������  ����
	 * @��ʼ      1.0
	 * @����޸�  1.0
	 * @����      �ڲ�
	 */
	var $pinyin_table = array();

	
	/**
	 * ��� GB <-> UNICODE ���ձ������
	 * @��������  
	 * @��ʼ      1.1
	 * @����޸�  1.2
	 * @����      �ڲ�
	 */
	var $unicode_table = array();

	/**
	 * �������ķ��򻥻�����ļ�ָ��
	 *
	 * @��������  ����
	 * @��ʼ      1.0
	 * @����޸�  1.0
	 * @����      �ڲ�
	 */
	var $ctf;

	/**
	 * �ȴ�ת�����ַ���
	 * @��������
	 * @��ʼ      1.0
	 * @����޸�  1.0
	 * @����      �ڲ�
	 */
	var $SourceText = "";

	/**
	 * Chinese ����������
	 *
	 * @��������  ����
	 * @��ʼ      1.0
	 * @����޸�  1.2
	 * @����      ����
	 */
	var $config  =  array(
		'codetable_dir'         => "./config/",           //  ��Ÿ������Ի������Ŀ¼
		'SourceLang'            => '',                    //  �ַ���ԭ����
		'TargetLang'            => '',                    //  ת����ı���
		'GBtoBIG5_table'        => 'gb-big5.table',       //  ��������ת��Ϊ�������ĵĶ��ձ�
		'BIG5toGB_table'        => 'big5-gb.table',       //  ��������ת��Ϊ�������ĵĶ��ձ�
		'GBtoPinYin_table'      => 'gb-pinyin.table',     //  ��������ת��Ϊƴ���Ķ��ձ�
		'GBtoUnicode_table'     => 'gb-unicode.table',    //  ��������ת��ΪUNICODE�Ķ��ձ�
		'BIG5toUnicode_table'   => 'big5-unicode.table'   //  ��������ת��ΪUNICODE�Ķ��ձ�
	);

	/**
	 * Chinese ��Ϥ������
	 *
	 * ��ϸ˵��
	 * @�β�      �ַ��� $SourceLang Ϊ��Ҫת�����ַ�����ԭ����
	 *            �ַ��� $TargetLang Ϊת����Ŀ�����
	 *            �ַ��� $SourceText Ϊ�ȴ�ת�����ַ���
	 *
	 * @��ʼ      1.0
	 * @����޸�  1.2
	 * @����      ����
	 * @����ֵ    ��
	 * @throws
	 */
	function Chinese( $SourceLang , $TargetLang , $SourceString='')
	{
		if ($SourceLang != '') {
		    $this->config['SourceLang'] = $SourceLang;
		}

		if ($TargetLang != '') {
		    $this->config['TargetLang'] = $TargetLang;
		}

		if ($SourceString != '') {
		    $this->SourceText = $SourceString;
		}

		$this->OpenTable();
	} // ���� Chinese ��Ϥ������


	/**
	 * �� 16 ����ת��Ϊ 2 �����ַ�
	 *
	 * ��ϸ˵��
	 * @�β�      $hexdata Ϊ16���Ƶı���
	 * @��ʼ      1.5
	 * @����޸�  1.5
	 * @����      �ڲ�
	 * @����      �ַ���
	 * @throws    
	 */
	function _hex2bin( $hexdata )
	{
		$bindata = "";
		for ( $i=0; $i<strlen($hexdata); $i+=2 )
			$bindata.=chr(hexdec(substr($hexdata,$i,2)));

		return $bindata;
	}


	/**
	 * �򿪶��ձ�
	 *
	 * ��ϸ˵��
	 * @�β�      
	 * @��ʼ      1.3
	 * @����޸�  1.3
	 * @����      �ڲ�
	 * @����      ��
	 * @throws    
	 */
	function OpenTable()
	{
	    
		// ����ԭ����Ϊ�������ĵĻ�
		if ($this->config['SourceLang']=="GB2312") {

			// ����ת��Ŀ�����Ϊ�������ĵĻ�
			if ($this->config['TargetLang'] == "BIG5") {
				$this->ctf = fopen($this->config['codetable_dir'].$this->config['GBtoBIG5_table'], "r");
				if (is_null($this->ctf)) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
			}

			// ����ת��Ŀ�����Ϊƴ���Ļ�
			if ($this->config['TargetLang'] == "PinYin") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoPinYin_table']);
				if (!$tmp) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
				//
				$i = 0;
				for ($i=0; $i<count($tmp); $i++) {
					$tmp1 = explode("	", $tmp[$i]);
					$this->pinyin_table[$i]=array($tmp1[0],$tmp1[1]);
				}
			}

			// ����ת��Ŀ�����Ϊ UTF8 �Ļ�
			if ($this->config['TargetLang'] == "UTF8") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoUnicode_table']);
				if (!$tmp) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,7,6);
			}

			// ����ת��Ŀ�����Ϊ UNICODE �Ļ�
			if ($this->config['TargetLang'] == "UNICODE") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoUnicode_table']);
				if (!$tmp) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,9,4);
			}
		}

		// ����ԭ����Ϊ�������ĵĻ�
		if ($this->config['SourceLang']=="BIG5") {
			// ����ת��Ŀ�����Ϊ�������ĵĻ�
			if ($this->config['TargetLang'] == "GB2312") {
				$this->ctf = fopen($this->config['codetable_dir'].$this->config['BIG5toGB_table'], "r");
				if (is_null($this->ctf)) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
			}
			// ����ת��Ŀ�����Ϊ UTF8 �Ļ�
			if ($this->config['TargetLang'] == "UTF8") {
				$tmp = @file($this->config['codetable_dir'].$this->config['BIG5toUnicode_table']);
				if (!$tmp) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,7,6);
			}

			// ����ת��Ŀ�����Ϊ UNICODE �Ļ�
			if ($this->config['TargetLang'] == "UNICODE") {
				$tmp = @file($this->config['codetable_dir'].$this->config['BIG5toUnicode_table']);
				if (!$tmp) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,9,4);
			}

			// ����ת��Ŀ�����Ϊƴ���Ļ�
			if ($this->config['TargetLang'] == "PinYin") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoPinYin_table']);
				if (!$tmp) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
				//
				$i = 0;
				for ($i=0; $i<count($tmp); $i++) {
					$tmp1 = explode("	", $tmp[$i]);
					$this->pinyin_table[$i]=array($tmp1[0],$tmp1[1]);
				}
			}
		}

		// ����ԭ����Ϊ UTF8 �Ļ�
		if ($this->config['SourceLang']=="UTF8") {

			// ����ת��Ŀ�����Ϊ GB2312 �Ļ�
			if ($this->config['TargetLang'] == "GB2312") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoUnicode_table']);
				if (!$tmp) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,7,6))]=substr($value,0,6);
			}

			// ����ת��Ŀ�����Ϊ BIG5 �Ļ�
			if ($this->config['TargetLang'] == "BIG5") {
				$tmp = @file($this->config['codetable_dir'].$this->config['BIG5toUnicode_table']);
				if (!$tmp) {
					echo "��ת�����ļ�ʧ�ܣ�";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,7,6))]=substr($value,0,6);
			}
		}

	} // ���� OpenTable ����

	/**
	 * �򿪱��ػ���Զ�̵��ļ�
	 *
	 * ��ϸ˵��
	 * @�β�      �ַ��� $position Ϊ��Ҫ�򿪵��ļ����ƣ�֧�ִ�·����URL
	 *            ����ֵ $isHTML Ϊ��ת�����ļ��Ƿ�Ϊhtml�ļ�
	 * @��ʼ      1.1
	 * @����޸�  1.1
	 * @����      ����
	 * @����      ��
	 * @throws    
	 */
	function OpenFile( $position , $isHTML=false )
	{
	    $tempcontent = @file($position);

		if (!$tempcontent) {
		    echo "���ļ�ʧ�ܣ�";
			exit;
		}

		$this->SourceText = implode("",$tempcontent);

		if ($isHTML) {
			$this->SourceText = eregi_replace( "charset=".$this->config['SourceLang'] , "charset=".$this->config['TargetLang'] , $this->SourceText);

			$this->SourceText = eregi_replace("\n", "", $this->SourceText);

			$this->SourceText = eregi_replace("\r", "", $this->SourceText);
		}
	} // ���� OpenFile ����

	/**
	 * �򿪱��ػ���Զ�̵��ļ�
	 *
	 * ��ϸ˵��
	 * @�β�      �ַ��� $position Ϊ��Ҫ�򿪵��ļ����ƣ�֧�ִ�·����URL
	 * @��ʼ      1.1
	 * @����޸�  1.1
	 * @����      ����
	 * @����      ��
	 * @throws    
	 */
	function SiteOpen( $position )
	{
	    $tempcontent = @file($position);

		if (!$tempcontent) {
		    echo "���ļ�ʧ�ܣ�";
			exit;
		}

		// ���������������ת��Ϊ�ַ���
		$this->SourceText = implode("",$tempcontent);

		$this->SourceText = eregi_replace( "charset=".$this->config['SourceLang'] , "charset=".$this->config['TargetLang'] , $this->SourceText);


//		ereg(href="css/dir.css"
	} // ���� OpenFile ����

	/**
	 * ���ñ�����ֵ
	 *
	 * ��ϸ˵��
	 * @�β�
	 * @��ʼ      1.0
	 * @����޸�  1.0
	 * @����      ����
	 * @����ֵ    ��
	 * @throws
	 */
	function setvar( $parameter , $value )
	{
		if(!trim($parameter))
			return $parameter;

		$this->config[$parameter] = $value;

	} // ���� setvar ����

	/**
	 * �����塢�������ĵ� UNICODE ����ת��Ϊ UTF8 �ַ�
	 *
	 * ��ϸ˵��
	 * @�β�      ���� $c �������ĺ��ֵ�UNICODE�����10����
	 * @��ʼ      1.1
	 * @����޸�  1.2
	 * @����      �ڲ�
	 * @����      �ַ���
	 * @throws    
	 */
	function CHSUtoUTF8($c)
	{
		$str="";

		if ($c < 0x80) {
			$str.=$c;
		}

		else if ($c < 0x800) {
			$str.=(0xC0 | $c>>6);
			$str.=(0x80 | $c & 0x3F);
		}

		else if ($c < 0x10000) {
			$str.=(0xE0 | $c>>12);
			$str.=(0x80 | $c>>6 & 0x3F);
			$str.=(0x80 | $c & 0x3F);
		}

		else if ($c < 0x200000) {
			$str.=(0xF0 | $c>>18);
			$str.=(0x80 | $c>>12 & 0x3F);
			$str.=(0x80 | $c>>6 & 0x3F);
			$str.=(0x80 | $c & 0x3F);
		}

		return $str;
	} // ���� CHSUtoUTF8 ����
	
	/**
	 * ���塢�������� <-> UTF8 ����ת���ĺ���
	 *
	 * ��ϸ˵��
	 * @�β�      
	 * @��ʼ      1.1
	 * @����޸�  1.5
	 * @����      �ڲ�
	 * @����      �ַ���
	 * @throws    
	 */
	function CHStoUTF8(){

		if ($this->config["SourceLang"]=="BIG5" || $this->config["SourceLang"]=="GB2312") {
			$ret="";

			while($this->SourceText){

				if(ord(substr($this->SourceText,0,1))>127){

					if ($this->config["SourceLang"]=="BIG5") {
						$utf8=$this->CHSUtoUTF8(hexdec($this->unicode_table[hexdec(bin2hex(substr($this->SourceText,0,2)))]));
					}
					if ($this->config["SourceLang"]=="GB2312") {
						$utf8=$this->CHSUtoUTF8(hexdec($this->unicode_table[hexdec(bin2hex(substr($this->SourceText,0,2)))-0x8080]));
					}
					for($i=0;$i<strlen($utf8);$i+=3)
						$ret.=chr(substr($utf8,$i,3));

					$this->SourceText=substr($this->SourceText,2,strlen($this->SourceText));
				}
				
				else{
					$ret.=substr($this->SourceText,0,1);
					$this->SourceText=substr($this->SourceText,1,strlen($this->SourceText));
				}
			}
			$this->unicode_table = array();
			$this->SourceText = "";
			return $ret;
		}

		if ($this->config["SourceLang"]=="UTF8") {
			$out = "";
			$len = strlen($this->SourceText);
			$i = 0;
			while($i < $len) {
				$c = ord( substr( $this->SourceText, $i++, 1 ) );
				switch($c >> 4)
				{ 
					case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
						// 0xxxxxxx
						$out .= substr( $this->SourceText, $i-1, 1 );
					break;
					case 12: case 13:
						// 110x xxxx   10xx xxxx
						$char2 = ord( substr( $this->SourceText, $i++, 1 ) );
						$char3 = $this->unicode_table[(($c & 0x1F) << 6) | ($char2 & 0x3F)];

						if ($this->config["TargetLang"]=="GB2312")
							$out .= $this->_hex2bin( dechex(  $char3 + 0x8080 ) );

						if ($this->config["TargetLang"]=="BIG5")
							$out .= $this->_hex2bin( $char3 );
					break;
					case 14:
						// 1110 xxxx  10xx xxxx  10xx xxxx
						$char2 = ord( substr( $this->SourceText, $i++, 1 ) );
						$char3 = ord( substr( $this->SourceText, $i++, 1 ) );
						$char4 = $this->unicode_table[(($c & 0x0F) << 12) | (($char2 & 0x3F) << 6) | (($char3 & 0x3F) << 0)];

						if ($this->config["TargetLang"]=="GB2312")
							$out .= $this->_hex2bin( dechex ( $char4 + 0x8080 ) );

						if ($this->config["TargetLang"]=="BIG5")
							$out .= $this->_hex2bin( $char4 );
					break;
				}
			}

			// ���ؽ��
			return $out;
		}
	} // ���� CHStoUTF8 ����

	/**
	 * ���塢��������ת��Ϊ UNICODE����
	 *
	 * ��ϸ˵��
	 * @�β�      
	 * @��ʼ      1.2
	 * @����޸�  1.2
	 * @����      �ڲ�
	 * @����      �ַ���
	 * @throws    
	 */
	function CHStoUNICODE()
	{

		$utf="";

		while($this->SourceText)
		{
			if (ord(substr($this->SourceText,0,1))>127)
			{

				if ($this->config["SourceLang"]=="GB2312")
					$utf.="&#x".$this->unicode_table[hexdec(bin2hex(substr($this->SourceText,0,2)))-0x8080].";";

				if ($this->config["SourceLang"]=="BIG5")
					$utf.="&#x".$this->unicode_table[hexdec(bin2hex(substr($this->SourceText,0,2)))].";";

				$this->SourceText=substr($this->SourceText,2,strlen($this->SourceText));
			}
			else
			{
				$utf.=substr($this->SourceText,0,1);
				$this->SourceText=substr($this->SourceText,1,strlen($this->SourceText));
			}
		}
		return $utf;
	} // ���� CHStoUNICODE ����

	/**
	 * �������� <-> �������� ����ת���ĺ���
	 *
	 * ��ϸ˵��
	 * @��ʼ      1.0
	 * @����      �ڲ�
	 * @����ֵ    ���������utf8�ַ�
	 * @throws
	 */
	function GB2312toBIG5()
	{
		// ��ȡ�ȴ�ת�����ַ������ܳ���
		$max=strlen($this->SourceText)-1;

		for($i=0;$i<$max;$i++){

			$h=ord($this->SourceText[$i]);

			if($h>=160){

				$l=ord($this->SourceText[$i+1]);

				if($h==161 && $l==64){
					$gb="  ";
				}
				else{
					fseek($this->ctf,($h-160)*510+($l-1)*2);
					$gb=fread($this->ctf,2);
				}

				$this->SourceText[$i]=$gb[0];
				$this->SourceText[$i+1]=$gb[1];
				$i++;
			}
		}
		fclose($this->ctf);

		// ��ת����Ľ������ $result;
		$result = $this->SourceText;

		// ��� $thisSourceText
		$this->SourceText = "";

		// ����ת�����
		return $result;
	} // ���� GB2312toBIG5 ����

	/**
	 * �������õ��ı�����Ѱƴ��
	 *
	 * ��ϸ˵��
	 * @��ʼ      1.0
	 * @����޸�  1.0
	 * @����      �ڲ�
	 * @����ֵ    �ַ���
	 * @throws
	 */
	function PinYinSearch($num){

		if($num>0&&$num<160){
			return chr($num);
		}

		elseif($num<-20319||$num>-10247){
			return "";
		}

		else{

			for($i=count($this->pinyin_table)-1;$i>=0;$i--){
				if($this->pinyin_table[$i][1]<=$num)
					break;
			}

			return $this->pinyin_table[$i][0];
		}
	} // ���� PinYinSearch ����

	/**
	 * ���塢�������� -> ƴ�� ת��
	 *
	 * ��ϸ˵��
	 * @��ʼ      1.0
	 * @����޸�  1.3
	 * @����      �ڲ�
	 * @����ֵ    �ַ�����ÿ��ƴ���ÿո�ֿ�
	 * @throws
	 */
	function CHStoPinYin(){
		if ( $this->config['SourceLang']=="BIG5" ) {
			$this->ctf = fopen($this->config['codetable_dir'].$this->config['BIG5toGB_table'], "r");
			if (is_null($this->ctf)) {
				echo "��ת�����ļ�ʧ�ܣ�";
				exit;
			}

			$this->SourceText = $this->GB2312toBIG5();
			$this->config['TargetLang'] = "PinYin";
		}

		$ret = array();
		$ri = 0;
		for($i=0;$i<strlen($this->SourceText);$i++){

			$p=ord(substr($this->SourceText,$i,1));

			if($p>160){
				$q=ord(substr($this->SourceText,++$i,1));
				$p=$p*256+$q-65536;
			}

			$ret[$ri]=$this->PinYinSearch($p);
			$ri = $ri + 1;
		}

		// ��� $this->SourceText
		$this->SourceText = "";

		$this->pinyin_table = array();

		// ����ת����Ľ��
		return implode(" ", $ret);
	} // ���� CHStoPinYin ����

	/**
	 * ���ת�����
	 *
	 * ��ϸ˵��
	 * @�β�
	 * @��ʼ      1.0
	 * @����޸�  1.2
	 * @����      ����
	 * @����      �ַ���
	 * @throws
	 */
	function ConvertIT()
	{
		// �ж��Ƿ�Ϊ���ķ�����ת��
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5") && ($this->config['TargetLang']=="GB2312" || $this->config['TargetLang']=="BIG5") ) {
			return $this->GB2312toBIG5();
		}

		// �ж��Ƿ�Ϊ����������ƴ��ת��
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5") && $this->config['TargetLang']=="PinYin" ) {
			return $this->CHStoPinYin();
		}

		// �ж��Ƿ�Ϊ���塢����������UTF8ת��
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5" || $this->config['SourceLang']=="UTF8") && ($this->config['TargetLang']=="UTF8" || $this->config['TargetLang']=="GB2312" || $this->config['TargetLang']=="BIG5") ) {
			return $this->CHStoUTF8();
		}

		// �ж��Ƿ�Ϊ���塢����������UNICODEת��
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5") && $this->config['TargetLang']=="UNICODE" ) {
			return $this->CHStoUNICODE();
		}

	} // ���� ConvertIT ����

} // �������

/**
*/

?>
<?php
/**************************************************************************************************
* Class: Advanced HTTP Client
***************************************************************************************************
* Version       : 1.1
* Released      : 06-20-2002
* Last Modified : 06-10-2003
* Author        : GuinuX <guinux@cosmoplazza.com>
*
***************************************************************************************************
* Changes 
***************************************************************************************************
* 2003-06-10 : GuinuX 
*   - Fixed a bug with multiple gets and basic auth
*   - Added support for Basic proxy Authentification 
* 2003-05-25: By Michael Mauch <michael.mauch@gmx.de>
*   - Fixed two occurences of the former "status" member which is now deprecated
* 2002-09-23: GuinuX
*   - Fixed a bug to the post method with some HTTP servers
*   - Thanx to l0rd jenci <lord_jenci@bigfoot.com> for reporting this bug.
* 2002-09-07: Dirk Fokken <fokken@cross-consulting.com>
*   - Deleted trailing characters at the end of the file, right after the php closing tag, in order 
*     to fix a bug with binary requests.
* 2002-20-06: GuinuX, Major changes
*   - Turned to a more OOP style => added class http_header, http_response_header, 
*       http_request_message, http_response_message.
*       The members : status, body, response_headers, cookies, _request_headers of the http class 
*       are Deprecated.
* 2002-19-06: GuinuX, fixed some bugs in the http::_get_response() method
* 2002-18-06: By Mate Jovic <jovic@matoma.de>
*   - Added support for Basic Authentification 
*       usage: $http_client = new http( HTTP_V11, false, Array('user','pass') );
*
***************************************************************************************************
* Description:  
***************************************************************************************************
*   A HTTP client class
*   Supports : 
*           - GET, HEAD and POST methods 
*           - Http cookies 
*           - multipart/form-data AND application/x-www-form-urlencoded
*           - Chunked Transfer-Encoding 
*           - HTTP 1.0 and 1.1 protocols 
*           - Keep-Alive Connections 
*           - Proxy
*           - Basic WWW-Authentification and Proxy-Authentification 
*
***************************************************************************************************
* TODO :
***************************************************************************************************
*           - Read trailing headers for Chunked Transfer-Encoding 
***************************************************************************************************
* usage
***************************************************************************************************
* See example scripts.
*
***************************************************************************************************
* License
***************************************************************************************************
* GNU Lesser General Public License (LGPL)
* http://www.opensource.org/licenses/lgpl-license.html
*
* For any suggestions or bug report please contact me : guinux@cosmoplazza.com
***************************************************************************************************/
/***************************************************************************************************
HTTPЭ����--����Chunked����:

������ʱ����������HTTP��Ӧ���޷�ȷ����Ϣ��С�ģ���ʱ��Content-Length���޷���
��д�볤�ȣ�����Ҫʵʱ������Ϣ���ȣ���ʱ������һ�����Chunked���롣
�����ڽ���Chunked���봫��ʱ���ڻظ���Ϣ��ͷ����transfer-coding����ΪChunked��
��ʾ����Chunked���봫�����ݡ��������·�ʽ���룺
����Chunked-Body = *chunk
������������������"0" CRLF
������������������footer
������������������CRLF 
����chunk = chunk-size [ chunk-ext ] CRLF
������������chunk-data CRLF

����hex-no-zero = <HEX excluding "0">

����chunk-size = hex-no-zero *HEX
����chunk-ext = *( ";" chunk-ext-name [ "=" chunk-ext-value ] )
����chunk-ext-name = token
����chunk-ext-val = token | quoted-string
����chunk-data = chunk-size(OCTET)

����footer = *entity-header
��������ʹ�����ɸ�Chunk��ɣ���һ����������Ϊ0��chunk������ÿ��Chunk��������
��ɣ���һ�����Ǹ�Chunk�ĳ��Ⱥͳ��ȵ�λ��һ�㲻д�����ڶ����־���ָ�����ȵ���
�ݣ�ÿ��������CRLF�����������һ������Ϊ0��Chunk�е������ǳ�Ϊfooter�����ݣ�
��һЩû��д��ͷ�����ݡ�
�����������һ��Chunked�Ľ�����̣�RFC�ĵ����У�
����length := 0
����read chunk-size, chunk-ext (if any) and CRLF
����while (chunk-size > 0) {
����read chunk-data and CRLF
����append chunk-data to entity-body
����length := length + chunk-size
����read chunk-size and CRLF
����}
����read entity-header
����while (entity-header not empty) {
����append entity-header to existing header fields
����read entity-header
����}
����Content-Length := length
����Remove "chunked" from Transfer-Encoding
***************************************************************************************************/
    if ( !defined('HTTP_CRLF') ) define( 'HTTP_CRLF', chr(13) . chr(10));
    define( 'HTTP_V10', '1.0');
    define( 'HTTP_V11', '1.1');
    define( 'HTTP_STATUS_CONTINUE',                 100 );
    define( 'HTTP_STATUS_SWITCHING_PROTOCOLS',      101 );
    define( 'HTTP_STATUS_OK',                       200 );
    define( 'HTTP_STATUS_CREATED',                  201 );
    define( 'HTTP_STATUS_ACCEPTED',                 202 );
    define( 'HTTP_STATUS_NON_AUTHORITATIVE',        203 );
    define( 'HTTP_STATUS_NO_CONTENT',               204 );
    define( 'HTTP_STATUS_RESET_CONTENT',            205 );
    define( 'HTTP_STATUS_PARTIAL_CONTENT',          206 );
    define( 'HTTP_STATUS_MULTIPLE_CHOICES',         300 );
    define( 'HTTP_STATUS_MOVED_PERMANENTLY',        301 );
    define( 'HTTP_STATUS_FOUND',                    302 );
    define( 'HTTP_STATUS_SEE_OTHER',                303 );
    define( 'HTTP_STATUS_NOT_MODIFIED',             304 );
    define( 'HTTP_STATUS_USE_PROXY',                305 );
    define( 'HTTP_STATUS_TEMPORARY_REDIRECT',       307 );
    define( 'HTTP_STATUS_BAD_REQUEST',              400 );
    define( 'HTTP_STATUS_UNAUTHORIZED',             401 );
    define( 'HTTP_STATUS_FORBIDDEN',                403 );
    define( 'HTTP_STATUS_NOT_FOUND',                404 );
    define( 'HTTP_STATUS_METHOD_NOT_ALLOWED',       405 );
    define( 'HTTP_STATUS_NOT_ACCEPTABLE',           406 );
    define( 'HTTP_STATUS_PROXY_AUTH_REQUIRED',      407 );
    define( 'HTTP_STATUS_REQUEST_TIMEOUT',          408 );
    define( 'HTTP_STATUS_CONFLICT',                 409 );
    define( 'HTTP_STATUS_GONE',                     410 );
    define( 'HTTP_STATUS_REQUEST_TOO_LARGE',        413 );
    define( 'HTTP_STATUS_URI_TOO_LONG',             414 );
    define( 'HTTP_STATUS_SERVER_ERROR',             500 );
    define( 'HTTP_STATUS_NOT_IMPLEMENTED',          501 );
    define( 'HTTP_STATUS_BAD_GATEWAY',              502 );
    define( 'HTTP_STATUS_SERVICE_UNAVAILABLE',      503 );
    define( 'HTTP_STATUS_VERSION_NOT_SUPPORTED',    505 );


/******************************************************************************************
* class http_header
******************************************************************************************/
    class http_header {
        var $_headers;
        var $_debug;

        function http_header() {
            $this->_headers = Array();
            $this->_debug   = '';
        } // End Of function http_header()
        
        function get_header( $header_name ) {
            $header_name = $this->_format_header_name( $header_name );
            if (isset($this->_headers[$header_name]))
                return $this->_headers[$header_name];
            else
                return null;
        } // End of function get()
        
        function set_header( $header_name, $value ) {
            if ($value != '') {
                $header_name = $this->_format_header_name( $header_name );
                $this->_headers[$header_name] = $value;
            }
        } // End of function set()
        
        function reset() {
            if ( count( $this->_headers ) > 0 ) $this->_headers = array();
            $this->_debug   .= "\n--------------- RESETED ---------------\n";
        } // End of function clear()

        function serialize_headers() {
            $str = '';
            foreach ( $this->_headers as $name=>$value) {
                $str .= "$name: $value" . HTTP_CRLF;
            }
            return $str;
        } // End of function serialize_headers()
        
        function _format_header_name( $header_name ) {
            $formatted = str_replace( '-', ' ', strtolower( $header_name ) );
            $formatted = ucwords( $formatted );
            $formatted = str_replace( ' ', '-', $formatted );
            return $formatted;
        }
        
        function add_debug_info( $data ) {
            $this->_debug .= $data;
        }

        function get_debug_info() {
            return $this->_debug;
        }
    
    } // End Of Class http_header

/******************************************************************************************
* class http_response_header
******************************************************************************************/
    class http_response_header extends http_header {
        var $cookies_headers;
        
        function http_response_header() {
            $this->cookies_headers = array();
            http_header::http_header();
        } // End of function http_response_header()
        
        function deserialize_headers( $flat_headers ) {
            $flat_headers = preg_replace( "/^" . HTTP_CRLF . "/", '', $flat_headers );
            $tmp_headers = split( HTTP_CRLF, $flat_headers );
            if (preg_match("'HTTP/(\d\.\d)\s+(\d+).*'i", $tmp_headers[0], $matches )) {
                $this->set_header( 'Protocol-Version', $matches[1] );
                $this->set_header( 'Status', $matches[2] );
            } 
            array_shift( $tmp_headers );
            foreach( $tmp_headers as $index=>$value ) {
                $pos = strpos( $value, ':' );
                if ( $pos ) {
                    $key = substr( $value, 0, $pos );
                    $value = trim( substr( $value, $pos +1) );
                    if ( strtoupper($key) == 'SET-COOKIE' )
                        $this->cookies_headers[] = $value;
                    else
                        $this->set_header( $key, $value );
                }
            }
        } // End of function deserialize_headers()
        
        function reset() {
            if ( count( $this->cookies_headers ) > 0 ) $this->cookies_headers = array();
            http_header::reset();
        }

    } // End of class http_response_header


/******************************************************************************************
* class http_request_message
******************************************************************************************/
    class http_request_message extends http_header {
        var $body;
        
        function http_request_message() {
            $this->body = '';
            http_header::http_header();
        } // End of function http_message()
        
        function reset() {
            $this->body = '';
            http_header::reset();
        }
    }

/******************************************************************************************
* class http_response_message
******************************************************************************************/
    class http_response_message extends http_response_header {
        var $body;
        var $cookies;
        
        function http_response_message() {
            $this->cookies = new http_cookie();
            $this->body = '';
            http_response_header::http_response_header();
        } // End of function http_response_message()
        
        function get_status() {
            if ( $this->get_header( 'Status' ) != null )
                return (integer)$this->get_header( 'Status' );
            else
                return -1;
        }
        
        function get_protocol_version() {
            if ( $this->get_header( 'Protocol-Version' ) != null )
                return $this->get_header( 'Protocol-Version' );
            else
                return HTTP_V10;
        }
        
        function get_content_type() {
            $this->get_header( 'Content-Type' );
        }
        
        function get_body() {
            return $this->body;
        }
        
        function reset() {
            $this->body = '';
            http_response_header::reset();
        }

        function parse_cookies( $host ) {
            for ( $i = 0; $i < count( $this->cookies_headers ); $i++ )
                $this->cookies->parse( $this->cookies_headers[$i], $host );
        }
    }

/******************************************************************************************
* class http_cookie
******************************************************************************************/
    class http_cookie {
        var $cookies;

        function http_cookie() {
            $this->cookies  = array();
        } // End of function http_cookies()
        
        function _now() {
            return strtotime( gmdate( "l, d-F-Y H:i:s", time() ) );
        } // End of function _now()
        
        function _timestamp( $date ) {
            if ( $date == '' ) return $this->_now()+3600;
            $time = strtotime( $date );
            return ($time>0?$time:$this->_now()+3600);
        } // End of function _timestamp()

        function get( $current_domain, $current_path ) {
            $cookie_str = '';
            $now = $this->_now();
            $new_cookies = array();

            foreach( $this->cookies as $cookie_name => $cookie_data ) {
                if ($cookie_data['expires'] > $now) {
                    $new_cookies[$cookie_name] = $cookie_data;
                    $domain = preg_quote( $cookie_data['domain'] );
                    $path = preg_quote( $cookie_data['path']  );
                    if ( preg_match( "'.*$domain$'i", $current_domain ) && preg_match( "'^$path.*'i", $current_path ) )
                        $cookie_str .= $cookie_name . '=' . $cookie_data['value'] . '; ';
                }
            }
            $this->cookies = $new_cookies;
            return $cookie_str;
        } // End of function get()
        
        function set( $name, $value, $domain, $path, $expires ) {
            $this->cookies[$name] = array(  'value' => $value,
                                            'domain' => $domain,
                                            'path' => $path,
                                            'expires' => $this->_timestamp( $expires )
                                            );
        } // End of function set()
        
        function parse( $cookie_str, $host ) {
            $cookie_str = str_replace( '; ', ';', $cookie_str ) . ';';
            $data = split( ';', $cookie_str );
            $value_str = $data[0];

            $cookie_param = 'domain=';
            $start = strpos( $cookie_str, $cookie_param );
            if ( $start > 0 ) {
                $domain = substr( $cookie_str, $start + strlen( $cookie_param ) );
                $domain = substr( $domain, 0, strpos( $domain, ';' ) );
            } else
                $domain = $host;

            $cookie_param = 'expires=';
            $start = strpos( $cookie_str, $cookie_param );
            if ( $start > 0 ) {
                $expires = substr( $cookie_str, $start + strlen( $cookie_param ) );
                $expires = substr( $expires, 0, strpos( $expires, ';' ) );
            } else
                $expires = '';
            
            $cookie_param = 'path=';
            $start = strpos( $cookie_str, $cookie_param );
            if ( $start > 0 ) {
                $path = substr( $cookie_str, $start + strlen( $cookie_param ) );
                $path = substr( $path, 0, strpos( $path, ';' ) );
            } else
                $path = '/';
                            
            $sep_pos = strpos( $value_str, '=');
            
            if ($sep_pos){
                $name = substr( $value_str, 0, $sep_pos );
                $value = substr( $value_str, $sep_pos+1 );
                $this->set( $name, $value, $domain, $path, $expires );
            }
        } // End of function parse()
        
    } // End of class http_cookie   
    
/******************************************************************************************
* class http
******************************************************************************************/
    class http {
        var $_socket;
        var $host;
        var $port;
        var $http_version;
        var $user_agent;
        var $errstr;
        var $connected;
        var $uri;
        var $_proxy_host;
        var $_proxy_port;
        var $_proxy_login;
        var $_proxy_pwd;
        var $_use_proxy;
        var $_auth_login;
        var $_auth_pwd; 
        var $_response; 
        var $_request;
        var $_keep_alive;       

        function http( $http_version = HTTP_V10, $keep_alive = false, $auth = false ) {
            $this->http_version = $http_version;
            $this->connected    = false;
            $this->user_agent   = 'QQClient/1.1 (compatible; QQClient; Hackfan)';
            $this->host         = '';
            $this->port         = 8000;
            $this->errstr       = '';

            $this->_keep_alive  = $keep_alive;
            $this->_proxy_host  = '';
            $this->_proxy_port  = -1;
            $this->_proxy_login = '';
            $this->_proxy_pwd   = '';
            $this->_auth_login  = '';
            $this->_auth_pwd    = '';
            $this->_use_proxy   = false;
            $this->_response    = new http_response_message();
            $this->_request     = new http_request_message();
            
        // Basic Authentification added by Mate Jovic, 2002-18-06, jovic@matoma.de
            if( is_array($auth) && count($auth) == 2 ){
                $this->_auth_login  = $auth[0];
                $this->_auth_pwd    = $auth[1];
            }
        } // End of Constuctor

        function use_proxy( $host, $port, $proxy_login = null, $proxy_pwd = null ) {
        // Proxy auth not yet supported
            $this->http_version = HTTP_V10;
            $this->_keep_alive  = false;
            $this->_proxy_host  = $host;
            $this->_proxy_port  = $port;
            $this->_proxy_login = $proxy_login;
            $this->_proxy_pwd   = $proxy_pwd;
            $this->_use_proxy   = true;
        }

        function set_request_header( $name, $value ) {
            $this->_request->set_header( $name, $value );
        }

        function get_response_body() {
            return $this->_response->body;
        }
        
        function get_response() {
            return $this->_response;
        }
        
        function head( $uri ) {
            $this->uri = $uri;

            if ( ($this->_keep_alive && !$this->connected) || !$this->_keep_alive ) {
                if ( !$this->_connect() ) {
                    $this->errstr = 'Could not connect to ' . $this->host;
                    return -1;
                }
            }
            $http_cookie = $this->_response->cookies->get( $this->host, $this->_current_directory( $uri ) );
            
            if ($this->_use_proxy) {
                $this->_request->set_header( 'Host', $this->host . ':' . $this->port );
                $this->_request->set_header( 'Proxy-Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
                if ( $this->_proxy_login != '' ) $this->_request->set_header( 'Proxy-Authorization', "Basic " . base64_encode( $this->_proxy_login . ":" . $this->_proxy_pwd ) );
                $uri = 'http://' . $this->host . ':' . $this->port . $uri;
            } else {
                $this->_request->set_header( 'Host', $this->host );
                $this->_request->set_header( 'Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
            }

            if ( $this->_auth_login != '' ) $this->_request->set_header( 'Authorization', "Basic " . base64_encode( $this->_auth_login . ":" . $this->_auth_pwd ) );            
            $this->_request->set_header( 'User-Agent', $this->user_agent );
            $this->_request->set_header( 'Accept', '*/*' );
            $this->_request->set_header( 'Cookie', $http_cookie );
            
            $cmd =  "HEAD $uri HTTP/" . $this->http_version . HTTP_CRLF . 
                    $this->_request->serialize_headers() .
                    HTTP_CRLF;
            fwrite( $this->_socket, $cmd );
            
            $this->_request->add_debug_info( $cmd );
            $this->_get_response( false );

            if ($this->_socket && !$this->_keep_alive) $this->disconnect();
            if ( $this->_response->get_header( 'Connection' ) != null ) {
                if ( $this->_keep_alive && strtolower( $this->_response->get_header( 'Connection' ) ) == 'close' ) {
                    $this->_keep_alive = false;
                    $this->disconnect();
                }
            }
            
            if ( $this->_response->get_status() == HTTP_STATUS_USE_PROXY ) {
                $location = $this->_parse_location( $this->_response->get_header( 'Location' ) );
                $this->disconnect();
                $this->use_proxy( $location['host'], $location['port'] );
                $this->head( $this->uri );
            }
            
            return $this->_response->get_header( 'Status' );
        } // End of function head()

        
        function get( $uri, $follow_redirects = true, $referer = '' ) {
            $this->uri = $uri;
            
            if ( ($this->_keep_alive && !$this->connected) || !$this->_keep_alive ) {
                if ( !$this->_connect() ) {
                    $this->errstr = 'Could not connect to ' . $this->host;
                    return -1;
                }
            }
            
            if ($this->_use_proxy) {
                $this->_request->set_header( 'Host', $this->host . ':' . $this->port );
                $this->_request->set_header( 'Proxy-Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
                if ( $this->_proxy_login != '' ) $this->_request->set_header( 'Proxy-Authorization', "Basic " . base64_encode( $this->_proxy_login . ":" . $this->_proxy_pwd ) );
                $uri = 'http://' . $this->host . ':' . $this->port . $uri;
            } else {
                $this->_request->set_header( 'Host', $this->host );
                $this->_request->set_header( 'Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
                $this->_request->set_header( 'Pragma', 'no-cache' );
                $this->_request->set_header( 'Cache-Control', 'no-cache' );
            }
            
            if ( $this->_auth_login != '' ) $this->_request->set_header( 'Authorization', "Basic " . base64_encode( $this->_auth_login . ":" . $this->_auth_pwd ) );
            $http_cookie = $this->_response->cookies->get( $this->host, $this->_current_directory( $uri ) );
            $this->_request->set_header( 'User-Agent', $this->user_agent );
            $this->_request->set_header( 'Accept', '*/*' );
            $this->_request->set_header( 'Referer', $referer );
            $this->_request->set_header( 'Cookie', $http_cookie );
            
            $cmd =  "GET $uri HTTP/" . $this->http_version . HTTP_CRLF . 
                    $this->_request->serialize_headers() .
                    HTTP_CRLF;
            fwrite( $this->_socket, $cmd );

            $this->_request->add_debug_info( $cmd );
            $this->_get_response();

            if ($this->_socket && !$this->_keep_alive) $this->disconnect();
            if (  $this->_response->get_header( 'Connection' ) != null ) {
                if ( $this->_keep_alive && strtolower( $this->_response->get_header( 'Connection' ) ) == 'close' ) {
                    $this->_keep_alive = false;
                    $this->disconnect();
                }
            }
            if ( $follow_redirects && ($this->_response->get_status() == HTTP_STATUS_MOVED_PERMANENTLY || $this->_response->get_status() == HTTP_STATUS_FOUND || $this->_response->get_status() == HTTP_STATUS_SEE_OTHER ) ) {
                if ( $this->_response->get_header( 'Location' ) != null  ) {
                    $this->_redirect( $this->_response->get_header( 'Location' ) );
                }
            }
            
            if ( $this->_response->get_status() == HTTP_STATUS_USE_PROXY ) {
                $location = $this->_parse_location( $this->_response->get_header( 'Location' ) );
                $this->disconnect();
                $this->use_proxy( $location['host'], $location['port'] );
                $this->get( $this->uri, $referer );
            }

            return $this->_response->get_status();
        } // End of function get()



        function multipart_post( $uri, &$form_fields, $form_files = null, $follow_redirects = true, $referer = '' ) {
            $this->uri = $uri;
            
            if ( ($this->_keep_alive && !$this->connected) || !$this->_keep_alive ) {
                if ( !$this->_connect() ) {
                    $this->errstr = 'Could not connect to ' . $this->host;
                    return -1;
                }
            }
            $boundary = uniqid('------------------');
            $http_cookie = $this->_response->cookies->get( $this->host, $this->_current_directory( $uri ) );
            $body = $this->_merge_multipart_form_data( $boundary, $form_fields, $form_files );
            $this->_request->body =  $body . HTTP_CRLF;
            $content_length = strlen( $body ); 


            if ($this->_use_proxy) {
                $this->_request->set_header( 'Host', $this->host . ':' . $this->port );
                $this->_request->set_header( 'Proxy-Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
                if ( $this->_proxy_login != '' ) $this->_request->set_header( 'Proxy-Authorization', "Basic " . base64_encode( $this->_proxy_login . ":" . $this->_proxy_pwd ) );
                $uri = 'http://' . $this->host . ':' . $this->port . $uri;
            } else {
                $this->_request->set_header( 'Host', $this->host );
                $this->_request->set_header( 'Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
                $this->_request->set_header( 'Pragma', 'no-cache' );
                $this->_request->set_header( 'Cache-Control', 'no-cache' );
            }

            if ( $this->_auth_login != '' ) $this->_request->set_header( 'Authorization', "Basic " . base64_encode( $this->_auth_login . ":" . $this->_auth_pwd ) );
            $this->_request->set_header( 'Accept', '*/*' );
            $this->_request->set_header( 'Content-Type', 'multipart/form-data; boundary=' . $boundary );
            $this->_request->set_header( 'User-Agent', $this->user_agent );
            $this->_request->set_header( 'Content-Length', $content_length );
            $this->_request->set_header( 'Cookie', $http_cookie );
            $this->_request->set_header( 'Referer', $referer );
                            
            $req_header = "POST $uri HTTP/" . $this->http_version . HTTP_CRLF . 
                        $this->_request->serialize_headers() .
                        HTTP_CRLF;

            fwrite( $this->_socket, $req_header );
            usleep(10);
            fwrite( $this->_socket, $this->_request->body );
            
            $this->_request->add_debug_info( $req_header );
            $this->_get_response();
            
            if ($this->_socket && !$this->_keep_alive) $this->disconnect();
            if ( $this->_response->get_header( 'Connection' ) != null ) {
                if ( $this->_keep_alive && strtolower( $this->_response->get_header( 'Connection' ) ) == 'close' ) {
                    $this->_keep_alive = false;
                    $this->disconnect();
                }
            }
            
            if ( $follow_redirects && ($this->_response->get_status() == HTTP_STATUS_MOVED_PERMANENTLY || $this->_response->get_status() == HTTP_STATUS_FOUND || $this->_response->get_status() == HTTP_STATUS_SEE_OTHER ) ) {
                if ( $this->_response->get_header( 'Location') != null ) {
                    $this->_redirect( $this->_response->get_header( 'Location') );
                }
            }
            
            if ( $this->_response->get_status() == HTTP_STATUS_USE_PROXY ) {
                $location = $this->_parse_location( $this->_response->get_header( 'Location') );
                $this->disconnect();
                $this->use_proxy( $location['host'], $location['port'] );
                $this->multipart_post( $this->uri, $form_fields, $form_files, $referer );
            }

            return $this->_response->get_status();
        } // End of function multipart_post()



        function post( $uri, &$form_data, $follow_redirects = true, $referer = '' ) {
            $this->uri = $uri;

            if ( ($this->_keep_alive && !$this->connected) || !$this->_keep_alive ) {
                if ( !$this->_connect() ) {
                    $this->errstr = 'Could not connect to ' . $this->host;
                    return -1;
                }
            }
            $http_cookie = $this->_response->cookies->get( $this->host, $this->_current_directory( $uri ) );
            $body = substr( $this->_merge_form_data( $form_data ), 1 );
            $this->_request->body =  $body . HTTP_CRLF . HTTP_CRLF;
            $content_length = strlen( $body ); 

            if ($this->_use_proxy) {
                $this->_request->set_header( 'Host', $this->host . ':' . $this->port );
                $this->_request->set_header( 'Proxy-Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
                if ( $this->_proxy_login != '' ) $this->_request->set_header( 'Proxy-Authorization', "Basic " . base64_encode( $this->_proxy_login . ":" . $this->_proxy_pwd ) );
                $uri = 'http://' . $this->host . ':' . $this->port . $uri;
            } else {
                $this->_request->set_header( 'Host', $this->host );
                $this->_request->set_header( 'Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
                $this->_request->set_header( 'Pragma', 'no-cache' );
                $this->_request->set_header( 'Cache-Control', 'no-cache' );
            }
            
            if ( $this->_auth_login != '' ) $this->_request->set_header( 'Authorization', "Basic " . base64_encode( $this->_auth_login . ":" . $this->_auth_pwd ) );            
            $this->_request->set_header( 'Accept', '*/*' );
            $this->_request->set_header( 'Content-Type', 'application/x-www-form-urlencoded' );
            $this->_request->set_header( 'User-Agent', $this->user_agent );
            $this->_request->set_header( 'Content-Length', $content_length );
            $this->_request->set_header( 'Cookie', $http_cookie );
            $this->_request->set_header( 'Referer', $referer );
                            
            $req_header = "POST $uri HTTP/" . $this->http_version . HTTP_CRLF . 
                        $this->_request->serialize_headers() .
                        HTTP_CRLF;

            fwrite( $this->_socket, $req_header );
            usleep( 10 );
            fwrite( $this->_socket, $this->_request->body );
            
            $this->_request->add_debug_info( $req_header );
            $this->_get_response();

            if ($this->_socket && !$this->_keep_alive) $this->disconnect();
            if ( $this->_response->get_header( 'Connection' ) != null ) {
                if ( $this->_keep_alive && strtolower( $this->_response->get_header( 'Connection' ) ) == 'close' ) {
                    $this->_keep_alive = false;
                    $this->disconnect();
                }
            }
            
            if ( $follow_redirects && ($this->_response->get_status() == HTTP_STATUS_MOVED_PERMANENTLY || $this->_response->get_status() == HTTP_STATUS_FOUND || $this->_response->get_status() == HTTP_STATUS_SEE_OTHER ) ) {
                if ( $this->_response->get_header( 'Location' ) != null ) {
                    $this->_redirect( $this->_response->get_header( 'Location' ) );
                }
            }
            
            if ( $this->_response->get_status() == HTTP_STATUS_USE_PROXY ) {
                $location = $this->_parse_location( $this->_response->get_header( 'Location' ) );
                $this->disconnect();
                $this->use_proxy( $location['host'], $location['port'] );
                $this->post( $this->uri, $form_data, $referer );
            }

            return $this->_response->get_status();
        } // End of function post()
        


        function post_xml( $uri, $xml_data, $follow_redirects = true, $referer = '' ) {
            $this->uri = $uri;

            if ( ($this->_keep_alive && !$this->connected) || !$this->_keep_alive ) {
                if ( !$this->_connect() ) {
                    $this->errstr = 'Could not connect to ' . $this->host;
                    return -1;
                }
            }
            $http_cookie = $this->_response->cookies->get( $this->host, $this->_current_directory( $uri ) );
            $body = $xml_data;
            $this->_request->body =  $body . HTTP_CRLF . HTTP_CRLF;
            $content_length = strlen( $body ); 

            if ($this->_use_proxy) {
                $this->_request->set_header( 'Host', $this->host . ':' . $this->port );
                $this->_request->set_header( 'Proxy-Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
                if ( $this->_proxy_login != '' ) $this->_request->set_header( 'Proxy-Authorization', "Basic " . base64_encode( $this->_proxy_login . ":" . $this->_proxy_pwd ) );
                $uri = 'http://' . $this->host . ':' . $this->port . $uri;
            } else {
                $this->_request->set_header( 'Host', $this->host );
                $this->_request->set_header( 'Connection', ($this->_keep_alive?'Keep-Alive':'Close') );
                $this->_request->set_header( 'Pragma', 'no-cache' );
                $this->_request->set_header( 'Cache-Control', 'no-cache' );
            }
            
            if ( $this->_auth_login != '' ) $this->_request->set_header( 'Authorization', "Basic " . base64_encode( $this->_auth_login . ":" . $this->_auth_pwd ) );            
            $this->_request->set_header( 'Accept', '*/*' );
            $this->_request->set_header( 'Content-Type', 'text/xml; charset=utf-8' );
            $this->_request->set_header( 'User-Agent', $this->user_agent );
            $this->_request->set_header( 'Content-Length', $content_length );
            $this->_request->set_header( 'Cookie', $http_cookie );
            $this->_request->set_header( 'Referer', $referer );
                            
            $req_header = "POST $uri HTTP/" . $this->http_version . HTTP_CRLF . 
                        $this->_request->serialize_headers() .
                        HTTP_CRLF;

            fwrite( $this->_socket, $req_header );
            usleep( 10 );
            fwrite( $this->_socket, $this->_request->body );
            
            $this->_request->add_debug_info( $req_header );
            $this->_get_response();

            if ($this->_socket && !$this->_keep_alive) $this->disconnect();
            if ( $this->_response->get_header( 'Connection' ) != null ) {
                if ( $this->_keep_alive && strtolower( $this->_response->get_header( 'Connection' ) ) == 'close' ) {
                    $this->_keep_alive = false;
                    $this->disconnect();
                }
            }
            
            if ( $follow_redirects && ($this->_response->get_status() == HTTP_STATUS_MOVED_PERMANENTLY || $this->_response->get_status() == HTTP_STATUS_FOUND || $this->_response->get_status() == HTTP_STATUS_SEE_OTHER ) ) {
                if ( $this->_response->get_header( 'Location' ) != null ) {
                    $this->_redirect( $this->_response->get_header( 'Location' ) );
                }
            }
            
            if ( $this->_response->get_status() == HTTP_STATUS_USE_PROXY ) {
                $location = $this->_parse_location( $this->_response->get_header( 'Location' ) );
                $this->disconnect();
                $this->use_proxy( $location['host'], $location['port'] );
                $this->post( $this->uri, $form_data, $referer );
            }

            return $this->_response->get_status();
        } // End of function post_xml()
        
        
        function disconnect() {
            if ($this->_socket && $this->connected) {
                 fclose($this->_socket);
                $this->connected = false;
             }
        } // End of function disconnect()


        /********************************************************************************
         * Private functions 
         ********************************************************************************/
         
        function _connect( ) {
            if ( $this->host == '' ) user_error( 'Class HTTP->_connect() : host property not set !' , E_ERROR );
            if (!$this->_use_proxy)
                $this->_socket = fsockopen( $this->host, $this->port, $errno, $errstr, 10 );
            else
                $this->_socket = fsockopen( $this->_proxy_host, $this->_proxy_port, $errno, $errstr, 10 );
            $this->errstr  = $errstr;
            $this->connected = ($this->_socket == true);
            return $this->connected;
        } // End of function connect()


        function _merge_multipart_form_data( $boundary, &$form_fields, &$form_files ) {
            $boundary = '--' . $boundary;
            $multipart_body = '';
            foreach ( $form_fields as $name => $data) {
                $multipart_body .= $boundary . HTTP_CRLF;
                $multipart_body .= 'Content-Disposition: form-data; name="' . $name . '"' . HTTP_CRLF;
                $multipart_body .=  HTTP_CRLF;
                $multipart_body .= $data . HTTP_CRLF;
            }
            if ( isset($form_files) ) {
                foreach ( $form_files as $data) {
                    $multipart_body .= $boundary . HTTP_CRLF;
                    $multipart_body .= 'Content-Disposition: form-data; name="' . $data['name'] . '"; filename="' . $data['filename'] . '"' . HTTP_CRLF;
                    if ($data['content-type']!='') 
                        $multipart_body .= 'Content-Type: ' . $data['content-type'] . HTTP_CRLF;
                    else
                        $multipart_body .= 'Content-Type: application/octet-stream' . HTTP_CRLF;
                    $multipart_body .=  HTTP_CRLF;
                    $multipart_body .= $data['data'] . HTTP_CRLF;
                }           
            }
            $multipart_body .= $boundary . '--' . HTTP_CRLF;
            return $multipart_body;
        } // End of function _merge_multipart_form_data()
        

        function _merge_form_data( &$param_array,  $param_name = '' ) {
            $params = '';
            $format = ($param_name !=''?'&'.$param_name.'[%s]=%s':'&%s=%s');
            foreach ( $param_array as $key=>$value ) {
                if ( !is_array( $value ) )
                    $params .= sprintf( $format, $key, urlencode( $value ) );
                else
                    $params .= $this->_merge_form_data( $param_array[$key],  $key );
            }
            return $params;
        } // End of function _merge_form_data()

        function _current_directory( $uri ) {
            $tmp = split( '/', $uri );
            array_pop($tmp);
            $current_dir = implode( '/', $tmp ) . '/';
            return ($current_dir!=''?$current_dir:'/');
        } // End of function _current_directory()       
        
        
        function _get_response( $get_body = true ) {
            $this->_response->reset();
            $this->_request->reset();
            $header = '';
            $body = '';
            $continue   = true;
            
            while ($continue) {
                $header = '';

                // Read the Response Headers
                while ( (($line = fgets( $this->_socket, 4096 )) != HTTP_CRLF || $header == '') && !feof( $this->_socket ) ) { 
                    if ($line != HTTP_CRLF) $header .= $line; 
                }
                $this->_response->deserialize_headers( $header );
                $this->_response->parse_cookies( $this->host );
                
                $this->_response->add_debug_info( $header );
                $continue = ($this->_response->get_status() == HTTP_STATUS_CONTINUE);
                if ($continue) fwrite( $this->_socket, HTTP_CRLF );
            }

            if ( !$get_body ) return;

            // Read the Response Body
            if ( strtolower( $this->_response->get_header( 'Transfer-Encoding' ) ) != 'chunked' && !$this->_keep_alive ) {
                while ( !feof( $this->_socket ) ) { 
                    $body .= fread( $this->_socket, 4096 ); 
                }
            } else {
                if ( $this->_response->get_header( 'Content-Length' ) != null ) {
                    $content_length = (integer)$this->_response->get_header( 'Content-Length' );
                    $body = fread( $this->_socket, $content_length ); 
                } else {
                    if ( $this->_response->get_header( 'Transfer-Encoding' ) != null ) {
                        if ( strtolower( $this->_response->get_header( 'Transfer-Encoding' ) ) == 'chunked' ) {
                            $chunk_size = (integer)hexdec(fgets( $this->_socket, 4096 ) ); 
                            while($chunk_size > 0) {
                                $body .= fread( $this->_socket, $chunk_size ); 
                                fread( $this->_socket, strlen(HTTP_CRLF) ); 
                                $chunk_size = (integer)hexdec(fgets( $this->_socket, 4096 ) ); 
                            }
                            // TODO : Read trailing http headers
                        }
                    } 
                }
            }
            $this->_response->body = $body;
        } // End of function _get_response()


        function _parse_location( $redirect_uri ) {
            $parsed_url     = parse_url( $redirect_uri );
            $scheme         = (isset($parsed_url['scheme'])?$parsed_url['scheme']:'');
            $port           = (isset($parsed_url['port'])?$parsed_url['port']:$this->port);
            $host           = (isset($parsed_url['host'])?$parsed_url['host']:$this->host);
            $request_file   = (isset($parsed_url['path'])?$parsed_url['path']:'');
            $query_string   = (isset($parsed_url['query'])?$parsed_url['query']:'');
            if ( substr( $request_file, 0, 1 ) != '/' )
                $request_file = $this->_current_directory( $this->uri ) . $request_file;
            
            return array(   'scheme' => $scheme,
                            'port' => $port,
                            'host' => $host,
                            'request_file' => $request_file,
                            'query_string' => $query_string
            );

        } // End of function _parse_location()
        
        
        function _redirect( $uri ) {
            $location = $this->_parse_location( $uri );
            if ( $location['host'] != $this->host || $location['port'] != $this->port ) {
                $this->host = $location['host'];
                $this->port = $location['port'];
                if ( !$this->_use_proxy) $this->disconnect();
            }
            usleep( 100 );
            $this->get( $location['request_file'] . '?' . $location['query_string'] );
        } // End of function _redirect()

    } // End of class http
?>