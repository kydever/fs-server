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
 * @property string $path 文件路径
 * @property string $hash 文件HASH
 * @property string $title 文件名
 * @property string $summary 文件描述
 * @property int $version 文件版本号
 * @property string $url 云服务URL
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class File extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'file';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'path', 'hash', 'title', 'summary', 'version', 'url', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'version' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
