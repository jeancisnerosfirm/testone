@extends('user_dashboard.layouts.app')

@section('content')
<section class="min-vh-100">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 col-xs-12 col-sm-12">
                <div class="flash-container">
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>@lang('message.dashboard.dispute.discussion.sidebar.header')</h3>
                    </div>

                    <div class="pb-4">
						<div class="ticket-line mt-2">
							<div class="titlecolor-txt">@lang('message.dashboard.dispute.dispute-id')</div>
							<div class="generalcolor-txt">{{ $dispute->code }}</div>
						</div>

						<div class="ticket-line mt-3">
							<div class="titlecolor-txt">@lang('message.dashboard.dispute.discussion.sidebar.title')</div>
							<div class="generalcolor-txt">{{ $dispute->title }}</div>
						</div>

						<div class="ticket-line mt-3">
							<div class="titlecolor-txt">@lang('message.dashboard.dispute.claimant')</div>
							<div class="generalcolor-txt">{{ $dispute->claimant->first_name .' '.$dispute->claimant->last_name}}</div>
						</div>


						<div class="ticket-line mt-3">
							<div class="titlecolor-txt">@lang('message.dashboard.dispute.defendant')</div>
							<div class="generalcolor-txt">{{ $dispute->defendant->first_name .' '.$dispute->defendant->last_name}}</div>
						</div>

						<div class="ticket-line mt-3">
							<div class="titlecolor-txt">@lang('message.form.date')</div>
							<div class="generalcolor-txt">{{ dateFormat($dispute->created_at) }}</div>
						</div>

						<div class="ticket-line mt-3">
							<div class="titlecolor-txt">@lang('message.dashboard.dispute.transaction-id')</div>
							<div class="generalcolor-txt">{{ $dispute->transaction->uuid }}</div>
						</div>

						<div class="ticket-line mt-3">
							<div class="titlecolor-txt">@lang('message.dashboard.dispute.status')</div>
							<div class="generalcolor-txt">
								@php
									echo getStatusBadge($dispute->status);
								@endphp
							</div>
						</div>

						<div class="ticket-line mt-3">
							<div class="titlecolor-txt">@lang('message.dashboard.dispute.discussion.sidebar.reason')</div>
							<div class="generalcolor-txt">{{ $dispute->reason->title }}</div>
						</div>

						<div class="ticket-btn ticket-line mt-3">
							@if($dispute->claimant_id == Auth::user()->id)
								@if ($dispute->status == 'Open')
									<label> @lang('message.dashboard.dispute.discussion.sidebar.change-status')</label>
									<select class="form-control" name="status" id="status">
										<option value="Open" <?= ($dispute->status == 'Open') ? 'selected' : '' ?>>@lang('message.dashboard.dispute.status-type.open')</option>
										<option value="Closed" <?= ($dispute->status == 'Closed') ? 'selected' : '' ?>>@lang('message.dashboard.dispute.status-type.close')</option>
									</select>
								@endif
							@endif
								<input type="hidden" name="id" value="{{$dispute->id}}" id="id">
						</div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-xs-12 col-sm-12">
				<div class="card">
					<div class="card-header">
						<h3>@lang('message.dashboard.dispute.discussion.form.title')</h3>
					</div>

					<div class="card-body">
						@include('user_dashboard.layouts.common.alert')
						<span id="alertDiv"></span>

						@if($dispute->status == 'Open')
							<form action="{{url('dispute/reply')}}" id="reply" method="post" enctype="multipart/form-data">
								<input type="hidden" name="dispute_id" value="{{ $dispute->id }}">
								{{ csrf_field() }}
								<div class="form-group">
								<label>@lang('message.dashboard.dispute.discussion.form.message')</label>

								<textarea name="description" id="description" class="form-control"></textarea>
									@if($errors->has('description'))
									<span class="error">
										{{ $errors->first('description') }}
									</span>
									@endif
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="file" name="file" id="file" hidden/>
											<label class="upload-file" for="file">
												<div class="upload-icon">
													<i class="fas fa-cloud-upload-alt text-28"></i>
												</div>
												<div class="upload-text">
													<span>{{ __('Choose a file') }}</span>
												</div>
											</label>
											<p id="file-chosen"></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="text-right">
											<button class="btn btn-grad" id="dispute-reply">
												<i class="spinner fa fa-spinner fa-spin" style="display: none;"></i>
												<span id="dispute-reply-text" style="font-weight: bolder;">
													@lang('message.dashboard.button.submit')
												</span>
											</button>
										</div>
									</div>
								</div>
							</form>
						@endif
						<div>
							<div class="reply-views mt20">
								<div class="reply-box">
									<div class="left">
										<div class="profile-id-pic left">
											@if(!empty($dispute->claimant->picture) && file_exists(public_path('user_dashboard/profile/' . $dispute->claimant->picture)))
												<img src="{{ url('public/user_dashboard/profile/' . $dispute->claimant->picture) }}" class="img-responsive" style="width:60px;">
											@else
												<img src="{{ url('public/user_dashboard/images/avatar.jpg') }}" alt="User Image" class="img-responsive" style="width:60px;">
											@endif
										</div>
										<div class="left">
										<h5 class="">{{$dispute->claimant->first_name .' '.$dispute->claimant->last_name}}</h5>
										</div>
									</div>
									<div class="right">
									<div class="update-time">{{ dateFormat($dispute->created_at) }}</div>
									</div>
									<div class="clearfix"></div>
								</div>

								<div class="reply-details">
									{!! $dispute->description !!}
								</div>
							</div>
						</div>
						<br>

						@if( $dispute->disputeDiscussions->count() > 0 )
							@foreach($dispute->disputeDiscussions as $result)
								@if($result->type == 'User' )
									<div class="">
									<div class="reply-views">
										<div class="reply-box">
										<div class="left">
										<div class="profile-id-pic left">

											@if(!empty($result->user->picture) && file_exists(public_path('user_dashboard/profile/' . $result->user->picture)))
												<img src="{{ url('public/user_dashboard/profile/' .  $result->user->picture) }}" class="rounded-circle" style="width:60px;">
											@else
												<img src="{{ url('public/user_dashboard/images/avatar.jpg') }}" alt="" class="rounded-circle" style="width:60px;">
											@endif

										</div>
										<div class="left">
											<h5 class="">{{ $result->user->first_name.' '.$result->user->last_name}}</h5>
										</div>
										</div>
										<div class="right">
											<div class="update-time">{{ dateFormat($result->created_at) }}</div>
										</div>
										<div class="clearfix"></div>
										</div>
										<div class="reply-details">

										<p>{!! $result->message !!}</p>
										@if($result->file)
											<?php
												$str_arr = explode('_', $result->file);
												$str_position = strlen($str_arr[0])+1;
												$file_name = substr($result->file,$str_position);
											?>
											<div class="mt-3">
												<a class="text-info" href="{{ url('public/uploads/files/' . $result->file) }}"><i class="fa fa-download"></i> {{$file_name}}</a>
											</div>
										@endif

										</div>
									</div>
									</div>
									<br>
								@else
									<div>
										<div class="reply-views">
											<div class="reply-box">
												<div class="left">
												<div class="profile-id-pic left">
													@if(!empty($result->admin->picture) && file_exists(public_path('public/uploads/userPic/' . $result->admin->picture)))
														<img src="{{ url('public/uploads/userPic/' . $result->admin->picture) }}" class="rounded-circle" style="width:60px;">
													@else
														<img src="{{ url('public/user_dashboard/images/avatar.jpg') }}" alt="" class="rounded-circle" style="width:60px;">
													@endif
												</div>
												<div class="left">
													<h5 class=""><?php echo $result->admin->first_name.' '.$result->admin->last_name ?></h5>
												</div>
												</div>
												<div class="right">
												<div class="update-time">{{ dateFormat($result->created_at) }}</div>
												</div>
												<div class="clearfix"></div>
											</div>

											<div class="reply-details">
												<p>{!! $result->message !!}</p>
												@if($result->file)
													<?php
														$str_arr = explode('_', $result->file);
														$str_position = strlen($str_arr[0])+1;
														$file_name = substr($result->file,$str_position);
													?>
													<div class="mt-3">
														<a class="text-info" href="{{ url('public/uploads/files/' . $result->file) }}"><i class="fa fa-download"></i> {{ $file_name }}</a>
													</div>
												@endif
											</div>
										</div>
									</div>
									<br>
								@endif
							@endforeach
						@endif
					</div>
				</div>
            </div>
		</div>
	</div>
</section>
@endsection

@section('js')
<script src="{{ theme_asset('public/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ theme_asset('public/js/additional-methods.min.js') }}" type="text/javascript"></script>

<script>

const actualBtn = document.getElementById('file');

const fileChosen = document.getElementById('file-chosen');

actualBtn.addEventListener('change', function(){
	fileChosen.textContent = this.files[0].name
})



jQuery.extend(jQuery.validator.messages, {
    required: "{{ __('This field is required.') }}",
})

$('#reply').validate({
	rules: {
			description: {
				required: true,
			},
			file: {
	            extension: "docx|rtf|doc|pdf|png|jpg|jpeg|gif|bmp",
	        },
		},
		messages: {
          file: {
            extension: "{{ __("Please select (docx, rtf, doc, pdf, png, jpg, jpeg, gif or bmp) file!") }}"
          }
        },
        submitHandler: function(form)
	    {
	        $("#dispute-reply").attr("disabled", true).click(function (e)
	        {
	            e.preventDefault();
	        });
	        $(".spinner").show();
	        $("#dispute-reply-text").text("{{ __('Submitting...') }}");
	        form.submit();
	    }
	});

$("#status").on('change', function()
{
	var status = $(this).val();
	var id = $("#id").val();
	$.ajax({
		method: "POST",
		url: SITE_URL+"/dispute/change_reply_status",
		data: { status: status, id:id}
	})
	.done(function( data )
	{
		if (status == 'Open') { status = 'Open'}
		else if (status == 'Solve') { status = 'Solved'}
		else if (status == 'Close') { status = 'Closed'}

		message = 'Dispute Discussion '+ status +' Successfully!';
		var messageBox = '<div class="alert alert-success" role="alert">'+ message +'</div><br>';
		$("#alertDiv").html(messageBox);

		setTimeout(function()
		{
		  location.reload()
		}, 2000);
	});
});

</script>
@endsection
