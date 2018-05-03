<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class MenuRequest extends Request
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
                            'rule_id'=>'required',
                            'menu_type'=>'required',
                            'rule_id'=>'required',
                            // 'url'=>'required',
                            'module'=>'required',
                            // 'menu'=>'required',
                            // 'sort'=>'required',
                            
                        ];

            case 'PUT':
                return [
                            'title'=>'required|max:40',
                            'pid'=>'Numeric',
                            'rule_id'=>'required',
                            'menu_type'=>'required',
                            'rule_id'=>'required',
                            // 'url'=>'required',
                            'module'=>'required',
                            // 'menu'=>'required',
                            // 'sort'=>'required',
                        ];



        }
        
    }

        public function messages(){
            return [
                        'title.require' => '标题必填',
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