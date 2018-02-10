<?php

namespace App\Http\Requests;

use App\Firebase\PopoMapper;
use App\Model\PresetModel;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class PresetQueueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            PresetModel::PRESET => 'required|size:20',
            PresetModel::PARTICIPANT => 'required'
        ];
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson())
        {
            throw new HttpResponseException(response()->json(PopoMapper::jsonResponse(422, 'Data is not valid', $validator->errors()->all()), 422));
        }
        else
        {
            parent::failedValidation($validator);
        }
    }
}
