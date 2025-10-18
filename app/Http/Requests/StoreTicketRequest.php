<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
            'name'    => ['required','string','max:255'],
            'email'   => ['required','email','max:255'],
            'phone'   => ['required','regex:/^\+?[1-9]\d{1,14}$/'], // E.164
            'subject' => ['required','string','max:255'],
            'message' => ['required','string','max:5000'],
            'attachments.*' => ['file','mimes:jpg,jpeg,png,pdf,txt,doc,docx','max:5120'],
        ];
    }
}
