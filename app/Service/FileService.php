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

use App\Constants\ErrorCode;
use App\Constants\Status;
use App\Exception\BusinessException;
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

    public function uploadFiles(array $files, string $dirname)
    {
        $dir = $this->dao->firstByPath($dirname);
        if (! $dir?->isDir()) {
            throw new BusinessException(ErrorCode::FILE_NOT_EXIST, '当前目录不存在');
        }

        foreach ($files as $file);
    }

    /**
     * @param $data = [
     *     'path' => '/file/xxx.md',
     *     'summary' => '',
     *     'tags' => ['设计稿', '文档'],
     * ]
     */
    public function saveFile(int $id, int $userId, ?UploadedFile $file = null, array $data = []): bool
    {
        if ($id > 0) {
            $model = $this->dao->first($id, true);
            ++$model->version;
        } else {
            $model = new File();
            $model->version = 1;
        }

        $path = $data['path'];
        if ($model->path != $path && $this->dao->existsByPath($path)) {
            throw new BusinessException(ErrorCode::PATH_IS_EXIST);
        }

        $info = pathinfo($path);
        $extension = $info['extension'] ?? null;
        $title = $info['filename'];
        $dirname = $info['dirname'];

        if (empty($extension)) {
            throw new BusinessException(ErrorCode::PARAM_INVALID, '暂不支持此类文件上传');
        }

        $this->dao->createDir($dirname, $userId);

        $model->user_id = $userId;
        $model->summary = $data['summary'] ?? '';
        $model->tags = $data['tags'] ?? [];
        $model->path = $path;
        $model->title = $title . '.' . $extension;
        $model->dirname = $dirname;
        $model->is_dir = Status::NO;

        if ($target = $this->upload->move($file, $extension)) {
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
