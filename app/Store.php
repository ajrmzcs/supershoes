<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address'
    ];

    /**
     * Get the articles in the store.
     */
    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    /**
     * Soft delete related articles
     */
    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {

            $model->load([ 'articles']);
            $model->articles()->delete();
        });
    }

}
