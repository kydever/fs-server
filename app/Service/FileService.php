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
namespace App\Service;

use Han\Utils\Service;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Hyperf\Utils\Filesystem\Filesystem;

class FileService extends Service
{
    /**
     * @param $data = [
     *     'path' => '/file/xxx.md',
     *     'summary' => '',
     *     'tags' => ['设计稿', '文档'],
     * ]
     */
    public function save(int $id, int $userId, UploadedFile $file, array $data = []): bool
    {
        $info = pathinfo($data['path']);

        $dir = BASE_PATH . '/runtime/uploaded/';

        di()->get(Filesystem::class)->makeDirectory($dir, recursive: true, force: true);

        $target = $dir . uniqid() . '.' . $info['extension'];

        $file->moveTo($target);

        $hash = hash_file('md5', $target);

        return false;
    }
}
