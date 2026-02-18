<x-filament-panels::page>
    <form wire:submit.prevent="save">
        <div class="space-y-4">
            {{-- Add new key --}}
            <div class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Translation Key</label>
                    <input type="text" wire:model="newKey" placeholder="e.g., Welcome" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                </div>
                <x-filament::button type="button" wire:click="addKey" size="sm">
                    Add Key
                </x-filament::button>
            </div>

            {{-- Translation table --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Key</th>
                            @foreach ($languages as $lang)
                                <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">{{ strtoupper($lang) }}</th>
                            @endforeach
                            <th class="px-4 py-3 w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($translations as $index => $row)
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="px-4 py-2">
                                    <span class="text-gray-600 dark:text-gray-400 font-mono text-xs">{{ $row['key'] }}</span>
                                </td>
                                @foreach ($languages as $lang)
                                    <td class="px-4 py-2">
                                        <input type="text" wire:model.lazy="translations.{{ $index }}.{{ $lang }}" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                    </td>
                                @endforeach
                                <td class="px-4 py-2">
                                    <button type="button" wire:click="removeKey({{ $index }})" class="text-red-500 hover:text-red-700">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @if (empty($translations))
                            <tr>
                                <td colspan="{{ count($languages) + 2 }}" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No translations yet. Add a key above to get started.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <x-filament::button type="submit">
                    Save Translations
                </x-filament::button>
            </div>
        </div>
    </form>
</x-filament-panels::page>
