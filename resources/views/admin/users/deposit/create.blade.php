@extends('admin.layouts.master')

@section('title', 'Profile')

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
                <a href="{{ url(\Config::get('adminPrefix').'/users/deposit/create/'. $users->id) }}" style="margin-top: 15px;" class="pull-right btn btn-theme active">Deposit</a>
            </div>
        </div>
    </div>

    <div class="box mt-20">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <form action="{{  url(\Config::get('adminPrefix')."/users/deposit/create/$users->id") }}" method="post" accept-charset='UTF-8' id="admin-user-deposit-create">
                                <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                                <input type="hidden" name="user_id" id="user_id" value="{{ $users->id }}">
                                <input type="hidden" name="fullname" id="user_id" value="{{ $users->first_name.' '.$users->last_name }}">
                                <input type="hidden" name="percentage_fee" id="percentage_fee" value="">
                                <input type="hidden" name="fixed_fee" id="fixed_fee" value="">
                                <input type="hidden" name="fee" class="total_fees" value="0.00">

                                <div class="row">
                                    <div class="col-md-12">
                                        {{-- Currency --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Currency</label>
                                                <select class="select2 wallet" name="currency_id" id="currency_id">
                                                    @foreach ($activeCurrencyList as $aCurrency)
                                                        <option data-type="{{ $aCurrency['type'] }}" value="{{ $aCurrency['id'] }}">{{ $aCurrency['code'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <small id="walletlHelp" class="form-text text-muted">
                                                Fee(<span class="pFees">0</span>%+<span class="fFees">0</span>), Total:  <span class="total_fees">0.00</span>
                                            </small>
                                        </div>
                                        {{-- Amount --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Amount</label>
                                                <input type="text" class="form-control amount" name="amount" placeholder="0.00" type="text" id="amount" onkeypress="return isNumberOrDecimalPointKey(this, event);"
                                                value="" oninput="restrictNumberToPrefdecimalOnInput(this)">
                                                <span class="amountLimit" style="color: red;font-weight: bold"></span>
                                                <div class="clearfix"></div>
                                                {{-- <small class="form-text text-muted"><strong>{{ allowedDecimalPlaceMessage($preference) }}</strong></small> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-5" style="display: none;">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Payment Method</label>
                                                <select class="form-control payment_method" name="payment_method" id="payment_method">
                                                    <option value="{{ $payment_met->id }}">{{ $payment_met->name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <a href="{{ url(\Config::get('adminPrefix').'/users/edit/'. $users->id) }}" class="btn btn-theme-danger"><span><i class="fa fa-angle-left"></i>&nbsp;Back</span></a>
                                            <button type="submit" class="btn btn-theme" id="deposit-create">
                                                <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                                                <span id="deposit-create-text">Next&nbsp;<i class="fa fa-angle-right"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

@include('common.restrict_number_to_pref_decimal')
@include('common.restrict_character_decimal_point')

<script type="text/javascript">
    $(".select2").select2({});

    $('#admin-user-deposit-create').validate({
        rules: {
            amount: {
                required: true,
            },
        },
        submitHandler: function (form)
        {
            $("#deposit-create").attr("disabled", true);
            $(".fa-spin").show();
            var pretext=$("#deposit-create-text").text();
            $("#deposit-create-text").text('Depositing...');
            form.submit();
            setTimeout(function(){
                $("#deposit-create-text").html(pretext + '<i class="fa fa-angle-right"></i>');
                $("#deposit-create").removeAttr("disabled");
                $(".fa-spin").hide();
            },1000);
        }
    });

    function restrictNumberToPrefdecimalOnInput(e)
    {
        var type = $('select#currency_id').find(':selected').data('type')
        restrictNumberToPrefdecimal(e, type);
    }

    function determineDecimalPoint() {
        
        var currencyType = $('select#currency_id').find(':selected').data('type')

        if (currencyType == 'crypto') {
            $('.pFees, .fFees, .total_fees').text(CRYPTODP);
            $("#amount").attr('placeholder', CRYPTODP);

        } else if (currencyType == 'fiat') {
            
            $('.pFees, .fFees, .total_fees').text(FIATDP);
            $("#amount").attr('placeholder', FIATDP);
        }
    }

    $(window).on('load', function (e) {
        determineDecimalPoint();
        checkAmountLimitAndFeesLimit();
    });

    $(document).on('input', '.amount', function (e) {
        checkAmountLimitAndFeesLimit();
    });

    $(document).on('change', '.wallet', function (e) {
        determineDecimalPoint();
        checkAmountLimitAndFeesLimit();
    });

    function checkAmountLimitAndFeesLimit()
    {
        var token = $("#token").val();
        var amount = $('#amount').val();
        var currency_id = $('#currency_id').val();
        var payment_method_id = $('#payment_method').val();

        $.ajax({
            method: "POST",
            url: SITE_URL+"/"+ADMIN_PREFIX+"/users/deposit/amount-fees-limit-check",
            dataType: "json",
            data: {
                "_token": token,
                'amount': amount,
                'currency_id': currency_id,
                'payment_method_id': payment_method_id,
                'user_id': '{{ $users->id }}',
                'transaction_type_id': '{{Deposit}}'
            }
        })
        .done(function (response)
        {
            // console.log(response.success);

            if (response.success.status == 200)
            {
                $("#percentage_fee").val(response.success.feesPercentage);
                $("#fixed_fee").val(response.success.feesFixed);
                $(".percentage_fees").html(response.success.feesPercentage);
                $(".fixed_fees").html(response.success.feesFixed);
                $(".total_fees").val(response.success.totalFees);
                $('.total_fees').html(response.success.totalFeesHtml);
                $('.pFees').html(response.success.pFeesHtml);
                $('.fFees').html(response.success.fFeesHtml);

                $('.amountLimit').text('');
                $("#deposit-create").attr("disabled", false);
                return true;
            }
            else
            {
                if (amount == '')
                {
                    $('.amountLimit').text('');
                }
                else
                {
                    $('.amountLimit').text(response.success.message);
                    $("#deposit-create").attr("disabled", true);
                    return false;
                }
            }
        });
    }
</script>

@endpush