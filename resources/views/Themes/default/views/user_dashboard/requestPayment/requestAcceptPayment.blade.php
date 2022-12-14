<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>{{ __('Print') }}</title>
</head>
<style>
body{ font-family: 'Lato', sans-serif; color:#121212;}

hr { border-top:1px solid #f0f0f0;}
table { border-collapse:collapse;}
.code td{ padding:5px;}
</style>
<body>
	<div style="width:900px; margin:auto; top:20px; position:relative;">
		<table style="margin-bottom:40px;">
			<tr>
				<td>
					@if(!empty(settings('logo')) && file_exists(public_path('images/logos/' . settings('logo'))))
						<img src="{{ public_path('/images/logos/' . settings('logo')) }}" alt="Logo" height="80" weight="80"/>
					@endif
				</td>
			</tr>
		</table>

		<table>
			<tr>
				<td>
					<table>
						<tr>
							<td style="font-size: 16px; color:#000000; line-height:25px; font-weight:bold;">{{ __('Sender') }}</td>
						</tr>
						<tr>
							<td style="font-size:15px; color:#4e5c6e; line-height:22px;">
								{{$transactionDetails->end_user->first_name}}&nbsp;{{$transactionDetails->end_user->last_name}}
							</td>
						</tr>
						<tr>
							<td style="font-size: 16px; color:#000000; line-height:25px; font-weight:bold;">{{ __('Receiver') }}</td>
						</tr>
						<tr>
							<td style="font-size:15px; color:#4e5c6e; line-height:22px;">
								{{($transactionDetails['user_type']=='registered'?$transactionDetails->user->first_name.' '.$transactionDetails->user->last_name:$transactionDetails['email'])}}
							</td>
						</tr>

						<tr>
							<td style="font-size:16px; color:#000000; line-height:25px; font-weight:bold;">{{ __('Pay With') }}</td>
						</tr>
						<tr>
							<td style="font-size:15px; color:#4e5c6e; line-height:22px;">{{$transactionDetails->currency->code}}</td>
						</tr>


					</table>
				</td>
			</tr>

			<tr>
				<td>
					<table style="margin-top:20px;">
						<tr>
							<td style="font-size:16px; color:#000000; line-height:25px; font-weight:bold;">{{ __('Transaction ID') }}</td>
						</tr>
						<tr>
							<td style="font-size:15px; color:#4e5c6e; line-height:22px;">{{$transactionDetails->uuid}}</td>
						</tr>
						<tr>
							<td style="font-size:16px; color:#000000; line-height:25px; font-weight:bold;">{{ __('Transaction Date') }}</td>
						</tr>
						<tr>
							<td style="font-size:15px; color:#4e5c6e; line-height:22px;">{{date('Y-m-d',strtotime($transactionDetails->created_at))}}</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td>
					<table style="margin-top:20px; width:300px;" class="code">
						<tr>
							<td colspan="2" style="font-size:16px; color:#000000; font-weight:bold;">{{ __('Details') }}</td>
						</tr>
						<tr>
							<td style="font-size:15px; color:#000000;">{{ __('Amount') }}</td>
							<td style="font-size:15px; color:#4e5c6e; text-align:right;">{{$transactionDetails->currency->symbol}} {{decimalFormat($transactionDetails->subtotal)}}</td>
						</tr>
						<tr style="padding-bottom:10px;">
							<td style="font-size:15px; color:#000000;">{{ __('Fee') }}</td>
							<td style="font-size:15px; color:#4e5c6e; text-align:right;">{{$transactionDetails->currency->symbol}} {{decimalFormat($transactionDetails->charge_percentage+$transactionDetails->charge_fixed)}}</td>
						</tr>
						<tr>
							<td colspan="2" style="border-top:1px solid #eaeaea; padding-top:0; margin-bottom:3px;"></td>
						</tr>
						<tr>
							<td style="font-size:15px; color:#000000; font-weight:bold;">{{ __('Total') }}</td>
							<td style="font-size:15px; color:#4e5c6e; text-align:right; font-weight:bold;">{{$transactionDetails->currency->symbol}} {{decimalFormat(str_replace("-",'',$transactionDetails->total))}}</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td>
					<table style="margin-top:40px;">
						<tr>
							<td style="font-size:16px; color:#000000; line-height:25px; font-weight:bold;">{{ __('Note') }}</td>
						</tr>
						<tr>
							<td style="font-size:15px; color:#4e5c6e; line-height:22px;">{{$transactionDetails->note}}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

	</div>
</body>
</html>
