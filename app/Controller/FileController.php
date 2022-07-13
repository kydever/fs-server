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
namespace App\Controller;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Request\DownloadUrlRequest;
use App\Request\FileSaveRequest;
use App\Service\Dao\FileDao;
use App\Service\FileService;
use Hyperf\Di\Annotation\Inject;

use function KY\WorkWxUser\get_user_id;

class FileController extends Controller
{
    #[Inject]
    protected FileService $service;

    #[Inject]
    protected FileDao $dao;

    public function index()
    {
        $dirname = $this->request->input('dirname');

        [$count, $result] = $this->service->findByDirname($dirname);

        return $this->response->success([
            'count' => $count,
            'list' => $result,
        ]);
    }

    public function save(int $id, FileSaveRequest $request)
    {
        $file = $request->file('file');
        $userId = get_user_id();
        $input = $request->all();
        if (isset($input['tags'])) {
            if (! is_array($input['tags'])) {
                $input['tags'] = explode(',', (string) $input['tags']);
            }
        }

        $result = $this->service->save($id, $userId, $file, $input);

        return $this->response->success([
            'saved' => $result,
        ]);
    }

    public function createDir()
    {
        $path = (string) $this->request->input('path');
        if (! str_starts_with($path, '/')) {
            throw new BusinessException(ErrorCode::PARAM_INVALID, '文件夹必须以斜线开头');
        }

        $this->dao->createDir($path, get_user_id());

        return $this->response->success([
            'saved' => true,
        ]);
    }

    public function info(int $id)
    {
        $result = $this->service->info($id);

        return $this->response->success([
            'saved' => $result,
        ]);
    }

    public function downloadUrl(DownloadUrlRequest $request)
    {
        $ids = (array) $request->input('ids');

        $result = $this->service->downloadUrl($ids);

        return $this->response->success([
            'list' => $result,
        ]);
    }
}
