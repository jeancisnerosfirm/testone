@php
	$getCompanyName = settings('name');
@endphp

@extends('frontend.layouts.app')
@section('content')
<!--Start header section-->
<section class="section-06 sector-guides">
	<div class="container">
		<div class="mt30">
			<div class="express-list">
				<ul>
					<li>
						<a href="{{url('/developer?type=standard')}}">
							Standard
						</a>
					</li>
					<li>
						<a href="{{url('/developer?type=express')}}">
							Express
						</a>
					</li>
					@if (!empty($publication_status) && $publication_status == 'Active' && isActive('Woocommerce'))
					<li><a class="active33" href="{{url('/developer?type=woocommerce')}}">WooCommerce Plugin</a></li>
					@endif
				</ul>
			</div>
			<p>
			</p>
			<div class="clearfix">
			</div>
		</div>
	</div>
</section>
<!--End Section -->

<!--Start woocommerce body section-->
<section class="section-06 sector-guides mt50">
	<div class="container">
		<div class="h4 mt30 mb20 text-center">
			{{ $getCompanyName }} {{ __('WooCommerce Plugin Installation') }}
		</div>
		<div class="mt10">
		</div>

	    <div class="guidepara-style mt30">
	    	<p>
	    		<div class="composer-box">
	            {{ __('Click') }}
	            <a style="color:rgba(74, 111, 197, 0.9) !important" href="{{ url('public/uploads/woocommerce').'/'.$plugin_name }}" download>{{ __('here') }}</a>
	            {{ __('to download the WooCommerce plugin') }}
	        </div>
	    	</p>
	    	<p>{{ __('After downloading the plugin (which will be a zip file), you will need to go to WordPress admin area and visit Plugins » Add New page.') }}</p>
	    	<p>{{ __('After that, click on the Upload Plugin button on top of the page.') }}</p>
	    	<br>
	    	<img src="{{ theme_asset('public/images/woocommerce/uploadpluginwpadmin.png') }}" class="img-thumbnail" style="width: 500px;">
	    	<br>
	    	<p>{{ __('This will bring you to the plugin upload page. Here you need to click on the choose file button and select the plugin file you downloaded earlier to your computer.') }}</p>
	    	<br>
	    	<img src="{{ theme_asset('public/images/woocommerce/pluginuploadpage.png') }}" class="img-thumbnail" style="width: 500px;">
	    	<br>
	    	<p>{{ __('After you have selected the file, you need to click on the install now button.') }}</p>
	    	<p>{{ __('WordPress will now upload the plugin file from your computer and install it for you. You will see a success message like this after the installation is finished.') }}</p>
	    	<br>
	    	<img src="{{ theme_asset('public/images/woocommerce/plugininstalledmanual.png') }}" class="img-thumbnail" style="width: 500px;">
	    	<br>
	    	<p>{{ __('Once installed, you need to click on the Activate Plugin link to start using the plugin.') }}</p>
	    	<p>{{ __('You would have to configure the settings to fit your needs. These settings will vary for each plugin.') }}</p>
	    </div>

		    <div class="mt30">
		    </div>
		</div>
	</section>
	<!--End woocommerce body section -->

	@endsection
