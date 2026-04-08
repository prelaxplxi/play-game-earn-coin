<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameCategory extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function games()
    {
        return $this->hasMany(Game::class, 'category_id');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
