<header>
    <section class="fixed w-full flex top-0 left-0 items-center bg-blue-50 px-5">
        <h1 class="font-bold text-xl leading-[64px]">パズ村</h1>
        @if (!Auth::check())
            <a href="{{ route('login') }}" class="bg-blue-500 flex gap-1 px-2 rounded-lg ml-auto">
                <img src="{{ asset('img/enter_village.svg') }}" width="20">
                <span class="text-white font-bold text-xs leading-8">村に入る</span>
            </a>
        @else
            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="bg-blue-500 flex gap-1 px-2 rounded-lg items-center">
                    <img src="{{ asset('img/leave_village.svg') }}" width="20">
                    <span class="text-white font-bold text-xs leading-8">村を出る</span>
                </button>
            </form>
        @endif
        {{-- <img src="{{ asset('img/Vector.svg') }}" width="32"> --}}
    </section>
    <div class="h-16"></div>
</header>
