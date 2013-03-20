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
<?php

/**
 * 中文编码集合类库
 *
 * 目前该类库可以实现，简体中文 <-> 繁体中文编码互换，简体中文、繁体中文 -> 拼音单向转换，
 * 简体中文、繁体中文 <-> UTF8 编码转换，简体中文、繁体中文 -> Unicode单向转换
 *
 * @作者         Hessian(solarischan@21cn.com)
 * @版本         1.5
 * @版权所有     Hessian / NETiS
 * @使用授权     GPL（不能应用于任何商业用途，无须经过作者同意即可修改代码，但修改后的代码必须按照GPL协议发布）
 * @特别鸣谢     unknow（繁简转换代码片断）
 * @起始         2003-04-01
 * @最后修改     2003-06-06
 * @访问         公开
 *
 * 更新记录
 *
 * ver 1.5 2003-06-06
 * 增加 UTF8 转换到 GB2312、BIG5的功能。
 *
 * ver 1.4 2003-04-07
 * 增加 当转换HTML时设定为true，即可改变charset的值。
 *
 * ver 1.3 2003-04-02
 * 增加 繁体中文转换至拼音的功能。
 *
 * ver 1.2 2003-04-02
 * 合并 简体、繁体中文转换至UTF8的函数。
 * 修改 简体中文转换至拼音的函数，返回值更改为字符串，每一个汉字的拼音用空格分开
 * 增加 简体中文转换为 UNICODE 的功能。
 * 增加 繁体中文转换为 UNICODE 的功能。
 *
 * ver 1.1 2003-04-02
 * 增加 OpenFile() 函数，支持打开本地文件和远程文件。
 * 增加 简体中文转换为 UTF8 的功能。
 * 增加 繁体中文转换为 UTF8 的功能。
 *
 * ver 1.0 2003-04-01
 * 一个集合了中文简体，中文繁体对应各种编码互换的类库已经初步完成。
 */
class Chinese
{

	/**
	 * 存放简体中文与拼音对照表
	 *
	 * @变量类型  数组
	 * @起始      1.0
	 * @最后修改  1.0
	 * @访问      内部
	 */
	var $pinyin_table = array();

	
	/**
	 * 存放 GB <-> UNICODE 对照表的内容
	 * @变量类型  
	 * @起始      1.1
	 * @最后修改  1.2
	 * @访问      内部
	 */
	var $unicode_table = array();

	/**
	 * 访问中文繁简互换表的文件指针
	 *
	 * @变量类型  对象
	 * @起始      1.0
	 * @最后修改  1.0
	 * @访问      内部
	 */
	var $ctf;

	/**
	 * 等待转换的字符串
	 * @变量类型
	 * @起始      1.0
	 * @最后修改  1.0
	 * @访问      内部
	 */
	var $SourceText = "";

	/**
	 * Chinese 的运行配置
	 *
	 * @变量类型  数组
	 * @起始      1.0
	 * @最后修改  1.2
	 * @访问      公开
	 */
	var $config  =  array(
		'codetable_dir'         => "./config/",           //  存放各种语言互换表的目录
		'SourceLang'            => '',                    //  字符的原编码
		'TargetLang'            => '',                    //  转换后的编码
		'GBtoBIG5_table'        => 'gb-big5.table',       //  简体中文转换为繁体中文的对照表
		'BIG5toGB_table'        => 'big5-gb.table',       //  繁体中文转换为简体中文的对照表
		'GBtoPinYin_table'      => 'gb-pinyin.table',     //  简体中文转换为拼音的对照表
		'GBtoUnicode_table'     => 'gb-unicode.table',    //  简体中文转换为UNICODE的对照表
		'BIG5toUnicode_table'   => 'big5-unicode.table'   //  繁体中文转换为UNICODE的对照表
	);

	/**
	 * Chinese 的悉构函数
	 *
	 * 详细说明
	 * @形参      字符串 $SourceLang 为需要转换的字符串的原编码
	 *            字符串 $TargetLang 为转换的目标编码
	 *            字符串 $SourceText 为等待转换的字符串
	 *
	 * @起始      1.0
	 * @最后修改  1.2
	 * @访问      公开
	 * @返回值    无
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
	} // 结束 Chinese 的悉构函数


	/**
	 * 将 16 进制转换为 2 进制字符
	 *
	 * 详细说明
	 * @形参      $hexdata 为16进制的编码
	 * @起始      1.5
	 * @最后修改  1.5
	 * @访问      内部
	 * @返回      字符串
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
	 * 打开对照表
	 *
	 * 详细说明
	 * @形参      
	 * @起始      1.3
	 * @最后修改  1.3
	 * @访问      内部
	 * @返回      无
	 * @throws    
	 */
	function OpenTable()
	{
	    
		// 假如原编码为简体中文的话
		if ($this->config['SourceLang']=="GB2312") {

			// 假如转换目标编码为繁体中文的话
			if ($this->config['TargetLang'] == "BIG5") {
				$this->ctf = fopen($this->config['codetable_dir'].$this->config['GBtoBIG5_table'], "r");
				if (is_null($this->ctf)) {
					echo "打开转换表文件失败！";
					exit;
				}
			}

			// 假如转换目标编码为拼音的话
			if ($this->config['TargetLang'] == "PinYin") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoPinYin_table']);
				if (!$tmp) {
					echo "打开转换表文件失败！";
					exit;
				}
				//
				$i = 0;
				for ($i=0; $i<count($tmp); $i++) {
					$tmp1 = explode("	", $tmp[$i]);
					$this->pinyin_table[$i]=array($tmp1[0],$tmp1[1]);
				}
			}

			// 假如转换目标编码为 UTF8 的话
			if ($this->config['TargetLang'] == "UTF8") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoUnicode_table']);
				if (!$tmp) {
					echo "打开转换表文件失败！";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,7,6);
			}

			// 假如转换目标编码为 UNICODE 的话
			if ($this->config['TargetLang'] == "UNICODE") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoUnicode_table']);
				if (!$tmp) {
					echo "打开转换表文件失败！";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,9,4);
			}
		}

		// 假如原编码为繁体中文的话
		if ($this->config['SourceLang']=="BIG5") {
			// 假如转换目标编码为简体中文的话
			if ($this->config['TargetLang'] == "GB2312") {
				$this->ctf = fopen($this->config['codetable_dir'].$this->config['BIG5toGB_table'], "r");
				if (is_null($this->ctf)) {
					echo "打开转换表文件失败！";
					exit;
				}
			}
			// 假如转换目标编码为 UTF8 的话
			if ($this->config['TargetLang'] == "UTF8") {
				$tmp = @file($this->config['codetable_dir'].$this->config['BIG5toUnicode_table']);
				if (!$tmp) {
					echo "打开转换表文件失败！";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,7,6);
			}

			// 假如转换目标编码为 UNICODE 的话
			if ($this->config['TargetLang'] == "UNICODE") {
				$tmp = @file($this->config['codetable_dir'].$this->config['BIG5toUnicode_table']);
				if (!$tmp) {
					echo "打开转换表文件失败！";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,9,4);
			}

			// 假如转换目标编码为拼音的话
			if ($this->config['TargetLang'] == "PinYin") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoPinYin_table']);
				if (!$tmp) {
					echo "打开转换表文件失败！";
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

		// 假如原编码为 UTF8 的话
		if ($this->config['SourceLang']=="UTF8") {

			// 假如转换目标编码为 GB2312 的话
			if ($this->config['TargetLang'] == "GB2312") {
				$tmp = @file($this->config['codetable_dir'].$this->config['GBtoUnicode_table']);
				if (!$tmp) {
					echo "打开转换表文件失败！";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,7,6))]=substr($value,0,6);
			}

			// 假如转换目标编码为 BIG5 的话
			if ($this->config['TargetLang'] == "BIG5") {
				$tmp = @file($this->config['codetable_dir'].$this->config['BIG5toUnicode_table']);
				if (!$tmp) {
					echo "打开转换表文件失败！";
					exit;
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
					$this->unicode_table[hexdec(substr($value,7,6))]=substr($value,0,6);
			}
		}

	} // 结束 OpenTable 函数

	/**
	 * 打开本地或者远程的文件
	 *
	 * 详细说明
	 * @形参      字符串 $position 为需要打开的文件名称，支持带路径或URL
	 *            布尔值 $isHTML 为待转换的文件是否为html文件
	 * @起始      1.1
	 * @最后修改  1.1
	 * @访问      公开
	 * @返回      无
	 * @throws    
	 */
	function OpenFile( $position , $isHTML=false )
	{
	    $tempcontent = @file($position);

		if (!$tempcontent) {
		    echo "打开文件失败！";
			exit;
		}

		$this->SourceText = implode("",$tempcontent);

		if ($isHTML) {
			$this->SourceText = eregi_replace( "charset=".$this->config['SourceLang'] , "charset=".$this->config['TargetLang'] , $this->SourceText);

			$this->SourceText = eregi_replace("\n", "", $this->SourceText);

			$this->SourceText = eregi_replace("\r", "", $this->SourceText);
		}
	} // 结束 OpenFile 函数

	/**
	 * 打开本地或者远程的文件
	 *
	 * 详细说明
	 * @形参      字符串 $position 为需要打开的文件名称，支持带路径或URL
	 * @起始      1.1
	 * @最后修改  1.1
	 * @访问      公开
	 * @返回      无
	 * @throws    
	 */
	function SiteOpen( $position )
	{
	    $tempcontent = @file($position);

		if (!$tempcontent) {
		    echo "打开文件失败！";
			exit;
		}

		// 将数组的所有内容转换为字符串
		$this->SourceText = implode("",$tempcontent);

		$this->SourceText = eregi_replace( "charset=".$this->config['SourceLang'] , "charset=".$this->config['TargetLang'] , $this->SourceText);


//		ereg(href="css/dir.css"
	} // 结束 OpenFile 函数

	/**
	 * 设置变量的值
	 *
	 * 详细说明
	 * @形参
	 * @起始      1.0
	 * @最后修改  1.0
	 * @访问      公开
	 * @返回值    无
	 * @throws
	 */
	function setvar( $parameter , $value )
	{
		if(!trim($parameter))
			return $parameter;

		$this->config[$parameter] = $value;

	} // 结束 setvar 函数

	/**
	 * 将简体、繁体中文的 UNICODE 编码转换为 UTF8 字符
	 *
	 * 详细说明
	 * @形参      数字 $c 简体中文汉字的UNICODE编码的10进制
	 * @起始      1.1
	 * @最后修改  1.2
	 * @访问      内部
	 * @返回      字符串
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
	} // 结束 CHSUtoUTF8 函数
	
	/**
	 * 简体、繁体中文 <-> UTF8 互相转换的函数
	 *
	 * 详细说明
	 * @形参      
	 * @起始      1.1
	 * @最后修改  1.5
	 * @访问      内部
	 * @返回      字符串
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

			// 返回结果
			return $out;
		}
	} // 结束 CHStoUTF8 函数

	/**
	 * 简体、繁体中文转换为 UNICODE编码
	 *
	 * 详细说明
	 * @形参      
	 * @起始      1.2
	 * @最后修改  1.2
	 * @访问      内部
	 * @返回      字符串
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
	} // 结束 CHStoUNICODE 函数

	/**
	 * 简体中文 <-> 繁体中文 互相转换的函数
	 *
	 * 详细说明
	 * @起始      1.0
	 * @访问      内部
	 * @返回值    经过编码的utf8字符
	 * @throws
	 */
	function GB2312toBIG5()
	{
		// 获取等待转换的字符串的总长度
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

		// 将转换后的结果赋予 $result;
		$result = $this->SourceText;

		// 清空 $thisSourceText
		$this->SourceText = "";

		// 返回转换结果
		return $result;
	} // 结束 GB2312toBIG5 函数

	/**
	 * 根据所得到的编码搜寻拼音
	 *
	 * 详细说明
	 * @起始      1.0
	 * @最后修改  1.0
	 * @访问      内部
	 * @返回值    字符串
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
	} // 结束 PinYinSearch 函数

	/**
	 * 简体、繁体中文 -> 拼音 转换
	 *
	 * 详细说明
	 * @起始      1.0
	 * @最后修改  1.3
	 * @访问      内部
	 * @返回值    字符串，每个拼音用空格分开
	 * @throws
	 */
	function CHStoPinYin(){
		if ( $this->config['SourceLang']=="BIG5" ) {
			$this->ctf = fopen($this->config['codetable_dir'].$this->config['BIG5toGB_table'], "r");
			if (is_null($this->ctf)) {
				echo "打开转换表文件失败！";
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

		// 清空 $this->SourceText
		$this->SourceText = "";

		$this->pinyin_table = array();

		// 返回转换后的结果
		return implode(" ", $ret);
	} // 结束 CHStoPinYin 函数

	/**
	 * 输出转换结果
	 *
	 * 详细说明
	 * @形参
	 * @起始      1.0
	 * @最后修改  1.2
	 * @访问      公开
	 * @返回      字符换
	 * @throws
	 */
	function ConvertIT()
	{
		// 判断是否为中文繁、简转换
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5") && ($this->config['TargetLang']=="GB2312" || $this->config['TargetLang']=="BIG5") ) {
			return $this->GB2312toBIG5();
		}

		// 判断是否为简体中文与拼音转换
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5") && $this->config['TargetLang']=="PinYin" ) {
			return $this->CHStoPinYin();
		}

		// 判断是否为简体、繁体中文与UTF8转换
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5" || $this->config['SourceLang']=="UTF8") && ($this->config['TargetLang']=="UTF8" || $this->config['TargetLang']=="GB2312" || $this->config['TargetLang']=="BIG5") ) {
			return $this->CHStoUTF8();
		}

		// 判断是否为简体、繁体中文与UNICODE转换
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5") && $this->config['TargetLang']=="UNICODE" ) {
			return $this->CHStoUNICODE();
		}

	} // 结束 ConvertIT 函数

} // 结束类库

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
HTTP协议四--关于Chunked编码:

　在有时服务器生成HTTP回应是无法确定消息大小的，这时用Content-Length就无法事
先写入长度，而需要实时生成消息长度，这时服务器一般采用Chunked编码。
　　在进行Chunked编码传输时，在回复消息的头部有transfer-coding并定为Chunked，
表示将用Chunked编码传输内容。采用以下方式编码：
　　Chunked-Body = *chunk
　　　　　　　　　"0" CRLF
　　　　　　　　　footer
　　　　　　　　　CRLF 
　　chunk = chunk-size [ chunk-ext ] CRLF
　　　　　　chunk-data CRLF

　　hex-no-zero = <HEX excluding "0">

　　chunk-size = hex-no-zero *HEX
　　chunk-ext = *( ";" chunk-ext-name [ "=" chunk-ext-value ] )
　　chunk-ext-name = token
　　chunk-ext-val = token | quoted-string
　　chunk-data = chunk-size(OCTET)

　　footer = *entity-header
　　编码使用若干个Chunk组成，由一个标明长度为0的chunk结束，每个Chunk有两部分
组成，第一部分是该Chunk的长度和长度单位（一般不写），第二部分就是指定长度的内
容，每个部分用CRLF隔开。在最后一个长度为0的Chunk中的内容是称为footer的内容，
是一些没有写的头部内容。
　　下面给出一个Chunked的解码过程（RFC文档中有）
　　length := 0
　　read chunk-size, chunk-ext (if any) and CRLF
　　while (chunk-size > 0) {
　　read chunk-data and CRLF
　　append chunk-data to entity-body
　　length := length + chunk-size
　　read chunk-size and CRLF
　　}
　　read entity-header
　　while (entity-header not empty) {
　　append entity-header to existing header fields
　　read entity-header
　　}
　　Content-Length := length
　　Remove "chunked" from Transfer-Encoding
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