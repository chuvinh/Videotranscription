@extends("master")
@section("content")
<div class="col-md-6">
	<div class="video-responsive" id="youTubeVideo">
		<iframe class="iframe" id="" src="" width="560" height="400"
		allow="autoplay" allowfullscreen="true" __idm_id__="1016238081"></iframe>
	</div>
</div>
<div class="col-md-6">
	<div style="text-align: center;line-height: 100px; color: #06b81c" class="mmocVideoTranscript" id="videoTranscriptBVMkUzxiMCo"
	style="border-style: groove;">
	
</div>
</div>

<script>
	//1 số biến toàn cục
	var pos;
	var idvideo;
	var arrnote;
	var idtranscript;
	var src;
	var page_url = urlObject(window.location.href);
	ytvid = page_url.parameters.v || "BVMkUzxiMCo";
	lang = page_url.parameters.lang || "en";
	vname = page_url.parameters.name || "";
	idvideo = page_url.parameters.v;
	timevideo = page_url.parameters.t;
	timeend = page_url.parameters.e;
	content=page_url.parameters.c;
	$(".mmocVideoTranscript").append(content);
	console.log(content);
    // change src video iframe
    var video_frame = $('.iframe')
    .attr("id", ytvid)
    .attr("src", "https://www.youtube.com/embed/" + ytvid + "?enablejsapi=1&start="+timevideo+"&end="+timeend+"&autoplay=1")
    .attr("width", 560)
    .attr("height", 315)
    .attr("allow", "")
    .attr("allowfullscreen", "true");
</script>
@endsection