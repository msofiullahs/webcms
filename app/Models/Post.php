<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'featured_image',
        'status', 'published_at', 'author_id', 'meta_title', 'meta_description',
    ];

    public array $translatable = ['title', 'content', 'excerpt', 'meta_title', 'meta_description'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    public function toArray()
    {
        $array = parent::toArray();
        foreach ($this->translatable as $field) {
            $array[$field] = $this->getTranslation($field, app()->getLocale());
        }
        return $array;
    }
}
