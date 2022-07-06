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
namespace App\Service\SubService;

use Han\Utils\Service;
use Hyperf\Config\Annotation\Value;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Upload\UploadedFile;
use League\Flysystem\Filesystem;

class UploadService extends Service
{
    #[Inject]
    protected Filesystem $filesystem;

    #[Value(key: 'file.domain')]
    protected string $domain;

    public function move(?UploadedFile $file, string $extension): ?string
    {
        if (! $file) {
            return null;
        }

        $dir = BASE_PATH . '/runtime/uploaded/';

        @mkdir($dir, 0755, true);

        $target = $dir . uniqid() . '.' . $extension;

        $file->moveTo($target);

        return $target;
    }

    public function upload(string $file, string $extension): string
    {
        $path = date('Y/m/d') . '/' . uniqid() . '.' . $extension;
        $stream = fopen($file, 'r+');
        try {
            $this->filesystem->writeStream($path, $stream);
        } finally {
            fclose($stream);
        }

        return trim($this->domain, '/') . '/' . $path;
    }
}
