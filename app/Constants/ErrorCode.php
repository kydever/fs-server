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
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error")
     */
    public const SERVER_ERROR = 500;

    /**
     * @Message("Token Invalid.")
     */
    public const TOKEN_INVALID = 700;

    /**
     * @Message("Param Invalid.")
     */
    public const PARAM_INVALID = 1000;

    /**
     * @Message("文件不存在.")
     */
    public const FILE_NOT_EXIST = 1001;

    /**
     * @Message("新建文件时，必须上传文件.")
     */
    public const FILE_MUST_EXIST = 1002;
}
