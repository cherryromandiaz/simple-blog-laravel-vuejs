<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
	
	protected $primaryKey = 'post_id';
	public $timestamps = true;
	
    protected $fillable = [
        'title',
        'content',
        'user_id', // Foreign key referencing users table
        'status',
		'likes',
		'dislikes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
