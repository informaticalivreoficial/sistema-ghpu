<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PropertyGb extends Model
{
    use HasFactory;

    protected $table = 'property_gbs'; 
    
    protected $fillable = [
        'property',
        'path',
        'cover',
        'watermark'
    ];

    /**
     * Accerssors and Mutators
    */
    public function getUrlImageAttribute()
    {
        return Storage::url($this->path);
    }

    public function setWatermarkAttribute($value)
    {
        $this->attributes['watermark'] = ($value == true || $value == '1' ? 1 : 0);
    }
}
