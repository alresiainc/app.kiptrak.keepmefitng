<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://mailtrap.io ">
    <title>KIPTRAK INVOICE</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('/myassets/css/invoice.css') }}">
</head>



<body>

    <div class="container mt-5 mb-3">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="d-flex flex-row p-2"> <img src="{{ asset('/assets/img/logo.png') }}" width="48">
                        <div class="d-flex flex-column"> <span class="font-weight-bold">Tax Invoice</span> <small>INV-001</small> </div>
                    </div>
                    <hr>
                    <div class="table-responsive p-2">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="add">
                                    <td>To</td>
                                    <td>From</td>
                                </tr>
                                <tr class="content">
                                    <td class="font-weight-bold">Google <br>Attn: John Smith Pymont <br>Australia</td>
                                    <td class="font-weight-bold">Facebook <br> Attn: John Right Polymont <br> USA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="products p-2">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="add">
                                    <td>Description</td>
                                    <td>Days</td>
                                    <td>Price</td>
                                    <td class="text-center">Total</td>
                                </tr>
                                <tr class="content">
                                    <td>Website Redesign</td>
                                    <td>15</td>
                                    <td>$1,500</td>
                                    <td class="text-center">$22,500</td>
                                </tr>
                                <tr class="content">
                                    <td>Logo & Identity</td>
                                    <td>10</td>
                                    <td>$1,500</td>
                                    <td class="text-center">$15,000</td>
                                </tr>
                                <tr class="content">
                                    <td>Marketing Collateral</td>
                                    <td>3</td>
                                    <td>$1,500</td>
                                    <td class="text-center">$4,500</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="products p-2">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="add">
                                    <td></td>
                                    <td>Subtotal</td>
                                    <td>GST(10%)</td>
                                    <td class="text-center">Total</td>
                                </tr>
                                <tr class="content">
                                    <td></td>
                                    <td>$40,000</td>
                                    <td>2,500</td>
                                    <td class="text-center">$42,500</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="address p-2">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="add">
                                    <td>Bank Details</td>
                                </tr>
                                <tr class="content">
                                    <td> Bank Name : ADS BANK <br> Swift Code : ADS1234Q <br> Account Holder : Jelly Pepper <br> Account Number : 5454542WQR <br> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</body>
</html>