<div
    class="flex min-h-screen items-center justify-center px-4 py-12"
    x-data
    x-on:scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
>
    <div class="w-full max-w-2xl animate-fade-in">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold tracking-tight text-white">Contact Verification</h1>
            <p class="mt-2 text-gray-400">Verify address, phone, and email details</p>
        </div>

        <form wire:submit="verify" class="space-y-8">
            {{-- Address Section --}}
            <div class="rounded-2xl border border-gray-800 bg-gray-900/50 p-6 backdrop-blur-sm">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    {{-- Street --}}
                    <div class="sm:col-span-3">
                        <div class="flex items-center gap-2">
                            <label for="street" class="mb-1 block text-sm font-medium text-gray-400">Street</label>
                            @if ($addressStatus)
                                <div class="group relative mb-1 animate-scale-in">
                                    @if ($addressStatus['valid'])
                                        <svg class="h-4 w-4 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                    <div class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-2 hidden w-64 -translate-x-1/2 rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-xs text-gray-300 shadow-xl group-hover:block">
                                        {{ $addressStatus['message'] }}
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-700"></div>
                                    </div>
                                </div>
                                @if ($addressStatus['valid'])
                                    <a
                                        href="https://www.openstreetmap.org/search?query={{ urlencode($street . ', ' . $zip . ' ' . $city . ', ' . $countryCode) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mb-1 animate-scale-in text-sm leading-none transition-opacity hover:opacity-75"
                                        title="View on OpenStreetMap"
                                    >&#x1F30D;</a>
                                @endif
                            @endif
                        </div>
                        <input
                            id="street"
                            type="text"
                            wire:model="street"
                            placeholder="e.g. Karlsplatz 1"
                            class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-gray-100 placeholder-gray-500 transition-colors focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                        />
                        @error('street') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- City --}}
                    <div class="sm:col-span-2">
                        <label for="city" class="mb-1 block text-sm font-medium text-gray-400">City</label>
                        <input
                            id="city"
                            type="text"
                            wire:model="city"
                            placeholder="e.g. Wien"
                            class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-gray-100 placeholder-gray-500 transition-colors focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                        />
                        @error('city') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- ZIP --}}
                    <div>
                        <label for="zip" class="mb-1 block text-sm font-medium text-gray-400">ZIP Code</label>
                        <input
                            id="zip"
                            type="text"
                            wire:model="zip"
                            placeholder="e.g. 1010"
                            class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-gray-100 placeholder-gray-500 transition-colors focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                        />
                        @error('zip') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Country Code + Phone + Email row --}}
                    <div class="sm:col-span-3">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-[5rem_1fr_1fr]">
                            {{-- Country Code --}}
                            <div>
                                <label for="countryCode" class="mb-1 block text-sm font-medium text-gray-400">Country</label>
                                <input
                                    id="countryCode"
                                    type="text"
                                    wire:model="countryCode"
                                    placeholder="AT"
                                    maxlength="2"
                                    class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-4 py-2.5 uppercase text-gray-100 placeholder-gray-500 transition-colors focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                                />
                                @error('countryCode') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="mb-1 block text-sm font-medium text-gray-400">Phone</label>
                                <div class="relative">
                                    <input
                                        id="phone"
                                        type="text"
                                        wire:model="phone"
                                        placeholder="e.g. +43 1 58801"
                                        class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-4 py-2.5 pr-10 text-gray-100 placeholder-gray-500 transition-colors focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                                    />
                                    @if ($phoneStatus)
                                        <div class="group absolute top-1/2 right-3 -translate-y-1/2 animate-scale-in">
                                            @if ($phoneStatus['valid'])
                                                <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                            <div class="pointer-events-none absolute bottom-full right-0 z-10 mb-2 hidden w-64 rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-xs text-gray-300 shadow-xl group-hover:block">
                                                {{ $phoneStatus['message'] }}
                                                <div class="absolute top-full right-3 border-4 border-transparent border-t-gray-700"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @error('phone') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="mb-1 block text-sm font-medium text-gray-400">Email</label>
                                <div class="relative">
                                    <input
                                        id="email"
                                        type="text"
                                        wire:model="email"
                                        placeholder="e.g. test@gmail.com"
                                        class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-4 py-2.5 pr-10 text-gray-100 placeholder-gray-500 transition-colors focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                                    />
                                    @if ($emailStatus)
                                        <div class="group absolute top-1/2 right-3 -translate-y-1/2 animate-scale-in">
                                            @if ($emailStatus['valid'])
                                                <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                            <div class="pointer-events-none absolute bottom-full right-0 z-10 mb-2 hidden w-64 rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-xs text-gray-300 shadow-xl group-hover:block">
                                                {{ $emailStatus['message'] }}
                                                <div class="absolute top-full right-3 border-4 border-transparent border-t-gray-700"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @error('email') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-center">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition-all hover:bg-indigo-500 hover:shadow-indigo-500/40 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-950 focus:outline-none disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Verify</span>
                    <span wire:loading.flex class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verifying...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
