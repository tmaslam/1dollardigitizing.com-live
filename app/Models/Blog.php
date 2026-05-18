<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content',
        'hero_image', 'hero_image_alt',
        'author_name', 'category', 'tags',
        'status', 'meta_title', 'meta_description', 'og_image',
        'published_at', 'date', 'decription', 'attached_file', 'end_date',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function isPublished(): bool
    {
        return $this->status === 'published'
            && ($this->published_at === null || $this->published_at->isPast());
    }

    public function heroImageUrl(): ?string
    {
        if (! $this->hero_image) {
            return null;
        }
        return str_starts_with($this->hero_image, 'http')
            ? $this->hero_image
            : url('storage/'.$this->hero_image);
    }

    public function ogImageUrl(): ?string
    {
        if ($this->og_image) {
            return str_starts_with($this->og_image, 'http')
                ? $this->og_image
                : url('storage/'.$this->og_image);
        }
        return $this->heroImageUrl();
    }

    public function getTagsArray(): array
    {
        if (! $this->tags) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $this->tags))));
    }

    public function readableDate(): string
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->format('F j, Y') : '';
    }

    public function publicUrl(): string
    {
        return url('/blog/'.($this->slug ?: $this->id));
    }

    public static function generateSlug(string $title, ?int $excludeId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $count = 1;

        while (true) {
            $query = static::where('slug', $slug);
            if ($excludeId !== null) {
                $query->where('id', '!=', $excludeId);
            }
            if (! $query->exists()) {
                break;
            }
            $slug = $base.'-'.$count++;
        }

        return $slug;
    }
}
