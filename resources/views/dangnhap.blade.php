@extends("master")
@section("content")
<div style="width: 100%">
	<h3 style="text-align: center;">Đăng nhập</h3>
	</div>
<div class="form-login">
	<form method="POST" role="form" action="{{route('login')}}">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<div class="form-group">
			<label>Email:</label>
			<input type="text" class="form-control" id="email" name="email" placeholder="Nhập vào tên đăng nhập của bạn">
			@if($errors->has('email'))
			<p style="color: red">{{ $errors->first('email') }}</p>
			@endif
		</div>
		<div class="form-group">
			<label>Mật khẩu:</label>
			<input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu của bạn">
			@if($errors->has('password'))
			<p style="color: red">{{ $errors->first('password') }}</p>
			@endif
		</div>
		<div class="form-group">
			<p><input type="checkbox" name="nhomatkhau"> Ghi nhớ mật khẩu | <a href="">Quên mật khẩu? </a></p>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-default">Đăng nhập</button>
		</div>
	</form>	
</div>
@endsection