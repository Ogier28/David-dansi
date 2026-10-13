@php
    $recipient = config('portfolio.contact.recipient');
@endphp

<x-ui.glass raised class="p-6 sm:p-8" x-data="{ sending: false }">
    @if (session('contact.success'))
        <div
            class="mb-6 flex items-start gap-3 rounded-tile border border-gold/30 bg-gold/8 p-4 text-[0.9375rem] text-gold-soft"
            role="status"
        >
            <x-icon name="check" :size="18" class="mt-0.5 shrink-0" />
            <p>{{ session('contact.success') }}</p>
        </div>
    @endif

    @if (session('contact.failure'))
        <div
            class="mb-6 flex items-start gap-3 rounded-tile border border-red-400/30 bg-red-400/8 p-4 text-[0.9375rem] text-red-200"
            role="alert"
        >
            <x-icon name="close" :size="18" class="mt-0.5 shrink-0" />
            <p>{{ session('contact.failure') }}</p>
        </div>
    @endif

    @error('form_token')
        <div class="mb-6 rounded-tile border border-red-400/30 bg-red-400/8 p-4 text-[0.9375rem] text-red-200" role="alert">
            <p>{{ $message }}</p>
        </div>
    @enderror

    @error('website')
        <div class="mb-6 rounded-tile border border-red-400/30 bg-red-400/8 p-4 text-[0.9375rem] text-red-200" role="alert">
            <p>{{ $message }}</p>
        </div>
    @enderror

    <form
        method="POST"
        action="{{ localized_route('contact.store') }}"
        @submit="sending = true"
        class="flex flex-col gap-5"
        novalidate
    >
        @csrf
        <input type="hidden" name="form_token" value="{{ $formToken }}">

        {{--
            Honeypot : hors flux, hors tabulation et masqué des lecteurs d'écran.
            Un humain ne le voit pas, un robot le remplit.
        --}}
        <div class="absolute -left-[9999px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
            <label for="website">{{ __('contact.form.honeypot_label') }}</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" value="">
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="contact-name" class="mb-2 block text-sm font-medium text-ink">
                    {{ __('contact.form.name') }}
                    <span class="text-ink-faint">({{ __('contact.form.required') }})</span>
                </label>
                <input
                    type="text"
                    id="contact-name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="{{ __('contact.form.name_placeholder') }}"
                    autocomplete="name"
                    required
                    maxlength="120"
                    class="field"
                    @error('name') aria-invalid="true" aria-describedby="contact-name-error" @enderror
                >
                @error('name')
                    <p id="contact-name-error" class="mt-2 text-[0.8125rem] text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-email" class="mb-2 block text-sm font-medium text-ink">
                    {{ __('contact.form.email') }}
                    <span class="text-ink-faint">({{ __('contact.form.required') }})</span>
                </label>
                <input
                    type="email"
                    id="contact-email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="{{ __('contact.form.email_placeholder') }}"
                    autocomplete="email"
                    required
                    maxlength="190"
                    class="field"
                    @error('email') aria-invalid="true" aria-describedby="contact-email-error" @enderror
                >
                @error('email')
                    <p id="contact-email-error" class="mt-2 text-[0.8125rem] text-red-300">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="contact-subject" class="mb-2 block text-sm font-medium text-ink">
                {{ __('contact.form.subject') }}
                <span class="text-ink-faint">({{ __('contact.form.required') }})</span>
            </label>
            <select
                id="contact-subject"
                name="subject"
                required
                class="field appearance-none bg-[length:1rem] bg-[right_0.9rem_center] bg-no-repeat pr-10"
                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2378839A' stroke-width='2' stroke-linecap='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E&quot;)"
                @error('subject') aria-invalid="true" aria-describedby="contact-subject-error" @enderror
            >
                <option value="" disabled @selected(! old('subject'))>{{ __('contact.form.subject_placeholder') }}</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject['value'] }}" @selected(old('subject') === $subject['value'])>
                        {{ $subject['label'] }}
                    </option>
                @endforeach
            </select>
            @error('subject')
                <p id="contact-subject-error" class="mt-2 text-[0.8125rem] text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contact-message" class="mb-2 block text-sm font-medium text-ink">
                {{ __('contact.form.message') }}
                <span class="text-ink-faint">({{ __('contact.form.required') }})</span>
            </label>
            <textarea
                id="contact-message"
                name="message"
                rows="6"
                required
                minlength="20"
                maxlength="4000"
                placeholder="{{ __('contact.form.message_placeholder') }}"
                class="field resize-y"
                @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror
            >{{ old('message') }}</textarea>
            @error('message')
                <p id="contact-message-error" class="mt-2 text-[0.8125rem] text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contact-consent" class="flex cursor-pointer items-start gap-3 text-[0.875rem] leading-relaxed text-ink-muted">
                <input
                    type="checkbox"
                    id="contact-consent"
                    name="consent"
                    value="1"
                    required
                    @checked(old('consent'))
                    class="mt-0.5 h-4 w-4 shrink-0 rounded-[4px] border border-white/20 bg-white/5 accent-gold"
                    @error('consent') aria-invalid="true" aria-describedby="contact-consent-error" @enderror
                >
                <span>
                    {{ __('contact.form.consent') }}
                    <span class="mt-1 block text-[0.8125rem] text-ink-faint">{{ __('contact.form.consent_note') }}</span>
                </span>
            </label>
            @error('consent')
                <p id="contact-consent-error" class="mt-2 text-[0.8125rem] text-red-300">{{ $message }}</p>
            @enderror
        </div>

        @if ($turnstileSiteKey)
            {{-- Turnstile en mode « interaction-only » : rien ne s'affiche tant que le visiteur n'est pas suspect. --}}
            <div>
                <div
                    class="cf-turnstile"
                    data-sitekey="{{ $turnstileSiteKey }}"
                    data-theme="dark"
                    data-appearance="interaction-only"
                    data-language="{{ app()->getLocale() }}"
                ></div>
                @error('cf-turnstile-response')
                    <p class="mt-2 text-[0.8125rem] text-red-300">{{ $message }}</p>
                @enderror
            </div>

            @pushOnce('scripts')
                <script
                    src="https://challenges.cloudflare.com/turnstile/v0/api.js"
                    nonce="{{ csp_nonce() }}"
                    async
                    defer
                ></script>
            @endPushOnce
        @endif

        <div class="mt-1 flex flex-wrap items-center gap-4">
            <x-ui.button type="submit" ::disabled="sending" class="disabled:pointer-events-none disabled:opacity-60">
                <span x-show="! sending">{{ __('contact.form.submit') }}</span>
                <span x-show="sending" x-cloak>{{ __('contact.form.sending') }}</span>
            </x-ui.button>

            <p class="text-[0.8125rem] text-ink-faint">
                {{ __('contact.details.response') }} : {{ __('contact.details.response_value') }}
            </p>
        </div>
    </form>
</x-ui.glass>
