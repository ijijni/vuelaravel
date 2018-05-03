<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class GroupsRequest extends Request
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
                            'title'=>'required|max:40|unique:zvue_admin_group,title',
                            'pid'=>'Numeric',
                            'remark'=>'required|max:200',
                            'rules'=>'required'
                        ];

            case 'PUT':
                return [
                            'title'=>'required|max:40|unique:zvue_admin_group,title,'.getid($url),
                            'pid'=>'Numeric|not_in:'.getid($url),
                            'remark'=>'required|max:200',
                            'rules'=>'required'
                        ];



        }
        
    }

        public function messages(){
            return [
                        'title.unique' => '此分组名已经存在',
                        'pid.numeric' => '请选择父级用户组',
                        'pid.not_in' => '请选择父级用户组'
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