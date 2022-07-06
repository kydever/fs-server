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
namespace App\Model;

/**
 * @property int $id
 * @property int $file_id 文件ID
 * @property string $hash 文件HASH
 * @property string $url 云服务URL
 * @property int $version 版本号
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 */
class FileHash extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'file_hash';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'file_id', 'hash', 'url', 'version', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'file_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'version' => 'integer'];
}
