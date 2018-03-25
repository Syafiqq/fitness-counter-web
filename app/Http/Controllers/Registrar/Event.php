<?php

namespace App\Http\Controllers\Registrar;

use App\Firebase\DataMapper;
use App\Firebase\FirebaseConnection;
use App\Firebase\PopoMapper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PresetQueueRequest;
use App\Model\PresetModel;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

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

    /**
     * @param FirebaseConnection $firebase
     * @param PresetQueueRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postQueueAddApi(FirebaseConnection $firebase, PresetQueueRequest $request)
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
            $model->setAttribute(PresetModel::CREATED_AT, $request->get('stamp', Carbon::now()->format('Y-m-d')));
            $model->save();
            $currentPreset = $model;
        }
        else if (!$currentPreset->getAttribute(PresetModel::CREATED_AT)->isSameDay(Carbon::createFromFormat('Y-m-d', $request->get('stamp'))))
        {
            $currentPreset->setAttribute(PresetModel::ID, -1);
        }

        $queue = $model
            ->where(PresetModel::PRESET, $request->get('preset', '-'))
            ->where(PresetModel::ID, '<=', $currentPreset->getAttribute(PresetModel::ID))
            ->whereDate(PresetModel::CREATED_AT, '=', $request->get('stamp'))
            ->count();

        $participant          = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::event(null, null, $request->get('event', '-'))['events'] . "/participant/" . $request->get('participant', '-'))
            ->getValue() ?: [];
        $participant['queue'] = $queue;

        return response()->json(PopoMapper::jsonResponse(200, 'success', $participant), 200);
    }
}
