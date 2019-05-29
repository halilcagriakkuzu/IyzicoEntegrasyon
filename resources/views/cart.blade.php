@extends('layout')

@section('title', 'Sepetim')

@section('totalProductCount', $summary['totalProductCount'])

@section('content')
<div class="row">

    <div class="col-lg-12">
        <h1 class="my-4 text-center">Akkuzu Bilgisayar</h1>

        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Sepetim</span>
                    <span class="badge badge-secondary badge-pill">{{ $summary['totalProductCount'] }}</span>
                </h4>
                @if ($cart != null)
                    <ul class="list-group mb-3">
                        @foreach ($cart as $productId => $product)
                        <li id="product{{ $productId }}"
                            class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">{{ $product['name'] }} <i>(<span
                                            id="productQuantity{{ $productId }}">{{ $product['quantity'] }}</span>)</i>
                                </h6>
                                <button class="btn btn-sm btn-success" onclick="addToCart({{ $productId }})"> + </button>
                                <button class="btn btn-sm btn-danger" onclick="removeToCart({{ $productId }})"> - </button>
                            </div>
                            <span class="text-muted">{{ $product['price']}}₺</span>
                        </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Toplam Fiyat (TL)</span>
                            <strong><span id="totalPrice">{{ $summary['totalPrice']}}</span>₺ + KDV</strong>
                        </li>
                    </ul>
                @endif
            </div>
            <div class="col-md-8 order-md-1">
                <h4 class="mb-3">Sipariş Bilgileriniz</h4>
                <form class="needs-validation" method="POST" action="{{ Route('requestPayment') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">Ad</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Adile"
                                value="" required="">
                            <div class="invalid-feedback">
                                Ad alanı gereklidir.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Soyad</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Naşit"
                                value="" required="">
                            <div class="invalid-feedback">
                                Soyad alanı gereklidir.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="username">TC Kimlik No / Vergi No</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="identity" name="identity"
                                placeholder="10000000000" required="">
                            <div class="invalid-feedback" style="width: 100%;">
                                TC Kimlik No / Vergi No alanı gereklidir.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                            <label for="phone">Cep Telefonu (Başında 0 olmadan)</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    placeholder="5549994411" required="">
                                <div class="invalid-feedback" style="width: 100%;">
                                        Cep Telefonu alanı gereklidir.
                                </div>
                            </div>
                        </div>

                    <div class="mb-3">
                        <label for="email">Eposta</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="isim@eposta.com"
                            required="">
                        <div class="invalid-feedback">
                            Eposta alanı gereklidir.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address">Kargo Adresi</label>
                        <input type="text" class="form-control" id="address" name="address"
                            placeholder="Atatürk Mahallesi - Yeşilçam Sokak" required="">
                        <div class="invalid-feedback">
                            Adres alanı gereklidir.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="country">Ülke</label>
                            <input type="text" class="form-control" id="country" name="country" placeholder="Türkiye"
                                required="">
                            <div class="invalid-feedback">
                                Ülke alanı gereklidir.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="city">Şehir</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="İstanbul"
                                required="">
                            <div class="invalid-feedback">
                                Ülke alanı gereklidir.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="zip">Posta Kodu</label>
                            <input type="text" class="form-control" id="zip" name="zip" placeholder="34999" required="">
                            <div class="invalid-feedback">
                                Posta kodu alanı gereklidir.
                            </div>
                        </div>
                    </div>
                    <div id="invoiceAddress">
                        <div class="mb-3">
                            <label for="Iaddress">Fatura Adresi</label>
                            <input type="text" class="form-control" id="Iaddress" name="Iaddress"
                                placeholder="Atatürk Mahallesi - Yeşilçam Sokak" required="">
                            <div class="invalid-feedback">
                                Adres alanı gereklidir.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="Icountry">Ülke</label>
                                <input type="text" class="form-control" id="Icountry" name="Icountry"
                                    placeholder="Türkiye" required="">
                                <div class="invalid-feedback">
                                    Ülke alanı gereklidir.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="Icity">Şehir</label>
                                <input type="text" class="form-control" id="Icity" name="Icity" placeholder="İstanbul"
                                    required="">
                                <div class="invalid-feedback">
                                    Ülke alanı gereklidir.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="Izip">Posta Kodu</label>
                                <input type="text" class="form-control" id="Izip" name="Izip" placeholder="34999"
                                    required="">
                                <div class="invalid-feedback">
                                    Posta kodu alanı gereklidir.
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="same-address" name="same-address" onchange="toggleSameAddress(event)">
                        <label class="custom-control-label" for="same-address">Fatura adresim kargo adresi ile aynı olsun.</label>
                    </div>
                    <hr class="mb-4">

                    <h4 class="mb-3">Kredi Kartı Bilgileri</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cc-name">Kart Üzerindeki İsim</label>
                            <input type="text" class="form-control" id="cc-name" name="cc-name" placeholder="" required="">
                            <small class="text-muted">Kartın üzerinde yazdığı şekilde tamamını yazınız.</small>
                            <div class="invalid-feedback">
                                    Kart Üzerindeki İsim alanı gereklidir.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc-number">Kredi Kartı Numarası</label>
                            <input type="text" class="form-control" id="cc-number" name="cc-number" placeholder="" required="">
                            <div class="invalid-feedback">
                                    Kredi Kartı Numarası alanı gereklidir.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="cc-expire-month">Son Kullanma Tarihi (Ay)</label>
                            <select class="custom-select d-block w-100" id="cc-expire-month" name="cc-expire-month" required="">
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>                            
                            <div class="invalid-feedback">
                                    Son Kullanma Tarihi (Ay) alanı gereklidir.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                                <label for="cc-expire-year">Son Kullanma Tarihi (Yıl)</label>
                                <select class="custom-select d-block w-100" id="cc-expire-year" name="cc-expire-year" required="">
                                    <option value="2019">2019</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                    <option value="2031">2031</option>
                                </select>                            
                                <div class="invalid-feedback">
                                        Son Kullanma Tarihi (Yıl) alanı gereklidir.
                                </div>
                            </div>
                        <div class="col-md-3 mb-3">
                            <label for="cc-cvv">Güvenlik Numarası (CVV)</label>
                            <input type="text" class="form-control" id="cc-cvv" name="cc-cvv" placeholder="999" required="">
                            <div class="invalid-feedback">
                                    Güvenlik Numarası (CVV) alanı gereklidir.
                            </div>
                        </div>
                    </div>
                    <hr class="mb-4">
                    @if ($cart != null)
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Ödemeyi Yap</button>
                    @else
                        <button class="btn btn-secondary btn-lg btn-block" disabled>Önce Sepete Ürün Ekle</button>
                    @endif
                </form>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.col-lg-12 -->

</div>
@endsection

@section('javascript')
<script>
    function toggleSameAddress(e) {
        var isChecked = $(e.target).is(":checked");
        if (isChecked) {
            $('#invoiceAddress input').prop('required',false);
            $('#invoiceAddress').hide(222);
        } else {
            $('#invoiceAddress input').prop('required',true);
            $('#invoiceAddress').show(222);
        }
    }
    function increaseTotalCartCount() {
        var total = $('#totalProductCount').text();
        total++
        $('#totalProductCount').text(total);
    }

    function decreaseTotalCartCount() {
        var total = $('#totalProductCount').text();
        total--
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
                        var quantity = $('#productQuantity' + productId).text();
                        quantity++;
                        increaseTotalCartCount();
                        $('#productQuantity' + productId).text(quantity)
                        $('#totalPrice').text(responseJSON.totalPrice);
                        //alert(responseJSON.message);
                    }           
                }
            });
    }

    function removeToCart(productId) {
        $.ajax({
                method: "POST",
                url: "{{ Route('removeToCart') }}",
                data: {
                    productId: productId
                },
                statusCode: {
                    404: function(responseObject, textStatus) {
                        alert(responseObject.responseJSON.message);
                    },
                    200: function(responseJSON, textStatus) {
                        var quantity = $('#productQuantity' + productId).text();
                        quantity--;
                        decreaseTotalCartCount();
                        if (quantity <= 0) {
                            $('#product' + productId).remove();
                        } else {
                            $('#productQuantity' + productId).text(quantity)
                        }
                        $('#totalPrice').text(responseJSON.totalPrice);
                        //alert(responseJSON.message);
                    }           
                }
            });
    }
</script>
@endsection