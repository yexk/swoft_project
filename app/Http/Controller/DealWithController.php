<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Redis\Redis;
use Swoft\Task\Task;

/**
 * Class DealWithController
 *
 * @since 2.0
 *
 * @Controller(prefix="/dealwith")
 */
class DealWithController
{
    /**
     * @RequestMapping("gitwebhook", method=RequestMethod::POST)
     *
     * @return Response
     * @throws SwoftException
     */
    public function gitWebHook(Request $request): Response
    {
        $res = $request->input();
        Task::async('UpdateNodeTask', 'build');
        
        $r_res = [];
        $r_res['time'] = time();
        $r_res['head_commit'] = $res['head_commit'];
        $r_res['commits'] = [];
        if (!empty($res['commits'])) {
            foreach ($res['commits'] as $v) {
                array_push($r_res['commits'], ['message' => $v['message'], 'timestamp' => $v['timestamp'], 'author' => $v['committer']]);
            }
        }

        Redis::hset('build', date('YmdHis'), json_encode($r_res));
        return context()->getResponse()->withContentType(ContentType::TEXT)->withContent('更新成功！' . '[' . date('Y-m-d H:i:s') . ']');
    }
}
