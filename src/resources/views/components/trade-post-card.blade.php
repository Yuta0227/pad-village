@props(['post'])
<section class="bg-white rounded-md p-3">
    <p class="flex">
        <span class="text-gray-400 text-lg" style="font-size:10px;">{{ $post->user->name }}</span>
        <span style="font-size:10px;"
            class="text-lg ml-auto text-gray-400">{{ $post->created_at->format('m/d H:i') }}</span>
        {{-- 日付の表示はあとあと時：分に変える https://qiita.com/shimotaroo/items/acd22877a09fb13827fb --}}
    </p>
    @if (!$post->trade_post_gives->isEmpty() || !$post->trade_post_requests->isEmpty())
        @if (!empty($post->user->pad_id))
            <p class="mt-2 leading-5">
                <span class="mr-2 text-sm">パズドラID:</span>
                <span class="text-sm">{{ $post->user->pad_id }}</span>
            </p>
        @endif
        <div class="flex mt-2 leading-5">
            <p class="mr-2 text-sm">出:</p>
            <ul class="flex gap-2 text-sm flex-wrap max-w-xl">
                @if (!empty($post->trade_post_gives))
                    @foreach ($post->trade_post_gives as $give)
                        <li class="rounded-md px-1">
                            {{ $give->monster_name . '×' . $give->monster_amount }}
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="flex mt-2 leading-5">
            <p class="mr-2 text-sm">求:</p>
            <ul class="flex gap-2 text-sm flex-wrap max-w-xl">
                @if (!empty($post->trade_post_requests))
                    @foreach ($post->trade_post_requests as $request)
                        <li class="rounded-md px-1">
                            {{ $request->monster_name . '×' . $request->monster_amount }}</li>
                    @endforeach
                @endif
            </ul>
        </div>
        @if ($post->description)
            <p class="mt-2 bg-gray-100 rounded-md px-2 py-2 text-sm">{{ $post->description }}</p>
        @endif
    @else
        <p class="mt-2 bg-gray-100 rounded-md px-2 py-2 text-sm">{{ $post->description }}</p>
    @endif
    {{ $slot }}
</section>
