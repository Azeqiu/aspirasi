<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('name');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('siswa')->after('password');
            }

            if (Schema::hasColumn('users', 'email')) {
                // SQLite requires dropping indexes before dropping indexed columns.
                $table->dropUnique('users_email_unique');
                $table->dropColumn('email');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) {
                $table->dropUnique('users_username_unique');
                $table->dropColumn('username');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique();
            }
        });
    }
};