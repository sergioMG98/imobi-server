<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['date','startVisit', 'endVisit' ,'adresse','title', 'lastnameVisitor', 'firstnameVisitor', 'phoneVisitor', 'phoneOwner', 'user_id'];
}
