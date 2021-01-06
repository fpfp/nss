<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Design extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;

    const PUBLIC_PATH = 'storage/designs';
    const STORAGE_PATH = 'public/designs';

    protected $fillable = [
        'name',
        'hash',
        'status',
        'tshirt_id',
    ];

    protected $appends = [
        'thumbnail', 
        'mid_image', 
        'full_image'
    ];

    protected function getThumbnailAttribute()
    {
        if (Storage::disk('local')->exists(self::STORAGE_PATH . '/'.$this->hash.'/thumb.png')) {
            return asset(self::PUBLIC_PATH . '/'.$this->hash.'/thumb.png');
        } else {
            return asset(self::PUBLIC_PATH . '/thumb_default.png');
        }
    }

    protected function getMidImageAttribute()
    {

        if (Storage::disk('local')->exists(self::STORAGE_PATH . '/'.$this->hash.'/mid.png')) {
            return asset(self::PUBLIC_PATH . '/'.$this->hash.'/mid.png');
        } else {
            return asset(self::PUBLIC_PATH . '/mid_default.png');
        }
    }

    protected function getFullImageAttribute()
    {

        if (Storage::disk('local')->exists(self::STORAGE_PATH . '/'.$this->hash.'/full.png')) {
            return asset(self::PUBLIC_PATH . '/'.$this->hash.'/full.png');
        } else {
            return asset(self::PUBLIC_PATH . '/full_default.png');
        }
    }

    public function tshirt()
    {
        return $this->belongsTo(Tshirt::class);
    }

    
}
