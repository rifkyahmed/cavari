@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Analytics</h1>
    
    <div class="flex items-center gap-4 w-full md:w-auto">
        <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex items-center gap-2 flex-grow md:flex-grow-0">
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" onchange="this.form.submit()" class="border border-gray-300 px-3 py-2 rounded-sm text-sm font-instrument focus:outline-none focus:border-black">
            <span class="text-gray-500 font-bold">to</span>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" onchange="this.form.submit()" class="border border-gray-300 px-3 py-2 rounded-sm text-sm font-instrument focus:outline-none focus:border-black">
            <button type="submit" class="px-4 py-2 bg-gray-100 border border-gray-300 text-black font-bold font-space-mono text-sm uppercase rounded-sm hover:bg-gray-200 transition-colors hidden sm:block">Filter</button>
            <button type="submit" class="p-2 bg-gray-100 border border-gray-300 text-black rounded-sm hover:bg-gray-200 transition-colors sm:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>

        <a href="{{ route('admin.analytics.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="px-6 py-2 bg-black text-white font-bold rounded-sm shadow hover:bg-gray-800 transition-colors font-space-mono text-xs uppercase tracking-widest flex items-center whitespace-nowrap">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export CSV
        </a>
    </div>
</div>

{{-- Top Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 flex flex-col justify-center">
        <h3 class="text-xs font-bold text-gray-400 mb-2 font-space-mono uppercase tracking-[0.2em]">Total Revenue</h3>
        <p class="text-4xl font-bold font-space-mono text-black">${{ number_format($totalRevenue, 2) }}</p>
    </div>
    
    <div class="glass-panel p-6 flex flex-col justify-center">
        <h3 class="text-xs font-bold text-gray-400 mb-2 font-space-mono uppercase tracking-[0.2em]">Total Orders</h3>
        <p class="text-4xl font-bold font-space-mono text-black">{{ number_format($totalOrders) }}</p>
    </div>

    <div class="glass-panel p-6 flex flex-col justify-center">
        <h3 class="text-xs font-bold text-gray-400 mb-2 font-space-mono uppercase tracking-[0.2em]">New Customers</h3>
        <p class="text-4xl font-bold font-space-mono text-black">{{ number_format($newCustomers) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Top Selling Products --}}
    <div class="glass-panel p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6 font-space-mono uppercase">Top Selling Products</h3>
        @if($topProducts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 text-xs font-bold text-gray-400 uppercase tracking-widest font-space-mono">Product</th>
                            <th class="pb-3 text-xs font-bold text-gray-400 uppercase tracking-widest text-center font-space-mono">Units Sold</th>
                            <th class="pb-3 text-xs font-bold text-gray-400 uppercase tracking-widest text-right font-space-mono">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="py-4">
                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="font-bold text-indigo-600 hover:underline">
                                    {{ Str::limit($product->name, 35) }}
                                </a>
                            </td>
                            <td class="py-4 text-center text-gray-600 font-bold font-space-mono">{{ $product->total_sold }}</td>
                            <td class="py-4 text-right text-gray-900 font-bold font-space-mono">${{ number_format($product->revenue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic text-sm py-4">No products sold in this period.</p>
        @endif
    </div>

    {{-- Daily Revenue Chart --}}
    <div class="glass-panel p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6 font-space-mono uppercase">Daily Revenue Graph</h3>
        @if($dailyRevenue->count() > 0)
            <div class="w-full" style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        @else
            <p class="text-gray-500 italic text-sm py-4">No revenue recorded on individual days in this period.</p>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const rawData = @json($dailyRevenue);
        
        const labels = rawData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        
        const data = rawData.map(item => item.sum);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: data,
                    borderColor: '#111827', // Tailwind gray-900
                    backgroundColor: 'rgba(17, 24, 39, 0.05)',
                    borderWidth: 2,
                    tension: 0.3, // smooth curves
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#111827',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleFont: { family: 'Space Mono', size: 12 },
                        bodyFont: { family: 'Instrument Sans', size: 14, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 4,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Space Mono', size: 10 },
                            color: '#6b7280'
                        }
                    },
                    y: {
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Space Mono', size: 10 },
                            color: '#6b7280',
                            callback: function(value) {
                                return '$' + value;
                            }
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
@endpush

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #e5e7eb;
    border-radius: 20px;
}
</style>
@endsection
