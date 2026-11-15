<x-sidebar>
<div class="w-75 m-auto">
    <div class="w-100">
<!-- ☆カレンダーのタイトルを表示（日付） -->
        <p>{{ $calendar->getTitle() }}</p>
<!-- ☆カレンダー本体を表示 -->
        <p>{!! $calendar->render() !!}</p>
    </div>
</div>
</x-sidebar>
