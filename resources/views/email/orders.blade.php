<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
</head>
<body>

    <h3>Thanks for shopping</h3>

    <div class="col-sm-4 invoice-col">
        <h1 class="h5 mb-3">Shipping Address</h1>
        <address>
            <strong>{{ $mailData['orders']->user_name }}</strong><br>
            {{ $mailData['orders']->address }}<br>
            {{ $mailData['orders']->city }}, {{ $mailData['orders']->zip }}<br>
            Phone: {{ $mailData['orders']->mobile_no }}<br>
        </address>
    </div>

    <div class="col-sm-4 invoice-col">
        <b>Invoice #{{ $mailData['orders']->id }}</b><br><br>

        <b>Order ID:</b> {{ $mailData['orders']->id }}<br>
        <b>Total:</b> ${{ $mailData['orders']->grand_total }}<br>
    </div>

    <div class="card-body table-responsive p-3">
        <table class="table table-striped" border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Product</th>
                    <th width="100">Price</th>
                    <th width="100">Qty</th>
                    <th width="100">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mailData['orders']->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>${{ $item->price }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>${{ $item->total  }}</td>
                    </tr>
                @endforeach

                <tr>
                    <th colspan="3" class="text-right">Subtotal:</th>
                    <td>${{ $mailData['orders']->subtotal }}</td>
                </tr>

                <tr>
                    <th colspan="3" class="text-right">Shipping:</th>
                    <td>${{ $mailData['orders']->shipping }}</td>
                </tr>

                <tr>
                    <th colspan="3" class="text-right">Discount:</th>
                    <td>${{ $mailData['orders']->discount != 0 ? $mailData['orders']->discount : 0 }}</td>
                </tr>

                <tr>
                    <th colspan="3" class="text-right">Grand Total:</th>
                    <td>${{ $mailData['orders']->grand_total }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
