<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Restaurant extends Model
{
    use HasFactory,Sortable;

    protected $fillable = [
        'content',
        'score',
        'restaurant_id',
        'user_id',
    ];

    public function categories() {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function regular_holidays() {
        return $this->belongsToMany(RegularHoliday::class)->withTimestamps();
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function ratingSortable($query, $direction) {
        return $query->withAvg('reviews', 'score')->orderBy('reviews_avg_score', $direction);
    }

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }
    
    public function popularSortable($query, $direction) {
        return $query->withCount('reservations')->orderBy('reservations_count', $direction);
}
}