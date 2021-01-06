<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tshirt extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;

    const DEFAULT_IMAGE = 'default_tshirt.png';

    const PUBLIC_PATH = 'public/storage/tshirts';
    const STORAGE_PATH = 'public/tshirts';


    protected $fillable = [
        'name',
        'image',
        'status'
    ];

    protected $appends = [
        'thumbnail',
        'full_image'
    ];

    protected function getThumbnailAttribute()
    {
        if (Storage::disk('local')->exists(self::STORAGE_PATH . '/thumb_' . $this->image)) {
            return asset(self::PUBLIC_PATH . '/thumb_' . $this->image);
        } else {
            return asset(self::PUBLIC_PATH . '/thumb_' . self::DEFAULT_IMAGE);
        }
    }

    protected function getFullImageAttribute()
    {

        if (Storage::disk('local')->exists(self::STORAGE_PATH . '/full_' . $this->image)) {
            return asset(self::PUBLIC_PATH . '/full_' . $this->image);
        } else {
            return asset(self::PUBLIC_PATH . '/full_' . self::DEFAULT_IMAGE);
        }
    }


}
