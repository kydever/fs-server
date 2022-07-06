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
namespace App\Service\Formatter;

use App\Model\File;
use Han\Utils\Service;

class FileFormatter extends Service
{
    public function base(File $model)
    {
        return [
            'id' => $model,
            'user_id' => $model->user_id,
            'path' => $model->path,
            'title' => $model->title,
            'summary' => $model->summary,
            'tags' => $model->tags,
            'version' => $model->version,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }
}
