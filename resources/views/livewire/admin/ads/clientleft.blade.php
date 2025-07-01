<div>
   
    <div class="container mt-4">
        <h2>Employees with client left Ads</h2>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Employee Name</th>
                    <th>Total Client Left Ads</th>
                    <th>Performance</th> <!-- New column -->
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $index => $employee)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $employee->user->name ?? 'N/A' }}</td>
                        <td>{{ $employee->client_left_count }}</td>
                        <td>
                            {{ number_format($employee->client_left_avg_performance, 1) }}%
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No employees with clientleft ads found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>