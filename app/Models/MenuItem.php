<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class MenuItem extends Model
{
    use HasTranslations;

    protected $fillable = [
        'menu_id', 'parent_id', 'title', 'url', 'route_name',
        'type', 'target', 'icon', 'order',
    ];

    public array $translatable = ['title'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
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
