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

use App\Model\File;
use App\Model\FileHash;
use Han\Utils\Service;

class FileHashDao extends Service
{
    public function create(File $file): void
    {
        $hash = new FileHash();
        $hash->file_id = $file->id;
        $hash->hash = $file->hash;
        $hash->url = $file->url;
        $hash->version = $file->version;
        $hash->save();
    }
}
