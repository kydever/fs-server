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
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateFileTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(0)->comment('用户ID');
            $table->string('path', 256)->default('')->comment('文件路径');
            $table->string('hash', 64)->nullable()->comment('文件HASH');
            $table->string('title', 64)->default('')->comment('文件名');
            $table->string('summary', 256)->default('')->comment('文件描述');
            $table->json('tags')->comment('标签');
            $table->unsignedInteger('version')->default(0)->comment('文件版本号');
            $table->string('url', 256)->default('')->comment('云服务URL');
            $table->unsignedTinyInteger('is_dir')->default(0)->comment('是否为文件夹');
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('是否删除 1是 0否');
            $table->string('dirname', 256)->default('/')->comment('文件夹名');
            $table->dateTime('created_at')->default('2022-01-01')->comment('创建时间');
            $table->dateTime('updated_at')->default('2022-01-01')->comment('更新时间');

            $table->index(['user_id'], 'INDEX_USER_ID');
            $table->index(['dirname'], 'INDEX_DIRNAME');
            $table->unique(['path'], 'UNIQUE_PATH');
            $table->unique(['hash'], 'UNIQUE_HASH');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file');
    }
}
