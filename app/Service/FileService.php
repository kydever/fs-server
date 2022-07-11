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

use App\Constants\Status;
use App\Model\File;
use App\Service\Dao\FileDao;
use App\Service\Formatter\FileFormatter;
use App\Service\SubService\UploadService;
use Han\Utils\Service;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Upload\UploadedFile;

class FileService extends Service
{
    #[Inject]
    protected UploadService $upload;

    #[Inject]
    protected FileDao $dao;

    #[Inject]
    protected FileFormatter $formatter;

    public function findByDirname(string $dirname)
    {
        [$count, $models] = $this->dao->findByDirname($dirname);

        $result = $this->formatter->formatList($models);

        return [$count, $result];
    }

    /**
     * @param $data = [
     *     'path' => '/file/xxx.md',
     *     'summary' => '',
     *     'tags' => ['设计稿', '文档'],
     * ]
     */
    public function save(int $id, int $userId, ?UploadedFile $file = null, array $data = []): bool
    {
        $info = pathinfo($data['path']);
        $extension = $info['extension'] ?? null;
        $title = $info['filename'];
        $dirname = $info['dirname'];

        $this->dao->createDir($dirname);

        $target = $this->upload->move($file, $extension);
        if (! $target) {
            return true;
        }
        if ($id > 0) {
            $model = $this->dao->first($id, true);
            ++$model->version;
        } else {
            $model = new File();
            $model->version = 1;
        }

        $model->user_id = $userId;
        $model->summary = $data['summary'] ?? '';
        $model->tags = $data['tags'] ?? [];
        $model->path = $data['path'];
        $model->title = $title;
        $model->dirname = $dirname;
        $model->is_dir = Status::NO;

        if ($target) {
            $hash = hash_file('md5', $target);
            $url = $this->upload->upload($target, $extension);

            $model->hash = $hash;
            $model->url = $url;
        }

        return $model->save();
    }

    public function info(int $id): array
    {
        $model = $this->dao->first($id, true);

        return $this->formatter->base($model);
    }

    public function downloadUrl(array $ids): array
    {
        if (! $ids) {
            return [];
        }

        $models = $this->dao->findMany($ids);

        return $this->formatter->formatDownloadUrl($models);
    }
}
