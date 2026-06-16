<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-16 10:00:00
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
        Schema::dropIfExists('menus');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('menus', static function (Blueprint $table): void {
            $table->id();
            $table->string('simbaid', 20)->nullable()->index();
            $table->string('parent_id')->nullable();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }
};
