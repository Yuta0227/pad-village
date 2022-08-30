<header class="flex top-0 left-0 h-40 w-screen items-center" style="padding:10px;">
    <div class="font-bold w-1/2 bg-slate-100" style="width:50%;">パズ村</div>
    <div class="w-2/5 flex justify-end" style="width:40%;">
        @if (!Auth::check())
        <div class="w-1/2 bg-slate-50">
            <a href="{{ route('login') }}">
                <img src="{{ asset('img/Frame8.svg') }}">
            </a>
        </div>
        <div class="w-1/2">
            <a href="{{ route('register') }}">
                <img src="{{ asset('img/Frame9.svg') }}">
            </a>
        </div>
        @endif
    </div>
    <div style="width:10%;" class="flex items-center justify-center">
        <img src="{{ asset('img/Vector.svg') }}">
    </div>
</header>

{{-- なぜか%のwidthがきかない --}}
{{-- bgも効かない --}}