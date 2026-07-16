@php $settings = \App\Models\Setting::current(); @endphp

<x-layouts.public :title="'Testimoni - ' . $settings->company_name">

    <x-include.page-hero
        title="Testimoni Jamaah"
        subtitle="Pengalaman para jamaah yang telah berangkat bersama kami." />

    <div class="mx-auto max-w-6xl px-6 py-12">
        @if ($testimonials->isNotEmpty())
            <div class="grid gap-5 md:grid-cols-3">
                @foreach ($testimonials as $index => $testimonial)
                    <div class="h-full" data-reveal style="--reveal-delay: {{ ($index % 3) * 90 }}ms">
                        <x-include.testimonial-card :testimonial="$testimonial" />
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $testimonials->links() }}
            </div>
        @else
            <x-include.empty-state
                icon="bx-message-square-dots"
                message="Belum ada testimoni dipublikasikan." />
        @endif
    </div>

</x-layouts.public>
