<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The hot tables behind the dashboards (orders, transaction) had no index at
 * all beyond the primary key, so every dashboard aggregate was a full table
 * scan - and the dashboards run a dozen of them per page load.
 *
 * The `users` table had the opposite problem: six indexes duplicating an
 * existing unique key or a left-prefix of a composite index. Redundant indexes
 * have to be maintained on every INSERT/UPDATE, which slowed registration down
 * for no read benefit.
 */
return new class extends Migration
{
    /**
     * index name => [table, columns]
     */
    private array $add = [
        // --- orders: dashboards filter by owner + type + status, sort by date
        'idx_orders_txuser_txtype_status' => ['orders', ['tx_user_id', 'tx_type', 'status']],
        'idx_orders_ucode_txtype_status'  => ['orders', ['u_code', 'tx_type', 'status']],
        'idx_orders_added_on'             => ['orders', ['added_on']],
        'idx_orders_status'               => ['orders', ['status']],

        // --- transaction: wallet/income/withdrawal aggregates per user
        'idx_tx_ucode_txtype_status'      => ['transaction', ['u_code', 'tx_type', 'status']],
        'idx_tx_ucode_txtype_source'      => ['transaction', ['u_code', 'tx_type', 'source']],
        'idx_tx_date'                     => ['transaction', ['date']],

        // --- settings tables: looked up by label on every page.
        // advanced_info.label is a longtext, so it needs a prefix length.
        'idx_company_info_label_status'   => ['company_info', ['label', 'status']],
        'idx_advanced_info_label'         => ['advanced_info', ['label(100)']],

        // --- order_items: recent-purchase lists
        'idx_order_items_ucode'           => ['order_items', ['u_code']],
    ];

    /**
     * Redundant indexes on `users`. Each is already covered by another index:
     *   idx_sponsor_id   -> left prefix of idx_sponsor_id_username
     *   idx_unique_name  -> duplicate of users_unique_name_unique
     *   idx_pan          -> duplicate of users_pan_unique
     *   idx_aadhaar      -> duplicate of users_aadhaar_unique
     *   idx_mobile       -> duplicate of users_mobile_index
     *   idx_username_prefix -> kept (no unique key exists on username)
     */
    private array $dropRedundant = [
        'users' => [
            'idx_sponsor_id'  => ['sponsor_id'],
            'idx_unique_name' => ['unique_name'],
            'idx_pan'         => ['pan'],
            'idx_aadhaar'     => ['aadhaar'],
            'idx_mobile'      => ['mobile'],
        ],
    ];

    public function up(): void
    {
        foreach ($this->add as $name => [$table, $columns]) {
            if (!Schema::hasTable($table) || $this->indexExists($table, $name)) {
                continue;
            }
            if (!$this->hasAllColumns($table, $columns)) {
                continue;
            }
            DB::statement("ALTER TABLE `$table` ADD INDEX `$name` ({$this->columnList($columns)})");
        }

        foreach ($this->dropRedundant as $table => $indexes) {
            foreach (array_keys($indexes) as $name) {
                if (Schema::hasTable($table) && $this->indexExists($table, $name)) {
                    DB::statement("ALTER TABLE `$table` DROP INDEX `$name`");
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->dropRedundant as $table => $indexes) {
            foreach ($indexes as $name => $columns) {
                if (!Schema::hasTable($table) || $this->indexExists($table, $name)) {
                    continue;
                }
                DB::statement("ALTER TABLE `$table` ADD INDEX `$name` ({$this->columnList($columns)})");
            }
        }

        foreach ($this->add as $name => [$table, $columns]) {
            if (Schema::hasTable($table) && $this->indexExists($table, $name)) {
                DB::statement("ALTER TABLE `$table` DROP INDEX `$name`");
            }
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return !empty(DB::select("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$index]));
    }

    private function hasAllColumns(string $table, array $columns): bool
    {
        foreach ($columns as $c) {
            if (!Schema::hasColumn($table, $this->bareColumn($c))) {
                return false;
            }
        }
        return true;
    }

    /**
     * Render index columns, honouring a "col(length)" prefix spec.
     */
    private function columnList(array $columns): string
    {
        return implode(', ', array_map(function ($c) {
            if (preg_match('/^(.+)\((\d+)\)$/', $c, $m)) {
                return "`{$m[1]}`({$m[2]})";
            }
            return "`$c`";
        }, $columns));
    }

    private function bareColumn(string $column): string
    {
        return preg_replace('/\(\d+\)$/', '', $column);
    }
};
