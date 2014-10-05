<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-8-14
 * Time: 下午4:28
 * To change this template use File | Settings | File Templates.
 */
class PrinterFeiE implements Printer
{
    const FEIE_HOST = '115.28.225.82';
    const FEIE_PORT = '80';


    public $client;
    public $device_no, $device_key, $shop_id;

    public function __construct($shop_id, $device_no, $device_key)
    {
        $this->device_no = $device_no;
        $this->device_key = $device_key;
        $this->shop_id = $shop_id;

        $this->client = new HttpClient(self::FEIE_HOST, self::FEIE_PORT);
    }


    public function makeup($data, $template = null)
    {
        if ($template) {
            $message = $data;
        } else {
            /*
             * 网站的结构
             * $data = array(
                    'items' => array(
                            array(
                                    'name' => '',
                                    'price' => '',
                                    'number' => '',
                                    'money' => '',
                            )
                    ),
                    'name' => '',
                    'comment' => '',
                    'money' => '',
                    'address' => '',
                    'mobile' => '',
                    'dateline' => '',
                    'qrcode' => '',
            );

            #接口中的结构

            $data = array(
                    'title' => '',
                    'list' => array(
                            array(
                                    'name' => '',
                                    'money' => '',
                                    'amount' => '',
                                    'cost' => '',
                            )
                    ),
                    'remark' => '',
                    'total' => '',
                    'address' => '',
                    'phone' => '',
                    'orderTime' => '',
                    'qrcode' => '',
            );*/

            $message = array(
                    'title' => $data['name'],
                    'remark' => $data['comment'],
                    'total' => $data['money'],
                    'address' => $data['address'],
                    'phone' => $data['mobile'],
                    'orderTime' => $data['dateline'],
                    'qrcode' => $data['qrcode'],
            );

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $key => $value) {
                    $message['list'][] = array(
                            'name' => $value['name'],
                            'money' => $value['price'],
                            'amount' => $value['number'],
                            'cost' => $value['money'],
                    );
                }
            }

            $message = json_encode($message);
        }

        return $message;
    }


    public function test($message)
    {
        $message = '<CB>测试打印</CB><BR>' . $message;

        $data = array(
                'clientCode' => $this->device_no,
                'printInfo' => $message,
                'apitype' => 'php',
                'key' => $this->device_key,
                'printTimes' => 1,
        );

        if (!$this->client->post('/FeieServer/printSelfFormatOrder', $data)) {
            return false;
        } else {
            return json_decode($this->client->getContent());
        }
    }

    public function diy($data)
    {
        $message = array(
                'clientCode' => $this->device_no,
                'printInfo' => $data,
                'apitype' => 'php',
                'key' => $this->device_key,
                'printTimes' => 1,
        );

        if (!$this->client->post('/FeieServer/printSelfFormatOrder', $message)) {
            return false;
        } else {
            return json_decode($this->client->getContent());
        }
    }


    /**
     * @param $data
     * @param int $number_of_copies 打印份数
     * @return bool|string
     */
    public function send($data, $number_of_copies = 1)
    {

        $json = $this->makeup($data);


        $message = array(
                'clientCode' => $this->device_no,
                'printInfo' => $json,
                'apitype' => 'php',
                'key' => $this->device_key,
                'printTimes' => $number_of_copies,
        );


        if (!$this->client->post('/FeieServer/printDefalutFormatOrder', $message)) { //提交失败
            $result = false;
        } else {
            $result = $this->client->getContent();
        }

        #todo 打印记录
        $log = new PrintLog();
        $log->setAttributes(array(
                'shop_id' => $this->shop_id,
                'content' => $json,
                'type' => 'O',
                'times' => '1',
                'dateline' => DATELINE,
                'status' => $result ? 'Y' : 'N',
        ));
        $log->save();

        return $result;
    }


    public function checkStatus()
    {
        if (!$this->client->get('/FeieServer/queryprinterstatus?clientCode=' . $this->device_no)) { //请求失败
            return false;
        } else {
            $result = $this->client->getContent();
            return json_decode($result);
        }
    }

}