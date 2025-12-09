@unless (app()->getLocale() == 'en')
<a class="" href="{{ route('language.switch', 'en') }}">
    <img src="{{ asset('countries/US.png') }}" alt="English"
        class="h-10 rounded-full p-1 bg-white border-2 border-blue-600 hover:border-blue-500">
</a>
@endunless

@unless (app()->getLocale() == 'ru')
<a class="" href="{{ route('language.switch', 'ru') }}">
    <img src="{{ asset('countries/RU.png') }}" alt="English"
        class="h-10 rounded-full p-1 bg-white border-2 border-blue-600 hover:border-blue-500">
</a>
@endunless