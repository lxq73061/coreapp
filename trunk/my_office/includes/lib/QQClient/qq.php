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

require_once( 'http.inc.php' );
require_once( 'class.Chinese.php');


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

	var $server	=	'119.147.65.70';
	var $port	=	80;

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
		$this->httpclient->host = $this->server;//119.147.10.11
		$this->httpclient->port = $this->port;
		//echo '<pre>';print_r($this->httpclient);

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