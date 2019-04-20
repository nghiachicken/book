<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
class Book extends Model
{
    protected $table = 'books';
    protected $fillable = ['id','name','image'];


    public function categories()
    {
        return $this->belongsToMany(Category::class, "book_category", "book_id", "category_id")->withTimestamps();
    }
}