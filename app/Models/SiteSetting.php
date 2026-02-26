<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';
    public $timestamps = true;

    protected $fillable = [
        'site_logo_path',
        'hero_bg_path',
        'seasonal_banner_enabled',
        'seasonal_banner_bg_color',
        'seasonal_banner_text_color',
        'seasonal_banner_message',
        'updated_by',
    ];
}
