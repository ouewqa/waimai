<%@LANGUAGE="VBSCRIPT" CODEPAGE="936"%>
<!--#include file="./classes/PayResponseHandler.asp"-->
<!--#include file="./util/tenpay_util.asp"-->
<!--#include file="./tenpay_config.asp"-->
<%
'---------------------------------------------------------
'财付通双接口处理回调示例，商户按照此示例进行开发即可
'---------------------------------------------------------

log_result("进入后台通知页面！！！！！")

'创建支付应答对象
Dim resHandler
Set resHandler = new PayResponseHandler
resHandler.setKey(key)

'判断签名
If resHandler.isTenpaySign() = True Then
	
	Dim notify_id
	Dim transaction_id
	Dim total_fee
	Dim out_trade_no
	Dim discount
	Dim trade_state
	
	 '通知id
	 notify_id = resHandler.getParameter("notify_id")
	'创建notify_id验证请求对象
	Dim reqHandler1
	Set reqHandler1 = new PayRequestHandler
	'商户在收到后台通知后根据通知ID向财付通发起验证确认，采用后台系统调用交互模式
	reqHandler1.setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml")
	'初始化
	reqHandler1.init()
	'设置密钥
	reqHandler1.setKey(key)
	'设置商户号
	reqHandler1.setParameter "partner", partner
	'商户订单号		
	reqHandler1.setParameter "notify_id", notify_id
	'创建服务器XMLHTTP请求对象
	Dim httpClient
	set httpClient = CreateObject("Msxml2.ServerXMLHTTP.3.0")
	'打开验证Url连接
	httpClient.Open "GET",reqHandler1.getRequestURL(),False
	'提交请求
	httpClient.Send
	'新建服务器XMLDOM文档解析对象
	Set xmlDoc = server.CreateObject("Microsoft.XMLDOM")
	'加载请求返回的XML文档
	xmlDoc.load(httpClient.responseXML)
	'获取文档根元素
	Set obj =  xmlDoc.selectSingleNode("root")
	'遍历root的所有子节点，获取返回的键值对
	For Each node in obj.childnodes
		reqHandler1.setParameter node.nodename, node.text
	Next
	'对返回值进行判断
	If reqHandler1.getParameter("retcode") = "0"  Then
		'商户交易单号
		out_trade_no = resHandler.getParameter("out_trade_no")	

		'财付通交易单号
		transaction_id = resHandler.getParameter("transaction_id")

		'商品金额,以分为单位
		total_fee = resHandler.getParameter("total_fee")
		
		'如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
		discount = resHandler.getParameter("discount")
		
		'支付结果
		trade_state = resHandler.getParameter("trade_state")
		
		'交易模式，1即时到账，2中介担保
		trade_mode = resHandler.getParameter("trade_mode")
		'可获取的其他参数还有
		'bank_type			银行类型,默认：BL
		'fee_type			现金支付币种,目前只支持人民币,默认值是1-人民币
		'input_charset		字符编码,取值：GBK、UTF-8，默认：GBK。
		'partner			商户号,由财付通统一分配的10位正整数(120XXXXXXX)号
		'product_fee		物品费用，单位分。如果有值，必须保证transport_fee + product_fee=total_fee
		'sign_type			签名类型，取值：MD5、RSA，默认：MD5
		'time_end			支付完成时间
		'transport_fee		物流费用，单位分，默认0。如果有值，必须保证transport_fee +  product_fee = total_fee
		'判断签名及结果
		If "1" = trade_mode Then '即时到帐
			If "0" = trade_state Then
			'----------------------
			'即时到帐处理业务开始
			'-----------------------
			'处理数据库逻辑
			'注意交易单不要重复处理
			'注意判断返回金额
			'-----------------------
			'即时到帐处理业务完毕
			'-----------------------
			'给财付通系统发送成功信息，给财付通系统收到此结果后不在进行后续通知
			log_result("即时到账后台通知成功")
			Response.Write("success")
		    '处理成功
			Else  
				log_result("即时到账后台通知失败")
				Response.Write("即时到帐支付失败")
			End If
		Else
			If trade_mode ="2" Then   '中介担保
				'-----------------------------
				'中介担保处理业务开始
				'------------------------------
						
				'处理数据库逻辑
				'注意交易单不要重复处理
				'注意判断返回金额
				Select Case trade_state
					Case "0":	'付款成功
					
					Case "1":	'交易创建

					Case "2":	'收获地址填写完毕

					Case "4":	'卖家发货成功

					Case "5":	'买家收货确认，交易成功

					Case "6":	'交易关闭，未完成超时关闭

					Case "7":	'修改交易价格成功

					Case "8":	'买家发起退款

					Case "9":	'退款成功

					Case "10":	'退款关闭

					Case else:	'error
						'nothing to do
				End Select
				'------------------------------
				'中介担保处理业务完毕
				'------------------------------
				log_result("中介担保后台通知成功，trade_state=" & trade_state)	
				'给财付通系统发送成功信息，财付通系统收到此结果后不再进行后续通知
				Response.Write("success")
			Else
				Response.Write("后台调用通信失败trade_mode错误")
				'有可能因为网络原因，请求已经处理，但未收到应答。
			End if
		End If
	Else
		Response.Write("Notifyid验证失败")
	End If
Else'签名失败
	Response.Write("签名验证失败")

	Dim debugInfo
	debugInfo = resHandler.getDebugInfo()
	Response.Write("<br/>debugInfo:" & debugInfo & "<br/>")

End If

%>