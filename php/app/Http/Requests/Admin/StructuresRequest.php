<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StructuresRequest extends Request
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
                            'name'=>'required|max:40',
                            'pid'=>'Numeric',
                        ];

            case 'PUT':
                return [
                            'name'=>'required|max:40|unique:zvue_admin_post,name,'.getid($url),
                            'pid'=>'Numeric|not_in:'.getid($url),


        }
        
    }

        public function messages(){
            return [
                        'username.unique' => '此用户名已经存在',
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