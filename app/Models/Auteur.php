<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auteur extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'image_profil',
    ];
}
