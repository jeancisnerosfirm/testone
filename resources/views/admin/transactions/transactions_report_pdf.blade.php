<!DOCTYPE html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <title>
            Transactions
        </title>
    </head>
    <style>
        body {
        font-family: "DeJaVu Sans", Helvetica, sans-serif;
        color: #121212;
        line-height: 15px;
    }

    table, tr, td {
        padding: 6px 6px;
        border: 1px solid black;
    }

    tr {
        height: 40px;
    }

    </style>

    <body>
        <div style="width:100%; margin:0px auto;">
            <div style="height:80px">
                <div style="width:80%; float:left; font-size:13px; color:#383838; font-weight:400;">
                    <div>
                        <strong>
                            {{ ucwords(Session::get('name')) }}
                        </strong>
                    </div>
                    <br>
                    <div>
                        Period : {{ $date_range }}
                    </div>
                    <br>
                    <div>
                        Print Date : {{ dateFormat(now())}}
                    </div>
                </div>
                <div style="width:20%; float:left;font-size:15px; color:#383838; font-weight:400;">
                    <div>
                        <div>
                            @if (!empty(settings('logo')) && file_exists(public_path('images/logos/' . settings('logo'))))
                                <img src="{{ url('public/images/logos/' . settings('logo')) }}" width="288" height="90" alt="Logo"/>
                            @else
                                <img src="{{ url('public/uploads/userPic/default-logo.jpg') }}" width="288" height="90">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear:both">
            </div>
            <div style="margin-top:30px;">
                <table style="width:100%; border-radius:1px; border-collapse: collapse; text-align:center;"> <!-- Add Text Aligned here -->
                    <tr style="background-color:#f0f0f0; font-size:12px; font-weight:bold;"> <!-- Remove Text Aligned from here -->
                        <td>Date</td>
                        <td>User</td>
                        <td>Type</td>
                        <td>Amount</td>
                        <td>Fees</td>
                        <td>Total</td>
                        <td>Currency</td>
                        <td>Receiver</td>
                        <td>Status</td>
                    </tr>

                    @foreach($transactions as $transaction)
                        <tr style="background-color:#fff; text-align:center; font-size:12px; font-weight:normal;">

                            <td>{{ dateFormat($transaction->created_at) }}</td>

                            {{-- User --}}
                            @if (in_array($transaction->transaction_type_id, getPaymoneySettings('transaction_types')['web']['sent']))
                                <td>{{ isset($transaction->user) ? $transaction->user->first_name.' '.$transaction->user->last_name : (isset($transaction->crypto_exchange) ? $transaction->crypto_exchange->email_phone : '-') }}</td>
                            @elseif (in_array($transaction->transaction_type_id, getPaymoneySettings('transaction_types')['web']['received']))
                                <td>{{ isset($transaction->end_user) ? $transaction->end_user->first_name.' '.$transaction->end_user->last_name :"-" }}</td>
                            @endif

                            <td>{{ str_replace('_', ' ', $transaction->transaction_type->name) }}</td>

                            {{-- Amount --}}
                            <td>{{ formatNumber($transaction->subtotal, $transaction->currency->id) }}</td>

                            <td>{{ (($transaction->charge_percentage == 0) && ($transaction->charge_fixed == 0)) ? '-' : formatNumber($transaction->charge_percentage + $transaction->charge_fixed, $transaction->currency->id) }}</td>

                            {{-- Total --}}
                            <td>{{ ($transaction->total > 0) ? '+' . formatNumber($transaction->total, $transaction->currency->id) : formatNumber($transaction->total, $transaction->currency->id) }}</td>

                            <td>{{ $transaction->currency->code }}</td>

                            {{-- Receiver --}}
                            @switch($transaction->transaction_type_id)
                                @case(Deposit)
                                @case(Exchange_From)
                                @case(Exchange_To)
                                @case(module('CryptoExchange') ? Crypto_Buy : false):
                                @case(module('CryptoExchange') ? Crypto_Sell : false):
                                @case(module('CryptoExchange') ? Crypto_Exchange : false):
                                @case(Withdrawal)
                                @case (config('referral.is_active') ? Referral_Award : false):
                                    <td>{{ isset($transaction->end_user) ? $transaction->end_user->first_name . ' ' . $transaction->end_user->last_name : "-" }}</td>
                                    @break
                                @case(Transferred)
                                @case(Received)
                                    <td>
                                        @if ($transaction->transfer->receiver)
                                        {{ $transaction->transfer->receiver->first_name.' '.$transaction->transfer->receiver->last_name }}
                                        @elseif ($transaction->transfer->email)
                                            {{ $transaction->transfer->email }}
                                        @elseif ($transaction->transfer->phone)
                                            {{ $transaction->transfer->phone }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    @break
                                @case(Request_From)
                                @case(Request_To)
                                    <td>{{ isset($transaction->request_payment->receiver) ? $transaction->request_payment->receiver->first_name.' '.$transaction->request_payment->receiver->last_name : $transaction->request_payment->email }}</td>
                                    @break
                                @case(Payment_Sent)
                                    <td>{{ isset($transaction->end_user) ? $transaction->end_user->first_name.' '.$transaction->end_user->last_name :"-" }}</td>
                                    @break
                                @case(Payment_Received)
                                    <td>{{ isset($transaction->user) ? $transaction->user->first_name.' '.$transaction->user->last_name :"-" }}</td>
                                    @break
                            @endswitch

                            <td>{{ (($transaction->status == 'Blocked') ? "Cancelled" :(($transaction->status == 'Refund') ? "Refunded" : $transaction->status)) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </body>
</html>
