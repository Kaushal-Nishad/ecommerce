<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Input;
use App\Models\UserWallet;
use App\Models\Wallet_Transactions;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Models\PaymentData;
use App\Models\Users;
use Yajra\DataTables\DataTables;
use Razorpay\Api\Api;
use Exception;

class PaymentController extends Controller
{
  private $paypalClient;

  public function __construct()
  {
    $paypal_conf = Config::get('paypal');
    $environment = $paypal_conf['settings']['mode'] === 'live'
      ? new ProductionEnvironment($paypal_conf['client_id'], $paypal_conf['secret'])
      : new SandboxEnvironment($paypal_conf['client_id'], $paypal_conf['secret']);

    $this->paypalClient = new PayPalHttpClient($environment);
  }




  public function payWithpaypal($amt, Request $request)
  {
    Session::put('order', $request->input());
    $amountToBePaid = $amt;

    $orderRequest = new OrdersCreateRequest();
    $orderRequest->prefer('return=representation');
    $orderRequest->body = [
      'intent' => 'CAPTURE',
      'purchase_units' => [[
        'amount' => [
          'currency_code' => 'USD',
          'value' => $amountToBePaid,
        ],
      ]],
      'application_context' => [
        'return_url' => route('paypal-status'),
        'cancel_url' => route('paypal-status'),
      ],
    ];

    try {
      $response = $this->paypalClient->execute($orderRequest);
      $approvalUrl = array_filter($response->result->links, fn($link) => $link->rel === 'approve')[0]->href;

      Session::put('paypal_payment_id', $response->result->id);
      Session::put('amount', $amt);

      return Redirect::away($approvalUrl);
    } catch (\Exception $ex) {
      Session::put('error', 'Connection timeout or error occurred');
      return redirect()->back();
    }
  }




  public function getPaymentStatus(Request $request)
  {
    $payment_id = Session::get('paypal_payment_id');

    if (empty($request->PayerID) || empty($request->token)) {
      session()->flash('error', 'Payment failed');
      return redirect()->back();
    }

    try {
      $captureRequest = new OrdersCaptureRequest($payment_id);
      $response = $this->paypalClient->execute($captureRequest);

      if ($response->result->status === 'COMPLETED') {
        $this->processOrder($request, $payment_id);
        session()->flash('success', 'Order Confirmed Successfully');
        return redirect('success');
      }
    } catch (\Exception $ex) {
      session()->flash('error', 'Payment failed: ' . $ex->getMessage());
    }

    return redirect()->back();
  }

  private function processOrder(Request $request, $payment_id)
  {
    $user_id = session()->get('user_id');
    $requestData = session()->get('order');

    Users::where('user_id', $user_id)->update([
      'phone' => $requestData['phone'],
      'country' => $requestData['country'],
      'state' => $requestData['state'],
      'city' => $requestData['city'],
      'address' => $requestData['address'],
      'pin_code' => $requestData['code'],
    ]);

    $total_qty = array_sum($requestData['qty']);

    $payment = PaymentData::create([
      'amount' => Session::get('amount'),
      'txn_id' => $payment_id,
      'pay_method' => 'paypal',
      'pay_status' => 1,
    ]);

    $order = Order::create([
      'user' => $user_id,
      'products' => count($requestData['product_id']),
      'qty' => $total_qty,
      'pay_id' => $payment->id,
      'amount' => Session::get('amount'),
    ]);

    foreach ($requestData['product_id'] as $i => $productId) {
      OrderProducts::create([
        'order_id' => $order->id,
        'product_id' => $productId,
        'product_qty' => $requestData['qty'][$productId],
        'product_color' => $requestData['product_color'][$productId] ?? null,
        'product_attr' => $requestData['product_attr'][$productId] ?? null,
        'product_amount' => $requestData['price'][$productId],
        'product_delivery' => 0,
      ]);

      DB::table('cart')->where('product_user', $user_id)->where('product_id', $productId)->delete();
    }

    Session::forget('paypal_payment_id');
    Session::forget('amount');
  }



  public function payWithRazorpay($amt, $pay_id, Request $request)
  {
    //  return $request->input();
    Session::put('order', $request->input());
    $user_id = session()->get('user_id');
    $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

    $payment = $api->payment->fetch($pay_id);

    if (!empty($pay_id)) {
      try {
        $response = $api->payment->fetch($pay_id)->capture(array('amount' => $payment['amount']));
        //  return $request->input();

        Users::where('user_id', $user_id)->update([
          'phone' => $request->phone,
          'country' => $request->country,
          'state' => $request->state,
          'city' => $request->city,
          'address' => $request->address,
          'pin_code' => $request->code,
        ]);
        $total_qty = 0;
        foreach ($request->qty as $key => $value) {
          $total_qty += $value;
        }
        // return $total_qty;

        $payment = new PaymentData();
        $payment->amount = $amt;
        $payment->txn_id = $pay_id;
        $payment->pay_method = 'razorpay';
        $payment->pay_status = 1;
        $payment->save();

        $order = new Order();
        $order->user = $user_id;
        $order->products = count($request->product_id);
        $order->qty = $total_qty;
        $order->pay_id = $payment->id;
        $order->amount = $amt;
        $order->save();

        $product_qty = (array) $request->qty;
        $product_color = (array) $request->product_color;
        $product_attr = (array) $request->product_attr;
        $product_amount = (array) $request->price;

        for ($i = 0; $i < count($request->product_id); $i++) {
          $order_products = new OrderProducts();
          $order_products->order_id = $order->id;
          $order_products->product_id = $request->product_id[$i];
          $order_products->product_qty = $product_qty[$request->product_id[$i]];
          if (!empty($product_color)) {
            $order_products->product_color = $product_color[$request['product_id'][$i]];
          }
          if (!empty($product_attr)) {
            $order_products->product_attr = $product_attr[$request['product_id'][$i]];
          }
          $order_products->product_amount = $product_amount[$request->product_id[$i]];
          $order_products->product_delivery = 0;
          $order_products->save();

          DB::table('cart')->where('product_user', $user_id)->where('product_id', $request->product_id[$i])->delete();
        }
        // if($request->checkout){
        //   DB::table('cart')->where('product_user',$user_id)->whereIn('product_id',$request->product_id)->delete();
        // }

        session()->flash('success', 'Order Confirmed Successfully');
        return redirect('success');
      } catch (Exception $e) {
        Session::flash('error', $e->getMessage());
        return redirect()->back();
      }
    }
  }


  public function success()
  {
    if (Session::has('order')) {
      Session::forget('order');
      return view('public.success');
    } else {
      return abort('404');
    }
  }
}
