<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteSources extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'source_id',
    ];


    // public function source() {
    //     return $this->hasOne(Sources::class, 'id', 'source_id');
    // }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['source'];

    /**
     * The appended attributes shown in JSON results.
     *
     * @var array
     */
    protected $appends = ['name'];

    /**
     * The username attribute accessor for JSON results.
     *
     * @var string
     */
    public function getSourcesAttribute()
    {
        return $this->sources->name;
    }
}
