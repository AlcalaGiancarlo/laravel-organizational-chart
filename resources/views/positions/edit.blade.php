<!DOCTYPE html>
<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Position</title>
    <style>
        .container {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Position</h1>
        <form action="{{ url('positions/' . $position->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Position Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $position->name }}" required>
            </div>

            <div class="form-group">
                <label for="reports_to">Reports To:</label>
                <select class="form-control" id="reports_to" name="reports_to">
                    @if (!$hasNullReportTo)
                        <option value="">None</option>
                    @endif
                    @foreach ($positions as $pos)
                        <option value="{{ $pos->id }}" {{ $pos->id == $position->reports_to ? 'selected' : '' }}>{{ $pos->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Position</button>
        </form>
    </div>
</body>
</html>
