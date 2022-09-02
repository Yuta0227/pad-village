<header class="fixed w-full flex top-0 left-0 items-center" style="padding:10px;background-color:#EEF6FF;height:50px;">
    <div style="width:50%;font-weight:bold;">パズ村</div>
    <div class="flex justify-end items-center" style="width:40%;">
        @if (!Auth::check())
            <div>
                <a href="{{ route('login') }}">
                    <img src="{{ asset('img/enter_village.svg') }}">
                </a>
            </div>
        @endif
    </div>
    <div style="width:10%;" class="flex items-center justify-center">
        <img src="{{ asset('img/Vector.svg') }}">
    </div>
</header>
