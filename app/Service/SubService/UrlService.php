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
namespace App\Service\SubService;

use Han\Utils\Service;
use Hyperf\Config\Annotation\Value;

class UrlService extends Service
{
    #[Value(key: 'file.default')]
    protected string $storage;

    protected int $expiredSeconds = 3600;

    public function getUrl(string $url): string
    {
        return match ($this->storage) {
            'qiniu' => $this->signQiniu($url),
            default => $url,
        };
    }

    public function signQiniu(string $url): string
    {
        $url .= '?e=' . time() + $this->expiredSeconds;
        $secret = config('file.storage.qiniu.secretKey');
        $hmac = hash_hmac('sha1', $url, $secret, true);
        $base64 = \Qiniu\base64_urlSafeEncode($hmac);
        $token = config('file.storage.qiniu.accessKey') . ':' . $base64;

        return $url . '&token=' . $token;
    }
}
