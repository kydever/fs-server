<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Constants\ErrorCode;
use App\Constants\Status;
use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use KY\WorkWxUser\Request\AuthorizeRequest;
use KY\WorkWxUser\UserService;
use KY\WorkWxUser\WeChat\WeChat;

class OAuthController extends Controller
{
    #[Inject]
    protected WeChat $wx;

    #[Inject]
    protected UserService $service;

    public function authorize(AuthorizeRequest $request)
    {
        $url = (string) $request->input('redirect_uri');
        $state = (string) $request->input('state');

        $redirectUrl = $this->wx->authorize($url, $state);

        return $this->response->redirect($redirectUrl);
    }

    public function login()
    {
        $code = $this->request->input('code');

        $result = $this->service->login($code);

        $user = $result->getUser();
        if ($user->status !== Status::YES || $user->enable !== Status::YES) {
            throw new BusinessException(ErrorCode::USER_NOT_ENABLE);
        }

        return $this->response->success([
            'token' => $result->getToken(),
            'user' => $user->toArray(),
        ]);
    }
}
