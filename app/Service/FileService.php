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

use App\Service\SubService\UploadService;
use Han\Utils\Service;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Hyperf\Utils\Filesystem\Filesystem;

class FileService extends Service
{
    #[Inject]
    protected UploadService $upload;

    /**
     * @param $data = [
     *     'path' => '/file/xxx.md',
     *     'summary' => '',
     *     'tags' => ['设计稿', '文档'],
     * ]
     */
    public function save(int $id, int $userId, UploadedFile $file, array $data = []): bool
    {
        $extension = pathinfo($data['path'])['extension'];

        $dir = BASE_PATH . '/runtime/uploaded/';

        di()->get(Filesystem::class)->makeDirectory($dir, recursive: true, force: true);

        $target = $dir . uniqid() . '.' . $extension;

        $file->moveTo($target);

        $hash = hash_file('md5', $target);

        $url = $this->upload->upload($target, $extension);

        return false;
    }
}
