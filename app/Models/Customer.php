<?php

namespace App\Models;

use App\Models\Traits\HasToggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasToggle;

    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getWhatsappPhoneAttribute()
    {
        if (! $this->phone) {
            return '';
        }

        return '62'.$this->phone;
    }
}
