<?php declare(strict_types=1);

namespace App\Http\Controller;

use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;

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
     * @param string $name
     *
     * @return Response
     * @throws SwoftException
     */
    public function gitWebHook(): Response
    {
        $request = context()->getRequest();
        $res = $request->input();
        return context()->getResponse()->withContentType(ContentType::JSON)->withContent(json_encode($res));
    }
    
}
