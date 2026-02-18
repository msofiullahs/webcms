<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'slug', 'content', 'template', 'layout_data',
        'status', 'meta_title', 'meta_description', 'is_homepage', 'sort_order',
    ];

    public array $translatable = ['title', 'content', 'meta_title', 'meta_description'];

    protected $casts = [
        'layout_data' => 'array',
        'is_homepage' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeHomepage($query)
    {
        return $query->where('is_homepage', true);
    }

    public function toArray()
    {
        $array = parent::toArray();
        foreach ($this->translatable as $field) {
            $array[$field] = $this->getTranslation($field, app()->getLocale());
        }
        return $array;
    }

    /**
     * Recursively find blocks of a given type, including those nested inside column blocks.
     */
    public static function findBlocksByType(array $blocks, string $type): array
    {
        $found = [];
        foreach ($blocks as $block) {
            if (($block['type'] ?? '') === $type) {
                $found[] = $block;
            }
            // Recurse into columns block
            if (($block['type'] ?? '') === 'columns' && !empty($block['data']['column_items'])) {
                foreach ($block['data']['column_items'] as $column) {
                    if (!empty($column['blocks'])) {
                        $found = array_merge($found, self::findBlocksByType($column['blocks'], $type));
                    }
                }
            }
        }
        return $found;
    }
}
