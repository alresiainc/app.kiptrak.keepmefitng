
<<html>
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
        <header>You just placed an order from Kiptrak.com.ng</header>

        <h1 class="invoice">Invoice No: 12345</h1>

        <div class="customer_details">
            Name: Ugo Sunday <br>
            Phone Numbers: 090876543456, 090876543456 <br>
            Delivery Address: Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio!
        </div>

        <hr>

        <h2>Products</h2>

        @foreach ($invoiceData['users'] as $user)
        <div class="product_details">
            Product {{ $user->name }} <br>
            Price: N1000 <br>
            Qty: 1
        </div>
        @endforeach

        <hr>
        <div class="total">
            Subtotal : N3000 <br>
            Discount: N0.00 <br>
            Grand Total: N3000
        </div>
        
        <hr>
        <footer>
           <p>Thanks for your patronage</p>
           <p>&copy; <span class="copyright-date"></span> <strong><span class="project-name">KIPTRAK</span></strong>. All rights reserved</p>
        </footer>



        <h1>Mail from kiptrak {{ $invoiceData['user']->name }}</h1>
        
    </body>
</html>

 
 