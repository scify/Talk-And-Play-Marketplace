<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DesktopAppAnnouncementTranslation extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'desktop_app_announcement_translations';

    protected $primaryKey = ['announcement_id', 'lang_id'];

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'announcement_id', 'lang_id',
        'title', 'message', 'link',
    ];

    // the following 2 methods are required when setting a composite promary key,
    // since Laravel does not support it out of the box.
    protected function setKeysForSaveQuery($query) {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param  mixed  $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null) {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    public function language(): HasOne {
        return $this->hasOne(ContentLanguageLkp::class, 'id', 'lang_id');
    }
}
