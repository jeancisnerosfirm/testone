@extends('user_dashboard.layouts.app')

@section('content')
    <section class="min-vh-100">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-12 col-xs-12">
                    @include('user_dashboard.layouts.common.alert')

                    <div class="right mb10">
                        <a href="{{url('/merchant/add')}}" class="btn btn-grad"><i class="fa fa-user"></i>&nbsp;
                            @lang('message.dashboard.button.new-merchant')</a>
                    </div>
                    <div class="clearfix"></div>

                    <div class="card mt-2">
                        <div class="card-header">
                            <div class="chart-list float-left">
                                <ul>
                                    <li class="active"><a href="{{url('/merchants')}}">@lang('message.dashboard.merchant.menu.merchant')</a></li>
                                    <li><a href="{{url('/merchant/payments')}}">@lang('message.dashboard.merchant.menu.payment')</a></li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <div class="table-responsive">
                                @if($list->count() > 0)
                                    <table class="table recent_activity">
                                        <thead>
                                        <tr>
                                            <td class="pl-5"><strong>@lang('message.dashboard.merchant.table.id')</strong></td>
                                            <td><strong>@lang('message.dashboard.merchant.table.business-name')</strong></td>
                                            <td><strong>@lang('message.dashboard.merchant.table.site-url')</strong></td>
                                            <td><strong>{{ __('Currency') }}</strong></td>
                                            <td><strong>@lang('message.dashboard.merchant.table.type')</strong></td>
                                            <td><strong>@lang('message.dashboard.merchant.table.status')</strong></td>
                                            <td class="pr-5 text-right"><strong>@lang('message.dashboard.merchant.table.action')</strong></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($list as $result)
                                            <tr>
                                                <td class="pl-5">{{ $result->merchant_uuid }}</td>
                                                <td>
                                                    <h4>{{ $result->business_name}}</h4>
                                                </td>
                                                <td>{{ $result->site_url}} </td>
                                                <td>{{ optional($result->currency)->code }} </td>
                                                <td>{{ ucfirst($result->type)}} </td>
                                                @if ($result->status == 'Moderation')
                                                    <td><span class="badge badge-warning">@lang('message.dashboard.merchant.table.moderation')</span></td>
                                                @elseif ($result->status == 'Disapproved')
                                                    <td><span class="badge badge-danger">@lang('message.dashboard.merchant.table.disapproved')</span></td>
                                                @elseif ($result->status == 'Approved')
                                                    <td><span class="badge badge-success">@lang('message.dashboard.merchant.table.approved')</span></td>
                                                @endif

                                                <td class="text-right pr-5">
                                                    @if($result->status == 'Approved')
                                                        @if($result->type=='standard')
                                                            <button data-type="{{$result->type}}" data-currencyType="{{ $result->currency->type }}" data-merchantCurrencyCode="{{ !empty($result->currency) ? $result->currency->code : $defaultWallet->currency->code }}"
                                                                    data-merchantCurrencyId="{{ !empty($result->currency) ? $result->currency->id : $defaultWallet->currency_id }}"
                                                                    data-marchantID="{{$result->id}}" type="button"
                                                                    data-marchant="{{$result->merchant_uuid}}" type="button"
                                                                    class="btn btn-action btn-sm gearBtn mt-2" data-toggle="modal" data-target="#merchantModal">
                                                                    <i class="fa fa-cog"></i>
                                                            </button>

                                                        @else
                                                        @if (!empty($result->appInfo->client_id) && !empty($result->appInfo->client_secret))
                                                            <!-- expressMerchantQrCodeModal -->
                                                            <button
                                                                    data-clientId="{{ !empty($result->appInfo->client_id) ? $result->appInfo->client_id : '' }}"
                                                                    data-clientSecret="{{ !empty($result->appInfo->client_secret) ? $result->appInfo->client_secret : '' }}"
                                                                    data-merchantId="{{$result->id}}"
                                                                    data-merchantDefaultCurrencyId="{{ !empty($result->currency) ? $result->currency->id : '' }}"{{-- below although named named default CUrrency will actually be merchant form currency --}}
                                                                    type="button" class="btn btn-action btn-info btn-sm mt-2 generateExpressMerchantQrCode" data-toggle="modal"
                                                                    data-target="#expressMerchantQrCodeModal"><i class="fa fa-qrcode"></i>
                                                            </button>
                                                            <button
                                                                    data-client-id="{{ isset($result->appInfo->client_id) ? $result->appInfo->client_id : '' }}"
                                                                    data-client-secret="{{ isset($result->appInfo->client_secret) ? $result->appInfo->client_secret : '' }}"
                                                                    data-merchantCurrencyId="{{ !empty($result->currency) ? $result->currency->id : $defaultWallet->currency_id }}"
                                                                    data-marchantID="{{$result->id}}" type="button"
                                                                    data-marchant="{{$result->merchant_uuid}}" type="button"
                                                                    class="btn btn-action btn-sm gearBtn mt-2" data-toggle="modal"
                                                                    data-target="#expressModal"><i class="fa fa-cog"></i>
                                                            </button>
                                                            @endif
                                                        @endif
                                                    @endif
                                                    <a href="{{url('merchant/detail/'.$result->id)}}"
                                                       class="btn btn-action btn-sm mt-2"><i class="fa fa-eye"></i></a>
                                                    <a href="{{url('merchant/edit/'.$result->id)}}"
                                                       class="btn btn-sm btn-action mt-2"><i class="fa fa-edit"></i></a>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                            </div>
                            @else
                                <h5 class="text-center p-5">
                                    @lang('message.dashboard.merchant.table.not-found')
                                </h5>
                            @endif

                            <div class="card-footer">
                                {{ $list->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="merchantModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">@lang('message.dashboard.merchant.html-form-generator.title')</h3>
                        <button type="button" class="close" data-dismiss="modal" id="form-modal-cross">&times;</button>
                    </div>
                    <div class="modal-body pl-4 pr-4">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>@lang('message.dashboard.merchant.html-form-generator.merchant-id')</label>
                                    <input readonly type="text" class="form-control" name="merchant_id" id="merchant_id">
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="merchant_main_id" id="merchant_main_id">
                                    <input type="hidden" name="currency_id" id="currency_id"/>
                                </div>

                                <div class="form-group">
                                    <label>@lang('message.dashboard.merchant.html-form-generator.item-name')</label>
                                    <input type="text" class="form-control" name="item_name" id="item_name">
                                </div>

                                <div class="form-group">
                                    <label>@lang('message.dashboard.merchant.html-form-generator.order-number')</label>
                                    <input type="text" class="form-control" name="order" id="order">
                                </div>

                                <div class="form-group">
                                    <label>@lang('message.dashboard.merchant.html-form-generator.price')<b><span id="merchantCurrencyCode"></span></b></label>
                                    <input type="text" class="form-control" name="amount" id="amount" onkeypress="return isNumberOrDecimalPointKey(this, event);" placeholder="0.00"
                                    oninput="restrictNumberToPrefdecimalOnInput(this)">
                                </div>

                                <div class="form-group">
                                    <label>@lang('message.dashboard.merchant.html-form-generator.custom')</label>
                                    <input type="text" class="form-control" name="custom" id="custom">
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="row m-0 mb-4 justify-content-between">
                                    <div>
                                        <h5>@lang('message.dashboard.merchant.html-form-generator.right-form-title')</h5>
                                    </div>
                                    <div>
                                        <span id="copiedMessage" style="display: none;margin-right: 10px">@lang('message.dashboard.merchant.html-form-generator.right-form-copied')</span>
                                        <button id="copyBtn" class="btn btn-grad">@lang('message.dashboard.merchant.html-form-generator.right-form-copy')</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="html" id="result" rows="16" disabled>
                                        <form method="POST" action="{{url('/payment/form')}}">
                                            <input type="hidden" name="order" id="result_order" value="#"/>
                                            <input type="hidden" name="merchant" id="result_merchant" value="#"/>
                                            <input type="hidden" name="merchant_id" id="result_merchant_id" value="#"/>
                                            <input type="hidden" name="item_name" id="result_item_name" value="Testing payment"/>
                                            <input type="hidden" name="amount" id="result_amount" value="#"/>
                                            <input type="hidden" name="custom" id="result_custom" value="comment"/>
                                            <button type="submit">@lang('message.express-payment.test-payment-form')</button>
                                        </form>
                                    </textarea>

                                    <p class="help-block font-weight-bold mt-2">@lang('message.dashboard.merchant.html-form-generator.right-form-footer')</p>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center pb-4">
                            <div class="w-100">
                                <div class="preloader mt-4" style="display: none;">
                                    <div class="loader"></div>
                                </div>

                                <!-- payment-form-qr-code -->
                                <div class="payment-form-qr-code" style="text-align: center;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="button" class="btn btn-danger mr-auto standard-payment-form-close" data-dismiss="modal" id="form-modal-close">@lang('message.dashboard.merchant.html-form-generator.close')
                        </button>
                        <button type="button" class="btn btn-grad" id="generate-standard-payment-form">@lang('message.dashboard.merchant.html-form-generator.generate')</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- express merchant QrCode modal -->
        <div id="expressMerchantQrCodeModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Express Merchant QR Code') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="preloader mt-4" style="display: none;">
                                        <div class="loader"></div>
                                    </div>
                                    <div class="express-merchant-qr-code" style="text-align: center;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger mr-auto" data-dismiss="modal">@lang('message.dashboard.merchant.html-form-generator.close')</button>
                        <a href="#" class="btn btn-grad" id="qr-code-print-express">
                            <strong>{{ __('Print') }}</strong>
                        </a>
                        <button type="button" class="btn btn-grad update-express-merchant-qr-code">@lang('message.dashboard.button.update')</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="expressModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('message.dashboard.merchant.html-form-generator.app-info')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('message.dashboard.merchant.html-form-generator.client-id')</label>
                                    <input type="text" class="form-control" id="client_id" readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <label>@lang('message.dashboard.merchant.html-form-generator.client-secret')</label>
                                    <input type="text" class="form-control" id="client_secret" readonly="readonly">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('message.dashboard.merchant.html-form-generator.close')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @include('common.restrict_number_to_pref_decimal')
    @include('common.restrict_character_decimal_point')

    <script>

        jQuery.fn.delay = function (time, func) {
            return this.each(function () {
                setTimeout(func, time);
            });
        };

        var result = document.getElementById('result'),
            f1 = document.getElementById('merchant_id'),
            f2 = document.getElementById('item_name'),
            f3 = document.getElementById('order'),
            f4 = document.getElementById('amount'),
            f5 = document.getElementById('custom'),
            f6 = document.getElementById('merchant_main_id'),
            f7 = document.getElementById('currency_id'),
            btn = document.getElementById('btn');

            generateStandardPaymentFormBtn = document.getElementById('generate-standard-payment-form');

            BtnClose = document.getElementById('form-modal-close');
            BtnCross = document.getElementById('form-modal-cross');

            generateStandardPaymentFormBtn.onclick = function ()
            {
                var merchant_id = f1.value,
                    item_name = f2.value,
                    order = f3.value,
                    paymentAmount = f4.value,
                    custom = f5.value;
                    merchant_main_id = f6.value;
                    merchantDefaultCurrency = f7.value;

                result.value =
                '<form method="POST" action="' + SITE_URL + '/payment/form"><input type="hidden" name="merchant" value="'
                + merchant_id + '" /><input type="hidden" name="merchant_id" value="'
                + merchant_main_id + '" /><input type="hidden" name="item_name" value="'
                + item_name + '" /><input type="hidden" name="currency_id" value="'
                + merchantDefaultCurrency + '" /><input type="hidden" name="order" value="'
                + order + '" /><input type="hidden" name="amount" value="' + paymentAmount
                + '" /><input type="hidden" name="custom" value="' + custom + '" /><button type="submit">'+"{{ __('message.express-payment.pay-now') }}"+'</button></form>';

                if (item_name != '' && order != '' && paymentAmount != '' && custom != '' && merchant_main_id != '' && merchantDefaultCurrency != '') {
                    //generate qr-code for above form
                    $.ajax({
                        headers:
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "POST",
                        url: SITE_URL + "/merchant/generate-standard-merchant-payment-qrCode",
                        dataType: "json",
                        data: {
                            'merchantId': merchant_main_id,
                            'merchantDefaultCurrency': merchantDefaultCurrency,
                            'paymentAmount': paymentAmount,
                        },
                        beforeSend: function () {
                            $('.preloader').show();
                        },
                    })
                    .done(function(response)
                    {
                        if (response.status == true) {
                            $('.payment-form-qr-code').html(`
                                <p class="help-block">-- {{ __('OR') }} --</p>
                                <br>
                                <p style="font-weight: bold;">{{ __('Scan QrCode To Pay') }}</p>
                                <br>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?data=${response.secret}&amp;size=200x200"/>
                            `);

                            //Add qr-code-print-standard anchor tag on click generateStandardPaymentFormBtn
                            $('.standard-payment-form-close').after(`<a href="#" class="btn btn-grad" id="qr-code-print-standard">
                                <strong>{{ __('Print') }}</strong>
                            </a>`);

                            $('.preloader').hide();
                        }

                    })
                    .fail(function(error)
                    {
                        console.log(error);
                    });
                }
                else
                {
                    $('.payment-form-qr-code').html('');
                }
            }

            BtnClose.onclick = function ()
            {
                var val1 = '',
                    val2 = '',
                    val3 = '',
                    val4 = '',
                    val5 = '';
                    val6 = '';
                    val7 = '';
                result.value = '<form method="POST" action="' + SITE_URL + '/payment/form"><input type="hidden" name="merchant" value="' + val1 + '" /><input type="hidden" name="merchant_id" value="' + val6 + '" /><input type="hidden" name="item_name" value="' + val2 + '" /><input type="hidden" name="currency_id" value="' + val7 + '" /><input type="hidden" name="order" value="' + val3 + '" /><input type="hidden" name="amount" value="' + val4 + '" /><input type="hidden" name="custom" value="' + val5 + '" /><button type="submit">Pay now!</button></form>';
                document.getElementById("item_name").value = "";
                document.getElementById("order").value = "";
                document.getElementById("amount").value = "";
                document.getElementById("custom").value = "";
                document.getElementById("merchantCurrencyCode").innerHTML = ""; //new

                $('.payment-form-qr-code').html('');
                $('#qr-code-print-standard').remove();
            }

            BtnCross.onclick = function ()
            {
                var val1 = '',
                    val2 = '',
                    val3 = '',
                    val4 = '',
                    val5 = '';
                    val6 = '';
                    val7 = '';
                result.value = '<form method="POST" action="' + SITE_URL + '/payment/form"><input type="hidden" name="merchant" value="' + val1 + '" /><input type="hidden" name="merchant_id" value="' + val6 + '" /><input type="hidden" name="item_name" value="' + val2 + '" /><input type="hidden" name="currency_id" value="' + val7 + '" /><input type="hidden" name="order" value="' + val3 + '" /><input type="hidden" name="amount" value="' + val4 + '" /><input type="hidden" name="custom" value="' + val5 + '" /><button type="submit">Pay now!</button></form>';
                document.getElementById("item_name").value = "";
                document.getElementById("order").value = "";
                document.getElementById("amount").value = "";
                document.getElementById("custom").value = "";
                document.getElementById("merchantCurrencyCode").innerHTML = "";

                $('.payment-form-qr-code').html('');
                $('#qr-code-print-standard').remove();
            }

        $(document).on('click','.gearBtn',function(e)
        {
            e.preventDefault();

            if ($(this).attr('data-type')=='standard') {
                // if not suspended
                var merchant = $(this).attr('data-marchant');
                $('#merchant_id').val(merchant);

                var merchant_main_id = $(this).attr('data-marchantID');
                $('#merchant_main_id').val(merchant_main_id);
                var merchantCurrencyCode = $(this).attr('data-merchantCurrencyCode'); //new
                if (merchantCurrencyCode) {
                    $('#merchantCurrencyCode').html(', '+merchantCurrencyCode);//new
                }
                var merchantCurrencyId = $(this).attr('data-merchantCurrencyId'); //new
                $('#currency_id').val(merchantCurrencyId);

                $('#merchantModal').modal('show');
            } else {
                var clientId = $(this).attr('data-client-id');
                var clientSecrect = $(this).attr('data-client-secret');

                $('#client_id').val(clientId);
                $('#client_secret').val(clientSecrect);

                var merchantCurrencyId = $(this).attr('data-merchantCurrencyId'); //new
                $('#currency_id').val(merchantCurrencyId);
            }
        });

        function restrictNumberToPrefdecimalOnInput(e)
        {
            var type = $('.gearBtn').attr('data-currencyType');
            restrictNumberToPrefdecimal(e, type);
        }

        function determineDecimalPoint() {
            
            var currencyType =  $('.gearBtn').attr('data-currencyType');

            if (currencyType == 'crypto') {
                $("#amount").attr('placeholder', CRYPTODP);

            } else if (currencyType == 'fiat') {
                $("#amount").attr('placeholder', FIATDP);
            }
        }

        $(document).on('ready', function () {
            determineDecimalPoint();
        });

        function executeExpressMerchantQrCode(endpoint, clientId, clientSecret, merchantId, merchantDefaultCurrencyId)
        {
            if (clientId != '' && clientSecret != '' && merchantId != '' && merchantDefaultCurrencyId != '') {
                $.ajax({
                    headers:
                    {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: SITE_URL + endpoint,
                    dataType: "json",
                    data: {
                        'merchantId': merchantId,
                        'merchantDefaultCurrencyId': merchantDefaultCurrencyId,
                        'clientId': clientId,
                        'clientSecret': clientSecret,
                    },
                    beforeSend: function () {
                        $('.preloader').show();
                    },
                })
                .done(function(response)
                {
                    if (response.status == true) {
                        $('.express-merchant-qr-code').html(`
                            <p style="font-weight: bold;">{{ __('Scan QR Code') }}</p>
                            <br>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?data=${response.secret}&amp;size=200x200"/>
                        `);
                        $('.preloader').hide();
                    }
                })
                .fail(function(error)
                {
                    console.log(error);
                });
            } else {
                $('.express-merchant-qr-code').html('');
            }
        }

        //modal on show - generate express merchant qr code
        $('#expressMerchantQrCodeModal').on('show.bs.modal', function (e)
        {
            var endpoint = "/merchant/generate-express-merchant-qr-code";
            var clientId = $(e.relatedTarget).attr('data-clientId');
            var clientSecret = $(e.relatedTarget).attr('data-clientSecret');
            var merchantId = $(e.relatedTarget).attr('data-merchantId');
            var merchantDefaultCurrencyId = $(e.relatedTarget).attr('data-merchantDefaultCurrencyId');

            $('#client_id').val(clientId);
            $('#client_secret').val(clientSecret);
            $('#merchant_id').val(merchantId);
            $('#currency_id').val(merchantDefaultCurrencyId);

            executeExpressMerchantQrCode(endpoint, clientId, clientSecret, merchantId, merchantDefaultCurrencyId);
        });


        //on click - update express merchant qr code
        $(document).on('click','.update-express-merchant-qr-code',function(e)
        {
            e.preventDefault();

            let endpoint = "/merchant/update-express-merchant-qr-code";
            var clientId = $('#client_id').val();
            var clientSecret = $('#client_secret').val();;
            var merchantId = $('#merchant_id').val();
            var merchantDefaultCurrencyId = $('#currency_id').val();
            executeExpressMerchantQrCode(endpoint, clientId, clientSecret, merchantId, merchantDefaultCurrencyId);
        });


        //on click - print express merchant qr code
        $(document).on('click','#qr-code-print-express',function(e)
        {
            e.preventDefault();

            let expressMerchantId = $('#merchant_id').val();
            let printQrCodeUrl = SITE_URL+'/merchant/qr-code-print/'+expressMerchantId+'/express_merchant';
            $(this).attr('href', printQrCodeUrl);
            window.open($(this).attr('href'), '_blank');
        });


        //on click - print standard merchant qr code
        $(document).on('click','#qr-code-print-standard',function(e)
        {
            e.preventDefault();

            let standardMerchantId = $('#merchant_main_id').val();
            let printQrCodeUrl = SITE_URL+'/merchant/qr-code-print/'+standardMerchantId+'/standard_merchant';
            $(this).attr('href', printQrCodeUrl);
            window.open($(this).attr('href'), '_blank');
        });

        $('#copyBtn').on('click', function () {
            $(this).css('color', '#cbc8c8');
            $('#result').removeAttr('disabled').select().attr('disabled', 'true');
            document.execCommand('copy');
            $('#copiedMessage').show().delay(5000, function () {
                $('#copiedMessage').fadeOut("slow")
            });
        });

        $('#client_id,#client_secret').on('focus', function ()
        {
            $(this).select();
            document.execCommand('copy');
            $(this).before("<span style='color: green;font-weight: 700' class='pull-right copied'>Copied</span>").delay(2000, function () {
                $('.copied').remove()
            });
        });
    </script>
@endsection
