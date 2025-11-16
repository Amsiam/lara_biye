<?php

use App\Models\Page;
use function Livewire\Volt\{state, computed};

state(['slug']);

$page = computed(function () {
    return Page::where('slug', $this->slug)
        ->where('is_active', true)
        ->firstOrFail();
});

?>

<div class="min-h-screen bg-gradient-to-b from-white to-custom-pink/5">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-custom-pink transition-colors">
                        Home
                    </a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li class="text-custom-red font-medium">
                    {{ $this->page->title }}
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-custom-red mb-4">
                {{ $this->page->title }}
            </h1>
            @if($this->page->meta_description)
                <p class="text-xl text-gray-600">
                    {{ $this->page->meta_description }}
                </p>
            @endif
        </div>

        <!-- Page Content -->
        <div class="bg-white rounded-xl shadow-lg p-8 md:p-12 border border-gray-200">
            <div class="prose prose-lg max-w-none
                prose-headings:text-custom-red
                prose-h2:text-3xl prose-h2:font-bold prose-h2:mt-8 prose-h2:mb-4
                prose-h3:text-2xl prose-h3:font-semibold prose-h3:mt-6 prose-h3:mb-3
                prose-p:text-gray-700 prose-p:leading-relaxed prose-p:mb-4
                prose-a:text-custom-pink prose-a:no-underline hover:prose-a:underline
                prose-strong:text-custom-red prose-strong:font-semibold
                prose-ul:list-disc prose-ul:ml-6 prose-ul:mb-4
                prose-ol:list-decimal prose-ol:ml-6 prose-ol:mb-4
                prose-li:text-gray-700 prose-li:mb-2
                prose-blockquote:border-l-4 prose-blockquote:border-custom-pink prose-blockquote:pl-4 prose-blockquote:italic prose-blockquote:text-gray-600
                prose-code:bg-gray-100 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:text-custom-red
            ">
                {!! $this->page->content !!}
            </div>
        </div>

        <!-- Last Updated -->
        <div class="mt-6 text-center text-sm text-gray-500">
            Last updated: {{ $this->page->updated_at->format('F j, Y') }}
        </div>

        <!-- Back to Home -->
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-custom-pink text-white rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
        </div>
    </div>
</div>
