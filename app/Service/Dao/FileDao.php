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
namespace App\Service\Dao;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\File;
use Han\Utils\Service;

class FileDao extends Service
{
    public function first(int $id, bool $throw = false): ?File
    {
        $model = File::findFromCache($id);
        if (empty($model) && $throw) {
            throw new BusinessException(ErrorCode::FILE_NOT_EXIST);
        }

        return $model;
    }
}
