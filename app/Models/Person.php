<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people';
    protected $fillable = ['email','firstname','lastname'];
    use HasFactory;

    public function chats(){
        return $this->hasMany(Chat::class, 'person_id', 'id');
    }

}
