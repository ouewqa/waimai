using System;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using tenpayApp;

public partial class _Default : System.Web.UI.Page 
{
    protected void Page_Load(object sender, EventArgs e)
    {
        string sp_billno = Request["order_no"];
        string product_name = Request["product_name"];
        string order_price = Request["order_price"]; ;
        string remarkexplain = Request["remarkexplain"];
        double money = 0;
        if (null == Request["order_price"])
        {
            Response.End();
            return;
        }
        try
        {
            money = Convert.ToDouble(order_price);
        }
        catch
        {
            //Response.Write("支付金额格式错误！");
            //Response.End();
            //return;
        }
        if (null == sp_billno)
        {
            //生成订单10位序列号，此处用时间和随机数生成，商户根据自己调整，保证唯一
            sp_billno = DateTime.Now.ToString("HHmmss") + TenpayUtil.BuildRandomStr(4);
        }
        else
        {
            sp_billno = Request["order_no"].ToString();
        }

        //创建RequestHandler实例
        RequestHandler reqHandler = new RequestHandler(Context);
        //初始化
        reqHandler.init();
        //设置密钥
        reqHandler.setKey(TenpayUtil.tenpay_key);
        reqHandler.setGateUrl("https://gw.tenpay.com/gateway/pay.htm");




        //-----------------------------
        //设置支付参数
        //-----------------------------
        reqHandler.setParameter("partner", TenpayUtil.bargainor_id);		        //商户号
        reqHandler.setParameter("out_trade_no", sp_billno);		//商家订单号
        reqHandler.setParameter("total_fee", (money * 100).ToString());			        //商品金额,以分为单位
        reqHandler.setParameter("return_url", TenpayUtil.tenpay_return);		    //交易完成后跳转的URL
        reqHandler.setParameter("notify_url", TenpayUtil.tenpay_notify);		    //接收财付通通知的URL
        reqHandler.setParameter("body", "测试");	                    //商品描述
        reqHandler.setParameter("bank_type", "DEFAULT");		    //银行类型(中介担保时此参数无效)
        reqHandler.setParameter("spbill_create_ip", Page.Request.UserHostAddress);   //用户的公网ip，不是商户服务器IP
        reqHandler.setParameter("fee_type", "1");                    //币种，1人民币
        reqHandler.setParameter("subject", "商品名称");              //商品名称(中介交易时必填)


        //系统可选参数
        reqHandler.setParameter("sign_type", "MD5");
        reqHandler.setParameter("service_version", "1.0");
        reqHandler.setParameter("input_charset", "UTF-8");
        reqHandler.setParameter("sign_key_index", "1");

        //业务可选参数

        reqHandler.setParameter("attach", "");                      //附加数据，原样返回
        reqHandler.setParameter("product_fee", "0");                 //商品费用，必须保证transport_fee + product_fee=total_fee
        reqHandler.setParameter("transport_fee", "0");               //物流费用，必须保证transport_fee + product_fee=total_fee
        reqHandler.setParameter("time_start", DateTime.Now.ToString("yyyyMMddHHmmss"));            //订单生成时间，格式为yyyymmddhhmmss
        reqHandler.setParameter("time_expire", "");                 //订单失效时间，格式为yyyymmddhhmmss
        reqHandler.setParameter("buyer_id", "");                    //买方财付通账号
        reqHandler.setParameter("goods_tag", "");                   //商品标记
        reqHandler.setParameter("trade_mode", "1");                 //交易模式，1即时到账(默认)，2中介担保，3后台选择（买家进支付中心列表选择）
        reqHandler.setParameter("transport_desc", "");              //物流说明
        reqHandler.setParameter("trans_type", "1");                  //交易类型，1实物交易，2虚拟交易
        reqHandler.setParameter("agentid", "");                     //平台ID
        reqHandler.setParameter("agent_type", "");                  //代理模式，0无代理(默认)，1表示卡易售模式，2表示网店模式
        reqHandler.setParameter("seller_id", "");                   //卖家商户号，为空则等同于partner



        //获取请求带参数的url
        string requestUrl = reqHandler.getRequestURL();

        //Get的实现方式
        string a_link = "<a target=\"_blank\" href=\"" + requestUrl + "\">" + "财付通支付" + "</a>";
       Response.Write(a_link);


        //post实现方式
        
       /* Response.Write("<form method=\"post\" action=\""+ reqHandler.getGateUrl() + "\" >\n");
        Hashtable ht = reqHandler.getAllParameters();
        foreach(DictionaryEntry de in ht) 
        {
            Response.Write("<input type=\"hidden\" name=\"" + de.Key + "\" value=\"" + de.Value + "\" >\n");
        }
        Response.Write("<input type=\"submit\" value=\"财付通支付\" >\n</form>\n");*/
        

        //获取debug信息,建议把请求和debug信息写入日志，方便定位问题
        string debuginfo = reqHandler.getDebugInfo();
        Response.Write("<br/>requestUrl:" + requestUrl + "<br/>");
        Response.Write("<br/>debuginfo:" + debuginfo + "<br/>");
    }
}
