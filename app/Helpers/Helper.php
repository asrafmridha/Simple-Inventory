<?php


if (!function_exists('generatePurchaseNumber')) {

    function generatePurchaseNumber()
    {
        $lastPurchase = \App\Models\Purchase::latest('id')->first();

        if (!$lastPurchase) {
            return 'PUR1001';
        }

        $lastNumber = intval(str_replace('PUR', '', $lastPurchase->purchase_number));

        $newNumber = $lastNumber + 1;

        return 'PUR' . $newNumber;
    }
}
