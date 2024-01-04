<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristique extends Model
{
    use HasFactory;
    protected $fillable = ['description','piece', 'surfaceTerrain', 'surface','salleDeBain','chambre','terrasse','cave','bilanEnergetique','product_id'];
}
