<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

/**
 * @method static findOrFail(int $id)
 */
class BankAccounts extends Model
{
    protected $fillable = [
        'bank_name',
        'branch_name',
        'bank_code',
        'account_name',
        'account_number',
        'account_type',
        'currency',
        'opening_balance',
        'current_balance',
        'is_active',
        'remarks'
    ];

    /**
     * Boot method to hook into model events
     */
    protected static function booted(): void
    {
        // ✅ CREATE
        static::created(static function ($bankAccount) {
            self::logActivity('created', null, $bankAccount);
        });

        // ✅ UPDATE
        static::updated(static function ($bankAccount) {
            self::logActivity(
                'updated',
                $bankAccount->getOriginal(),
                $bankAccount->getChanges(),
                $bankAccount
            );
        });

        // ✅ DELETE
        static::deleted(static function ($bankAccount) {
            self::logActivity('deleted', $bankAccount->toArray(), null, $bankAccount);
        });
    }

    /**
     * Central logging function
     */
    protected static function logActivity($action, $oldValues = null, $newValues = null, $bankAccount = null): void
    {
        try {
            ActivityLog::create([
                'module'      => 'bank_account',
                'module_id'   => $bankAccount?->id,
                'action'      => $action,
                'user_id'     => Auth::id(),

                'old_values'  => $oldValues,
                'new_values'  => $newValues,

                'description' => self::generateDescription($action, $bankAccount),

                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),

                'status'      => $bankAccount?->is_active,
            ]);
        } catch (\Exception $e) {
            // Prevent breaking the app if logging fails
        }
    }

    /**
     * Generate human-readable description
     */
    protected static function generateDescription($action, $bankAccount): ?string
    {
        if (!$bankAccount) {
            return null;
        }

        return match ($action) {
            'created' => "Bank account {$bankAccount->account_name} ({$bankAccount->account_number}) created.",
            'updated' => "Bank account {$bankAccount->account_name} ({$bankAccount->account_number}) updated.",
            'deleted' => "Bank account {$bankAccount->account_name} ({$bankAccount->account_number}) deleted.",
            default   => "Bank account {$bankAccount->account_name} ({$bankAccount->account_number}) {$action}.",
        };
    }
}
