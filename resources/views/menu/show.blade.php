@extends('layouts.menu')

@section('title', $restaurant->name)

@section('content')
<div class="max-w-xl mx-auto pb-28">

    {{-- Cabecera del restaurante --}}
    <header class="bg-white shadow-sm px-4 py-6 text-center">
        @if($restaurant->logo)
            <img src="{{ Storage::url($restaurant->logo) }}"
                 alt="{{ $restaurant->name }}"
                 class="w-20 h-20 rounded-full mx-auto mb-3 object-cover">
        @endif
        <h1 class="text-2xl font-bold text-gray-900">{{ $restaurant->name }}</h1>
        @if($restaurant->address)
            <p class="text-sm text-gray-500 mt-1">{{ $restaurant->address }}</p>
        @endif
        @if($restaurant->phone)
            <p class="text-sm text-gray-500">{{ $restaurant->phone }}</p>
        @endif
    </header>

    {{-- Categorias e items --}}
    <main class="px-4 py-6 space-y-8">
        @forelse($categories as $category)
            <section>
                <h2 class="text-base font-semibold text-gray-700 uppercase tracking-wide mb-3 pb-2 border-b border-gray-300">
                    {{ $category->name }}
                </h2>
                <div class="space-y-3">
                    @foreach($category->menuItems as $item)
                        <div class="bg-white rounded-xl shadow-sm flex gap-3 p-4">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}"
                                     alt="{{ $item->name }}"
                                     class="w-24 h-24 rounded-lg object-cover flex-shrink-0">
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-2">
                                    <h3 class="font-semibold text-gray-900 leading-snug">{{ $item->name }}</h3>
                                    <span class="font-bold text-gray-900 whitespace-nowrap text-sm">
                                        ${{ number_format($item->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                @if($item->description)
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $item->description }}</p>
                                @endif
                                @if($item->variants->isNotEmpty())
                                    <ul class="mt-2 space-y-1">
                                        @foreach($item->variants as $variant)
                                            <li class="flex justify-between text-xs text-gray-600">
                                                <span>{{ $variant->name }}</span>
                                                @if($variant->price_delta !== 0)
                                                    <span>{{ $variant->price_delta > 0 ? '+' : '' }}${{ number_format($variant->price_delta, 0, ',', '.') }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <p class="text-center text-gray-400 py-16">El menú está siendo preparado.</p>
        @endforelse
    </main>

</div>

{{-- Boton flotante de WhatsApp --}}
@if($restaurant->whatsapp)
    <a href="https://wa.me/{{ preg_replace('/\D/', '', $restaurant->whatsapp) }}"
       target="_blank"
       rel="noopener noreferrer"
       class="fixed bottom-6 right-4 left-4 max-w-xl mx-auto bg-green-500 hover:bg-green-600 active:bg-green-700 text-white rounded-full py-4 px-6 shadow-lg flex items-center justify-center gap-3 transition-colors font-semibold">
        <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        Pedir por WhatsApp
    </a>
@endif
@endsection
