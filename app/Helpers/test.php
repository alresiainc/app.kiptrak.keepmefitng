<?php

$data = $request->all();
if (isset($order->customer_id)) {

    //save Order
    $newOrder = new Order();
    $newOrder->form_holder_id = $formHolder->id;
    $newOrder->source_type = 'form_holder_module';
    $newOrder->status = 'new';
    $newOrder->save();

    //making a copy from the former outgoingStocks, in the case of dealing with an edited or duplicated form
    $outgoingStocks = $order->outgoingStocks; //this should be $order->outgoingStock->package_bundle
    
    foreach($outgoingStocks as $i => $outgoingStock)
    {
        //make copy of rows, and create new records
        if(isset($outgoingStock->product)) {
            $outgoingStocks[$i]->order_id = $newOrder->id;
            $outgoingStocks[$i]->quantity_returned = 0;
            $outgoingStocks[$i]->quantity_removed = 1;
            $outgoingStocks[$i]->amount_accrued = $outgoingStock->product->sale_price;
            $outgoingStocks[$i]->isCombo = isset($outgoingStock->product->combo_product_ids) ? 'true' : null;

            $x[$i] = (new OutgoingStock())->create($outgoingStock->only(['product_id', 'order_id', 'quantity_removed', 'amount_accrued',
            'reason_removed', 'quantity_returned', 'created_by', 'status']));
        }
    }

    foreach ($data['product_packages'] as $key => $product_id) {
        $data['product_id'] = $product_id;
        if (!empty($product_id)) {

            $idPriceQty = explode('-', $product_id);
            $productId = $idPriceQty[0];
            $saleUnitPrice = $idPriceQty[1];
            $qtyRemoved = $idPriceQty[2];

            //accepted updated
            $amount_accrued = $qtyRemoved * $saleUnitPrice;
            OutgoingStock::where(['product_id'=>$productId, 'order_id'=>$newOrder->id, 'reason_removed'=>'as_order_firstphase'])
            ->update(['quantity_removed'=>$qtyRemoved, 'amount_accrued'=>$amount_accrued, 'customer_acceptance_status'=>'accepted']);

            //rejected or declined updated
            $rejected_products = OutgoingStock::where('product_id', '!=', $productId)->where('order_id', $newOrder->id)
            ->where('reason_removed','as_order_firstphase')->get();
            foreach ($rejected_products as $key => $rejected) {
                $rejected->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>$rejected->quantity_removed]);
            }
        }
    }
}
//This code should take your "package_bundle" array, group it by "product_id," calculate the sums for "amount_accrued" and "quantity_removed" for each group, and then sort the groups based on the sum of "amount_accrued" in descending order, achieving the same result as your DB::raw logic.


//////////////////////////////////////////////////////////////////////////////////
$data = $request->all();

if (isset($order->customer_id)) {
    // Save Order
    $newOrder = new Order();
    $newOrder->form_holder_id = $formHolder->id;
    $newOrder->source_type = 'form_holder_module';
    $newOrder->status = 'new';
    $newOrder->save();

    // Extract the package_bundle from the order's outgoingStock
    $outgoingStockPackageBundle = $order->outgoingStock->package_bundle;

    foreach ($outgoingStockPackageBundle as $i => $outgoingStock) {
        // Make a copy of rows and create new records
        $outgoingStockData = [
            'product_id' => $outgoingStock['product_id'],
            'order_id' => $newOrder->id,
            'quantity_removed' => 1,
            'amount_accrued' => $outgoingStock['amount_accrued'],
            'reason_removed' => $outgoingStock['reason_removed'],
            'quantity_returned' => 0,
            'created_by' => /* provide the appropriate value */,
            'status' => /* provide the appropriate value */,
            'isCombo' => isset($outgoingStock['isCombo']) ? 'true' : null,
        ];

        // Create a new OutgoingStock record
        $x[$i] = (new OutgoingStock())->create($outgoingStockData);
    }

    foreach ($data['product_packages'] as $key => $product_id) {
        if (!empty($product_id)) {
            $idPriceQty = explode('-', $product_id);
            $productId = $idPriceQty[0];
            $saleUnitPrice = $idPriceQty[1];
            $qtyRemoved = $idPriceQty[2];

            // Accepted updated
            $amount_accrued = $qtyRemoved * $saleUnitPrice;
            OutgoingStock::where([
                'product_id' => $productId,
                'order_id' => $newOrder->id,
                'reason_removed' => 'as_order_firstphase'
            ])->update([
                'quantity_removed' => $qtyRemoved,
                'amount_accrued' => $amount_accrued,
                'customer_acceptance_status' => 'accepted'
            ]);

            // Rejected or declined updated
            $rejected_products = OutgoingStock::where('product_id', '!=', $productId)
                ->where('order_id', $newOrder->id)
                ->where('reason_removed', 'as_order_firstphase')
                ->get();

            foreach ($rejected_products as $key => $rejected) {
                $rejected->update([
                    'customer_acceptance_status' => 'rejected',
                    'quantity_returned' => $rejected->quantity_removed
                ]);
            }
        }
    }
}

///////////////////////////////////////////////////////////////////////////////////

$data = $request->all();

if (isset($order->customer_id)) {
    // Save Order
    $newOrder = new Order();
    $newOrder->form_holder_id = $formHolder->id;
    $newOrder->source_type = 'form_holder_module';
    $newOrder->status = 'new';
    $newOrder->save();

    // Extract the package_bundle from the order's outgoingStock
    $outgoingStockPackageBundle = $order->outgoingStock->package_bundle;

    foreach ($outgoingStockPackageBundle as $i => $outgoingStock) {
        // Only consider items with 'customer_acceptance_status' being 'accepted'
        if ($outgoingStock['customer_acceptance_status'] === 'accepted') {
            $product_id = $outgoingStock['product_id'];
            $amount_accrued = $outgoingStock['amount_accrued'];

            // Find the specific outgoingStock item by matching 'product_id' in the JSON
            $matchingOutgoingStock = collect($outgoingStocks)->first(function ($item) use ($product_id) {
                return $item['product_id'] === $product_id;
            });

            if ($matchingOutgoingStock) {
                // Update the matching outgoingStock item
                $matchingOutgoingStock->update([
                    'order_id' => $newOrder->id,
                    'quantity_removed' => 1,
                    'amount_accrued' => $amount_accrued,
                    'customer_acceptance_status' => 'accepted',
                    'isCombo' => isset($matchingOutgoingStock['isCombo']) ? 'true' : null,
                ]);
            }
        }
    }

    foreach ($data['product_packages'] as $key => $product_id) {
        if (!empty($product_id)) {
            $idPriceQty = explode('-', $product_id);
            $productId = $idPriceQty[0];
            $saleUnitPrice = $idPriceQty[1];
            $qtyRemoved = $idPriceQty[2];

            // Accepted updated
            $amount_accrued = $qtyRemoved * $saleUnitPrice;

            // Update the specific outgoingStock item with the matching 'product_id'
            OutgoingStock::whereJsonContains('package_bundle', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->where('order_id', $newOrder->id)
            ->where('reason_removed', 'as_order_firstphase')
            ->update([
                'quantity_removed' => $qtyRemoved,
                'amount_accrued' => $amount_accrued,
                'customer_acceptance_status' => 'accepted'
            ]);

            // Rejected or declined updated
            $rejected_products = OutgoingStock::where('order_id', $newOrder->id)
                ->whereJsonContains('package_bundle', function ($q) use ($productId) {
                    $q->where('product_id', '!=', $productId);
                })
                ->where('reason_removed', 'as_order_firstphase')
                ->get();

            foreach ($rejected_products as $key => $rejected) {
                $rejected->update([
                    'customer_acceptance_status' => 'rejected',
                    'quantity_returned' => $rejected->quantity_removed
                ]);
            }
        }
    }
}



?>
