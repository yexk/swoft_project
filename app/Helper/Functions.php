<?php
/**
 * common function 
 * @author Yexk <yexk@yexk.cn>
 */

use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;

/**
 * 封装的 返回
 *
 * @author Yexk <yexk@yexk.cn>
 * @param array $data 数据
 * @param integer $code 状态码
 * @param string $msg 消息
 * @return Response
 */
function returnJson($data = [],int $code = 200,string $msg = ''): Response {
    $r = [];
    $r['code'] = $code;
    $r['msg'] = $msg ?? '获取成功';
    $r['data'] = $data;
    return context()->getResponse()->withContentType(ContentType::JSON)->withContent(json_encode($r));
}
