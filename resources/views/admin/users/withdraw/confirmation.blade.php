@extends('admin.layouts.master')

@section('title', 'Payout')

@section('page_content')

<div class="box">
    <div class="panel-body">
        <ul class="nav nav-tabs cus" role="tablist">
            <li class="active">
                <a href='{{ url(\Config::get('adminPrefix')."/users/edit/$users->id")}}'>Profile</a>
            </li>
            <li>
                <a href="{{ url(\Config::get('adminPrefix')."/users/transactions/$users->id")}}">Transactions</a>
            </li>
            <li>
                <a href="{{ url(\Config::get('adminPrefix')."/users/wallets/$users->id")}}">Wallets</a>
            </li>
            <li>
                <a href="{{ url(\Config::get('adminPrefix')."/users/tickets/$users->id")}}">Tickets</a>
            </li>
            <li>
                <a href="{{ url(\Config::get('adminPrefix')."/users/disputes/$users->id")}}">Disputes</a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <h3>{{ $users->first_name.' '.$users->last_name }}</h3>
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-5">
        <div class="pull-right">
            <a href="{{ url(\Config::get('adminPrefix').'/users/withdraw/create/' . $users->id) }}" style="margin-top: 15px;" class="pull-right btn btn-theme active">Withdraw</a>
        </div>
    </div>
</div>

<div class="box mt-20">
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7">

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h3 class="text-center"><strong>Details</strong></h3>
                                <div class="row">
                                    <div class="col-md-6 pull-left">Amount</div>
                                    <div class="col-md-6  text-right"><strong>{{ moneyFormat($transInfo['currSymbol'], isset($transInfo['amount']) ? formatNumber($transInfo['amount'], $transInfo['currency_id']) : 0.00) }}</strong></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pull-left">Fee</div>
                                    <div class="col-md-6 text-right"><strong>{{ moneyFormat($transInfo['currSymbol'], isset($transInfo['fee']) ? formatNumber($transInfo['fee'], $transInfo['currency_id']) : 0.00) }}</strong></div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-6 pull-left"><strong>Total</strong></div>
                                    <div class="col-md-6 text-right"><strong>{{ moneyFormat($transInfo['currSymbol'], isset($transInfo['totalAmount']) ? formatNumber($transInfo['totalAmount'], $transInfo['currency_id']) : 0.00) }}</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div style="margin-left: 0 auto">
                            <div style="float: left;">
                                <a href="#" class="admin-user-withdraw-confirm-back-link">
                                    <button class="btn btn-theme-danger admin-user-withdraw-confirm-back-btn"><strong><i class="fa fa-angle-left"></i>&nbsp;&nbsp;Back</strong></button>
                                </a>
                            </div>
                            <div style="float: right;">
                                <form action="{{ url(\Config::get('adminPrefix').'/users/withdraw/storeFromAdmin') }}" style="display: block;" method="POST" accept-charset="UTF-8" id="admin-user-withdraw-confirm" novalidate="novalidate">
                                    <input value="{{csrf_token()}}" name="_token" id="token" type="hidden">
                                    <input value="{{$transInfo['totalAmount']}}" name="amount" id="amount" type="hidden">
                                    <input value="{{$users->id}}" name="user_id" type="hidden">

                                    <button type="submit" class="btn btn-theme" id="withdrawal-confirm">
                                        <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                                        <span id="withdrawal-confirm-text">
                                            <strong>Confirm&nbsp; <i class="fa fa-angle-right"></i></strong>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('extra_body_scripts')

<!-- jquery.validate -->
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    $('#admin-user-withdraw-confirm').validate({
        rules: {
            amount: {
                required: false,
            },
        },
        submitHandler: function(form)
        {
            $("#withdrawal-confirm").attr("disabled", true);
            $(".fa-spin").show();
            var pretext=$("#withdrawal-confirm-text").text();
            $("#withdrawal-confirm-text").text('Confirming...');

            //Make back button disabled and prevent click
            $('.admin-user-withdraw-confirm-back-btn').attr("disabled", true).click(function (e)
            {
                e.preventDefault();
            });

            //Make back anchor prevent click
            $('.admin-user-withdraw-confirm-back-link').click(function (e)
            {
                e.preventDefault();
            });

            form.submit();
            setTimeout(function(){
                $("#withdrawal-confirm").removeAttr("disabled");
                $(".fa-spin").hide();
                $("#withdrawal-confirm-text").text(pretext);
            },10000);
        }
    });

    //Only go back by back button, if submit button is not clicked
    $(document).on('click', '.admin-user-withdraw-confirm-back-btn', function (e)
    {
        e.preventDefault();
        window.history.back();
    });

</script>
@endpush