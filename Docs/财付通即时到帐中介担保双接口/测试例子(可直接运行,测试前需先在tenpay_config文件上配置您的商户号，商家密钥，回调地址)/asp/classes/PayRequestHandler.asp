<!--#include file="../util/md5.asp"-->
<!--#include file="../util/tenpay_util.asp"-->
<%
'
'即时到帐支付请求类
'============================================================================
'api说明：
'init(),初始化函数，默认给一些参数赋值，如cmdno,date等。
'getGateURL()/setGateURL(),获取/设置入口地址,不包含参数值
'getKey()/setKey(),获取/设置密钥
'getParameter()/setParameter(),获取/设置参数值
'getAllParameters(),获取所有参数
'getRequestURL(),获取带参数的请求URL
'doSend(),重定向到财付通支付
'getDebugInfo(),获取debug信息
'
'============================================================================
'

Class PayRequestHandler
	
	'网关url地址
	Private gateUrl
	
	'密钥
	Private key
	
	'请求的参数
	Private parameters
	
	'debug信息
	Private debugInfo
	
	'初始构造函数
	Private Sub class_initialize()
		gateUrl = "https://gw.tenpay.com/gateway/pay.htm"
		key = ""
		Set parameters = Server.CreateObject("Scripting.Dictionary")
		debugInfo = ""
	End Sub
	
	'初始化函数
	Public Function init()
		parameters.RemoveAll
	End Function
	
	'获取入口地址,不包含参数值
	Public Function getGateURL()
		getGateURL = gateUrl
	End Function
	
	'设置入口地址,不包含参数值
	Public Function setGateURL(gateUrl_)
		gateUrl = gateUrl_
	End Function
	
	'获取密钥
	Public Function getKey()
		getKey = key
	End Function
	
	'设置密钥
	Public Function setKey(key_)
		key = key_
	End Function
	
	'获取参数值
	Public Function getParameter(parameter)
		getParameter = parameters.Item(parameter)
	End Function
	
	'设置参数值
	Public Sub setParameter(parameter, parameterValue)
		If parameters.Exists(parameter) = True Then
			parameters.Remove(parameter)
		End If
		parameters.Add parameter, parameterValue	
	End Sub

	'获取所有请求的参数,返回Scripting.Dictionary
	Public Function getAllParameters()
		getAllParameters = parameters
	End Function
	
	'获取带参数的请求URL
	Public Function getRequestURL()

		Call createSign()
		
		Dim reqPars
		Dim k
		For Each k In parameters
			reqPars = reqPars & k & "=" & Server.URLEncode(parameters(k)) & "&" 
		Next
		
		'去掉最后一个&
		reqPars = Left(reqPars, Len(reqPars)-1)

		getRequestURL = getGateURL & "?" & reqPars

	End Function
	
	'重定向到财付通支付
	Public Function doSend()
		Response.Redirect(getRequestURL())
		Response.End
	End Function	
	
	'获取debug信息
	Public Function getDebugInfo()
		getDebugInfo = debugInfo
	End Function
	
	'创建签名
	Private Sub createSign()
		Dim keys,k,v
		keys	= parameters.Keys()
		'按字母顺序排序
		for i=0 to UBound(keys)-1
			for j=i+1 to UBound(keys)
				if StrComp(keys(i), keys(j)) > 0 then 
					tmp=keys(i)
					keys(i)=keys(j)
					keys(j)=tmp
				end if
			next
		next
		md5str = ""
		'组合签名字符串
		For Each k in keys
			v = getParameter(k)
			if v <> "" and k <> "sign" and k <> "key" then
				md5str = md5str & k & "=" & v & "&"
			end if
		Next

		md5str = md5str & "key=" & key

		Dim sign
		sign= LCase(ASP_MD5(md5str))

		setParameter "sign", sign

		'debuginfo
		debugInfo = md5str & " => sign:" & sign
		
	End Sub

End Class

%>