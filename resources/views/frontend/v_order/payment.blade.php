@extends('frontend.layouts.index')
@section('content')
    <div class="section">
        <div class="container">
            <div class="row row-equal">
                <div class="col-md-12">
                    <div class="order-summary clearfix">
                        <div class="section-title">
                            <p>KERANJANG</p>
                            <h3 class="title">Keranjang Belanja</h3>
                        </div>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <strong>{{ session('error') }}</strong>
                            </div>
                        @endif
                        @if ($order && $order->orderItems->count() > 0)
                            <table class="shopping-cart-table table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th></th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Total</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalHarga = 0;
                                    @endphp
                                    @foreach ($order->orderItems as $item)
                                        @php
                                            $totalHarga += $item->harga * $item->quantity;
                                        @endphp
                                        <tr>
                                            <td class="thumb"><img
                                                    src="{{ asset('storage/' . $item->produk->image) }}"
                                                    alt=""></td>
                                            <td class="details">
                                                <a>{{ $item->produk->nm_barang }}</a>
                                            </td>
                                            <td class="price text-center"><strong>Rp.
                                                    {{ number_format($item->produk->hrgjual_barang, 0, ',', '.') }}</strong></td>
                                            <td class="qty text-center">
                                                <a> {{ $item->quantity }} </a>
                                            </td>
                                            <td class="total text-center"><strong class="primary-color">Rp.
                                                    {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</strong>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="empty" colspan="3"></th>
                                        <th>SUBTOTAL</th>
                                        <th colspan="2" class="sub-total">Rp.
                                            {{ number_format($totalHarga, 0, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th class="empty" colspan="3"></th>
                                        <th>TOTAL BAYAR</th>
                                        <th colspan="2" class="total">Rp.
                                            {{ number_format($totalHarga, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>

                            <input type="hidden" name="total_price" value="{{ $totalHarga }}">
                            <div class="pull-right">
                                <button class="primary-btn" id="pay-button">Bayar Sekarang</button>
                            </div>
                        @else
                            <p>Keranjang belanja kosong.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    alert("payment success!");
                    console.log(result);
                    window.location.href = "{{ route('order.complete') }}";
                },
                onPending: function(result) {
                    alert("waiting for your payment!");
                    console.log(result);
                },
                onError: function(result) {
                    alert("payment failed!");
                    console.log(result);
                },
                onClose: function() {
                    alert('you closed the popup without finishing the payment');
                }
            });
        });
    </script>
@endsection
