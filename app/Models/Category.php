<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Fillable attributes to protect against mass-assignment
    protected $fillable = [
        'name',
        'slug',
        'generated_slug',
        'img',
        'page_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'parent_id'
    ];

    // Relationship to parent category (one-to-many inverse)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relationship to subcategories (one-to-many)
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
