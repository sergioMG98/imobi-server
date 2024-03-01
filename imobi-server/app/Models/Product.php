<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Picture;
use App\Models\Agenda;
use App\Models\Caracteristique;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['status', 'prix', 'description', 'surface', 'ges', 'dpe', 'type', 'piece', 'surfaceTerrain', 'salleDeBain', 'chambre', 'terrasse', 'balcon', 'garage', 'piscine', 'ascenseur', 'cave', 'longitude', 'latitude','ville', 'user_id', 'label'];

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function caracteristique(): HasOne
    {
        return $this->hasOne(Caracteristique::class);
    }

/*     public function picture(): BelongsToMany
    {
        return $this->belognsToMany(Picture::class);
    } */


}
