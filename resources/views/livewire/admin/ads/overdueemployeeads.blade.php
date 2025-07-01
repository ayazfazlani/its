<div>
   
    <div class="container mt-4">
        <h2>Employees with Overdue Ads</h2>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Employee Name</th>
                    <th>Total Overdue Ads</th>
                    <th>Performance</th> <!-- New column -->
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $index => $employee)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $employee->user->name ?? 'N/A' }}</td>
                        <td>{{ $employee->overdue_ads_count }}</td>
                        <td>
                            {{ number_format($employee->overdue_ads_avg_performance, 1) }}%
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No employees with active ads found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>