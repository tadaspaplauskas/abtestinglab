<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Payment;

class PaymentController extends Controller
{

    public $plans = [
        'v10' => ['visitors' => 10000, 'price' => 9],
        'v50' => ['visitors' => 50000, 'price' => 39],
        'v100' => ['visitors' => 100000, 'price' => 59],
    ];

    public $paypal = [
        'url' => 'ssl://www.sandbox.paypal.com', //live https://www.paypal.com/cgi-bin/webscr, sandbox https://www.sandbox.paypal.com/cgi-bin/webscr
        'path' => '/cgi-bin/webscr',
    ];

    public $receiver_email = ['abtestinglab.com@gmail.com', 'abtestinglab.com-facilitator@gmail.com'];



    //handle paypal call
    public function receivedPaypal(Request $request, User $user, Payment $payment)
    {
        //logging raw entry to have entries if stuff will go wrong
        DB::table('paypal_logs')->insert([
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
            'dump' => json_encode($request->all())
            ]);

        //check if the same transaction didnt already come
        if ($payment->where('txn_id', $request->get('txn_id'))->exists())
            return $this->customPaypalError($request, $request->get('txn_id') . ' transaction logged');

        //check if status is completed - we want to count only the money that reached us
        if (strcasecmp($request->get('payment_status'), 'Completed') !== 0)
            return $this->customPaypalError($request, 'status not completed');

        //check if status is completed - we want to count only the money that reached us
        if (!in_array($request->get('receiver_email'), $this->receiver_email))
            return $this->customPaypalError($request, 'wrong receiver email');

        //seems okay

        $user = $user->where('email', $request->get('payer_email'))->first();

        if (!isset($user->id))
            $user_id = 0;
        else
            $user_id = $user->id;

        $visitors = 0;
        if (isset($this->plans[$request->get('item_number')]))
        {
            $visitors = $this->plans[$request->get('item_number')]['visitors'] * $request->get('quantity');
        }

        $payment = $payment->create([
            'user_id' => $user_id,
            'email' => $request->get('payer_email'),
            'visitors' => $visitors,
            'plan' => $request->get('item_number'),
            'quantity' => $request->get('quantity'),
            'gross' => $request->get('mc_gross'),
            'txn_id' => $request->get('txn_id'),
            'dump' => json_encode($request->all()),

        ]);

        //gotta return empty 200 response to make paypal happy
        return '';

    }

    public function success(Request $request)
    {
        return ' Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you.';

    }

    public function cancel(Request $request)
    {


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function customPaypalError($request, $msg = '')
    {
        if (stripos($request->header('User-Agent'), 'PayPal') === false)
            return $this->respondError($msg);
        else
        {
            //TODO implement some kind of reporting to me
            return '';
        }
    }
}
