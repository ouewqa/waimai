<%@LANGUAGE="VBSCRIPT" CODEPAGE="936"%> 
<!--#include file="./classes/PayRequestHandler.asp"-->
<!--#include file="./tenpay_config.asp"-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<title>财付通双接口支付请求示例</title>
</head>
<body>
<%
'---------------------------------------------------------
'财付通双接口支付请求示例，商户按照此文档进行开发即可
'---------------------------------------------------------
'获取提交的商品价格
order_price=trim(request("order_price"))

'获取提交的商品名称
product_name=trim(request("product_name"))

'获取提交的备注信息
remarkexplain=trim(request("remarkexplain"))

'获取提交的订单号
out_trade_no=trim(request("order_no"))

'接口类型
trade_mode=trim(request("trade_mode"))

'支付方式
bank_type_value=trim(request("bank_type_value"))

Dim total_fee
Dim desc

'商品价格（包含运费），以分为单位
total_fee = csng(order_price*100)

'商品名称
desc = "商品：" & product_name&",备注："&remarkexplain

'订单生成时间
strNow = Now()
strNow = Year(strNow) & Right(("00" & Month(strNow)),2) & Right(("00" & Day(strNow)),2) & Right(("00" & Hour(strNow)),2) & Right(("00" &  Minute(strNow)),2) & Right(("00" & Second(strNow)),2)


'创建支付请求对象
Dim reqHandler
Set reqHandler = new PayRequestHandler
reqHandler.setGateUrl("https://gw.tenpay.com/gateway/pay.htm")

'初始化
reqHandler.init()

'设置密钥
reqHandler.setKey(key)

'-----------------------------
'设置支付参数
'-----------------------------
reqHandler.setParameter "partner", partner		'设置商户号
reqHandler.setParameter "out_trade_no", out_trade_no				'商户订单号
reqHandler.setParameter "total_fee", total_fee				'商品总金额,以分为单位
reqHandler.setParameter "return_url", return_url			'回调地址
reqHandler.setParameter "notify_url", notify_url			'通知地址
reqHandler.setParameter "body", desc	                    '商品描述
reqHandler.setParameter "bank_type", bank_type_value						'银行类型
reqHandler.setParameter "fee_type", "1"						'银行币种
reqHandler.setParameter "subject", desc             '商品名称(中介交易时必填)
reqHandler.setParameter "spbill_create_ip", Request.ServerVariables("REMOTE_ADDR")  '支付机器IP

'系统可选参数
reqHandler.setParameter "sign_type", "MD5"        '签名方式
reqHandler.setParameter "service_version", "1.0"  '接口版本
reqHandler.setParameter "input_charset", "GBK"    '字符集
reqHandler.setParameter "sign_key_index", "1"     '密钥序号

'业务可选参数
reqHandler.setParameter "attach", ""                      '附加数据，原样返回
reqHandler.setParameter "product_fee", ""                 '商品费用，必须保证transport_fee + product_fee=total_fee
reqHandler.setParameter "transport_fee", "0"            '物流费用，必须保证transport_fee + product_fee=total_fee
reqHandler.setParameter "time_start", strNow            '订单生成时间，格式为yyyymmddhhmmss
reqHandler.setParameter "time_expire", ""                 '订单失效时间，格式为yyyymmddhhmmss
reqHandler.setParameter "buyer_id", ""                    '买方财付通账号
reqHandler.setParameter "goods_tag", ""                   '商品标记
reqHandler.setParameter "trade_mode", trade_mode                 '交易模式，1即时到账(默认)，2中介担保，3后台选择（买家进支付中心列表选择）
reqHandler.setParameter "transport_desc", ""              '物流说明
reqHandler.setParameter "trans_type", "1"                 '交易类型，1实物交易，2虚拟交易
reqHandler.setParameter "agentid", ""                     '平台ID
reqHandler.setParameter "agent_type", ""                  '代理模式，0无代理(默认)，1表示卡易售模式，2表示网店模式
reqHandler.setParameter "seller_id", ""                   '卖家商户号，为空则等同于partner

'请求的URL
Dim reqUrl
reqUrl = reqHandler.getRequestURL()

'debug信息
Dim debugInfo
debugInfo = reqHandler.getDebugInfo()
Response.Write("<br/>debugInfo:" & debugInfo & "<br/>")
Response.Write("<br/>reqUrl:" & reqUrl & "<br/>")
'重定向到财付通支付
'reqHandler.doSend()

%>
<br/><a href="<%=reqUrl%>" target="_blank">财付通支付</a>
</body>
</html>