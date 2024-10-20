<?php

namespace App\Helpers;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class FormHelper
{
    /**
     * Get the next available staff ID for order assignment.
     * 
     * This method checks for staff members who have not yet reached their maximum allowed incomplete orders.
     * It returns the ID of the next available staff member based on fair distribution rules.
     *
     * @param object $formHolder The form holder object containing staff members and related orders.
     * @param bool $useFormOrder Flag indicating whether to use only orders related to the form holder (true) 
     *                           or all orders in the system (false).
     * @return object of the next available staff member or null if no staff is available.
     */
    public function getNextAvailableStaff($formHolder, $useFormOrder = false)
    {
        // Check if the form holder has staff members and auto distribution is enabled
        if ($formHolder->staffs->count() > 0 && $formHolder->auto_orders_distribution) {
            // Get all staff members related to the form holder
            $staffMembers = $formHolder->staffs;

            // Get the maximum number of incomplete orders a staff member can have
            $staff_workload_threshold = $formHolder->staff_workload_threshold;

            // Retrieve orders based on the $useFormOrder flag
            if ($useFormOrder) {
                $orders = $formHolder->formOrders;
            } else {
                $orders = Order::all();
            }

            // Map each staff member to their current count of incomplete orders
            $staffLoad = $staffMembers->mapWithKeys(function ($staff) use ($orders) {
                $incompleteOrdersCount = $orders->filter(function ($order) use ($staff) {
                    return $order->staff_assigned_id == $staff->id
                        && $order->status !== 'delivered_and_remitted';
                })->count();

                return [$staff->id => $incompleteOrdersCount];
            });

            // Filter staff members who have not yet reached their maximum incomplete orders
            $availableStaff = $staffMembers->filter(function ($staff) use ($staffLoad, $staff_workload_threshold) {
                if ($staff_workload_threshold === 0) {
                    return true; // No limit enforced
                }
                return $staffLoad[$staff->id] < $staff_workload_threshold;
            })->values(); // Reset keys for proper indexing

            // If no staff member is available, return null
            if ($availableStaff->isEmpty()) {
                return null; // No available staff member
            }

            // Determine the last assigned staff member's index (this would ideally be stored in the database/session)
            $lastAssignedStaffId = $formHolder->last_assigned_staff_id; // Fetch the last assigned staff from your data source

            // Find the index of the last assigned staff in available staff
            $lastAssignedIndex = $availableStaff->search(function ($staff) use ($lastAssignedStaffId) {
                return $staff->id === $lastAssignedStaffId;
            });

            // If last assigned staff is not found or it was the last in the available list, wrap to start
            if ($lastAssignedIndex === false || $lastAssignedIndex === count($availableStaff) - 1) {
                $nextIndex = 0; // Go to the first staff member
            } else {
                $nextIndex = $lastAssignedIndex + 1; // Move to the next staff member
            }

            // Select the next available staff member
            $nextStaff = $availableStaff[$nextIndex];

            // Update the last assigned staff member's ID (save it back to your data source)
            $formHolder->last_assigned_staff_id = $nextStaff->id; // Save this back to the database
            $formHolder->save(); // Ensure the changes are saved

            // Debug log to check the assigned staff
            Log::info('Assigned Staff ID: ' . $nextStaff->id);


            // Return the ID of the next available staff member
            return $nextStaff;
        }

        return null; // Return null if no staff are available or auto distribution is disabled
    }
}
