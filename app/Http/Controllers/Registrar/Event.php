<?php

namespace App\Http\Controllers\Registrar;

use App\Firebase\PopoMapper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PresetQueueRequest;
use App\Model\PresetModel;
use Illuminate\Database\Query\Builder;

class Event extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOverview($event)
    {
        $this->meta['event'] = $event;

        return view("layout.registrar.event.overview.registrar_event_overview_{$this->theme}", ['meta' => $this->meta]);
    }

    public function postQueueAddApi(PresetQueueRequest $request)
    {
        /** @var PresetModel|Builder $model */
        $model         = new PresetModel();
        $currentPreset = $model
            ->where(PresetModel::PRESET, $request->get('preset', '-'))
            ->where(PresetModel::PARTICIPANT, $request->get('participant', '-'))
            ->first();
        if (is_null($currentPreset))
        {
            $model->setAttribute(PresetModel::PRESET, $request->get('preset', '-'));
            $model->setAttribute(PresetModel::PARTICIPANT, $request->get('participant', '-'));
            $model->save();
            $currentPreset = $model;
        }

        return response()->json(PopoMapper::jsonResponse(200, 'success', ['queue' => $model->where(PresetModel::PRESET, $request->get('preset', '-'))->where(PresetModel::ID, '<=', $currentPreset->getAttribute(PresetModel::ID))->count()]), 200);
    }
}
