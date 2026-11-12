<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[+\d\x{0660}-\x{0669}][\d\x{0660}-\x{0669}\s\-()]{6,28}$/u'],
            'email' => ['nullable', 'email', 'max:160'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'message' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'من فضلك اكتب اسمك.',
            'name.min' => 'الاسم قصير جدًا.',
            'name.max' => 'الاسم طويل جدًا.',
            'phone.required' => 'من فضلك اكتب رقم الهاتف لنتمكن من التواصل معك.',
            'phone.regex' => 'رقم الهاتف غير صحيح. مثال: 01004312938',
            'phone.max' => 'رقم الهاتف غير صحيح.',
            'email.email' => 'البريد الإلكتروني غير صحيح.',
            'service_id.exists' => 'الخدمة المختارة غير متاحة.',
            'message.max' => 'الرسالة طويلة جدًا (الحد الأقصى 3000 حرف).',
        ];
    }
}
