
<html>
    <head>
        <style>
            header {
                text-align: center;
                font-size: 24px;
                color: rgb(219, 18, 18);
            }

            .invoice {
                text-align: left;
                font-size: 24px;
            }

            .customer_details {
                text-align: left;
                font-size: 18px;
            }
            .product_details {
                text-align: left;
                font-size: 18px;
                border: 1px solid gray;
                padding: 5px;
                border-radius: 5px;
                margin-bottom: 5px;
            }

            footer{
                display: flex;
                justify-content: space-between;
                align-content: center;
            }

        </style>
    </head>

    <body>
        <header>You just received a new order</header>

        <h1 class="invoice">Invoice No: kp-{{ $invoiceData['orderId'] }}</h1>

        <div class="customer_details">

            Name: {{ $invoiceData['customer']->firstname }} {{ $invoiceData['customer']->lastname }} <br>
            Phone Number: <a href="tel:{{ $invoiceData['customer']->phone_number }}">{{ $invoiceData['customer']->phone_number }}</a><br>
            Whatsapp Phone Number:
            @if (substr($invoiceData['customer']->whatsapp_phone_number, 0, 1) === '0')
                @php
                    $whatsapp_phone_number = '234' . substr($invoiceData['customer']->whatsapp_phone_number, 1)
                @endphp
            <a href="https://wa.me/{{ $whatsapp_phone_number }}?text={{ $invoiceData['whatsapp_msg'] }}">
                {{ $invoiceData['customer']->whatsapp_phone_number }}</a> <br>
            @else
            <a href="https://wa.me/{{ $whatsapp_phone_number }}?text={{ $invoiceData['whatsapp_msg'] }}">
                {{ $invoiceData['customer']->whatsapp_phone_number }}</a> <br>
            @endif
            
            Delivery Address: {{ $invoiceData['customer']->delivery_address }}
        </div>

        <hr>

        <h2>Products</h2>

        {{-- $invoiceData = [
            'order' => $order,
            'orderId' => $orderId,
            'customer' => $customer,
            'mainProducts_outgoingStocks' => $mainProducts_outgoingStocks,
            'mainProduct_revenue' => $mainProduct_revenue,
            'orderbump_outgoingStock' => $orderbump_outgoingStock,
            '$orderbumpProduct_revenue' => $orderbumpProduct_revenue,

            'upsell_outgoingStock' => $upsell_outgoingStock,
            'upsellProduct_revenue' => $upsellProduct_revenue,
            'qty_total' => $qty_total,
            'order_total_amount' => $order_total_amount,
            'grand_total' => $grand_total,
        ]; --}}

        @foreach ($invoiceData['mainProducts_outgoingStocks'] as $main_outgoingStock)
        <div class="product_details">
            <h4>Main Product</h4>
            Product {{ $main_outgoingStock->product->name }} <br>
            Price: N{{ $invoiceData['mainProduct_revenue'] }} <br>
            Qty: {{ $main_outgoingStock->quantity_removed }}
        </div>
        @endforeach

        @if ($invoiceData['orderbump_outgoingStock'] != '')
        
            <div class="product_details">
                <h4>Order bump Product</h4>
                Product {{ $invoiceData['orderbump_outgoingStock']->product->name }} <br>
                Price: N{{ $invoiceData['orderbump_outgoingStock']->product->sale_price * $invoiceData['orderbump_outgoingStock']->quantity_removed }} <br>
                Qty: {{ $invoiceData['orderbump_outgoingStock']->quantity_removed }}
            </div>
        
        @endif

        @if ($invoiceData['upsell_outgoingStock'] != '')
        
            <div class="product_details">
                <h4>Upsell Product</h4>
                Product {{ $invoiceData['upsell_outgoingStock']->product->name }} <br>
                Price: N{{ $invoiceData['upsell_outgoingStock']->product->sale_price * $invoiceData['upsell_outgoingStock']->quantity_removed }} <br>
                Qty: {{ $invoiceData['upsell_outgoingStock']->quantity_removed }}
            </div>
        
        @endif

        <hr>
        <div class="total">
            Total Qty : {{ $invoiceData['qty_total'] }} <br>
            Subtotal : N{{ $invoiceData['order_total_amount'] }} <br>
            Discount:  <br>
            Grand Total: N{{ $invoiceData['grand_total'] }}
        </div>
        
        <hr>
        <footer>
           <p>Thanks for your patronage</p>
           <p>&copy; <span class="copyright-date"></span> <strong><span class="project-name">KIPTRAK</span></strong>. All rights reserved</p>
        </footer>



        <h1>Mail from kiptrak</h1>
        
    </body>
</html>

 
 