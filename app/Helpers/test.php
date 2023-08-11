<?php

// Your array containing the "package_bundle" data
$packageBundleArray = [
    /* ... (your array here) ... */
];

// Initialize an empty array to store the grouped and summarized results
$groupedSummarizedArray = [];

// Step 2: Group the items by "product_id"
foreach ($packageBundleArray as $item) {
    $product_id = $item[0]['product_id'];
    if (!isset($groupedSummarizedArray[$product_id])) {
        $groupedSummarizedArray[$product_id] = [
            'product_id' => $product_id,
            'sold_amount' => 0,
            'sold_qty' => 0,
        ];
    }

    // Step 3: Calculate the sum of "amount_accrued" and "quantity_removed" for each group
    foreach ($item as $subItem) {
        $groupedSummarizedArray[$product_id]['sold_amount'] += $subItem['amount_accrued'];
        $groupedSummarizedArray[$product_id]['sold_qty'] += $subItem['quantity_removed'];
    }
}

// Step 4: Sort the groups based on the sum of "amount_accrued" in descending order
usort($groupedSummarizedArray, function ($a, $b) {
    return $b['sold_amount'] - $a['sold_amount'];
});

// Output the resulting array
print_r($groupedSummarizedArray);
?>
This code should take your "package_bundle" array, group it by "product_id," calculate the sums for "amount_accrued" and "quantity_removed" for each group, and then sort the groups based on the sum of "amount_accrued" in descending order, achieving the same result as your DB::raw logic.





