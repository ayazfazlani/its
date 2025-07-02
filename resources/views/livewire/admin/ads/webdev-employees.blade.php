<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Web Developer Employees</h1>
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Employee</th>
                    {{-- <th>Email</th> --}}
                    <th>Web Projects Count</th>
                    <th>Avg. Performance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ optional($employee->user)->name ?? '-' }}</td>
                        {{-- <td>{{ optional($employee->user)->email ?? '-' }}</td> --}}
                        <td>{{ $employee->web_projects_count }}</td>
                        <td>{{ number_format($employee->web_projects_performance, 1) }}%</td>
                        <td>
                            <a href="{{ route('webdev.employee.sites', ['id' => $employee->id]) }}" class="btn btn-sm btn-primary">Show</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 