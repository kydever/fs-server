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
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CachePut;
use Hyperf\DbConnection\Db;
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
     *     'dirname' => '/file',
     *     'summary' => '',
     *     'tags' => ['设计稿', '文档'],
     * ]
     */
    public function uploadFiles(int $userId, array $files, array $data): bool
    {
        $dirname = $data['dirname'] ?? '';
        if ($dirname !== '') {
            $dir = $this->dao->firstByPath($dirname);
            if (! $dir?->isDir()) {
                throw new BusinessException(ErrorCode::FILE_NOT_EXIST, '当前目录不存在');
            }
        }

        $this->dao->createDir($dirname, $userId);

        $paths = [];
        foreach ($files as $file) {
            $fileName = $file->getClientFilename();
            $paths[] = $dirname . '/' . $fileName;
        }

        $pathModels = $this->dao->findByPaths($paths);
        $pathArray = [];
        foreach ($pathModels as $pathModel) {
            $pathArray[$pathModel->path] = $pathModel;
        }

        foreach ($files as $file) {
            $fileName = $file->getClientFilename();
            $path = $dirname . '/' . $fileName;

            $info = pathinfo($path);
            $extension = $info['extension'] ?? null;

            if (empty($extension)) {
                continue;
            }

            $model = $pathArray[$path] ?? null;
            if (empty($model)) {
                $model = new File();
                $model->version = 1;
            } else {
                ++$model->version;
            }

            $target = $this->upload->move($file, $extension);
            if (empty($target)) {
                continue;
            }
            $hash = hash('md5', $target);
            $url = $this->upload->upload($target, $extension);

            $model->user_id = $userId;
            $model->summary = $data['summary'] ?? '';
            $model->tags = $data['tags'] ?? [];
            $model->path = $path;
            $model->title = $fileName;
            $model->dirname = $dirname ?: '/';
            $model->is_dir = Status::NO;
            $model->is_deleted = Status::NO;
            $model->hash = $hash;
            $model->url = $url;

            $model->save();
        }
        return true;
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
        $path = $data['path'];
        $toModel = $this->dao->firstByPath($path);

        if ($toModel && ! $toModel->isDelete() && $toModel->id != $id) {
            throw new BusinessException(ErrorCode::PATH_IS_EXIST);
        }

        if ($id > 0) {
            $model = $this->dao->first($id, true);
            ++$model->version;
        } else {
            $model = $toModel ?: new File();
            $model->version = 1;
            $model->hash = '';
            $model->url = '';
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
        $model->is_deleted = Status::NO;

        if ($target = $this->upload->move($file, $extension)) {
            $hash = hash('md5', $target);
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

    #[Cacheable(prefix: 'tree:all', ttl: 864000)]
    public function getTreeCache(): array
    {
        return $this->getTree();
    }

    #[CachePut(prefix: 'tree:all', ttl: 864000)]
    public function putTreeCache(): array
    {
        return $this->getTree();
    }

    public function getTree(): array
    {
        $result = Db::select(
            'select title, path, dirname from file where is_dir = ? and is_deleted = ?',
            [Status::YES, Status::NO]
        );

        $data = [];
        foreach ($result as $datum) {
            $datum->children = [];
            $data[$datum->path] = $datum;
        }

        foreach ($data as $datum) {
            if ($datum->dirname && ($parent = $data[$datum->dirname] ?? null)) {
                $parent->children[] = $datum;
            }
        }

        foreach ($data as $key => $datum) {
            if ($datum->dirname != '/') {
                unset($data[$key]);
            }
        }

        return array_values($data);
    }

    public function delete(array $ids): bool
    {
        if (empty($ids)) {
            return true;
        }
        $models = $this->dao->findMany($ids);

        /** @var File $model */
        foreach ($models as $model) {
            $model->is_deleted = Status::YES;
            $model->save();
        }

        $this->putTreeCache();

        return true;
    }
}
