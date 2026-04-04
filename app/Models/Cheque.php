<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @method static latest()
 * @method static create(array $data)
 * @method static count()
 * @method static where(string $string, string $string1)
 */
class Cheque extends Model
{
    protected $fillable = [
        'cheque_no',
        'cheque_date',
        'cheque_exp_date',
        'cheque_amount',
        'cheque_type_cross_cheque',
        'bank_account_id',
        'bank_name',
        'branch_name',
        'company_name',
        'cheque_type',
        'status',
        'deposit_date',
        'realization_date',
        'bounce_date',
        'customer_id',
        'supplier_id',
        'invoice_id',
        'received_by',
        'deposited_by',
        'approved_by',
        'bank_charges',
        'penalty_amount',
        'remarks',
        'reference_no'
    ];

    /**
     * Boot method to hook into model events
     */
    protected static function booted(): void
    {
        // ✅ CREATE
        static::created(static function ($cheque) {
            self::logActivity('created', null, $cheque);
        });

        // ✅ UPDATE
        static::updated(static function ($cheque) {
            self::logActivity(
                'updated',
                $cheque->getOriginal(),
                $cheque->getChanges(),
                $cheque
            );
        });

        // ✅ DELETE (soft delete)
        static::deleted(static function ($cheque) {
            self::logActivity('deleted', $cheque->toArray(), null, $cheque);
        });
    }

    /**
     * Central logging function
     */
    protected static function logActivity($action, $oldValues = null, $newValues = null, $cheque = null): void
    {
        try {
            ActivityLog::create([
                'module'      => 'cheque',
                'module_id'   => $cheque?->id,
                'action'      => $action,
                'user_id'     => Auth::id(),

                'old_values'  => $oldValues,
                'new_values'  => $newValues,

                'description' => self::generateDescription($action, $cheque),

                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),

                'status'      => $cheque?->status,
            ]);
        } catch (\Exception $e) {
            // Avoid breaking the app if logging fails
        }
    }

    /**
     * Generate human-readable description
     */
    protected static function generateDescription($action, $cheque): ?string
    {
        if (!$cheque) {
            return null;
        }

        return match ($action) {
            'created' => "Cheque {$cheque->cheque_no} created.",
            'updated' => "Cheque {$cheque->cheque_no} updated.",
            'deleted' => "Cheque {$cheque->cheque_no} deleted.",
            default   => "Cheque {$cheque->cheque_no} {$action}.",
        };
    }
}
