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
        // Helper function to check if index exists
        $indexExists = function ($table, $indexName) {
            if (DB::getDriverName() !== 'mysql') {
                return false;
            }
            $connection = Schema::getConnection();
            $database = $connection->getDatabaseName();
            $result = $connection->select(
                "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.STATISTICS
                 WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                [$database, $table, $indexName]
            );
            return $result[0]->count > 0;
        };

        // Users table indexes
        Schema::table('users', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('users', 'idx_users_is_admin')) {
                $table->index('is_admin', 'idx_users_is_admin');
            }
        });

        // Basic infos table indexes
        Schema::table('basic_infos', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('basic_infos', 'idx_basic_infos_gender')) {
                $table->index('gender', 'idx_basic_infos_gender');
            }
            if (!$indexExists('basic_infos', 'idx_basic_infos_dob')) {
                $table->index('dob', 'idx_basic_infos_dob');
            }
            if (!$indexExists('basic_infos', 'idx_basic_infos_marital_status')) {
                $table->index('marital_status', 'idx_basic_infos_marital_status');
            }
            if (!$indexExists('basic_infos', 'idx_basic_infos_religion')) {
                $table->index('religion', 'idx_basic_infos_religion');
            }
            if (!$indexExists('basic_infos', 'idx_basic_infos_height')) {
                $table->index('height', 'idx_basic_infos_height');
            }
            if (!$indexExists('basic_infos', 'idx_basic_infos_gender_dob')) {
                $table->index(['gender', 'dob'], 'idx_basic_infos_gender_dob');
            }
        });

        // Physical attributes table indexes
        Schema::table('physical_attributes', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('physical_attributes', 'idx_physical_attributes_body_type')) {
                $table->index('body_type', 'idx_physical_attributes_body_type');
            }
            if (!$indexExists('physical_attributes', 'idx_physical_attributes_complexion')) {
                $table->index('complexion', 'idx_physical_attributes_complexion');
            }
        });

        // Education careers table indexes
        Schema::table('education_careers', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('education_careers', 'idx_education_careers_highest_education')) {
                $table->index('highest_education', 'idx_education_careers_highest_education');
            }
            if (!$indexExists('education_careers', 'idx_education_careers_occupation')) {
                $table->index('occupation', 'idx_education_careers_occupation');
            }
            if (!$indexExists('education_careers', 'idx_education_careers_annual_income')) {
                $table->index('annual_income', 'idx_education_careers_annual_income');
            }
        });

        // Locations table indexes
        Schema::table('locations', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('locations', 'idx_locations_division')) {
                $table->index('division', 'idx_locations_division');
            }
            if (!$indexExists('locations', 'idx_locations_district')) {
                $table->index('district', 'idx_locations_district');
            }
            if (!$indexExists('locations', 'idx_locations_upazilla')) {
                $table->index('upazilla', 'idx_locations_upazilla');
            }
            if (!$indexExists('locations', 'idx_locations_division_district')) {
                $table->index(['division', 'district'], 'idx_locations_division_district');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Helper function to check if index exists
        $indexExists = function ($table, $indexName) {
            if (DB::getDriverName() !== 'mysql') {
                return false;
            }
            $connection = Schema::getConnection();
            $database = $connection->getDatabaseName();
            $result = $connection->select(
                "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.STATISTICS
                 WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                [$database, $table, $indexName]
            );
            return $result[0]->count > 0;
        };

        // Drop indexes in reverse order (only if they exist)
        Schema::table('locations', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('locations', 'idx_locations_division_district')) {
                $table->dropIndex('idx_locations_division_district');
            }
            if ($indexExists('locations', 'idx_locations_upazilla')) {
                $table->dropIndex('idx_locations_upazilla');
            }
            if ($indexExists('locations', 'idx_locations_district')) {
                $table->dropIndex('idx_locations_district');
            }
            if ($indexExists('locations', 'idx_locations_division')) {
                $table->dropIndex('idx_locations_division');
            }
        });

        Schema::table('education_careers', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('education_careers', 'idx_education_careers_annual_income')) {
                $table->dropIndex('idx_education_careers_annual_income');
            }
            if ($indexExists('education_careers', 'idx_education_careers_occupation')) {
                $table->dropIndex('idx_education_careers_occupation');
            }
            if ($indexExists('education_careers', 'idx_education_careers_highest_education')) {
                $table->dropIndex('idx_education_careers_highest_education');
            }
        });

        Schema::table('physical_attributes', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('physical_attributes', 'idx_physical_attributes_complexion')) {
                $table->dropIndex('idx_physical_attributes_complexion');
            }
            if ($indexExists('physical_attributes', 'idx_physical_attributes_body_type')) {
                $table->dropIndex('idx_physical_attributes_body_type');
            }
        });

        Schema::table('basic_infos', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('basic_infos', 'idx_basic_infos_gender_dob')) {
                $table->dropIndex('idx_basic_infos_gender_dob');
            }
            if ($indexExists('basic_infos', 'idx_basic_infos_height')) {
                $table->dropIndex('idx_basic_infos_height');
            }
            if ($indexExists('basic_infos', 'idx_basic_infos_religion')) {
                $table->dropIndex('idx_basic_infos_religion');
            }
            if ($indexExists('basic_infos', 'idx_basic_infos_marital_status')) {
                $table->dropIndex('idx_basic_infos_marital_status');
            }
            if ($indexExists('basic_infos', 'idx_basic_infos_dob')) {
                $table->dropIndex('idx_basic_infos_dob');
            }
            if ($indexExists('basic_infos', 'idx_basic_infos_gender')) {
                $table->dropIndex('idx_basic_infos_gender');
            }
        });

        Schema::table('users', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('users', 'idx_users_is_admin')) {
                $table->dropIndex('idx_users_is_admin');
            }
        });
    }
};
