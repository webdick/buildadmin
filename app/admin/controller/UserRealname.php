<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Exception;

/**
 * 用户实名管理
 *
 */
class UserRealname extends Backend
{
    /**
     * UserRealname模型对象
     * @var \app\admin\model\UserRealname
     */
    protected $model = null;

    protected $quickSearchField = ['uid'];

    protected $defaultSortField = 'uid,desc';

    protected $preExcludeFields = ['createtime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\UserRealname;
    }

    public function idcardCheck(){
        $cardNo = $this->request->post('idcard');
        $realName = $this->request->post('name');
        if(empty($realName)||empty($cardNo)){
            $this->error('参数获取失败');
        }
        $config = get_sys_config('', 'idcard');
        // 云市场分配的密钥Id
        $secretId = $config['secretId'];
        // 云市场分配的密钥Key
        $secretKey = $config['secretKey'];

        if(empty($secretId)||empty($secretKey)){
            $this->error('此功能需要先在系统配置中配置接口密钥');
        }

        $source = 'market';

        // 签名
        $datetime = gmdate('D, d M Y H:i:s T');
        $signStr = sprintf("x-date: %s\nx-source: %s", $datetime, $source);
        $sign = base64_encode(hash_hmac('sha1', $signStr, $secretKey, true));
        $auth = sprintf('hmac id="%s", algorithm="hmac-sha1", headers="x-date x-source", signature="%s"', $secretId, $sign);

        // 请求方法
        $method = 'POST';
        // 请求头
        $headers = array(
            'X-Source' => $source,
            'X-Date' => $datetime,
            'Authorization' => $auth,

        );
        // 查询参数
        $queryParams = array (

        );
        // body参数（POST方法下）
        $bodyParams = array (
            'cardNo' => $cardNo,
            'realName' => $realName,
        );

        // url参数拼接
        $url =  'https://service-hcgajsa5-1253495967.ap-beijing.apigateway.myqcloud.com/release/idcard/idcheckPost';
        if (count($queryParams) > 0) {
            $url .= '?' . http_build_query($queryParams);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(function ($v, $k) {
            return $k . ': ' . $v;
        }, array_values($headers), array_keys($headers)));
        if (in_array($method, array('POST', 'PUT', 'PATCH'), true)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bodyParams));
        }

        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            $this->error('接口请求发起错误');
        } else {
            curl_close($ch);
            $res = json_decode($data,true);
            if(!isset($res['error_code'])){
                $this->error('接口次数已用尽');
            }
            if($res['error_code'] == 0){
                if($res['result']['isok']){
                    $this->success('验证通过');
                }else{
                    $this->error('验证未通过',['resData'=>$res]);
                }
            }else{
                $this->error('接口请求失败',['resData'=>$res]);
            }
        }

    }
}