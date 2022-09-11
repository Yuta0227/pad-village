<header>
    <section class="fixed w-full flex top-0 left-0 items-center bg-blue-50 px-5">
        <h1 class="font-bold text-xl leading-[64px]">パズ村</h1>
        @if (!Auth::check())
            <a href="{{ route('login') }}" class="bg-blue-500 flex gap-1 px-2 rounded-lg ml-auto mr-3">
                <img src="{{ asset('img/enter_village.svg') }}">
                <span class="text-white font-bold text-sm leading-8">村に入る</span>
            </a>
        @endif
        <img src="{{ asset('img/Vector.svg') }}" width="32">
    </section>
    <div class="h-16"></div>
</header>
