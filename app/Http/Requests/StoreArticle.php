<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreArticle extends FormRequest
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
     * Prepare the data for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug ?? $this->title) // If the slug isn't set, we take the title
        ]);

        if ($this->published) {
            $this->merge([
                'published_at' => new \DateTime(),
                'published' => 1
            ]);
        } else {
            $this->merge([
               'published' => 0,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'summary' => 'required',
            'content' => 'required',
            'published_at' => 'date',
            'published' => 'sometimes',
            'slug' => 'unique:articles,slug,' . (optional($this->article)->id) ?: 'NULL'
        ];
    }
}
