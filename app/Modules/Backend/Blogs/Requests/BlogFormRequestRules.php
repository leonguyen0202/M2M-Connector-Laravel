<?php

namespace App\Modules\Backend\Blogs\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class BlogFormRequestRules extends FormRequest
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
    public function rules($type, $prefix)
    {
        if (strtolower($type) == 'create') {
            $rules = [
                $prefix . '_title' => ['required', 'string', 'min:10', 'max:100', 'unique:blogs'],
                $prefix . '_description' => ['required'],
            ];
    
            if ($prefix == Config::get('app.fallback_locale')) {
    
                $rules['categories'] = ['required', 'array'];
            }
        } else {

        }
        

        return $rules;
    }
}
