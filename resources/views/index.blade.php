@extends('master')
@section('content')
<div class="inputvideo">
<h3 style="color: ">Input Video URL</h3>	
</div>
<div class="input-text">
	<input style="width: 35%;height: 40px; font-size: 20px" type="text" name="getsub" >
	<button style="background: #c93f3f;height: 40px; font-size: 20px;color: #fff" id="getsub">Enter</button>
	<p style="padding-top: 30px">
	Dán video của bạn vào ô để bắt đầu học</p>
	<p>Để sử dụng chức năng MyCentences, vui lòng đăng nhập
	</p>
	<h3 style="color: #5a7f42; padding-top: 20px">CÓ CÔNG MÀI SẮT, CÓ NGÀY NÊN KIM</h3>
</div>
<script type="text/javascript">
	$("#getsub").click(function() {
		var url = $("input[name='getsub']").val();
		var key = url.split("?v=")[1];
		var key2 = key.split("&")[0];
		console.log(key2);
		window.location.href = "video?v=" + key2;
	});
</script>
@endsection