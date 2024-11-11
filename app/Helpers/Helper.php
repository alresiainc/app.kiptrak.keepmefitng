<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\OutgoingStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class Helper
{
    public function orderSalesRevenue($delivered_and_remitted_orders)
    {
        $sales_paid = 0;

        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
            foreach ($flattenedArray as $package) {
                if ($package['customer_acceptance_status'] == 'accepted') {
                    $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                }
            }
            // }
        }

        return $sales_paid;
    }

    public function totalSalesRevenue()
    {
        $sales_paid = 0;

        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

        //return $productIds = array_column($accepted_outgoing_stock->toArray(), 'product_id');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
            foreach ($flattenedArray as $package) {
                if ($package['customer_acceptance_status'] == 'accepted') {
                    $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                }
            }
            // }
        }

        return $sales_paid;
    }

    public function totalSalesCount()
    {
        $sales_count = 0;

        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
        // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        // return count($accepted_outgoing_stock);
        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
            foreach ($flattenedArray as $package) {
                if ($package['customer_acceptance_status'] == 'accepted') {
                    $sales_count += isset($package['quantity_removed']) ? (int) $package['quantity_removed'] : 0;
                }
            }
            // }
        }
        return $sales_count;
    }

    //warehouse revenue
    public function totalSalesRevenueByWarehouse($warehouse_id)
    {
        $sales_paid = 0;

        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->where('warehouse_id', $warehouse_id)->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

        //return $productIds = array_column($accepted_outgoing_stock->toArray(), 'product_id');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
            foreach ($flattenedArray as $package) {
                if ($package['customer_acceptance_status'] == 'accepted') {
                    $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                }
            }
            // }
        }

        return $sales_paid;
    }

    //duration revenue
    public function totalSalesRevenueByDuration($duration_start = "", $duration_end = "")
    {
        $sales_paid = 0;

        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->whereBetween('created_at', [$duration_start, $duration_end])->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

        //return $productIds = array_column($accepted_outgoing_stock->toArray(), 'product_id');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
            foreach ($flattenedArray as $package) {
                if ($package['customer_acceptance_status'] == 'accepted') {
                    $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                }
            }
            // }
        }

        return $sales_paid;
    }

    //duration-warehouse revenue
    public function totalSalesRevenueByDurationWarehouse($duration_start = "", $duration_end = "", $warehouse_id = "")
    {
        $sales_paid = 0;

        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->where('warehouse_id', $warehouse_id)
            ->whereBetween('created_at', [$duration_start, $duration_end])->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

        //return $productIds = array_column($accepted_outgoing_stock->toArray(), 'product_id');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
            foreach ($flattenedArray as $package) {
                if ($package['customer_acceptance_status'] == 'accepted') {
                    $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                }
            }
            // }
        }

        return $sales_paid;
    }

    public function yearlySalesReportChart($delivered_and_remitted_orders)
    {
        // Initialize an array to store the monthly sale amounts
        $yearly_sale_amount = [];

        // Loop through each month of the year
        for ($i = 1; $i <= 12; $i++) {
            // Query the database to retrieve the relevant data
            $monthly_sale_amount = DB::table('outgoing_stocks')
                ->whereYear('created_at', (string) Carbon::now()->year)
                ->whereMonth('created_at', $i)
                ->whereIn('order_id', $delivered_and_remitted_orders)
                ->get(); // Retrieve the rows for the specific month

            // Initialize variables to store the sum for the month
            $monthly_sum_amount = 0;

            // Iterate through the retrieved rows for the month
            foreach ($monthly_sale_amount as $row) {
                // Access the package_bundle JSON data
                $package_bundle = json_decode($row->package_bundle, true);
                //$package_bundle = $row->package_bundle;

                // Check if the item has 'customer_acceptance_status' as 'accepted'
                foreach ($package_bundle as $item) {
                    if ($item['customer_acceptance_status'] == 'accepted') {
                        $monthly_sum_amount += (int) $item['amount_accrued'];
                    }
                }
            }

            // Add the monthly sum to the yearly_sale_amount array
            $yearly_sale_amount[] = number_format($monthly_sum_amount, 2, '.', '');
        }

        return $yearly_sale_amount;
    }

    //unused
    public function stock_available2($product_id)
    {
        //product stock available
        $stock_available = 0;
        $product = Product::where('id', $product_id)->first();
        $sum_incomingStocks = $product->incomingStocks->sum('quantity_added');

        //outgoingstocks
        $delivered_order_ids = Order::where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->pluck('package_bundle'); //[[{}], [{}], [{}]]

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        // Now, use array_column to get the product_id values
        $productIds = array_column($flattenedArray, 'product_id'); //["1","2","3","4","5","6","7"]

        // Check if the $product_id is contained in the extracted array
        if (!in_array($product_id, $productIds)) {
            // Product with the given $product_id does not exist in the array
            $stock_available = $sum_incomingStocks;
        } else {

            // Product with the given $product_id exists in the array
            //$sum_outgoingStocks = $this->outgoingStocks->sum->outgoingStockTotal();

            // return count($accepted_outgoing_stock);
            $quantity_removed = 0;
            $quantity_returned = 0;
            $sum_outgoingStocks = 0;
            $comboOutgoingStocksArray = [];
            $comboOutgoingStocks = '';
            if (count($accepted_outgoing_stock) > 0) {

                foreach ($flattenedArray as $key => $package) {
                    if ((!isset($package['isCombo'])) && ($package['isCombo'] !== 'true')) {

                        if (($package['customer_acceptance_status'] == 'accepted') && ($package['product_id'] == $product_id)) {
                            $quantity_removed += isset($package['quantity_removed']) ? (int) $package['quantity_removed'] : 0; //sum
                            $quantity_returned += isset($package['quantity_returned']) ? (int) $package['quantity_returned'] : 0; //sum
                        }
                    }
                    if ((isset($package['isCombo'])) && ($package['isCombo'] == 'true')) {
                        $comboOutgoingStocksArray[] = $package;
                    }
                    // if ( ($package['customer_acceptance_status'] == 'accepted') && ($package['product_id'] == $this->id) && (isset($package['isCombo'])) && ($package['isCombo'] == 'true') ) {
                    //     $product = Product::where('id', $this->id)->first();
                    //     $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $this->id)) > 0 ? 
                    //     collect($product->comboProducts())->where('id', $this->id)->first()->quantity_combined : 0;
                    //     return 'dwn';
                    // }
                }
                $comboOutgoingStocks = count($comboOutgoingStocksArray) > 0 ? array_merge(...$comboOutgoingStocksArray) : '';
            }
            //return count($accepted_outgoing_stock);

            // $sum_outgoingStocks = count($delivered_order_ids) > 0 ?
            // $this->outgoingStocks()->whereIn('order_id', $delivered_order_ids)->sum(DB::raw('quantity_removed - quantity_returned')) : 0;

            $sum_outgoingStocks = count($delivered_order_ids) > 0 ? $quantity_removed - $quantity_returned : 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////

            //incase of combo
            // $comboOutgoingStocks = OutgoingStock::whereIn('order_id', $delivered_order_ids)->where('isCombo','true')->get();

            ///previous code
            // if (count($comboOutgoingStocks) > 0) {
            //     $comboOutgoingStocksProducts = $comboOutgoingStocks->pluck('product_id'); //['4'] combo product id. we need to get out the pro
            //     $comboProducts = Product::whereIn('id', $comboOutgoingStocksProducts)->get(); //combo products

            //     foreach ($comboProducts as $key => $product) {
            //         $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $this->id)) > 0 ? 
            //         collect($product->comboProducts())->where('id', $this->id)->first()->quantity_combined : 0;
            //     } 
            // }

            if ($comboOutgoingStocks !== '') {
                //$comboOutgoingStocksProducts = $comboOutgoingStocks->pluck('product_id'); //['4'] combo product id. we need to get out the pro
                $comboOutgoingStocksProducts = array_column($comboOutgoingStocksArray, 'product_id');
                $comboProducts = Product::whereIn('id', $comboOutgoingStocksProducts)->get(); //combo products

                foreach ($comboProducts as $key => $product) {

                    $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $product_id)) > 0 ?
                        collect($product->comboProducts())->where('id', $product_id)->first()->quantity_combined : 0;
                }
            }

            //incase of combo


            $stock_available = $sum_incomingStocks - $sum_outgoingStocks;
        }
        return $sum_outgoingStocks;
        return $stock_available;
    }

    public function stock_available($product_id)
    {
        //product stock available
        $stock_available = 0;
        $product = Product::where('id', $product_id)->first();
        $sum_incomingStocks = $product->incomingStocks->sum('quantity_added');

        //outgoingstocks
        $delivered_order_ids = Order::where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->pluck('package_bundle'); //[[{}], [{}], [{}]]

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]


        // Now, use array_column to get the product_id values
        $productIds = array_column($flattenedArray, 'product_id'); //["1","2","3","4","5","6","7"]

        // Product with the given $product_id exists in the array
        $quantity_removed = 0;
        $quantity_returned = 0;
        $sum_outgoingStocks = 0;
        $comboOutgoingStocksArray = [];
        $comboOutgoingStocks = '';
        if (count($accepted_outgoing_stock) > 0) {

            foreach ($flattenedArray as $key => $package) {
                if ((!isset($package['isCombo'])) && ($package['isCombo'] !== 'true')) {

                    if (($package['customer_acceptance_status'] == 'accepted') && ($package['product_id'] == $product_id)) {
                        $quantity_removed += isset($package['quantity_removed']) ? (int) $package['quantity_removed'] : 0; //sum
                        $quantity_returned += isset($package['quantity_returned']) ? (int) $package['quantity_returned'] : 0; //sum
                    }
                }
                if ((isset($package['isCombo'])) && ($package['isCombo'] == 'true')) {
                    if ($package['customer_acceptance_status'] == 'accepted') {
                        $comboOutgoingStocksArray[] = $package;
                    }
                }
            }
            $comboOutgoingStocks = count($comboOutgoingStocksArray) > 0 ? array_merge(...$comboOutgoingStocksArray) : '';
        }

        $sum_outgoingStocks = count($delivered_order_ids) > 0 ? $quantity_removed - $quantity_returned : 0;
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        //incase of combo
        if ($comboOutgoingStocks !== '') {
            $comboOutgoingStocksProducts = array_column($comboOutgoingStocksArray, 'product_id');
            $comboProducts = Product::whereIn('id', $comboOutgoingStocksProducts)->get(); //combo products

            foreach ($comboProducts as $key => $product) {

                $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $product_id)) > 0 ?
                    collect($product->comboProducts())->where('id', $product_id)->first()->quantity_combined : 0;
            }
        }

        $stock_available = $sum_incomingStocks - $sum_outgoingStocks;

        return $stock_available;
    }

    public function stockDiscount($sale_price, $discount, $discount_type = 'fixed')
    {

        if ($discount && $discount_type == 'fixed') {
            $amount = $sale_price - $discount;
        } elseif ($discount && $discount_type == 'percentage') {
            $amount =
                $sale_price - $sale_price * ($discount / 100);
        } else {
            $amount = $sale_price;
        }
        return $amount;
    }

    /**
     * Match form fields with required fields intelligently.
     *
     * @param string|array $requiredFields - The required field(s) from the model. Can be a string or an array.
     * @param array $formFields - The dynamic form fields.
     * @return array - An associative array of matched fields.
     */
    public function matchFormFieldsOLD($requiredFields, array $formFields): array
    {
        // Ensure $requiredFields is an array, even if it's a single string.
        $requiredFields = is_array($requiredFields) ? $requiredFields : [$requiredFields];

        $matchedFields = [];

        foreach ($requiredFields as $requiredField) {
            // Normalize the required field name (e.g., convert to lowercase and remove special characters)
            $normalizedRequiredField = $this->normalizeFieldName($requiredField);

            // Attempt to find a direct match first
            $directMatch = array_filter($formFields, function ($field) use ($normalizedRequiredField) {
                return $this->normalizeFieldName($field) === $normalizedRequiredField;
            });

            if (!empty($directMatch)) {
                $matchedFields[$requiredField] = reset($directMatch);
                continue;
            }

            // Try to find a partial match (e.g., contains both 'first' and 'name')
            $partialMatch = array_filter($formFields, function ($field) use ($normalizedRequiredField) {
                $normalizedFormField = $this->normalizeFieldName($field);
                return strpos($normalizedFormField, '-') !== false &&
                    strpos($normalizedFormField, '-') !== false;
            });

            if (!empty($partialMatch)) {
                $matchedFields[$requiredField] = reset($partialMatch);
                continue;
            }

            // As a last resort, find the closest match using Levenshtein distance
            $closestMatch = null;
            $shortestDistance = null;

            foreach ($formFields as $field) {
                $normalizedFormField = $this->normalizeFieldName($field);
                $levenshteinDistance = levenshtein($normalizedRequiredField, $normalizedFormField);

                if (is_null($shortestDistance) || $levenshteinDistance < $shortestDistance) {
                    $closestMatch = $field;
                    $shortestDistance = $levenshteinDistance;
                }
            }

            // Accept the closest match if it's within a reasonable threshold
            if ($closestMatch && $shortestDistance <= 3) { // 3 can be adjusted as needed
                $matchedFields[$requiredField] = $closestMatch;
            } else {
                // If no match is found, return null
                $matchedFields[$requiredField] = null;
            }
        }

        return $matchedFields;
    }

    /**
     * Normalize a field name for easier comparison.
     *
     * @param string $fieldName
     * @return string
     */
    public function normalizeFieldName(string $fieldName): string
    {
        return strtolower(preg_replace('/[^a-z0-9]/', '', $fieldName));
    }

    public function resetSite()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        //remember to change env to local, to avoid 'STDIN' constant error

        Artisan::call('migrate:refresh', [
            '--seed' => true
        ]);

        dd('Site reset successfully');
    }

    /**
     * Match required fields to form fields with fuzzy matching.
     *
     * @param array $requiredFields - An associative array of required fields and their variations.
     * @param array $formFields - An associative array of form fields submitted (key-value pairs).
     * @return array - An associative array with matched fields and their corresponding values.
     */
    public function matchFields(array $requiredFields, array $formFields)
    {
        $matchedFields = [];

        // Loop through each required field and its variations
        foreach ($requiredFields as $mainField => $variations) {
            // If the required field is just a string (no variations provided)
            if (is_int($mainField)) {
                $mainField = $variations; // Set the main field name
                $variations = [$mainField]; // Use the main field as the only variation
            } elseif (is_string($variations)) {
                // If the value is a string, convert it to an array with just that string
                $variations = [$variations];
            }

            $foundMatch = false;

            // Exact match search for any variation in the form fields
            foreach ($variations as $variation) {
                if (array_key_exists($variation, $formFields)) {
                    $matchedFields[$mainField] = $formFields[$variation];
                    $foundMatch = true;
                    break; // Stop once the first exact match is found
                }
            }

            // If no exact match is found, try fuzzy matching
            if (!$foundMatch) {
                $bestMatch = null;
                $highestSimilarity = 0;

                foreach ($formFields as $formFieldKey => $formFieldValue) {
                    foreach ($variations as $variation) {
                        // Calculate the similarity between the variation and the form field key
                        similar_text(strtolower($variation), strtolower($formFieldKey), $similarity);

                        // If similarity is higher than the previous highest and above a certain threshold (e.g., 60%)
                        if ($similarity > $highestSimilarity && $similarity > 60) {
                            $highestSimilarity = $similarity;
                            $bestMatch = $formFieldKey;
                        }
                    }
                }

                // If a fuzzy match is found, use it; otherwise, set it to null
                if ($bestMatch) {
                    $matchedFields[$mainField] = $formFields[$bestMatch];
                } else {
                    $matchedFields[$mainField] = null;
                }
            }
        }

        return $matchedFields;
    }
}
