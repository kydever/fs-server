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

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->unsignedInteger('id', false)->primary();
            $table->string('name', 64)->default('')->comment('部门名称');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级部门ID');
            $table->dateTime('created_at')->default('2022-01-01')->comment('创建时间');
            $table->dateTime('updated_at')->default('2022-01-01')->comment('更新时间');

            $table->index(['parent_id'], 'INDEX_PARENT_ID');
            $table->comment('部门表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
}
