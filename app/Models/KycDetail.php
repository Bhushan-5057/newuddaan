<?php
// app/Models/KycDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'mobile',
        'state',
        'address',
        'profile_pic',
        'attached_doc',
        'tax_id',
        'front_image',
        'back_image',
        'pan_no',
        'front_image_pan',
        'account_holder_name',
        'bank_name',
        'account_no',
        'ifsc_code',
        'bank_branch',
        'front_image_bank',
        'kyc_status_personal',
        'kyc_status_identity',
        'kyc_status_pan',
        'kyc_status_bank',
        'personal_remark',
        'kyc_remark',
        'identity_remark',
        'pan_remark',
        'bank_remark',
    ];

    /**
     * KYC sections the member can actually submit.
     *
     * 'personal' is deliberately absent: its tab is commented out on the KYC
     * page, so kyc_status_personal can never leave 'pending'. Counting it made
     * every user look permanently pending and left the admin "Approved" list
     * empty. Re-enable the tab and add 'personal' back here together.
     */
    public const ACTIVE_SECTIONS = ['identity', 'pan', 'bank'];

    /**
     * Status of each active section, e.g. ['identity' => 'approved', ...].
     *
     * @return array<string, string|null>
     */
    public function sectionStatuses(): array
    {
        $out = [];
        foreach (self::ACTIVE_SECTIONS as $section) {
            $out[$section] = $this->{"kyc_status_{$section}"};
        }

        return $out;
    }

    /**
     * One word for the whole KYC: rejected > submitted > pending > approved.
     */
    public function overallState(): string
    {
        $statuses = array_values($this->sectionStatuses());

        if (in_array('rejected', $statuses, true)) {
            return 'rejected';
        }
        if (in_array('submitted', $statuses, true)) {
            return 'submitted';
        }
        if (in_array('pending', $statuses, true) || in_array(null, $statuses, true)) {
            return 'pending';
        }

        return 'approved';
    }

    /**
     * Constrain a query to rows where any active section has $status.
     */
    public function scopeWhereAnySection($query, string $status)
    {
        return $query->where(function ($q) use ($status) {
            foreach (self::ACTIVE_SECTIONS as $section) {
                $q->orWhere("kyc_status_{$section}", $status);
            }
        });
    }

    /**
     * Constrain a query to rows where every active section has $status.
     */
    public function scopeWhereAllSections($query, string $status)
    {
        foreach (self::ACTIVE_SECTIONS as $section) {
            $query->where("kyc_status_{$section}", $status);
        }

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}