<?php

namespace App\Http\Controllers;

use App\Models\DeploymentRequest;
use App\Models\DeployedItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeploymentRequestController extends Controller
{
    /**
     * Display a listing of the deployment requests.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = DeploymentRequest::with(['deployedItem', 'requester', 'checker'])
            ->latest()
            ->paginate(15);

        return view('deployment-requests.index', compact('requests'));
    }

    /**
     * Display the specified deployment request.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = DeploymentRequest::with(['deployedItem', 'requester', 'checker'])
            ->findOrFail($id);

        return view('deployment-requests.show', compact('request'));
    }

    /**
     * Get a list of approvers (users with permission to approve requests).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApprovers()
    {
        // This is a simple example - you might want to implement a more sophisticated
        // way to determine who can approve requests (e.g., by role or permission)
        $approvers = User::where('id', '!=', Auth::id()) // Exclude current user
            ->select('id', 'name', 'email')
            ->get();

        return response()->json($approvers);
    }

    /**
     * Store a newly created deployment request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Update the specified deployment request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $deploymentRequest = DeploymentRequest::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string|max:1000',
        ]);

        // Only allow updating if the request is still pending
        if ($deploymentRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This request has already been processed.');
        }

        // Update the request status and set the checked_by and checked_at fields
        $deploymentRequest->update([
            'status' => $validated['status'],
            'checkedBy' => auth()->id(),
            'checked_at' => now(),
            'remarks' => $validated['remarks'] ?? $deploymentRequest->remarks,
        ]);

        // TODO: Add notification to the requester

        return redirect()->route('deployment-requests.show', $deploymentRequest->requestID)
            ->with('success', 'Request has been ' . $validated['status'] . ' successfully.');
    }

    /**
     * Store a newly created deployment request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'requestType' => 'required|in:new,replacement,additional',
            'department_id' => 'required|exists:departments,departmentID',
            'remarks' => 'nullable|string|max:1000',
            'deployedID' => 'required|exists:supplies,itemID',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get the supply item
            $supply = \App\Models\Supply::findOrFail($request->deployedID);
            
            // Get supply details
            $supply = \App\Models\Supply::findOrFail($request->deployedID);
            
            // Create a new deployed item with database column names
            $deployedItem = new DeployedItem([
                'itemName' => $supply->name,
                'itemDescription' => $supply->description,
                'dateAcquired' => now(),
                'cost' => $supply->cost ?? 0,
                'itemCategory' => $supply->category?->categoryName ?? 'Uncategorized',
                'qr_code' => 'DEP-' . time() . '-' . $supply->itemID, // Generate a simple QR code
                'departmentID' => $request->department_id,
                'dateDeployed' => now(),
                'status' => 'pending',
                'remarks' => 'Requested: ' . $request->requestType . ($request->remarks ? ' - ' . $request->remarks : '')
            ]);
            
            $deployedItem->save();

            // Create the deployment request
            $deploymentRequest = new DeploymentRequest([
                'deployedID' => $deployedItem->deployedID,
                'requestType' => $request->requestType,
                'requestBy' => Auth::id(),
                'requestDate' => now(),
                'status' => 'pending',
                'remarks' => $request->remarks,
                'department_id' => $request->department_id
            ]);

            $deploymentRequest->save();

            // Create notification for approvers
            $notification = new \App\Models\Notification([
                'type' => 'deployment_request',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => Auth::id(),
                'data' => json_encode([
                    'message' => 'New deployment request for ' . $supply->name,
                    'url' => route('deployment-requests.show', $deploymentRequest->requestID)
                ])
            ]);
            $notification->save();

            return response()->json([
                'success' => true,
                'message' => 'Deployment request submitted successfully',
                'data' => $deploymentRequest->load('deployedItem', 'requester')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error creating deployment request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create deployment request',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
