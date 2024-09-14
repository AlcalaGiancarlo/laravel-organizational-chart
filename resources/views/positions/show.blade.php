<!DOCTYPE html>
<html>
<head>
    <title>Position Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add custom styles here if needed */
        .card {
            margin-top: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .btn-back {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
    <body>
    <div class="container mt-4">
        <a href="{{ route('positions.index') }}" class="btn btn-secondary btn-back">Back to Positions</a>

        <div class="card">
            <div class="card-header">
                <h3>Position Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Name:</th>
                        <td>{{ $position->name }}</td>
                    </tr>
                    <tr>
                        <th>Reports To:</th>
                        <td>
                            @if ($position->reports_to)
                                {{ $position->parentPosition->name }}
                            @else
                                None
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $position->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At:</th>
                        <td>{{ $position->updated_at }}</td>
                    </tr>
                </table>

                <a href="{{ route('positions.edit', $position->id) }}" class="btn btn-primary">Edit Position</a>

                <form action="{{ route('positions.destroy', $position->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this position?')">Delete Position</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
