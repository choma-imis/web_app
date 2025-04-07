<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'language.languages';

    protected $fillable = ['name', 'code', 'status'];


    public function translate()
    {
        return $this->hasMany(Translate::class, 'name', 'code');
    }
}
