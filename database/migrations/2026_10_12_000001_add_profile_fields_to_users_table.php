<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('password')->nullable()->change();
            $table->string('avatar')->nullable()->after('phone');
            $table->string('company_name')->nullable()->after('avatar');
            $table->string('property_type', 50)->nullable()->after('company_name');
            $table->text('default_address')->nullable()->after('property_type');
            $table->string('division', 50)->nullable()->after('default_address');
            $table->string('district', 50)->nullable()->after('division');
            $table->string('area', 80)->nullable()->after('district');
            $table->string('role', 30)->default('customer')->index()->after('area');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'avatar',
                'company_name',
                'property_type',
                'default_address',
                'division',
                'district',
                'area',
                'role',
                'is_active',
            ]);
        });
    }
};
