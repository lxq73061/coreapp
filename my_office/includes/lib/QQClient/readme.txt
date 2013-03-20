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
					QQ号码,
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