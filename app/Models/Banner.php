<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use App\Scopes\ZoneScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class Banner
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property string|null $image
 * @property bool $status
 * @property string $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $zone_id
 * @property int $module_id
 * @property bool $featured
 * @property string|null $default_link
 * @property string $created_by
 */
class Banner extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'common_banner_id',
        'title_one',
        'title_two',
        'type',
        'image',
        'status',
        'featured',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'store_id' => 'integer',
        'data' => 'integer',
        'status' => 'boolean',
        'featured' => 'boolean',
    ];

    protected $appends = ['image_full_url'];

    public function storage()
    {
        return $this->morphMany(Storage::class, 'data');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'data');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query): mixed
    {
        return $query->where('status', '=', 1)->whereHas('store', function ($query) {
            $query->active();
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeFeatured($query): mixed
    {
        return $query->where('featured', '=', 1);
    }

    public function getImageFullUrlAttribute(){
        $value = $this->image;
        if (count($this->storage) > 0) {
            foreach ($this->storage as $storage) {
                if ($storage['key'] == 'image') {
                    return Helpers::get_full_url('banner',$value,$storage['value']);
                }
            }
        }

        return Helpers::get_full_url('banner',$value,'public');
    }

    /**
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope('storage', function ($builder) {
            $builder->with('storage');
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            Helpers::deleteCacheData('banners_');

            if($model->isDirty('image')){
                $value = Helpers::getDisk();

                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => 'image',
                ], [
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
        static::created(function () {
            Helpers::deleteCacheData('banners_');
        });
        static::deleted(function(){
            Helpers::deleteCacheData('banners_');
        });

        static::updated(function(){
            Helpers::deleteCacheData('banners_');
        });

    }
}
