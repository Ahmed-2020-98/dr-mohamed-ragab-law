<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultationFormRequest;
use App\Models\ConsultationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    public function store(ConsultationFormRequest $request): RedirectResponse
    {
        $redirect = redirect()->to(route('contact').'#consultation-form')
            ->with('consultation_sent', true);

        // Honeypot: real visitors never see or fill this field.
        if (filled($request->input('website'))) {
            return $redirect;
        }

        $consultation = ConsultationRequest::create([
            ...$request->validated(),
            'status' => 'new',
            'ip' => $request->ip(),
        ]);

        $this->notify($consultation);

        return $redirect;
    }

    /** Email the office when a notification address is configured; never block the visitor on mail errors. */
    protected function notify(ConsultationRequest $consultation): void
    {
        $to = setting('notify_email');

        if (! $to) {
            return;
        }

        try {
            $lines = array_filter([
                'طلب استشارة جديد من الموقع',
                '',
                'الاسم: '.$consultation->name,
                'الهاتف: '.$consultation->phone,
                $consultation->email ? 'البريد: '.$consultation->email : null,
                $consultation->service ? 'الخدمة: '.$consultation->service->title : null,
                $consultation->message ? "\n".$consultation->message : null,
                '',
                url('/admin/consultation-requests'),
            ], fn ($line) => $line !== null);

            Mail::raw(implode("\n", $lines), fn ($mail) => $mail
                ->to($to)
                ->subject('طلب استشارة جديد — '.$consultation->name));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
