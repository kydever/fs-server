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

class CreateFileHashTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file_hash', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('file_id')->default(0)->comment('文件ID');
            $table->string('hash')->default('')->comment('文件HASH');
            $table->string('url', 256)->default('')->comment('云服务URL');
            $table->timestamps();

            $table->unique(['hash'], 'UNIQUE_HASH');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_hash');
    }
}
