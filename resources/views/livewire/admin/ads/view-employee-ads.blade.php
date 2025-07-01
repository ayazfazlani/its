<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            Active Ads for {{ $employee->user->name ?? 'N/A' }}
        </x-slot:title>
        <x-slot:subtitle>
            Total Active Ads: {{ $activeAds->count() }}
        </x-slot:subtitle>
        <x-slot:buttons>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </x-slot:buttons>
    </x-page-heading>

    <div class="mt-6">
        <table class="table-auto w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Ad Name</th>
                    <th class="px-4 py-2">Web URL</th>
                    <th class="px-4 py-2">Performance</th>
                    <th class="px-4 py-2">Start Date</th>
                    <th class="px-4 py-2">End Date</th>
                    <th class="px-4 py-2">Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeAds as $index => $ad)
                    <tr>
                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border px-4 py-2">{{ $ad->name }}</td>
                        <td class="border px-4 py-2"><a href="{{ $ad->web_url }}" target="_blank" class="text-blue-600 underline">{{ $ad->web_url }}</a></td>
                        <td class="border px-4 py-2">{{ number_format($ad->performance, 1) }}%</td>
                        <td class="border px-4 py-2">{{ $ad->start_date }}</td>
                        <td class="border px-4 py-2">{{ $ad->end_date }}</td>
                        <td class="border px-4 py-2">{{ $ad->reason ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No active ads found for this employee.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
