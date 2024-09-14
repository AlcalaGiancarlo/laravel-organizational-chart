<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Positions</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> {{-- Link to your CSS --}}
</head>
<body>
    <h1>Restore Positions</h1>

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error message (if applicable) -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table to display soft-deleted positions -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Reports To</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positions as $position)
                <tr>
                    <td>{{ $position->id }}</td>
                    <td>{{ $position->name }}</td>
                    <td>{{ $position->reports_to ? $position->parentPosition->name : 'None' }}</td>
                    <td>
                        <form action="{{ route('positions.restorePosition', $position->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-primary">Restore</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
