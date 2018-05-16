<div id="header" class="header-v2">
	<div class="header-row row">
		<ul class="header-nav ">

			<li class="header-nav-item plain">
				<a href="{{ route('home') }}" class="nav-item-btn">
					<span class="nav-item-btn-text">home</span>
				</a>
			</li>
			@if(Auth::check())
			<li class="header-nav-item plain ">
				<a href="mysentences/{{Auth::user()->id}}" class="nav-item-btn">
					<span class="nav-item-btn-text">Your Centences</span>
				</a>
			</li>
			@endif

			<li class="header-nav-item plain create ">
				<a href="video/" class="nav-item-btn">
					<span class="nav-item-btn-text">Video</span>
				</a>
			</li>

			@if(Auth::check())
			<li class="header-nav-item colored premium">
				<a href="{{route('logout')}}" class="nav-item-btn">
					<span class="ico"></span>
					<span class="nav-item-btn-text">Đăng xuất</span>
				</a>
			</li>
			<li style="margin-left: 400px" class="header-nav-item colored premium">
				<a href="dang-nhap/" class="nav-item-btn">
					<span class="ico"></span>
					<span class="nav-item-btn-text">Chào bạn: {{Auth::user()->name}}</span>
				</a>
			</li>

			
			@else
			<li class="header-nav-item colored premium">
				<a href="dang-nhap/" class="nav-item-btn">
					<span class="ico"></span>
					<span class="nav-item-btn-text">Đăng nhập</span>
				</a>
			</li>

			<li class="header-nav-item colored premium">
				<a href="dang-ki/" class="nav-item-btn">
					<span class="ico"></span>
					<span class="nav-item-btn-text">Đăng Ký</span>
				</a>
			</li>
			@endif
		</ul>
	</div>
</div>
<script>
	$(".header-nav-item").click(function(){
		$(".header-nav-item").removeClass("is-active");
		$(this).addClass("is-active");
	});
</script>