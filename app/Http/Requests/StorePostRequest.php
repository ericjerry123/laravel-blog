<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array|max:5',
            'tags.*' => 'string|exists:tags,name',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '標題是必填的',
            'content.required' => '內容是必填的',
            'category_id.exists' => '選擇的分類不存在',
            'tags.array' => '標籤必須是數組格式',
            'tags.max' => '最多只能選擇5個標籤',
            'tags.*.exists' => '選擇的標籤不存在',
        ];
    }
}
