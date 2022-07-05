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

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userid', 32)->default('')->comment('企业微信用户ID');
            $table->string('name', 64)->default('')->comment('用户昵称');
            $table->string('alias', 64)->default('')->comment('用户别名');
            $table->string('position', 64)->default('')->comment('职位');
            $table->json('departments')->comment('部门列表');
            $table->string('mobile', 32)->default('')->comment('手机号');
            $table->string('email', 32)->default('')->comment('邮箱');
            $table->string('avatar', 256)->default('')->comment('头像');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态');
            $table->unsignedTinyInteger('enable')->default(1)->comment('是否可用');
            $table->timestamps();

            $table->unique(['userid'], 'UNIQUE_USERID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
