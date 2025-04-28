@php
    $percentage = $getPercentage();
@endphp

<div class="w-full mt-4 mb-4">
    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
        <div class="bg-primary-500 h-4" style="width: {{ $percentage }}%"></div>
    </div>
    <div class="text-sm mt-1 text-center">
        {{ $percentage }}%
    </div>
</div>
