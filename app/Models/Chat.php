<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';
    protected $fillable = ['content', 'from'];
    use HasFactory;

    public function person(){
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

}
