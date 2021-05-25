<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'array'
    ];

    protected $dates = ['date'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }

    protected function validateUserHasEvent(User $user )
	{
		if( $user->events->contains( $this ) )
			return true;
		return false;
	}


    // public function delete( User $user )
	// {
	// 	if( ! $this->validateUserHasPost($user) )
	// 		throw new DomainException('User ' . $user->name . ' does not own this post. Therefore it cannot be deleted');

	// 	return parent::delete();
	// }	
}
