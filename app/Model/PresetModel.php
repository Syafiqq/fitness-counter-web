<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Carbon\Carbon $created_at
 * @property int $id
 * @property \Carbon\Carbon $updated_at
 */
class PresetModel extends Model
{
    const ID = 'id';
    const PRESET = 'preset';
    const PARTICIPANT = 'participant';

    protected $table = 'preset_queue';
    protected $primaryKey = 'id';
    protected $dates = [PresetModel::CREATED_AT];
    public $timestamps = false;
    protected $guarded = ['id', 'preset', 'participant'];
}
