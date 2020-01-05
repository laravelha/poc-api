<?php

namespace App;

use Laravelha\Support\Traits\RequestQueryBuildable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Post extends Model
{
    use RequestQueryBuildable;

    protected $guarded = ['id'];

    public function setPublishedAtAttribute($value)
    {
        $this->attributes['published_at'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function getPublishedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    
    /**
     * Get the category of Post.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @inheritDoc
     */
    public static function searchable(): array
    {
        return [
            'id' => '=',
            'title' => 'like',
            'content' => 'like',
            'category_id' => 'like',
        ];
    }

}
