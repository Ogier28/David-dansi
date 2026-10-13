@props(['identity'])

<x-ui.section id="contact" width="max-w-7xl">
    <div class="grid gap-12 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:gap-16">
        <div>
            <x-ui.heading
                :eyebrow="__('contact.eyebrow')"
                :title="__('contact.title')"
                :lead="__('contact.lead')"
                data-reveal
            />

            <dl class="mt-10 flex flex-col gap-5" data-reveal>
                <div class="flex items-start gap-4">
                    <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-field border border-white/10 bg-white/5 text-gold">
                        <x-icon name="mail" :size="17" />
                    </span>
                    <div>
                        <dt class="text-[0.8125rem] text-ink-faint">{{ __('contact.details.email') }}</dt>
                        <dd class="mt-0.5">
                            <a href="mailto:{{ $identity['email'] }}" class="text-[0.9375rem] font-medium text-ink transition-colors hover:text-gold">
                                {{ $identity['email'] }}
                            </a>
                        </dd>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-field border border-white/10 bg-white/5 text-gold">
                        <x-icon name="phone" :size="17" />
                    </span>
                    <div>
                        <dt class="text-[0.8125rem] text-ink-faint">{{ __('contact.details.phone') }}</dt>
                        <dd class="mt-0.5">
                            <a href="tel:{{ $identity['phone_href'] }}" class="tabular text-[0.9375rem] font-medium text-ink transition-colors hover:text-gold">
                                {{ $identity['phone'] }}
                            </a>
                        </dd>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-field border border-white/10 bg-white/5 text-gold">
                        <x-icon name="clock" :size="17" />
                    </span>
                    <div>
                        <dt class="text-[0.8125rem] text-ink-faint">{{ __('contact.details.availability') }}</dt>
                        <dd class="mt-0.5 text-[0.9375rem] font-medium text-ink">{{ __('contact.details.availability_value') }}</dd>
                    </div>
                </div>
            </dl>

            <x-ui.glass radius="tile" class="mt-9 flex items-center justify-between gap-4 p-5" data-reveal>
                <div>
                    <p class="text-[0.9375rem] font-semibold text-ink">LinkedIn</p>
                    <p class="mt-0.5 text-[0.8125rem] text-ink-faint">{{ $identity['location'] }}</p>
                </div>
                <x-ui.button
                    :href="$identity['socials']['linkedin']"
                    variant="glass"
                    class="shrink-0 px-4 py-2.5 text-sm"
                    rel="noopener noreferrer"
                    target="_blank"
                >
                    <x-icon name="linkedin" :size="16" />
                    {{ __('site.actions.view_profile') }}
                </x-ui.button>
            </x-ui.glass>
        </div>

        <div data-reveal>
            <x-contact-form />
        </div>
    </div>
</x-ui.section>
