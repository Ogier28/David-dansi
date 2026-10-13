@php
    $locale = $contactMessage->locale;
@endphp

<x-mail::message>
# {{ __('mail.contact.heading', [], $locale) }}

{{ __('mail.contact.intro', ['date' => $contactMessage->created_at->translatedFormat('d F Y, H:i')], $locale) }}

<x-mail::table>
| | |
|:--- |:--- |
| **{{ __('contact.form.name', [], $locale) }}** | {{ $contactMessage->name }} |
| **{{ __('contact.form.email', [], $locale) }}** | {{ $contactMessage->email }} |
| **{{ __('contact.form.subject', [], $locale) }}** | {{ $contactMessage->subjectLabel() }} |
| **{{ __('mail.contact.locale', [], $locale) }}** | {{ strtoupper($contactMessage->locale) }} |
</x-mail::table>

**{{ __('contact.form.message', [], $locale) }}**

{{ $contactMessage->message }}

<x-mail::button :url="'mailto:'.$contactMessage->email">
{{ __('mail.contact.reply', [], $locale) }}
</x-mail::button>

{{ __('mail.contact.footer', ['id' => $contactMessage->id], $locale) }}
</x-mail::message>
