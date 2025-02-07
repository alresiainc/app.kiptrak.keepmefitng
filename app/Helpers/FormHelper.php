<?php

namespace App\Helpers;

use App\Models\Customer;
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
    // public function customerExists($form_holder_id, $inputData = [], $package_bundle = [])
    // {
    //     // Define field variations (from your code)
    //     $fieldVariations = [
    //         'firstname' => ['first_name', 'firstname', 'name', 'full_name', 'first', 'given_name', 'forename'],
    //         'lastname' => ['last_name', 'lastname', 'surname', 'family_name', 'second_name', 'last', 'surname_name'],
    //         'phone_number' => ['phone_number', 'phone', 'number', 'mobile_number', 'contact_number', 'mobile', 'phoneNumber', 'cell', 'cellphone', 'cell_number', 'telephone', 'tel_number'],
    //         'whatsapp_phone_number' => ['contact', 'whatsapp_number', 'whatsapp', 'phone', 'number', 'mobile_number', 'contact_number', 'mobile', 'wa_number', 'whatsapp_contact', 'whatsappPhone', 'active_whatsapp_number'],
    //         'email' => ['email', 'email_address', 'e-mail', 'mail', 'contact_email', 'active_email', 'active_email_address'],
    //         'city' => ['city', 'location', 'town', 'municipality', 'urban_area', 'metropolis'],
    //         'state' => ['state', 'region', 'province', 'territory', 'county', 'district'],
    //         'delivery_address' => ['address', 'delivery_address', 'shipping_address', 'postal_address', 'street_address', 'recipient_address', 'full_address', 'full_delivery_address'],
    //         'delivery_duration' => ['duration', 'delivery_duration', 'time', 'delivery_time', 'shipping_time', 'estimated_time', 'eta', 'delivery_period'],
    //     ];

    //     // Use the FieldMatcher class to map input data to normalized fields
    //     $matchedData = (new FieldMatcher())->matchFields($fieldVariations, $inputData);

    //     // Build the query to check for an existing customer
    //     $query = Customer::query();

    //     foreach ($matchedData as $field => $value) {
    //         if (!empty($value)) {
    //             $query->where($field, $value);
    //         }
    //     }

    //     // Check for matching customers and retrieve their IDs
    //     $customers = $query->select('id')->get();

    //     if ($customers->isNotEmpty()) {
    //         $customerIds = $customers->pluck('id')->toArray();
    //         $orders = Order::whereIn('customer_id', $customerIds)
    //             ->where('form_holder_id', $form_holder_id)
    //             ->get();

    //             //each other package_bundle //json
    //             $outgoingStock = $order->outgoingStock;
    //     $outgoingStockPackageBundle = $outgoingStock->package_bundle; 

    //     //Current package_bundle
    //     $current_package_bundle = $package_bundle; //json

    //         return [
    //             'exists' => true,
    //             'orders' => $orders,
    //             'customer_ids' => $customerIds,
    //         ];
    //     }

    //     return [
    //         'exists' => false,
    //         'orders' => [],
    //         'customer_ids' => [],
    //     ];
    // }
    public function customerExists($form_holder_id, $inputData = [], $package_bundle = [])
    {

        $form_fields = $inputData['form_fields'];
        Log::alert('form_fields', ['form_fields' => $form_fields]);
        // Retrieve all customers and check JSON 'data' field for a match
        $customers = Customer::select('id', 'data')->get();
        Log::alert("Fetched Customers:", ['customers' => $customers]);

        $customerIds = [];



        foreach ($customers as $customer) {
            // Decode JSON column 'data'
            $customerData = $customer->data;

            // Ensure both are arrays before comparison
            if (!is_array($customerData) || !is_array($form_fields)) {
                continue;
            }

            // Normalize: Sort Arrays to Avoid Key Order Issues
            ksort($customerData);
            ksort($form_fields);

            // Compare customer data with input data
            if ($customerData == $form_fields) { // Use == (not ===) to ignore minor type mismatches
                $customerIds[] = $customer->id;
            }
        }

        // If no customers were found, return early
        if (empty($customerIds)) {
            return [
                'exists' => false,
                'orders' => [],
                'customer_ids' => [],
            ];
        }

        Log::alert("Matching Customer IDs:", ['customer_ids' => $customerIds]);

        // Fetch orders for the matched customers within the same form_holder_id
        $orders = Order::whereIn('customer_id', $customerIds)
            ->where('form_holder_id', $form_holder_id)
            ->get();

        Log::alert("Orders found:", ['orders' => $orders]);

        foreach ($orders as $order) {
            $outgoingStock = $order->outgoingStock;

            if (!$outgoingStock) {
                continue;
            }

            $outgoingStockPackageBundle = $outgoingStock->package_bundle;

            if (!is_array($outgoingStockPackageBundle)) {
                continue;
            }

            // Sort both arrays before comparison
            ksort($package_bundle);
            ksort($outgoingStockPackageBundle);

            // Check if the package bundle matches exactly
            if ($package_bundle == $outgoingStockPackageBundle) {
                Log::alert("Matching Order Found:", [
                    'matching_order_id' => $order->id,
                    'package_bundle' => $package_bundle,
                    'outgoingStockPackageBundle' => $outgoingStockPackageBundle
                ]);

                return [
                    'exists' => true,
                    'orders' => $orders,
                    'customer_ids' => $customerIds,
                    'matching_order_id' => $order->id,
                ];
            }
        }

        return [
            'exists' => false,
            'orders' => [],
            'customer_ids' => $customerIds, // Keep the found customers even if no order matched
        ];
    }


    public function customerExistss($form_holder_id, $inputData = [], $package_bundle = [])
    {
        $fieldVariations = [
            'firstname' => ['first_name', 'firstname', 'name', 'full_name', 'first', 'given_name', 'forename'],
            'lastname' => ['last_name', 'lastname', 'surname', 'family_name', 'second_name', 'last', 'surname_name'],
            'phone_number' => ['phone_number', 'phone', 'number', 'mobile_number', 'contact_number', 'mobile', 'phoneNumber', 'cell', 'cellphone', 'cell_number', 'telephone', 'tel_number'],
            'whatsapp_phone_number' => ['contact', 'whatsapp_number', 'whatsapp', 'phone', 'number', 'mobile_number', 'contact_number', 'mobile', 'wa_number', 'whatsapp_contact', 'whatsappPhone', 'active_whatsapp_number'],
            'email' => ['email', 'email_address', 'e-mail', 'mail', 'contact_email', 'active_email', 'active_email_address'],
            'city' => ['city', 'location', 'town', 'municipality', 'urban_area', 'metropolis'],
            'state' => ['state', 'region', 'province', 'territory', 'county', 'district'],
            'delivery_address' => ['address', 'delivery_address', 'shipping_address', 'postal_address', 'street_address', 'recipient_address', 'full_address', 'full_delivery_address'],
            'delivery_duration' => ['duration', 'delivery_duration', 'time', 'delivery_time', 'shipping_time', 'estimated_time', 'eta', 'delivery_period'],
        ];

        // Match input fields to standard fields
        $matchedData = (new FieldMatcher())->matchFields($fieldVariations, $inputData);
        Log::alert("matchedData:", ['matchedData' => $matchedData]);
        // Query to check if a matching customer exists
        $query = Customer::query();
        foreach ($matchedData as $field => $value) {
            if ($value !== null && $value !== '') {
                $query->where($field, '=', trim($value)); // Trim spaces
            } else {
                $query->whereNull($field); // Ensure missing fields are NULL
            }
        }


        // $customers  = Customer::where('firstname')

        // Retrieve matching customers
        $customers = $query->select('id')->get();
        Log::alert("customers:", ['customers' => $customers]);
        if ($customers->isEmpty()) {
            return [
                'exists' => false,
                'orders' => [],
                'customer_ids' => [],
            ];
        }

        $customerIds = $customers->pluck('id')->toArray();

        // Fetch orders by the found customers within the same form_holder_id
        $orders = Order::whereIn('customer_id', $customerIds)
            ->where('form_holder_id', $form_holder_id)
            ->get();

        Log::alert("Orders found:", ['orders' => $orders]);

        foreach ($orders as $order) {
            $outgoingStock = $order->outgoingStock;

            if (!$outgoingStock) {
                continue;
            }

            $outgoingStockPackageBundle = $outgoingStock->package_bundle;

            if (!is_array($outgoingStockPackageBundle)) {
                continue;
            }

            ksort($package_bundle);
            ksort($outgoingStockPackageBundle);

            // Check if the package bundle matches exactly
            if ($package_bundle == $outgoingStockPackageBundle) {
                // Log::alert("there is match:", [
                //     'incoming' => $package_bundle,
                //     'existing' => $outgoingStockPackageBundle
                // ]);

                return [
                    'exists' => true,
                    'orders' => $orders,
                    'customer_ids' => $customerIds,
                    'matching_order_id' => $order->id,
                    'outgoingStockPackageBundle' => $outgoingStockPackageBundle,
                ];
            }
        }

        return [
            'exists' => false,
            'orders' => [],
            'customer_ids' => [],
        ];
    }

    public function customerExistsss($form_holder_id, $inputData = [], $package_bundle = [])
    {
        // Define field variations (from your code)
        $fieldVariations = [
            'firstname' => ['first_name', 'firstname', 'name', 'full_name', 'first', 'given_name', 'forename'],
            'lastname' => ['last_name', 'lastname', 'surname', 'family_name', 'second_name', 'last', 'surname_name'],
            'phone_number' => ['phone_number', 'phone', 'number', 'mobile_number', 'contact_number', 'mobile', 'phoneNumber', 'cell', 'cellphone', 'cell_number', 'telephone', 'tel_number'],
            'whatsapp_phone_number' => ['contact', 'whatsapp_number', 'whatsapp', 'phone', 'number', 'mobile_number', 'contact_number', 'mobile', 'wa_number', 'whatsapp_contact', 'whatsappPhone', 'active_whatsapp_number'],
            'email' => ['email', 'email_address', 'e-mail', 'mail', 'contact_email', 'active_email', 'active_email_address'],
            'city' => ['city', 'location', 'town', 'municipality', 'urban_area', 'metropolis'],
            'state' => ['state', 'region', 'province', 'territory', 'county', 'district'],
            'delivery_address' => ['address', 'delivery_address', 'shipping_address', 'postal_address', 'street_address', 'recipient_address', 'full_address', 'full_delivery_address'],
            'delivery_duration' => ['duration', 'delivery_duration', 'time', 'delivery_time', 'shipping_time', 'estimated_time', 'eta', 'delivery_period'],
        ];

        // Use the FieldMatcher class to map input data to normalized fields
        $matchedData = (new FieldMatcher())->matchFields($fieldVariations, $inputData);

        // Build the query to check for an existing customer
        $query = Customer::query();

        foreach ($matchedData as $field => $value) {
            if (!empty($value)) {
                $query->where($field, $value);
            }
        }

        // Check for matching customers and retrieve their IDs
        $customers = $query->select('id')->get();

        if ($customers->isNotEmpty()) {
            $customerIds = $customers->pluck('id')->toArray();
            $orders = Order::whereIn('customer_id', $customerIds)
                ->where('form_holder_id', $form_holder_id)
                ->get();

            Log::alert("orders");
            Log::alert($orders);
            foreach ($orders as $order) {
                $outgoingStock = $order->outgoingStock;

                if ($outgoingStock) {
                    $outgoingStockPackageBundle = $outgoingStock->package_bundle; // Already cast as array
                    // dd($outgoingStockPackageBundle);
                    // Check if all key-value pairs in the current package bundle exist in the outgoing stock package bundle
                    $allKeyValuePairsExist = true;
                    foreach ($package_bundle as $key => $value) {
                        if (!array_key_exists($key, $outgoingStockPackageBundle) || $outgoingStockPackageBundle[$key] !== $value) {
                            $allKeyValuePairsExist = false;
                            break;
                        }
                        Log::alert("package_bundle");
                        Log::alert($package_bundle);
                        Log::alert("outgoingStockPackageBundle");
                        Log::alert($outgoingStockPackageBundle);
                    }

                    if ($allKeyValuePairsExist) {
                        return [
                            'exists' => true,
                            'orders' => $orders,
                            'customer_ids' => $customerIds,
                            'matching_order_id' => $order->id,
                        ];
                    }
                }
            }
        }

        return [
            'exists' => false,
            'orders' => [],
            'customer_ids' => [],
        ];
    }
}
