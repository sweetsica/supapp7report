<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('report_uploads')) {
            return;
        }

        Schema::table('report_uploads', function (Blueprint $table) {
            if (Schema::hasColumn('report_uploads', 'file')) {
                $table->renameColumn('file', 'file_path');
            }

            if (Schema::hasColumn('report_uploads', 'path')) {
                $table->renameColumn('path', 'file_url');
            }
        });

        Schema::table('report_uploads', function (Blueprint $table) {
            if (Schema::hasColumn('report_uploads', 'token')) {
                $table->string('token')->nullable()->change();
            }

            if (Schema::hasColumn('report_uploads', 'type')) {
                $table->string('type')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('report_uploads')) {
            return;
        }

        Schema::table('report_uploads', function (Blueprint $table) {
            if (Schema::hasColumn('report_uploads', 'file_path')) {
                $table->renameColumn('file_path', 'file');
            }

            if (Schema::hasColumn('report_uploads', 'file_url')) {
                $table->renameColumn('file_url', 'path');
            }
        });

        Schema::table('report_uploads', function (Blueprint $table) {
            if (Schema::hasColumn('report_uploads', 'token')) {
                $table->string('token')->nullable(false)->change();
            }

            if (Schema::hasColumn('report_uploads', 'type')) {
                $table->string('type')->nullable(false)->change();
            }
        });
    }
};
