<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('fileName')->nullable();
            $table->text('linkFile')->nullable();
            $table->text('linkDownFile')->nullable();
            $table->string('userName')->nullable();
            $table->string('userId')->nullable();
            $table->string('positionId')->nullable();
            $table->string('departmentId')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_uploads');
    }
};
