<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\RolePermission;
use App\Models\UserPermission;
use App\Models\RankHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'custom_fields' => 'array',
        'status' => 'integer',
        'position' => 'integer',
        // Date-only fields, so the forms can bind them with format('Y-m-d')
        // without every caller having to parse strings.
        'date_of_birth' => 'date',
        'marriage_anniversary' => 'date',
        'nominee_dob' => 'date',
    ];

    protected $fillable = [
        'sponsor_id', 'u_sponsor', 'user_wallet_address', 'placement_id', 'placement_side',
        'parent_id', 'position', 'left_id', 'right_id', 'left_count', 'right_count',
        'name', 'username', 'email', 'password', 'profile_image', 'status', 'rank',
        'rank_id', 'deposit_status', 'last_mine_at', 'activate_date', 'email_verified_at',
        'role', 'custom_fields', 'join_date', 'rankrise_status', 'royalty_rank_id',
        'rank_achieved_on', 'income_status', 'royalty', 'bonus_status', 'bonus_hit_date',
        'capping_hit_date', 'last_login_ip', 'last_login_location', 'last_login_at',
        'otp', 'otp_expires_at', 'is_free_id',

        // --- Newly added native registration fields ---
        'title',
        'first_name',
        'last_name',
        'gender',
        'address',
        'state',
        'city',
        'mobile',
        'pan',
        'aadhaar',
        'wallet_address',
        'unique_name',

        // --- Member-maintained profile fields (My Profile) ---
        // Nothing here overlaps KYC: PAN, Aadhaar, address and the bank block
        // live in kyc_details and stay read-only on the profile page.
        'middle_name',
        'date_of_birth',
        'marriage_anniversary',
        'profession',
        'nationality',
        'country',
        'district',
        'pin_code',
        'alternate_mobile',
        'std_code',
        'phone_number',

        // --- Nominee ---
        'nominee_title',
        'nominee_first_name',
        'nominee_middle_name',
        'nominee_last_name',
        'nominee_gender',
        'nominee_email',
        'nominee_mobile',
        'nominee_dob',
        'nominee_relation',
    ];

    // app/Models/User.php

    /**
     * Per-request permission caches.
     *
     * The admin layout alone calls hasPermission() ~57 times, and each call
     * used to be its own SELECT. Both tables are small, so the whole set for a
     * role (or subadmin user) is loaded once and answered from memory after
     * that.
     *
     * @var array<string, \Illuminate\Support\Collection>
     */
    protected static array $rolePermissionCache = [];

    /** @var array<int, \Illuminate\Support\Collection> */
    protected static array $userPermissionCache = [];

    public function hasPermission($feature, $permission, $role = null)
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $role = $role ?? $user->role ?? 'user';

        /**
         * 1️⃣ USER PERMISSIONS — ONLY FOR SUBADMIN
         */
        // Keys are lower-cased on both sides. The columns are utf8mb4_unicode_ci,
        // so the original per-call query matched case-insensitively; a plain PHP
        // array lookup does not, which silently hid menus whose blade spelling
        // differed from the stored row (e.g. 'Product' vs 'product').
        $needle = mb_strtolower(trim((string) $feature));

        if ($role === 'subadmin') {
            if (!isset(self::$userPermissionCache[$user->id])) {
                self::$userPermissionCache[$user->id] = self::keyByLower(
                    UserPermission::where('user_id', $user->id)->orderBy('id')->get(),
                    'permission_slug'
                );
            }

            $userPerm = self::$userPermissionCache[$user->id]->get($needle);

            if ($userPerm) {
                return (int) $userPerm->$permission === 1;
            }
        }

        /**
         * 2️⃣ ROLE PERMISSIONS — FOR ALL ROLES
         */
        if (!isset(self::$rolePermissionCache[$role])) {
            self::$rolePermissionCache[$role] = self::keyByLower(
                RolePermission::where('role', $role)->orderBy('id')->get(),
                'feature'
            );
        }

        $rolePerm = self::$rolePermissionCache[$role]->get($needle);

        if ($rolePerm) {
            return (int) $rolePerm->$permission === 1;
        }

        /**
         * 3️⃣ DEFAULT DENY
         */
        return false;
    }

    /**
     * Index rows by a lower-cased column, keeping the FIRST row when two
     * differ only by case - which is what the old ->first() query returned.
     */
    private static function keyByLower($rows, string $column): \Illuminate\Support\Collection
    {
        $out = collect();

        foreach ($rows as $row) {
            $key = mb_strtolower(trim((string) $row->{$column}));
            if (!$out->has($key)) {
                $out->put($key, $row);
            }
        }

        return $out;
    }

    /**
     * Drop the cached permission sets. Call after changing permissions so the
     * same request sees the new values.
     */
    public static function flushPermissionCache(): void
    {
        self::$rolePermissionCache = [];
        self::$userPermissionCache = [];
    }

    public function wallet()
    {
        return $this->hasOne(UserWallet::class, 'u_code', 'id'); // u_code references user id
    }

    /**
     * The user's KYC record.
     *
     * This relation was missing, so $user->kycDetail silently resolved to null
     * everywhere it was used - which made the header treat every user as having
     * no KYC at all and blink the menu red even after approval.
     */
    public function kycDetail(): HasOne
    {
        return $this->hasOne(KycDetail::class, 'user_id', 'id');
    }

    public function rankHistories()
    {
        return $this->hasMany(RankHistory::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'tx_user_id', 'id');
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id', 'username');
    }

    public function sponsorByUsername(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sponsor_id', 'username');
    }

    public function sponsorByid(): BelongsTo
    {
        return $this->belongsTo(User::class, 'u_sponsor', 'id');
    }

    // Parent (binary placement)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Direct children in binary tree
    public function leftChild(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'left_id');
    }

    public function rightChild(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'right_id');
    }

    public function hasLeft(): bool
    {
        return !is_null($this->left_id);
    }

    public function hasRight(): bool
    {
        return !is_null($this->right_id);
    }

    public function canPlaceOn(string $side): bool
    {
        return $side === 'L' ? !$this->hasLeft() : !$this->hasRight();
    }

    /**
     * Get the user this user is placed under.
     */
    public function placement(): BelongsTo
    {
        return $this->belongsTo(User::class, 'placement_id');
    }

    /**
     * Get the downline members placed directly under this user.
     */
    public function children(): HasMany
    {
        return $this->hasMany(User::class, 'placement_id');
    }

    /**
     * Get the bonus history for the user.
     */
    public function history(): HasMany
    {
        return $this->hasMany(MatchingBonusHistory::class, 'user_id');
    }

    public function rankHistory(): HasMany
    {
        return $this->hasMany(RankHistory::class, 'user_id', 'id');
    }
}