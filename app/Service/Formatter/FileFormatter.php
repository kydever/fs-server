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
use App\Service\SubService\UrlService;
use Han\Utils\Service;
use Hyperf\Database\Model\Collection;

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
            'is_dir' => $model->isDir(),
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }

    public function formatList($models)
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = $this->base($model);
        }
        return $result;
    }

    /**
     * @param Collection<int, File> $models
     */
    public function formatDownloadUrl(Collection $models): array
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'id' => $model->id,
                'url' => di()->get(UrlService::class)->getUrl($model->url),
            ];
        }

        return $result;
    }
}
