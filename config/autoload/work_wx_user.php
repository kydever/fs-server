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
return [
    'corp_id' => env('WORK_WX_CORP_ID'),
    'agent_id' => (int) env('WORK_WX_AGENT_ID'),
    'secret' => env('WORK_WX_SECRET'),
    'token' => env('WORK_WX_TOKEN'),
    'aes_key' => env('WORK_WX_AES_KEY'),
    'oauth' => [
        'redirect_url' => '',
    ],
];
