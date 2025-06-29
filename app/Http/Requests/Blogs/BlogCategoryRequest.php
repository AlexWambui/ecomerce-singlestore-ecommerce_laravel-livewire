<?php

namespace App\Http\Requests\Blogs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $blog_category = $this->route('blog_category');

        return [
            'title' => [
                'required',
                'string',
                'max:80',
                $blog_category ? Rule::unique('blog_categories', 'title')->ignoreModel($blog_category) : Rule::unique('blog_categories', 'title'),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.unique' => 'This title is already taken. Please choose another.',
            'image.image' => 'The uploaded file must be an image.',
            'image.max' => 'Image must be under 2MB.',
        ];
    }
}
