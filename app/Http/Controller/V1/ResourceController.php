<?php

declare(strict_types=1);

namespace App\Http\Controller\V1;

use App\Model\Entity\Baiduyun;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Redis\Redis;
use Swoft\Task\Task;

/**
 * Class ResourceController
 *
 * @since 2.0
 *
 * @Controller(prefix="/api/")
 */
class ResourceController
{
    /**
     * @RequestMapping("getbaiduyun", method=RequestMethod::GET)
     *
     * @return Response
     * @throws SwoftException
     */
    public function getBaiduYun(Request $request): Response
    {
        $res = Baiduyun::get();

        return returnJson($res);
    }

    /**
     * @RequestMapping("setbaiduyun", method=RequestMethod::POST)
     *
     * @return Response
     * @throws SwoftException
     */
    public function setBaiduYun(Request $request): Response
    {
        $data = $request->input();
        if (!empty($data['msg'])) {
            // https://yun.baidu.com/s/1x1bBhcIibumrxAD-Z7o8JQ 复制这段内容后打开百度网盘手机App，操作更方便哦
            // 链接:https://yun.baidu.com/s/1mSBY4eJW2RrqN4vk06lG-g 提取码:dn77 复制这段内容后打开百度网盘手机App，操作更方便哦
            $msg = $data['msg'];
            $_d = [];
            $reg = "/https?:\/\/(([a-zA-Z0-9_-])+(\.)?)*(:\d+)?(\/((\.)?(\?)?=?&?[a-zA-Z0-9_-](\?)?)*)*/";
            $reg_code = "/提取码:(\w+) /";
            preg_match_all($reg, $msg, $_d['url']);
            preg_match_all($reg_code, $msg, $_d['code']);
            
        }else{

        }
        
        return returnJson($_d);
    }
}