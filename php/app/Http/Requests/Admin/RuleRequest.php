<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class RuleRequest extends Request
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
        $url = $this->url();
        switch($this->method()){
            case 'POST':
                return [
                            'title'=>'required|max:40',
                            'pid'=>'Numeric',
                            'name'=>'required',
                            'level'=>'required',
                        ];

            case 'PUT':
                return [
                            'title'=>'required|max:40',
                            'pid'=>'Numeric',
                            'name'=>'required',
                            'level'=>'required',
                        ];



        }
        
    }

        public function messages(){
            return [
                        'username.name' => '必填',
    ];
        
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        foreach ($errors->all() as $v) {
            $message = $v;
        }
        throw new HttpResponseException(response()->json(formatErrors($message), 200));

    }
}