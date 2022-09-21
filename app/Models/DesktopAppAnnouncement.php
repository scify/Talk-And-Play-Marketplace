<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesktopAppAnnouncement extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'desktop_app_announcements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'severity', 'default_title', 'status', 'min_version','max_version'
    ];

    public function translations(): HasMany {
        return $this->hasMany(
            DesktopAppAnnouncementTranslation::class,
            'announcement_id',
            'id'
        );
    }
}
