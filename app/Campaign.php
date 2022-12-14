<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    public function group(): HasOne 
    {
        return $this->hasOne(Group::class, 'campaign_id');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(ProductDiscount::class);
    }
}
