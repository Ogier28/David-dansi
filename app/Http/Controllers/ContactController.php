<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request): RedirectResponse
    {
        $message = ContactMessage::create([
            ...$request->safe()->only(['name', 'email', 'subject', 'message']),
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
            'user_agent' => str($request->userAgent())->limit(250)->toString(),
        ]);

        // Le message est enregistré avant l'envoi : une panne du serveur de
        // courrier ne doit pas faire perdre la demande du visiteur.
        try {
            Mail::to(config('portfolio.contact.recipient'))->send(new ContactMessageReceived($message));
        } catch (\Throwable $exception) {
            Log::error('Notification de contact non envoyée.', [
                'contact_message_id' => $message->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()
                ->withFragment('contact')
                ->with('contact.failure', __('contact.failure', [
                    'email' => config('portfolio.contact.recipient'),
                ]));
        }

        return back()
            ->withFragment('contact')
            ->with('contact.success', __('contact.success'));
    }
}
