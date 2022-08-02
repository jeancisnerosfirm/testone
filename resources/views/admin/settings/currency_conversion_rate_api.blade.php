@extends('admin.layouts.master')
@section('title', 'Currency Conversion Rate Api')

@section('head_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/bootstrap-select-1.13.12/css/bootstrap-select.min.css')}}">
@endsection


@section('page_content')

    <!-- Main content -->
    <div class="row">
        <div class="col-md-3 settings_bar_gap">
            @include('admin.common.settings_bar')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Currency Conversion Api</h3>
                </div>

                <form action="{{ url(\Config::get('adminPrefix').'/settings/currency-conversion-rate-api') }}" method="post" class="form-horizontal" id="CurrencyConversionRateApi" >
                    {!! csrf_field() !!}

                    <!-- box-body -->
                    <div class="box-body">

                        <!-- Exchange enabled api -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="exchange_enabled_api">Enabled Api</label>
                            <div class="col-sm-6">
                                <select class="form-control exchange_enabled_api" name="exchange_enabled_api" id="exchange_enabled_api">
                                    <option value='Disabled' {{ $currencyExchangeApi['exchange_enabled_api'] == 'Disabled' ? 'selected':""}}>Disabled</option>
                                    <option value='currency_converter_api_key' {{ $currencyExchangeApi['exchange_enabled_api'] == 'currency_converter_api_key' ? 'selected':""}}>Currency Converter Api</option>
                                    <option value='exchange_rate_api_key' {{ $currencyExchangeApi['exchange_enabled_api'] == 'exchange_rate_api_key' ? 'selected':""}}>Exchange Rate Api</option>
                                </select>
                                @if($errors->has('exchange_enabled_api'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('exchange_enabled_api') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Currency converter api -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="currency_converter_api_key">Currency Converter Api</label>
                            <div class="col-sm-6">
                                <input type="text" name="currency_converter_api_key" class="form-control exchange-api" value="{{ $currencyExchangeApi['currency_converter_api_key'] ?? '' }}" placeholder="currency converter api key" id="currency_converter_api_key">
                                @if($errors->has('currency_converter_api_key'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('currency_converter_api_key') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Exchange rate api -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="exchange_rate_api_key">Exchange rate Api</label>
                            <div class="col-sm-6">
                                <input type="text" name="exchange_rate_api_key" class="form-control exchange-api" value="{{ $currencyExchangeApi['exchange_rate_api_key'] ?? '' }}" placeholder="Exchange rate api key" id="exchange_rate_api_key">
                                @if($errors->has('exchange_rate_api_key'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('exchange_rate_api_key') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <!-- box-footer -->
                    @if(Common::has_permission(\Auth::guard('admin')->user()->id, 'edit_conversion_rate_api'))
                        <div class="box-footer">
                            <button class="btn btn-theme pull-right" type="submit">Submit</button>
                        </div>
                    @endif
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>

@endsection

@push('extra_body_scripts')

<!-- jquery.validate -->
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/dist/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/backend/bootstrap-select-1.13.12/js/bootstrap-select.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    $(window).on('load', function()
    {
        $(".exchange_enabled_api").select2({});
    });

    $.validator.setDefaults({
        highlight: function(element) {
            $(element).parent('div').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).parent('div').removeClass('has-error');
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        }
    });

    $('#CurrencyConversionRateApi').validate({
        rules: {
            currency_converter_api_key: {
                require_from_group: [1, ".exchange-api"]
            },
            exchange_rate_api_key: {
                require_from_group: [1, ".exchange-api"]
            }
        },
    });

</script>

@endpush
