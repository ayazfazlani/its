<div>
   
    <div class="container mt-4">
        <h2>Employees with Active Ads</h2>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Employee Name</th>
                    <th>Total Active Ads</th>
                    <th>Performance</th> <!-- New column -->
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $index => $employee)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $employee->user->name ?? 'N/A' }}</td>
                        <td>{{ $employee->active_ads_count }}</td>
                        <td>
                            {{ number_format($employee->active_ads_performance, 1) }}%
                        </td>
                        <td>
                            <a href="{{ route('employee.view', $employee->id) }}" class="btn btn-primary">View</a>
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