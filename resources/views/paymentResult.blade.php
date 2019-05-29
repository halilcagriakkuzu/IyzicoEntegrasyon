@extends('layout')

@section('title', 'Ana Sayfa')

@section('totalProductCount', $totalProductCount)

@section('content')
<div class="row">

    <div class="col-lg-12">
        <h1 class="my-4 text-center">Akkuzu Bilgisayar</h1>

        <div class="row">
            <div class="col-md-12">
                @if ($payment->getStatus() == 'success')
                    <div class="jumbotron">
                        <h1 class="display-4">Başarılı!</h1>
                        <p class="lead">{{$payment->getPaymentId()}} Numaralı siparişiniz alınmıştır.</p>
                        @if ($isDiscounted)
                            <p class="alert alert-success">
                                Size özel %{{$discountRate}} indirim fırsatını yakaladınız.<br>
                                {{$priceWithoutDiscount}} yerine {{$totalPriceWithVAT}} (KDV dahil) ödediniz.
                            </p>
                        @endif
                    </div>
                @else
                    <div class="jumbotron">
                        <h1 class="display-4">Bir Hata Oluştu!</h1>
                        <p class="lead">Hata: {{$payment->getErrorMessage()}}</p>
                    </div>
                @endif
                
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.col-lg-12 -->

</div>
@endsection

@section('javascript')

@endsection