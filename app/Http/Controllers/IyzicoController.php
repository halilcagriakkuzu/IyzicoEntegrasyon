<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Faker\Provider\bg_BG\Payment;

class IyzicoController extends Controller
{
    private function getTotalPrice() {
        $cart = session()->get('cart');
        $totalPrice = 0;
        foreach ($cart as $productId => $detail) {
            $totalPrice += $detail['price'] * $detail['quantity'];
        }
        $totalPrice = number_format((float)$totalPrice, 2, '.', '');
        return $totalPrice;
    }

    private function getDiscountPrice($cut) {
        $cart = session()->get('cart');
        $totalPrice = 0;
        foreach ($cart as $productId => $detail) {
            $totalPrice += $this->priceCut($detail['price'], $cut) * $detail['quantity'];
        }
        $totalPrice = number_format((float)$totalPrice, 2, '.', '');
        return $totalPrice;
    }

    private function getTotalPriceWithVAT($price) {
        $priceVAT = $price * 1.18;
        $priceVAT = number_format((float)$priceVAT, 2, '.', '');
        return $priceVAT;
    }

    private function priceCut($price, $cut){
        return $price * (1 - ($cut / 100));
    }

    private function checkDiscount($ccNumber){
        if (substr($ccNumber,0, 6) == '498749') {
            return true;
        }
        return false;
    }

    public function requestPayment(Request $request)
    {
        
        // validate $request;

        $options = new \Iyzipay\Options();
        $options->setApiKey(env('IYZICO_API_KEY', null));
        $options->setSecretKey(env('IYZICO_API_SECRET', null));
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");

        $discountRate = 20;
        $isDiscounted = $this->checkDiscount($request->input('cc-number'));
        
        $payRequest = new \Iyzipay\Request\CreatePaymentRequest();
        $payRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $dummyConversationId = mt_rand(100000000,999999999);
        $payRequest->setConversationId($dummyConversationId);
        $totalPrice = $this->getTotalPrice();
        $priceWithoutDiscount = 0;
        if ($isDiscounted) {
            $priceWithoutDiscount = $totalPrice;
            $totalPrice = $this->getDiscountPrice($discountRate);
        }
        $totalPriceWithVAT = $this->getTotalPriceWithVAT($totalPrice);
        $payRequest->setPrice($totalPrice);
        $payRequest->setPaidPrice($totalPriceWithVAT);
        $payRequest->setCurrency(\Iyzipay\Model\Currency::TL);
        $payRequest->setInstallment(1);
        $dummyBasketId = mt_rand(10000,99999);
        $payRequest->setBasketId("B".$dummyBasketId);
        $payRequest->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $payRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);

        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($request->input('cc-name'));
        $paymentCard->setCardNumber($request->input('cc-number')); //5528790000000008
        $paymentCard->setExpireMonth($request->input('cc-expire-month')); //12
        $paymentCard->setExpireYear($request->input('cc-expire-year')); //2030
        $paymentCard->setCvc($request->input('cc-cvv')); //123
        $paymentCard->setRegisterCard(0);
        $payRequest->setPaymentCard($paymentCard);

        $buyer = new \Iyzipay\Model\Buyer();
        $dummyBuyerId = mt_rand(100,999);
        $buyer->setId("BY".$dummyBuyerId);
        $buyer->setName($request->input('firstName'));
        $buyer->setSurname($request->input('lastName'));
        $buyer->setGsmNumber('+90'.$request->input('phone'));
        $buyer->setEmail($request->input('email'));
        $buyer->setIdentityNumber($request->input('identity'));
        $buyer->setLastLoginDate("2019-10-05 12:43:35");
        $buyer->setRegistrationDate("2018-04-21 15:12:09");
        $buyer->setRegistrationAddress($request->input('address'));
        $buyer->setIp($request->ip());
        $buyer->setCity($request->input('city'));
        $buyer->setCountry($request->input('country'));
        $buyer->setZipCode($request->input('zip'));
        $payRequest->setBuyer($buyer);

        $fullName = $request->input('firstName') . ' ' . $request->input('lastName');

        if ($request->input('same-address') == 'on') {
            $Icountry = $request->input('country');
            $Icity = $request->input('city');
            $Iaddress = $request->input('address');
            $Izip = $request->input('zip');
        } else {
            $Icountry = $request->input('Icountry');
            $Icity = $request->input('Icity');
            $Iaddress = $request->input('Iaddress');
            $Izip = $request->input('Izip');
        }

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($fullName);
        $shippingAddress->setCity($request->input('city'));
        $shippingAddress->setCountry($request->input('country'));
        $shippingAddress->setAddress($request->input('address'));
        $shippingAddress->setZipCode($request->input('zip'));
        $payRequest->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($fullName);
        $billingAddress->setCity($Icity);
        $billingAddress->setCountry($Icountry);
        $billingAddress->setAddress($Iaddress);
        $billingAddress->setZipCode($Izip);
        $payRequest->setBillingAddress($billingAddress);

        $basketItems = array();
        $cart = session()->get('cart');
        foreach ($cart as $productId => $detail) {
            for ($i = 0; $i < $detail["quantity"] ; $i++) {
                $basketItem = new \Iyzipay\Model\BasketItem();
                $basketItem->setId("BI".$productId);
                $basketItem->setName($detail["name"]);
                $basketItem->setCategory1("Electronics");
                $basketItem->setCategory2("OEM");
                $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
                $basketItem->setPrice($detail['price']);
                if ($isDiscounted) {
                    $basketItem->setPrice($this->priceCut($detail['price'], $discountRate));
                }
                array_push($basketItems, $basketItem);
            }
        }
        $payRequest->setBasketItems($basketItems);

        $payment = \Iyzipay\Model\Payment::create($payRequest, $options);

        session()->flush();
        return view('paymentResult', [
            'payment' => $payment, 
            'totalProductCount' => 0, 
            'isDiscounted' => $isDiscounted, 
            'discountRate' => $discountRate, 
            'priceWithoutDiscount' => $this->getTotalPriceWithVAT($priceWithoutDiscount),
            'totalPriceWithVAT' => $totalPriceWithVAT
            ]);
    }
}