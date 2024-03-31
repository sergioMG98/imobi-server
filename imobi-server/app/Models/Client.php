<?php

namespace App\Models;

use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    use HasFactory;
    
    protected $fillable = ['lastname','firstname','email','phone', 'user_id' ];

    public function customerProduct(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

}
