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
use App\Constants\Status;
use App\Exception\BusinessException;
use App\Model\File;
use Han\Utils\Service;
use Hyperf\Database\Model\Collection;

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

    public function findByPaths(array $paths)
    {
        return File::query()->whereIn('path', $paths)->get();
    }

    /**
     * @return Collection<int, File>
     */
    public function findMany(array $ids): Collection
    {
        return File::findManyFromCache($ids);
    }

    public function createDir(string $dirname): void
    {
        $dirs = explode('/', $dirname);
        $dir = '';
        $paths = [];
        foreach ($dirs as $item) {
            if (! $item) {
                continue;
            }

            $dir .= '/' . $item;
            $paths[] = $dir;
        }

        $pathArray = $this->findByPaths($paths)->columns('path')->toArray();
        foreach ($paths as $path) {
            if (! in_array($path, $pathArray)) {
                $model = new File();
                $model->path = $path;
                $model->is_dir = Status::YES;
                $model->dirname = dirname($path);
                $model->tags = [];
                $model->hash = null;
                $model->save();
            }
        }
    }
}
