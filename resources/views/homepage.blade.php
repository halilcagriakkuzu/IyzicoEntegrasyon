@extends('layout')

@section('title', 'Ana Sayfa')

@section('totalProductCount', $totalProductCount)

@section('content')
<div class="row">

    <div class="col-lg-12">
        <h1 class="my-4 text-center">Akkuzu Bilgisayar</h1>

        <div class="row">

            @foreach ($products as $product)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <a href="#" class="product-image"
                        style="background-image: url('{{ asset('product_images/'.$product->image) }}')"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <span>{{ $product->name }}</span>
                        </h4>
                        <h5>{{ $product->price }}₺</h5>
                        <p class="card-text">{{ $product->description }}</p>
                    </div>
                    <div class="card-footer">
                        <div class="float-left">
                            @if (empty($product->stock) || $product->stock <= 0) <small class="text-danger">
                                Stokta Yok
                                </small>
                                @else
                                <small class="text-primary">
                                    Stokta Var ({{ $product->stock }} Adet)
                                </small>
                                @endif
                        </div>
                        <div class="float-right">
                            <button class="btn btn-sm btn-success" type="button" onclick="addToCart({{ empty($product->stock) || $product->stock <= 0 ? '-1' : $product->id }})"
                                {{ empty($product->stock) || $product->stock <= 0 ? 'disabled' : '' }}>Sepete
                                Ekle</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        <!-- /.row -->

    </div>
    <!-- /.col-lg-12 -->

</div>
@endsection

@section('javascript')
<script>
    function increaseTotalCartCount() {
        var total = $('#totalProductCount').text();
        total++
        $('#totalProductCount').text(total);
    }

    function addToCart(productId) {
        $.ajax({
                method: "POST",
                url: "{{ Route('addToCart') }}",
                data: {
                    productId: productId
                },
                statusCode: {
                    404: function(responseObject, textStatus) {
                        alert(responseObject.responseJSON.message);
                    },
                    200: function(responseJSON, textStatus) {
                        increaseTotalCartCount();
                        alert(responseJSON.message);
                    }           
                }
            });
    }
</script>
@endsection