<?
/***************************************
QQ Client

作者：Hackfan
来源：http://blog.hackfan.net/
2005.8.18

QQ客户端，使用腾讯tqq.tencent.com:8000 HTTP接口

参考文章：http://spaces.msn.com/members/mprogramer

使用到的类：
Advanced HTTP Client
中文编码集合类库

类接口：

	初始化类：
		$qq = new QQClient('106814','password');

	登陆：
		$qq -> login();
		参数：
			void
		返回：
			服务器返回成功：
				登陆成功：QQ_LOGIN_SUCCESS
				登陆失败：QQ_LOGIN_FAILED
					同时，全局变量$QQ_ERROR_MSG记录了服务器返回的错误说明
			服务器返回失败：QQ_RETURN_FAILED

	获得好友列表：
		$qq -> getFriendsList();
		参数：
			void
		返回：
			成功：
				array
				(
					QQ号码1,
					QQ号码2
				)
			失败：QQ_RETURN_FAILED

	获得在线列表:
		$qq -> getOnlineList();
		参数：
			void
		返回：
			成功：
				好友数 > 0
					array
					(
						array
						(
							"UN" => QQ号码,
							"NK" => QQ昵称,
							"ST" => QQ状态,
							"FC" => QQ头像
						),
					)

					关于ST：
						10为上线QQ_STATUS_ONLINE，20为离线QQ_STATUS_OFFLINE，30为忙碌QQ_STATUS_BUSY
					关于FC：
						FC为QQ头像的的ID，如的头像ID为270，那么其头使用的图片为91.bmp，其算法为FC/3+1

				好友数 = 0
					QQ_LIST_NONE
			错误：
				!(在线好友数==在线好友昵称数==在线好友状态数==在线好友头像数)：QQ_LIST_ERROR
			失败：QQ_RETURN_FAILED

	获得号码信息：
		$qq -> getInfo('106814');
		参数：
			string QQ号码
		返回：
			成功：
				array
				(
					'AD' => ,		//联系地址
					'AG' => ,		//年龄
					'BT' => ,		//血型
					'CO' => ,		//星座
					'CT' => ,		//城市
					'CY' => ,		//国家
					'EM' => ,		//Email
					'FC' => ,		//头像
					'HP' => ,		//网站
					'JB' => ,		//职业
					'MO' => ,		//移动电话
					'PC' => ,		//邮编
					'PH' => ,		//联系电话
					'PR' => ,		//简介
					'PV' => ,		//省
					'RN' => ,		//真实姓名
					'SC' => ,		//毕业院校
					'SX' => ,		//性别
					'UN' => ,		//QQ号
					'NK' => 		//昵称
				)
			失败：QQ_RETURN_FAILED

	添加好友：
		$qq -> addFriend( '106814' );
		参数：
			string QQ号码
		返回：
			成功：
				对方允许任何人加为好友：QQ_ADDTOLIST_SUCCESS;
				需要验证：QQ_ADDTOLIST_NEEDAUTH;
				不允许任何人加为好友：QQ_ADDTOLIST_REFUSE;
				未知的代码：QQ_ADDTOLIST_UNKNOWN;
			失败：QQ_RETURN_FAILED

	验证：
		$qq -> replyAdd( '106814' , TYPE, MSG );
		参数：
			string QQ号码
			enum(0,1,2) 类型
				*0表示“通过验证”，1表示“拒决加为对方为好友”，2表示“为请求对方加为好友”
			string 理由
		返回：
			成功：QQ_RETURN_SUCCESS
			失败：QQ_RETURN_FAILED

	删除好友：
		$qq -> delFriend( '106814' );
		参数：
			string QQ号码
		返回：
			成功：QQ_RETURN_SUCCESS
			失败：QQ_RETURN_FAILED

	改变状态：
		$qq -> changeStatus( QQ_STATUS );
		参数：
			enum(QQ_STATUS_ONLINE,QQ_STATUS_OFFLINE,QQ_STATUS_BUSY) 类型
		返回：
			成功：QQ_RETURN_SUCCESS
			失败：QQ_RETURN_FAILED

	登出：
		$qq -> logout();
		参数：
			void
		返回：
			成功：QQ_RETURN_SUCCESS
			失败：QQ_RETURN_FAILED

	接收信息：
		$qq -> getMsg();
		参数：
			void
		返回：
				消息数 > 0
					array
					(
						array
						(
							"MT" => 消息类型,
							"UN" => 发送者号码,
							"MG" => 消息内容
						),
					)

					关于MT：
						9为用户消息，99为系统消息，2为请求信息，3为通过验证，4为拒绝被加好友
					关于MG：
						当MT=9时，MG为用户发送的消息内容
						当MT=99时,
							MG=10(QQ_STATUS_ONLINE)表示对方上线
							MG=20(QQ_STATUS_OFFLINE)表示对方下线
							MG=30(QQ_STATUS_BUSY)表示对方进入忙碌状态
						当MT=2时，MG为请求验证的信息
						当MT=3时，MG为?
						当MT=4时，MG为拒绝理由

				好友数 = 0
					QQ_LIST_NONE
			错误：
				!(在线好友数==在线好友昵称数==在线好友状态数==在线好友头像数)：QQ_LIST_ERROR
			失败：QQ_RETURN_FAILED

	发送信息：
		$qq -> sendMsg($uin,$msg);
		参数：
		返回：
			成功：QQ_RETURN_SUCCESS
			失败：QQ_RETURN_FAILED

解释：
	QQ_RETURN_SUCCESS表示服务器返回执行成功的信息
	QQ_RETURN_FAILED表示服务器没有正确返回或者返回没有正确执行
		本代码处于调试状态，当服务器没有正确返回的时候，将会打印出详细的信息

运行：推荐在Console模式下运行本程序，不建议使用WebServer运行。

***************************************/
error_reporting(E_ALL ^ E_NOTICE);

require_once( 'http.inc.php' );
require_once( 'class.Chinese.php');


//成功2xx
	define( 'QQ_RETURN_SUCCESS',	200 );
	define( 'QQ_LOGIN_SUCCESS',	201 );
	define( 'QQ_LIST_NONE',		202 );
	define( 'QQ_ADDTOLIST_SUCCESS',	203 );
	define( 'QQ_REPLYADD_SUCCESS',	204 );
	define( 'QQ_GETMSG_NONE',	205 );

//警告3xx
	define( 'QQ_ADDTOLIST_NEEDAUTH',300 );
	define( 'QQ_ADDTOLIST_REFUSE',	301 );
	define( 'QQ_ADDTOLIST_UNKNOWN',	302 );

//失败4xx
	define( 'QQ_RETURN_FAILED',	400 );
	define( 'QQ_LIST_ERROR',	401 );
	define( 'QQ_GETMSG_ERROR',	402 );

//在线状态
	define( 'QQ_STATUS_ONLINE',	10);
	define( 'QQ_STATUS_OFFLINE',	20);
	define( 'QQ_STATUS_BUSY',	30);

//血型
	$QQ_DATA_BT = array
		(
			0 => '',
			1 => 'A型',
			2 => 'B型',
			3 => 'O型',
			4 => 'AB型',
			5 => '其他'
		);

//星座
	$QQ_DATA_CO = array
		(
			0 => '',
			1 => '水瓶座',
			2 => '双鱼座',
			3 => '牡羊座',
			4 => '金牛座',
			5 => '双子座',
			6 => '巨蟹座',
			7 => '狮子座',
			8 => '处女座',
			9 => '天秤座',
			10 => '天蝎座',
			11 => '射手座',
			12 => '摩羯座'
		);

//生肖
	$QQ_DATA_SH = array
		(
			0 => '',
			1 => '鼠',
			2 => '牛',
			3 => '虎',
			4 => '兔',
			5 => '龙',
			6 => '蛇',
			7 => '马',
			8 => '羊',
			9 => '猴',
			10 => '鸡',
			11 => '狗',
			12 => '猪'
		);

//性别
	$QQ_DATA_SX = array
		(
			0 => '男',
			1 => '女'
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
		说明：把KEY1=VAL1&KEY2=VAL2格式变为数组
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
		//登陆
		//VER=1.1&CMD=Login&SEQ=&UIN=&PS=&M5=1&LC=9326B87B234E7235
		$str = "VER=1.1&CMD=Login&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&PS=".md5($this->pwd)."&M5=1&LC=9326B87B234E7235";
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//返回成功
			if($return['RS']==0)
			{
				//登陆成功
				return QQ_LOGIN_SUCCESS;
			}
			else
			{
				//登陆失败
				$GLOBALS['QQ_ERROR_MSG'] = $this->utf8_to_gb2312($return['RA']);
				return QQ_LOGIN_FAILED;
			}
		}
		else
		{
			//返回失败
			return QQ_RETURN_FAILED;
			
		}
	}

	function getFriendsList()
	{
		//得到好友列表
		//VER=1.1&CMD=List&SEQ=&UIN=&TN=160&UN=0 
		$str = "VER=1.1&CMD=List&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&TN=160&UN=0";
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//返回成功
			return $this->split_str($return['UN']);
		}
		else
		{
			//返回失败
			return QQ_RETURN_FAILED;
			
		}
	}

	function getOnlineList()
	{
		//得到在线好友列表
		//VER=1.1&CMD=Query_Stat&SEQ=&UIN=&TN=50&UN=0 
		$str = "VER=1.1&CMD=Query_Stat&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&TN=50&UN=0";
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//返回成功
			if($return['SN'] > 0)
			{
				//在线好友数>0
				$uns = $this->split_str($return['UN']);	//号码列表
				$nks = $this->split_str($return['NK']); //昵称列表
				$sts = $this->split_str($return['ST']); //状态列表
				$fcs = $this->split_str($return['FC']); //头像列表
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
				//在线好友数<=0
				return QQ_LIST_NONE;
			}
			
		}
		else
		{
			//返回失败
			return QQ_RETURN_FAILED;
				
		}
	}

	function getInfo($uin)
	{
		//得到好友信息
		//AD为联系地址，AG为年龄，EM为MAIL，FC为头像，HP为网站，JB为职业，PC为邮编，PH为联系电话，PR为简介，PV为省，RN为真实名称，SC为毕业院校，SX为性别，UN为QQ号，NK为QQ昵称
		//以下注释研究 by Hackfan
		//BT为血型，CO为星座，CT为城市，CY为国家，MO为移动电话，SH生肖
		//LV为查询的号码(1为精简查询，2为普通查询，3为详细查询)
		//CV未知，ID未知(身份证?)，MT未知，MV未知，
		//VER=1.1&CMD=GetInfo&SEQ=&UIN=&LV=3&UN=
		$str = "VER=1.1&CMD=GetInfo&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&LV=3&UN=".$uin;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//返回成功
			$arr = array
				(
					'AD' => $this->utf8_to_gb2312($return['AD']),		//联系地址
					'AG' => $this->utf8_to_gb2312($return['AG']),		//年龄
					'BT' => $return['BT'],		//血型
					'CO' => $return['CO'],		//星座
					'CT' => $this->utf8_to_gb2312($return['CT']),		//城市
					'CY' => $this->utf8_to_gb2312($return['CY']),		//国家
					'EM' => $this->utf8_to_gb2312($return['EM']),		//Email
					'FC' => $return['FC'],		//头像
					'HP' => $this->utf8_to_gb2312($return['HP']),		//网站
					'JB' => $this->utf8_to_gb2312($return['JB']),		//职业
					'MO' => $return['MO'],		//移动电话
					'PC' => $this->utf8_to_gb2312($return['PC']),		//邮编
					'PH' => $this->utf8_to_gb2312($return['PH']),		//联系电话
					'PR' => $this->utf8_to_gb2312($return['PR']),		//简介
					'PV' => $this->utf8_to_gb2312($return['PV']),		//省
					'RN' => $this->utf8_to_gb2312($return['RN']),		//真实姓名
					'SC' => $this->utf8_to_gb2312($return['SC']),		//毕业院校
					'SH' => $return['SH'],		//生肖
					'SX' => $return['SX'],		//性别
					'UN' => $return['UN'],		//QQ号
					'NK' => $this->utf8_to_gb2312($return['NK'])		//昵称
				);
			return $arr;
		}
		else
		{
			//返回失败
			return QQ_RETURN_FAILED;
				
		}

	}

	function addFriend($uin)
	{
		//添加新好友
		//VER=1.1&CMD=AddToList&SEQ=&UIN=&UN=
		$str = "VER=1.1&CMD=AddToList&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&UN=".$uin;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//返回成功
			switch($return['CD'])
			{
				case 0 :
					//对方允许任何人加为好友
					return QQ_ADDTOLIST_SUCCESS;
					break;
				case 1 :
					//需要验证
					return QQ_ADDTOLIST_NEEDAUTH;
					break;
				case 3 :
					//不允许任何人加为好友
					return QQ_ADDTOLIST_REFUSE;
					break;
				default :
					//未知的代码
					return QQ_ADDTOLIST_UNKNOWN;
					break;
			}
		}
		else
		{
			//返回失败
			return QQ_RETURN_FAILED;
		}
	}

	function replyAdd($uin,$type,$msg)
	{
		//回应添加好友
		//VER=1.1&CMD=Ack_AddToList&SEQ=&UIN=&UN=&CD=&RS=
		//CD为响应状态，CD为0表示“通过验证”。CD为1表示“拒决加为对方为好友”。CD为2表示“为请求对方加为好友”。RS为你要请求的理由
		$str = "VER=1.2&CMD=Ack_AddToList&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&UN=".$uin."&CD=".$type."&RS=".$this->gb2312_to_utf8($msg);
		$return = $this->encode($this->query($str));
		
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//服务器成功得到信息
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//失败
			return QQ_RETURN_FAILED;			
		}
	}

	function delFriend($uin)
	{
		//删除好友
		//VER=1.1&CMD=DelFromList&SEQ=&UIN=&UN=
		$str = "VER=1.1&CMD=DelFromList&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&UN=$uin";
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//服务器成功得到信息
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//失败
			return QQ_RETURN_FAILED;
		}
	}

	function changeStatus($status)
	{
		//改变状态
		//VER=1.1&CMD=Change_Stat&SEQ=&UIN=&ST= 
		//ST为要改变的状态，10为上线，20为离线，30为忙碌。
		$str = "VER=1.1&CMD=Change_stat&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&ST=".$status;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//服务器成功得到信息
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//失败
			return QQ_RETURN_FAILED;
		}
	}

	function logout()
	{
		//退出登陆
		//VER=1.1&CMD=Logout&SEQ=&UIN=
		$str = "VER=1.1&CMD=Logout&SEQ=".rand(1000,9000)."&UIN=".$this->uin;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//服务器成功得到信息
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//失败
			return QQ_RETURN_FAILED;
		}
	}

	function getMsg()
	{
		//获得消息
		//VER=1.1&CMD=GetMsgEx&SEQ=&UIN=
		//MT表示消息类型，99表示系统消息，9表示用户消息。UN表示消息发送来源用户，MG表示发送的消息，MG消息可以表示某些特定的系统含意
		//当MT=99时：MG=10表示用户上线，MG=20表示用户离线，MG=30表示用户忙碌
		$str = "VER=1.1&CMD=GetMsgEx&SEQ=".rand(1000,9000)."&UIN=".$this->uin;
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//服务器成功得到信息
			if($return['MN'] > 0)
			{
				//消息数>0
				$mts = $this->split_str($return['MT']);	//消息类型
				$uns = $this->split_str($return['UN']); //发送者号码
				$mgs = $this->split_str($return['MG']); //消息内容
				$error = 0;
				(count($mts)==count($uns))==(count($uns)==count($mgs))?
					$num = count($uns)
					:
					$error = 1;
				;
				if($error == 1) return QQ_GETMSG_ERROR;	//出差错了
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
				//在线好友数<=0
				return QQ_GETMSG_NONE;
			}
		}
		else
		{
			//失败
			return QQ_RETURN_FAILED;
		}
	}

	function sendMsg($uin,$msg)
	{
		//发送消息
		//VER=1.1&CMD=CLTMSG&SEQ=&UIN=&UN=&MG= 
		$str = "VER=1.1&CMD=CLTMSG&SEQ=".rand(1000,9000)."&UIN=".$this->uin."&UN=".$uin."&MG=".$this->gb2312_to_utf8($msg);
		$return = $this->encode($this->query($str));
		if($return['RES']==0 and $return['UIN'] == $this->uin)
		{
			//服务器成功得到信息
			return QQ_RETURN_SUCCESS;
		}
		else
		{
			//失败
			return QQ_RETURN_FAILED;
		}
	}

}
?>