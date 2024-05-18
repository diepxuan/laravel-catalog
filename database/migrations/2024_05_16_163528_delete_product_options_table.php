<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-16 23:36:16
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
        Schema::dropIfExists('product_options');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('product_options', static function (Blueprint $table): void {
            $table->id();
            $table->integer('product_id');
            $table->string('code');
            $table->string('value');
            $table->timestamps();
        });
    }
};
