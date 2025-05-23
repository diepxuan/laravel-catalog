<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-22 08:51:36
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_links', static function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('laravel_user_id'); // users.id
            $table->string('simba_user_id'); // SysUserInfo.UserID (nếu là string, đổi kiểu nếu cần)

            $table->timestamps();

            $table->unique('simba_user_id');

            $table->unique('laravel_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_links');
    }
};
