<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fields the member can maintain themselves from My Profile.
 *
 * Nothing here duplicates KYC: PAN, Aadhaar, address and the whole bank block
 * live in `kyc_details` and stay read-only on the profile page, sourced from
 * there. These columns are the personal and nominee details KYC does not hold.
 */
return new class extends Migration
{
    /**
     * column => closure that defines it. Each is added only if missing, so the
     * migration is safe to re-run and safe alongside the hand-run SQL file.
     */
    private function definitions(): array
    {
        return [
            // --- personal -------------------------------------------------
            'middle_name'          => fn (Blueprint $t) => $t->string('middle_name', 255)->nullable(),
            'date_of_birth'        => fn (Blueprint $t) => $t->date('date_of_birth')->nullable(),
            'marriage_anniversary' => fn (Blueprint $t) => $t->date('marriage_anniversary')->nullable(),
            'profession'           => fn (Blueprint $t) => $t->string('profession', 100)->nullable(),
            'nationality'          => fn (Blueprint $t) => $t->string('nationality', 100)->nullable(),

            // --- address (country/district/pin; state + city already exist) -
            'country'              => fn (Blueprint $t) => $t->string('country', 100)->nullable(),
            'district'             => fn (Blueprint $t) => $t->string('district', 100)->nullable(),
            'pin_code'             => fn (Blueprint $t) => $t->string('pin_code', 10)->nullable(),

            // --- extra contact numbers (primary `mobile` already exists) ----
            'alternate_mobile'     => fn (Blueprint $t) => $t->string('alternate_mobile', 20)->nullable(),
            'std_code'             => fn (Blueprint $t) => $t->string('std_code', 10)->nullable(),
            'phone_number'         => fn (Blueprint $t) => $t->string('phone_number', 20)->nullable(),

            // --- nominee ---------------------------------------------------
            'nominee_title'        => fn (Blueprint $t) => $t->string('nominee_title', 20)->nullable(),
            'nominee_first_name'   => fn (Blueprint $t) => $t->string('nominee_first_name', 255)->nullable(),
            'nominee_middle_name'  => fn (Blueprint $t) => $t->string('nominee_middle_name', 255)->nullable(),
            'nominee_last_name'    => fn (Blueprint $t) => $t->string('nominee_last_name', 255)->nullable(),
            'nominee_gender'       => fn (Blueprint $t) => $t->string('nominee_gender', 10)->nullable(),
            'nominee_email'        => fn (Blueprint $t) => $t->string('nominee_email', 255)->nullable(),
            'nominee_mobile'       => fn (Blueprint $t) => $t->string('nominee_mobile', 20)->nullable(),
            'nominee_dob'          => fn (Blueprint $t) => $t->date('nominee_dob')->nullable(),
            'nominee_relation'     => fn (Blueprint $t) => $t->string('nominee_relation', 100)->nullable(),
        ];
    }

    public function up(): void
    {
        $missing = array_filter(
            $this->definitions(),
            fn ($_, $column) => !Schema::hasColumn('users', $column),
            ARRAY_FILTER_USE_BOTH
        );

        if (empty($missing)) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($missing) {
            foreach ($missing as $define) {
                $define($table);
            }
        });
    }

    public function down(): void
    {
        $present = array_keys(array_filter(
            $this->definitions(),
            fn ($_, $column) => Schema::hasColumn('users', $column),
            ARRAY_FILTER_USE_BOTH
        ));

        if (empty($present)) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($present) {
            $table->dropColumn($present);
        });
    }
};
