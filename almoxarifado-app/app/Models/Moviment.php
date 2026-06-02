<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produto;

class Moviment extends Model
{
    protected $fillabe = [
        'produto_id',
        'quantidade',
        'tipo',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class,'produto_id');
    }
}
