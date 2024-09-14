<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <title>Organizational Chart</title>
    <style>
        .container {
            margin-top: 20px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .form-inline {
            margin-bottom: 20px;
        }
        .form-inline input[type="text"] {
            margin-right: 10px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table .btn {
            margin-right: 5px;
        }
        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Positions</h1>

        <!-- Display success message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Display error message -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Search and filter form -->
        <form method="GET" action="{{ route('positions.index') }}" class="form-inline mb-4">
            <label for="search" class="mr-sm-2">Search:</label>
            <input type="text" id="search" name="search" class="form-control mr-sm-2" placeholder="Search positions..." autocomplete="off">
            
            <label for="show_deleted" class="mr-sm-2">Show Deleted:</label>
            <select id="show_deleted" name="show_deleted" class="form-control mr-sm-2">
                <option value="0" {{ request('show_deleted') == '0' ? 'selected' : '' }}>No</option>
                <option value="1" {{ request('show_deleted') == '1' ? 'selected' : '' }}>Yes</option>
            </select>
            
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <a href="{{ route('positions.create') }}" class="btn btn-primary mb-4">Add New Position</a>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Reports To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($positions as $position)
                    <tr>
                        <td>{{ $position->name }}</td>
                        <td>
                            @if ($position->reports_to)
                                {{ $position->parentPosition->name }}
                            @else
                                None
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('positions.show', $position->id) }}" class="btn btn-info btn-sm">
                                View
                            </a>
                            @if($position->soft_delete)
                                <!-- Restore Button -->
                                <form action="{{ route('positions.restore', $position->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm" title="Restore" onclick="return confirm('Are you sure you want to restore this position?')">
                                        Restore
                                    </button>
                                </form>
                            @else
                                <!-- Edit and Delete Buttons for active records -->
                                <a href="{{ route('positions.edit', $position->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                
                                <form action="{{ route('positions.destroy', $position->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this position?')">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination justify-content-center">
            {{ $positions->appends(['search' => request('search'), 'show_deleted' => request('show_deleted')])->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#search').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('positions.search') }}",
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.name,
                                    value: item.name
                                };
                            }));
                        }
                    });
                },
                minLength: 2
            });
        });
    </script>
</body>
</html>
