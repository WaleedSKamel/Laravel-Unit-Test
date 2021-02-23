<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author_id'];

    public function path()
    {
        return '/books/'.$this->id;
    }

    public function setAuthorIdAttribute($author)
    {
        $this->attributes['author_id'] = Author::firstOrCreate([
            'name' => $author
        ])->id;
    }

    public function checkout($user)
    {
        $this->reservations()->create([
            'user_id' => $user->id,
            'checked_out_at' => now(),
            'book_id' => $this->id,
        ]);
    }

    public function checkin($user)
    {
        $reservations = $this->reservations()->where('user_id', $user->id)
            ->whereNotNull('checked_out_at')
            ->whereNull('checked_in_at')
            ->first();
        if (is_null($reservations)){
            throw new \Exception();
        }
        $reservations->update([
           'checked_in_at' => now()
        ]);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }


}
