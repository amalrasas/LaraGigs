<?php

namespace App\Models;

use Database\Factories\ListingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class listing extends Model
{
    protected $table = 'listing';
    protected $fillable = ['title', 'company', 'location', 'website', 'email', 'description', 'tags', 'user_id'];

    use HasFactory;
    protected static function newFactory()
    {
        return ListingFactory::new();
    }
    public function scopeFilter($query, array $filters)
    {
        if ($filters['tag'] ?? false) {
            $query->where('tags', 'like', '%' . request('tag') . '%');
        }
        if ($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')->orwhere('description', 'like', '%' . request('search') . '%')->orwhere('tags', 'like', '%' . request('search') . '%');
        }
    }
    // Relationship To User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
