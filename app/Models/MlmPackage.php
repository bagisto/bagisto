<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'description',
        'commission_type', // percentage, fixed
        'commission_value',
        'level_1_rate',
        'level_2_rate',
        'level_3_rate',
        'level_4_rate',
        'level_5_rate',
        'max_levels',
        'min_purchase_amount',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'level_1_rate' => 'decimal:2',
        'level_2_rate' => 'decimal:2',
        'level_3_rate' => 'decimal:2',
        'level_4_rate' => 'decimal:2',
        'level_5_rate' => 'decimal:2',
        'max_levels' => 'integer',
        'min_purchase_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function commissions()
    {
        return $this->hasMany(MlmCommission::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Methods
    public function calculateCommission(float $amount, int $quantity = 1): float
    {
        $totalAmount = $amount * $quantity;

        if ($this->commission_type === 'percentage') {
            return $totalAmount * ($this->commission_value / 100);
        }

        return $this->commission_value * $quantity;
    }

    public function getLevelRate(int $level): float
    {
        $rates = [
            1 => $this->level_1_rate,
            2 => $this->level_2_rate,
            3 => $this->level_3_rate,
            4 => $this->level_4_rate,
            5 => $this->level_5_rate,
        ];

        return $rates[$level] ?? 0;
    }

    public function calculateLevelCommission(float $baseAmount, int $level): float
    {
        if ($level > $this->max_levels) {
            return 0;
        }

        $rate = $this->getLevelRate($level);
        return $baseAmount * ($rate / 100);
    }

    public function distributeCommissions(int $userId, float $amount, int $orderId): void
    {
        // This would implement the MLM commission distribution logic
        // Finding upline users and distributing commissions based on levels
        
        $currentUser = User::find($userId);
        $level = 1;

        while ($currentUser && $level <= $this->max_levels) {
            $upline = $this->getUpline($currentUser);
            
            if (!$upline) {
                break;
            }

            $commissionAmount = $this->calculateLevelCommission($amount, $level);

            if ($commissionAmount > 0) {
                MlmCommission::create([
                    'user_id' => $upline->id,
                    'downline_user_id' => $userId,
                    'mlm_package_id' => $this->id,
                    'order_id' => $orderId,
                    'level' => $level,
                    'commission_amount' => $commissionAmount,
                    'base_amount' => $amount,
                    'commission_rate' => $this->getLevelRate($level),
                    'status' => 'pending',
                ]);
            }

            $currentUser = $upline;
            $level++;
        }
    }

    private function getUpline(User $user): ?User
    {
        // Implement logic to get upline user
        // This would depend on your MLM structure (binary, unilevel, matrix, etc.)
        return null;
    }
}
