<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Production Report</h1>
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Batch</th>
                    <th>Purpose</th>
                    <th>Customer</th>
                    <th>Produced By</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($finishedGoods as $item)
                    <tr>
                        <td>{{ $item->production_date }}</td>
                        <td>{{ $item->product->name ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->batch_number }}</td>
                        <td>{{ $item->purpose }}</td>
                        <td>{{ $item->customer->name ?? '-' }}</td>
                        <td>{{ $item->producedBy->name ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $finishedGoods->links() }}</div>
</div>
