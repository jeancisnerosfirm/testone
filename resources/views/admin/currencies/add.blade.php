@extends('admin.layouts.master')
@section('title', 'Add Currency')

@section('head_style')
    <link rel="stylesheet" href="{{ asset('public/backend/bootstrap-toggle/css/bootstrap-toggle.min.css') }}">
@endsection

@section('page_content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('Add Currency') }}</h3>
                </div>
                <br>
                <form action="{{ url(\Config::get('adminPrefix') . '/settings/add_currency') }}" method="post" class="form-horizontal" enctype="multipart/form-data" id="add_currency_form">
                    @csrf
                    <!-- Name -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{ __('Name') }}</label>
                        <div class="col-sm-6">
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Name" id="name">
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <!-- Code -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="code">{{ __('Code') }}</label>
                        <div class="col-sm-6">
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="Code" id="code">
                            <span class="text-danger">{{ $errors->first('code') }}</span>
                        </div>
                    </div>
                    <!-- Symbol -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="symbol">{{ __('Symbol') }}</label>
                        <div class="col-sm-6">
                            <input type="text" name="symbol" class="form-control" value="{{ old('symbol') }}" placeholder="Symbol" id="symbol">
                            <span class="text-danger">{{ $errors->first('symbol') }}</span>
                        </div>
                    </div>
					<!-- Type -->
					<div class="form-group">
                        <label class="col-sm-3 control-label" for="type">{{ __('Type') }}</label>
                        <div class="col-sm-6">
                            <select class="form-control type" name="type" id="type">
                                <option {{ old('type') == 'fiat' ? 'selected' : '' }} value='fiat'>{{ __('Fiat') }}</option>
                                <option {{ old('type') == 'crypto' ? 'selected' : '' }} value='crypto'>{{ __('Crypto') }}</option>
                            </select>
                        </div>
                    </div>
                     <!-- Address -->
                     <div class="form-group" id="address_div">
                        <label class="col-sm-3 control-label" for="symbol">{{ __('Merchant Address') }}</label>
                        <div class="col-sm-6">
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Currency crypto address" id="address">
                            <span class="text-danger">{{ $errors->first('address') }}</span>
                        </div>
                    </div>
                    <!-- Exchange Rate -->
                    <div class="form-group" id="exchange_rate_div">
                        <label class="col-sm-3 control-label" for="rate">{{ __('Exchange Rate') }}</label>
                        <div class="col-sm-6">
                            <input type="text" name="rate" class="form-control" value="{{ old('rate') }}" placeholder="Rate" id="rate"
                            onkeypress="return isNumberOrDecimalPointKey(this, event);" oninput="restrictNumberToPrefdecimalOnInput(this)">
                            <span class="text-danger">{{ $errors->first('rate') }}</span>
                            <div class="clearfix"></div>
                            <small class="form-text text-muted">
								<strong>{{ __('*Exchagne rate should be equivalent to default currency (allowed upto 8 decimal places).') }}</strong>
							</small>
                        </div>
                    </div>
                    <!-- Logo -->
                    <div class="form-group">
                        <label for="currency-logo" class="col-sm-3 control-label">{{ __('Logo') }}</label>
                        <div class="col-sm-6">
                            <input type="file" name="logo" class="form-control input-file-field" id="currency-logo">
                            <div class="clearfix"></div>
                            <small class="form-text text-muted"><strong>{{ allowedImageDimension(64, 64) }}</strong></small>
                            <div class="setting-img">
                                <img src='{{ url('public/user_dashboard/images/favicon.png') }}' width="64" height="64" id="currency-demo-logo-preview">
                            </div>
                        </div>
                    </div>

                    <!-- Exchange From -->
                    <div class="form-group" id="exchange_from_div">
                        <label class="col-sm-3 control-label" for="exchange_from">{{ __('Exchange From') }}</label>
                        <div class="col-sm-6">
                            <select class="form-control exchange_from" name="exchange_from" id="exchange_from">
                                <option {{ old('exchange_from') == 'local' ? 'selected' : '' }} value='local'>Local</option>
                                <option {{ old('exchange_from') == 'api' ? 'selected' : '' }} value='api'>API</option>
                            </select>
                            <small class="rate-setup-text text-muted" role='button' style="display:none;"><strong><span style="color: red;">{{ __('Setup Currency Converter') }}</span> <a href=""data-toggle="modal" data-target="#currency-converter">{{ __('Click here.') }}</a></strong></small>
                        </div>
                    </div>

					<!-- Status -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="status">{{ __('Status') }}</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="status" id="status">
                                <option {{ old('status') == 'Active' ? 'selected' : '' }} value='Active'>{{ __('Active') }}</option>
                                <option {{ old('status') == 'Inactive' ? 'selected' : '' }} value='Inactive'>{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a class="btn btn-theme-danger" href="{{ url(\Config::get('adminPrefix') . '/settings/currency') }}">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-theme pull-right" id="currency-add-submit-btn">
                            <i class="fa fa-spinner fa-spin" style="display: none;"></i>
							<span id="currency-add-submit-btn-text">{{ __('Submit') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="currency-converter" class="modal fade display_none" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content pB-27 M-50">
                <div class="modal-header header-background">
                    <h3 class="header-text">{{ __('How to set up currrency conveter') }}</h3>
                </div>
                <div class="modal-body p-4 pL-24">
                    <div class="text-strong">
                        <p>{{ __('Set up any currency converter api') }}</p>
                        <p class="blue-text">{{ __('Currency Converter Api') }}</p>
                        <ul>
                            <li><strong>{{ __('URL') }}: </strong><a target="_blank" href="//free.currencyconverterapi.com">free.currencyconverterapi.com</a>
                            <li><strong>{{ __('Api Key') }}:</strong> {{ __('Get an api key from URL') }}</li>
                            <li>{{ __('After that set the value in the Api Key field.') }}</li>
                        </ul>
                        <p class="blue-text">{{ __('Exchange Rate Api') }}</p>
                        <ul>
                            <li><strong>{{ __('URL') }}: </strong><a target="_blank" href="//exchangerate-api.com/">exchangerate-api.com</a>
                            <li><strong>{{ __('Api Key') }}:</strong>{{ __('Get an api key from URL') }}</li>
                            <li>{{ __('After that set the value in the Api Key field.') }}</li>
                        </ul>
                        <a target="_blank" href="{{ url(\Config::get('adminPrefix') . '/settings/currency-conversion-rate-api') }}" class="currency-converter-link">{{ __('Click this for Currency Converter Setup') }}</a>
                    </div>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-primary custom-btn-small float-right mt-2 mR-30" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extra_body_scripts')

    <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/js/jquery-validation-1.17.0/dist/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/backend/bootstrap-toggle/js/bootstrap-toggle.min.js') }}" type="text/javascript"></script>

    @include('common.read-file-on-change')
    @include('common.restrict_number_to_pref_decimal')
    @include('common.restrict_character_decimal_point')

    <script type="text/javascript">

        $(window).on('load', function(){
            var type = $('select#type').find(':selected').val();
            if (type == 'crypto') {
                $('#exchange_from_div, #exchange_rate_div').css('display', 'none');
                $('#address_div').css('display', 'block');
            } else if (type == 'fiat') {
                $('#exchange_from_div, #exchange_rate_div').css('display', 'block');
                $('#address_div').css('display', 'none');
            }
        });

        $(document).on('change', '#currency-logo', function() {
            let orginalSource = '{{ url('public/user_dashboard/images/favicon.png') }}';
            readFileOnChange(this, $('#currency-demo-logo-preview'), orginalSource);
        });

        function restrictNumberToPrefdecimalOnInput(e)
        {
            var type = $('select#type').find(':selected').val()
            restrictNumberToPrefdecimal(e, type);
        }

        $.validator.setDefaults({
            highlight: function(element) {
                $(element).parent('div').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).parent('div').removeClass('has-error');
            },
        });

        $('#type').on('change', function() {
            var type = $(this).find('option:selected').val();
            if (type == 'crypto') {
                $('#exchange_from_div, #exchange_rate_div').css('display', 'none');
                $('#address_div').css('display', 'block');
            } else if (type == 'fiat') {
                $('#exchange_from_div, #exchange_rate_div').css('display', 'block');
                $('#address_div').css('display', 'none');
            }
        });

        $('#exchange_from').on('change', function() {
            var exchangeFrom = $(this).find('option:selected').val();
            if (exchangeFrom == 'api') {
                var exchangeEnabledApi = "{{ settings('exchange_enabled_api') }}";
                var currencyConverterApiKey = "{{ settings('currency_converter_api_key') }}";
                var exchangeRateApiKey = "{{ settings('exchange_rate_api_key') }}";

                if (exchangeEnabledApi != 'Disabled' && ((exchangeEnabledApi == 'currency_converter_api_key' && currencyConverterApiKey != '') || ( exchangeEnabledApi == 'exchange_rate_api_key' && exchangeRateApiKey != ''))) {
                    $('#currency-add-submit-btn').attr('disabled', false);
                } else {
                    $('#currency-add-submit-btn').attr('disabled', true);
                    $('.rate-setup-text').css('display', 'block');
                }
            } else if (exchangeFrom == 'local') {
                $('.rate-setup-text').css('display', 'none');
                $('#currency-add-submit-btn').attr('disabled', false);
            }
        });

        $('#add_currency_form').validate({
            rules: {
                name: {
                    required: true,
                },
                code: {
                    required: true,
                },
                symbol: {
                    required: true,
                },
                type: {
                    required: true,
                },
                rate: {
                    required: true,
                    number: true,
                    min: 0.0001,
                },
                address: {
                    required: true,
                },
                logo: {
                    extension: "png|jpg|jpeg|gif|bmp",
                },
            },
            messages: {
                rate: {
                    min: "Please enter values greater than 0.0001!"
                },
                logo: {
                    extension: "Please select (png, jpg, jpeg, gif or bmp) file!"
                }
            },
            submitHandler: function(form) {
                $("#currency-add-submit-btn").attr("disabled", true);
                $('#cancel-link').attr("disabled", true);
                $(".fa-spin").show();
                $("#currency-add-submit-btn-text").text('Submitting...');
                $('#currency-add-submit-btn').click(function(e) {
                    e.preventDefault();
                });
                $('#cancel-link').click(function(e) {
                    e.preventDefault();
                });
                form.submit();
            }
        });
    </script>

@endpush
