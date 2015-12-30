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
        'url' => 'tls://www.sandbox.paypal.com', //live https://www.paypal.com/cgi-bin/webscr, sandbox https://www.sandbox.paypal.com/cgi-bin/webscr
        'path' => '/cgi-bin/webscr',
    ];

    public $receiver_email = 'abtestinglab.com@gmail.com';



    //handle paypal call
    public function receivedPaypal(Request $request, User $user, Payment $payment)
    {
        $requestAll = $request->all();

        //verify transaction with paypal TODO
        $req = 'cmd=_notify-validate';

        foreach ($requestAll as $key => $value)
        {
            $value = urlencode(stripslashes($value));
            $req .= '&' . $key . '=' . $value;
        }
        $header  = "POST " . $this->paypal['path'] . " HTTP/1.1\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

        $fp = fsockopen($this->paypal['url'], 443, $errno, $errstr, 30);
        fputs($fp, $header . $req);

        $response = fgets($fp);
        fclose($fp);

        //logging raw entry to have entries if stuff will go wrong
        DB::table('paypal_logs')->insert([
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
            'dump' => json_encode(['response' => $response] + $requestAll)
            ]);

        if (strcasecmp($response, 'VERIFIED') != 0)
            return $this->respondError($response);


        //check if the same transaction didnt already come
        if (Payment::where('txn_id', $request->get('txn_id'))->exists())
            return $this->respondError('transaction logged');

        //check if status is completed - we want to count only the money that reached us
        if (strcasecmp($request->get('payment_status'), 'completed') !== 0)
            return $this->respondError('status not completed');

        //check if status is completed - we want to count only the money that reached us
        if (strcasecmp($request->get('receiver_email'), $this->receiver_email) !== 0)
            return $this->respondError('wrong receiver email');

        //check if price is correct DISABLED FOR THE TIME BEING BECAUSE CAN CAUSE MORE PROBLEMS THAN IT SOLVES
        /*if (!isset($this->plans[$request->get('item_name')]) ||
            $this->plans[$request->get('item_name')]['price'] * $request->get('quantity') != floatval($request->get('mc_gross')))
            return $this->respondError('price incorrect');
        */

        //seems okay

        $user = $user->where('email', $request->get('payer_email'))->first();

        if (!isset($user->id))
            $user_id = 0;
        else
            $user_id = $user->id;

        $visitors = 0;
        if (isset($this->plans[$request->get('item_name')]))
        {
            $visitors = $this->plans[$request->get('item_name')]['visitors'] * $request->get('quantity');
        }

        $payment = Payment::create([
            'user_id' => $user_id,
            'email' => $request->get('payer_email'),
            'visitors' => $visitors,
            'plan' => $request->get('item_name'),
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
}
