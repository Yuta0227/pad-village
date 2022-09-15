@props(['post'])
<section class="bg-white rounded-md p-3">
    <p class="flex">
        <span class="text-gray-400 text-lg">{{ $post->user->user_name }}</span>
        <span class="ml-auto text-gray-400">{{ $post->created_at->format('H:i') }}</span>
        {{-- 日付の表示はあとあと時：分に変える https://qiita.com/shimotaroo/items/acd22877a09fb13827fb --}}
    </p>
    @if (!empty($post->trade_post_gives) || !empty($post->trade_post_requests))
        @if (!empty($post->user->pad_id))
            <p class="mt-2">
                <span class="mr-2 text-gray-400">パズドラID:</span>
                <span class="font-bold">{{ $post->user->pad_id }}</span>
            </p>
        @endif
        <div class="flex mt-2">
            <p class="mr-2 text-gray-400">出:</p>
            <ul class="flex gap-2 font-bold flex-wrap max-w-xl overflow-scroll">
                @if (!empty($post->trade_post_gives))
                    @foreach ($post->trade_post_gives as $give)
                        <li class="rounded-md px-1">
                            {{ $give->monster_name . '×' . $give->monster_amount }}
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="flex mt-2">
            <p class="mr-2 text-gray-400">求:</p>
            <ul class="flex gap-2 font-bold flex-wrap max-w-xl overflow-scroll">
                @if (!empty($post->trade_post_requests))
                    @foreach ($post->trade_post_requests as $request)
                        <li class="rounded-md px-1">
                            {{ $request->monster_name . '×' . $request->monster_amount }}</li>
                    @endforeach
                @endif
            </ul>
        </div>
        @if ($post->description)
            <p class="mt-2 bg-gray-100 rounded-md px-2 py-2">{{ $post->description }}</p>
        @endif
    @else
        <p class="mt-2 bg-gray-100 rounded-md px-2 py-2">{{ $post->description }}</p>
    @endif
    {{ $slot }}
</section>
