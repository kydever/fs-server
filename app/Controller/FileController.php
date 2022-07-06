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

use App\Request\DownloadUrlRequest;
use App\Request\FileSaveRequest;
use App\Service\FileService;
use Hyperf\Di\Annotation\Inject;
use function KY\WorkWxUser\get_user_id;

class FileController extends Controller
{
    #[Inject]
    protected FileService $service;

    public function save(int $id, FileSaveRequest $request)
    {
        $file = $request->file('file');
        $userId = get_user_id();

        $result = $this->service->save($id, $userId, $file, $request->all());

        return $this->response->success([
            'saved' => $result,
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

        return $this->response->success($result);
    }
}
