<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{

    

     // Create a new position
     public function store(Request $request)
     {
         $validated = $request->validate([
             'name' => 'required|unique:positions',
             'reports_to' => 'nullable|exists:positions,id',
         ]);
 
         /*
         Position::create($validated);
         return redirect()->route('positions.index'); */

         try {
            
            Position::create($request->all());
            return redirect()->route('positions.index')->with('success', 'Position created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['name' => 'This position already exists.']);
        }
     }
 
     /*
     public function index(Request $request)
     {
         $query = Position::query();
     
         // Search functionality
         if ($request->filled('search')) {
             $query->where('name', 'like', '%' . $request->search . '%');
         }
     
         // Filter functionality (e.g., filter by positions with a specific report_to)
         if ($request->filled('reports_to')) {
             $query->where('reports_to', $request->reports_to);
         }
     
         // Filter by soft_deleted status
         if ($request->has('show_deleted') && $request->show_deleted == '1') {
             $query->where('soft_delete', 1);
         } else {
             $query->where('soft_delete', 0);
         }
     
         $positions = $query->with('parentPosition')->get();
     
         return view('positions.index', compact('positions'));
     } */

     public function index(Request $request)
    {
        $search = $request->input('search');
        $showDeleted = $request->input('show_deleted') == '1';

        $positions = Position::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->when($showDeleted, function ($query) {
                return $query->where('soft_delete', 1);
            }, function ($query) {
                return $query->where('soft_delete', 0);
            })
            ->paginate(10); // Adjust the number of items per page if needed

        return view('positions.index', compact('positions'));
    }


     public function show($id)
    {
        $position = Position::with('childPositions')->findOrFail($id);

        // Format the date (assuming you have a date field in your model)
        $position->created_at = Carbon::parse($position->created_at)->toDateString();
        $position->updated_at = Carbon::parse($position->updated_at)->toDateString();

        return view('positions.show', compact('position'));
    }

 
     // Update a position
     public function update(Request $request, $id)
     {
         $position = Position::findOrFail($id);
         $validated = $request->validate([
             'name' => 'required|unique:positions,name,' . $position->id,
             'reports_to' => 'nullable|exists:positions,id',
         ]);
 
         $position->update($validated);
         return redirect()->route('positions.index');
     }
 
    
    public function destroy($id)
    {
        // Find the position by ID
        $position = Position::findOrFail($id);

        // Check if the position has any active (not soft deleted) subordinates
        $activeSubordinates = Position::where('reports_to', $id)
                                  ->where('soft_delete', 0)
                                  ->count();

        // If there are active subordinates, prevent the deletion
        if ($activeSubordinates > 0) {
            return redirect()->route('positions.index')->with('error', 'Cannot delete position because it has active subordinates.');
        }

        // Soft delete the position (set soft_delete to 1)
        $position->soft_delete = 1;
        $position->save();

        return redirect()->route('positions.index')->with('success', 'Position soft deleted successfully.');
    }

 
     

     public function create()
     {
         // Fetch all positions that are not soft deleted
         $positions = Position::where('soft_delete', 0)->get();
     
         // Check if there is any position with reports_to set to null
         $hasNullReportTo = Position::whereNull('reports_to')
                                     ->where('soft_delete', 0)
                                     ->exists();
     
         return view('positions.create', compact('positions', 'hasNullReportTo'));
     }
     
     
     // Show the form to edit an existing position
    public function edit($id)
    {
        $position = Position::findOrFail($id);
        $positions = Position::where('soft_delete', 0) // Exclude soft deleted positions
                         ->where('id', '!=', $id) // Exclude the current position
                         ->get();

        // Check if there is any position with reports_to set to null
        $hasNullReportTo = Position::whereNull('reports_to')
                                ->where('soft_delete', 0)
                                ->exists();
    
        return view('positions.edit', compact('position', 'positions', 'hasNullReportTo'));
    }

    public function search(Request $request)
    {
        // Validate the request
        $request->validate([
        'query' => 'required|string',
        ]);

        // Get the search query
        $query = $request->input('query');

        // Perform a case-insensitive search for position names
        $positions = Position::where('name', 'like', '%' . $query . '%')
                         ->where('soft_delete', 0)
                         ->get(['id', 'name']);

        // Return the results as JSON
        return response()->json($positions);
    }

    public function restore($id)
    {
        // Find the position that is soft-deleted
        $position = Position::where('id', $id)
                        ->where('soft_delete', 1)
                        ->firstOrFail();

        // Update the soft_delete field to 0 to restore the position
        $position->update(['soft_delete' => 0]);

        // Redirect back to the index page with a success message
        return redirect()->route('positions.index')->with('success', 'Position restored successfully.');
    }

    public function restorePosition($id)
    {
        // Find the position by ID, including soft-deleted ones
        $position = Position::withTrashed()->findOrFail($id);

        // Check if the position's 'reports_to' is set
        if ($position->reports_to) {
            // Find the position it's reporting to and check if it's soft deleted
            $reportsToPosition = Position::withTrashed()->find($position->reports_to);

            if ($reportsToPosition && $reportsToPosition->soft_delete == 1) {
                // If the 'reports_to' position is soft deleted, do not allow restore
                return redirect()->route('positions.index')->with('error', 'Cannot restore position because its superior is soft deleted.');
            }
        }

        // Restore the position (set soft_delete to 0)
        $position->soft_delete = 0;
        $position->save();

        return redirect()->route('positions.index')->with('success', 'Position restored successfully.');
    }

}