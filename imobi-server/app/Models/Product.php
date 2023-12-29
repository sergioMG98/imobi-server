<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['description','piece', 'surfaceTerrain', 'surface','salleDeBain','chambre','terrasse','cave','bilanEnergenique','longitude','latitude','user_id'];
}
