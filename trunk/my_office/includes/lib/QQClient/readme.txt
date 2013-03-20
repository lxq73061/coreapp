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
					QQ����,
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