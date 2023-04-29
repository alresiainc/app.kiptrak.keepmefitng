<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTransfer extends Model
{
    use HasFactory, SoftDeletes;

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');    
    }
    public function fromWarehouse() {
        return $this->belongsTo(WareHouse::class, 'from_warehouse_id');    
    }
    public function toWarehouse() {
        return $this->belongsTo(WareHouse::class, 'to_warehouse_id');    
    }
    public function getProductQtyTransferredAttribute($value) {
        $idQtys = unserialize($value);
        $productsQty=[];
        foreach ($idQtys as $key => $idQty) {
            $id = strtok($idQty, '-');
            $product = Product::where('id', $id)->first();
            $qty = substr($idQty, strpos($idQty, "-") + 1);
            if (isset($product)) {
                $new = ['each_product'=>[$qty.' quantity of '.$product->name]];
                $idQtys[$key] = $new;
            }
        }
        $output = $idQtys; //"product_qty_transferred":[{"each_product":["10 quantity of Product 5"]},{"each_product":["10 quantity of Product 6"]}]
        return $output;   
    }
}
