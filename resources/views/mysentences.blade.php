@extends("master")
@section("content")
<h4 style="text-align: center;padding: 20px; color: red">Your sentences 	</h4>
<div style="width: 70%;margin-left: 15%;border:1px solid #d2d2d2">
	@if(Session::has('thanhcong'))
	<div class="alert alert-success">{{Session::get('thanhcong')}}</div>
	@endif
	@foreach($transcript as $tr)
	<div class="btnSeek">
		<a style="text-decoration: none" href="http://localhost:81/videotranscript/public/myvideo?v={{$tr->video}}&t={{$tr->time}}&e={{$tr->timeend}}&c={{$tr->content}}"><strong>{{$tr->content}}</strong>
			<a style="float:right;" href="xoa/{{$tr->id}}" style="margin-left: 20px;"><i class="fa fa-times"></i></a>
		</a>
	</div>
	@endforeach
</div>
@endsection