<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UsersRequest extends Request
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
                            'username'=>'required|max:40|unique:zvue_admin_user,username',
                            'password'=>'required|max:200',
                            'realname'=>'required|max:200',
                            'structure_id'=>'required|max:200',
                            'post_id'=>'required|max:200',
                            'remark'=>'required|max:200',
                            'status'=>'Numeric|max:1',
                            'groups'=>'required',
                        ];

            case 'PUT':
                return [
                            'username'=>'required|max:40|unique:zvue_admin_user,username,'.getid($url),
                            'password'=>'max:200',
                            'realname'=>'required|max:200',
                            'structure_id'=>'required|max:200',
                            'post_id'=>'required|max:200',
                            'remark'=>'required|max:200',
                            'status'=>'Numeric|max:1',
                            'groups'=>'required',
                        ];



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