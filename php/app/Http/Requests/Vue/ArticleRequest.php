<?php

namespace App\Http\Requests\Vue;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class ArticleRequest extends Request
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
        switch($this->method()){
            case 'POST':
                return [
                            'title'=>'required|max:40|unique:zvue_vue_article,title',
                            'note'=>'required|max:200',
                            'content'=>'required',
                        ];

            case 'PUT':
                return [
                            'title'=>'required|max:40|unique:zvue_vue_article,title,'.$this->get('id'),
                            'note'=>'required|max:200',
                            'content'=>'required',
                        ];



        }
        
    }

        public function messages(){
            return [
        'title.unique' => '文章标题已存在',
        
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
