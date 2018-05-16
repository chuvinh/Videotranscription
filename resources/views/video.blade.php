@extends('master')
@section('content')
<div class="col-md-6" style="margin-top: 20px">
	<div class="video-responsive" id="youTubeVideo">
		<iframe class="iframe" id="" src="" width="560" height="400"
		allow="autoplay" allowfullscreen="true" __idm_id__="1016238081"></iframe>
	</div>

</div>
<div class="col-md-6"style="margin-top: 20px">
	<div class="mmocVideoTranscript" id="videoTranscriptBVMkUzxiMCo"
	style="border-style: groove;"></div>
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
    // change src video iframe
    var video_frame = $('.iframe')
    .attr("id", ytvid)
    .attr("src", "https://www.youtube.com/embed/" + ytvid + "?enablejsapi=1")
    .attr("width", 560)
    .attr("height", 315)
    .attr("allow", "autoplay")
    .attr("allowfullscreen", "true");
    // get script
    var transcript_div = $('.mmocVideoTranscript')
    .attr("id", "videoTranscript" + ytvid)
    .data('language', lang)
    .data('name', vname);
    idtranscript = "videoTranscript" + ytvid;

    // $("#youTubeVideo").append(video_frame);
    //$("#transcript").append(transcript_div);

    $(function(){

    	var windowH = $(window).height();
    	var wrapperH = $('.mmocVideoTranscript').height();
    	if(windowH > wrapperH) {
    		$('.mmocVideoTranscript').css({'height':($(window).height()-150)+'px'});
    	}
    	$(window).resize(function(){
    		var windowH = $(window).height();
    		var wrapperH = $('.mmocVideoTranscript').height();
    		if(windowH > wrapperH) {
    			$('.mmocVideoTranscript').css({'height':($(window).height()-150)+'px'});
    		}

    	})
    });
</script>
<script>
	this.mmooc=this.mmooc||{};

    //https://medium.com/@pointbmusic/youtube-api-checklist-c195e9abaff1
    this.mmooc.youtube = function() {
    	var hrefPrefix = "https://video.google.com/timedtext?v=";
    	var transcriptIdPrefix = "videoTranscript";
    	var transcriptArr = [];
    	var initialized = false;

        //tính thời gian
        function makeTimeline (time) {
        	var string, time_array = [];

        	time_array.push(Math.floor(time / (60 * 60)));
        	time_array.push(Math.floor((time - (time_array[0] * 60 * 60)) / 60));
        	time_array.push(Math.floor(time - ((time_array[1] * 60) + (time_array[0] * 60 * 60))));

        	for (var i = 0, il = time_array.length; i < il; i++) {
        		string = '' + time_array[i];
        		if (1 === string.length) {
        			time_array[i] = '0' + string;
        		}
        	}
        	return time_array.join(':');
        };

        function transcript(transcriptId, language, name)
        {
        	var transcriptId = transcriptId;
        	var videoId = transcriptId.split(transcriptIdPrefix)[1];

        	var href = hrefPrefix + videoId;
        	if(language != "")
        	{
        		href = href + "&lang=" + language;
        	}
        	if(name != "")
        	{
        		href = href + "&name=" + name;
        	}

            //Array of captions in video
            var captionsLoaded = false;

            //Timeout for next caption
            var captionTimeout = null;
            
            var captions = null;

            //Keep track of which captions we are showing
            var currentCaptionIndex = 0;
            var nextCaptionIndex = 0;

            this.player = new YT.Player(videoId, {
            	videoId: videoId,
            	events: {
            		'onReady': onPlayerReady,
            		'onStateChange': onPlayerStateChange
            	}
            });
            
            var findCaptionIndexFromTimestamp = function(timeStamp)
            {
            	var start = 0;
            	var duration = 0;
            	for (var i = 0, il = captions.length; i < il; i++) {
            		start = Number(getStartTimeFromCaption(i));
            		duration = Number(getDurationFromCaption(i));

                    //Return the first caption if the timeStamp is smaller than the first caption start time.
                    if(timeStamp < start)
                    {
                    	break;
                    }

                    //Check if the timestamp is in the interval of this caption.
                    if((timeStamp >= start) && (timeStamp < (start + duration)))
                    {
                    	break;
                    }        
                }
                return i;
            }
            var clearCurrentHighlighting = function()
            {   
            	var timeStampId = getTimeIdFromTimestampIndex(currentCaptionIndex);
            	$("#"+timeStampId).css('background-color', '');
            	for(var i=0; i<src.length; i++){
            		$("#t"+i).popover('hide');
            	}
            }

            var highlightNextCaption = function ()
            {
            	var timestampId = getTimeIdFromTimestampIndex(nextCaptionIndex);
            	$("#"+timestampId).css('background-color', '#e5e5e5');
            }

            var calculateTimeout = function (currentTime)
            {
            	var startTime = Number(getStartTimeFromCaption(currentCaptionIndex));
            	var duration = Number(getDurationFromCaption(currentCaptionIndex));
            	var timeoutValue = startTime - currentTime + duration;
            	return timeoutValue;
            }

            this.setCaptionTimeout = function (timeoutValue)
            {
            	if(timeoutValue < 0)
            	{
            		return;
            	}

            	clearTimeout(captionTimeout);

            	var transcript = this;

            	captionTimeout = setTimeout(function() {
            		transcript.highlightCaptionAndPrepareForNext();
            	}, timeoutValue*1000);
            }

            var getStartTimeFromCaption = function(i)
            {
            	if(i >= captions.length)
            	{
            		return -1;
            	}
            	return captions[i].getAttribute('start');
            }
            var getDurationFromCaption = function(i) 
            {
            	if(i >= captions.length)
            	{
            		return -1;
            	}
            	return captions[i].getAttribute('dur');
            }
            var getTimeIdFromTimestampIndex = function(i)
            {
            	var strTimestamp = "" + i;
            	return "t" + strTimestamp;
            }


            //////////////////
            //Public functions
            /////////////////

            //This function highlights the next caption in the list and
            //sets a timeout for the next one after that.
            //It must be public as it is called from a timer.
            this.highlightCaptionAndPrepareForNext = function ()
            {
            	clearCurrentHighlighting();
            	highlightNextCaption();
            	currentCaptionIndex = nextCaptionIndex;
            	nextCaptionIndex++;

            	var currentTime = this.player.getCurrentTime();
            	var timeoutValue = calculateTimeout(currentTime);

            	if(nextCaptionIndex <= captions.length)         
            	{
            		this.setCaptionTimeout(timeoutValue);
                    //scroll tới chỗ đoạn script
                    //vì chiều cao của mỗi đoạn script sẽ là 45px nên m = 45
                    //có 12 phần tử hiển thị trên thanh nên đến phần tử thứ 7 mới scroll
                    var m = 45;
                    if(currentCaptionIndex > 6){
                    	$('html, .mmocVideoTranscript').animate({ scrollTop: m*(currentCaptionIndex-5) }, 50);
                    }else{//scroll ontop
                    	$('html, .mmocVideoTranscript').animate({ scrollTop: 0}, 50);
                    }
                    //getStartTimeFromCaption(currentCaptionIndex)=>time
                    
                    pos = currentCaptionIndex;
                	// chạy ghi chú theo sub
                	for(var i = 0; i < arrnote.length; i++){
                		if(arrnote[i].pos == currentCaptionIndex){
                			//document.getElementById("editnote").disabled = false;
                			$("#note_content").html(arrnote[i].content);
                			break;
                		}
                		else{
                			//document.getElementById("editnote").disabled = true;
                			$("#note_content").html("");
                		}
                	}
                	document.getElementById('note').innerHTML = src[currentCaptionIndex].textContent.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/&#39;/g, "'").replace(/&amp;/g, "&");
                }
            }
            
            //Called if the user has dragged the slider to somewhere in the video.
            this.highlightCaptionFromTimestamp = function(timeStamp)
            {
            	clearCurrentHighlighting();
            	nextCaptionIndex = findCaptionIndexFromTimestamp(timeStamp);
            	currentCaptionIndex = nextCaptionIndex;

            	var startTime = Number(getStartTimeFromCaption(currentCaptionIndex));

            	var timeoutValue = -1;      
            	if(timeStamp < startTime)
            	{
            		timeoutValue = startTime - currentTime;
            	}
            	else
            	{
            		highlightNextCaption();
            		timeoutValue = calculateTimeout(currentTime);
            	}
            	this.setCaptionTimeout(timeoutValue);
                //scroll tới chỗ đoạn script
                var m = 45;
                if(currentCaptionIndex > 6){
                	$('html, .mmocVideoTranscript').animate({ scrollTop: m*(currentCaptionIndex-5) }, 50);
                }else{// scroll ontop
                	$('html, .mmocVideoTranscript').animate({ scrollTop: 0}, 50);
                }
                pos = currentCaptionIndex;
                // chạy ghi chú theo sub
                for(var i = 0; i < arrnote.length; i++){
                	if(arrnote[i].pos == currentCaptionIndex){
            			//document.getElementById("editnote").disabled = false;
            			$("#note_content").html(arrnote[i].content);
            			break;
            		}
            		else{
            			//document.getElementById("editnote").disabled = true;
            			$("#note_content").html("");
            		}
            	}
            	document.getElementById('note').innerHTML = src[currentCaptionIndex].textContent.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/&#39;/g, "'").replace(/&amp;/g, "&");
            }   

            this.transcriptLoaded = function(transcript) {
            	var start = 0;
            	captions = transcript.getElementsByTagName('text');
            	src = captions;
            	var srt_output="";
            	for (var i = 0, il = captions.length; i < il; i++) {
            		start =+ getStartTimeFromCaption(i);
                    end =+ getStartTimeFromCaption(i+1);
                    captionText = captions[i].textContent.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    var timestampId = getTimeIdFromTimestampIndex(i);
                    srt_output += 
                    '<div class="grid-row col-md-12" style="padding:0">'+
                    '<div class="col-sm-12" style="padding:0" >'+
                    '<div class="btnSeek" data-seek="'+ start +'" id="'+ timestampId +'"><strong>'+ makeTimeline(start) +' - </strong>'+ captionText +'</div>'+
                    '</div>'+
                    '<div class="btnadd" data-time="'+ makeTimeline(start) +'" data-start="'+ Math.floor(start) +'" data-end="'+ Math.ceil(end) +'"  data-value="'+ captionText +'" style="float:right">'+
                    '<a><i class="fa fa-plus-square" aria-hidden="true" id="show" data-pos="'+i+'"></i></a>'+
                    '</div>'+
                    '</div>';
                };

                $("#videoTranscript" + videoId).append(srt_output);
                captionsLoaded = true;
            }
            
            this.getTranscriptId = function()
            {
            	return transcriptId;
            }
            this.getVideoId = function()
            {
            	return videoId;
            }
            
            this.getTranscript = function()
            {
            	var oTranscript = this;
            	console.log(href);
            	$.ajax({
            		url: href,
            		type: 'GET',
            		data: {},
            		success: function(response) {
                        if(response === '' || response === null){// nếu response trả về là null

                            // đổi từ chuẩn US sang UK
                            var pos = href.indexOf('lang');
                            var temp = href.slice(0, pos);
                            temp = temp + 'lang=en-GB';// chuẩn vương quốc anh
                            href = temp;
                            //oTranscript.getTranscript();
                            $.ajax({
                            	url: href,
                            	type: 'GET',
                            	data: {},
                            	success: function(res) {
                                    if(res === '' || res === null){// đối với bản dịch tự động sẽ không lấy được dữ liệu 
                                    	alert('An error accurred. Please try other link!');
                                    	return true;
                                    }else{
                                    	oTranscript.transcriptLoaded(res);
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                	console.log("Error during GET");
                                }
                            });
                        }
                        else{
                        	oTranscript.transcriptLoaded(response);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                    	console.log("Error during GET");
                    }
                });           
            }
            
            this.playerPlaying = function()
            {
            	if(!captionsLoaded)
            	{
            		return;
            	}   

            	currentTime = this.player.getCurrentTime();
            	this.highlightCaptionFromTimestamp(currentTime);
            }
            this.playerNotPlaying = function (transcript)
            {
            	if(!captionsLoaded)
            	{
            		return;
            	}   
            	clearTimeout(captionTimeout);
            }
        }

        //Called when user clicks somewhere in the transcript.
        $(function() {
        	$(document).on('click', '.btnSeek', function() {
        		var seekToTime = $(this).data('seek');
        		var transcript = mmooc.youtube.getTranscriptFromTranscriptId($(this).parent().parent().parent().attr("id"));
        		transcript.player.seekTo(seekToTime, true);
        		transcript.player.playVideo();
        	});
        	$(document).on('click', '.btnadd', function() {
        		console.log($(this).data("time"));
        		console.log($(this).data("start"));
        		console.log($(this).data("value"));
        		var page_url = urlObject(window.location.href);
        		ytvid = page_url.parameters.v || "BVMkUzxiMCo";
        		console.log(ytvid);
                @if(Auth::check())
        		window.location="mysentencesadd/"+$(this).data("time")+"/"+$(this).data("start")+"/"+$(this).data("value")+"/"+ytvid+"/"+{{Auth::user()->id}}+"/"+$(this).data("end")+"";
                 alert("Bạn đã thêm thành công!");
                @else
                alert('Vui lòng đăng nhập để xử dụng chức năng');
                @endif
        	});  
        	$(document).on('click', '.clicknote', function() {
				// kiểm tra đăng nhập chưa?
				if(emailuser == null || emailuser == 'null'){
					alert('Please login!');
					$("#cancel").click();
					return false;
				}
				
				// kiểm tra đã lưu bài học chưa?
				var isSave = document.getElementById("savelesson").disabled;
				if(!isSave){
					$("#cancel").click();
					alert('Please save lesson before start note! Please click button "Save lesson"!');
					return false;
				}

				var transcript = mmooc.youtube.getTranscriptFromTranscriptId($(this).parent().parent().parent().attr("id"));
                //transcript.player.playVideo();
                var seekToTime = $(this).data('seek');
                //transcript.player.playVideo();
                transcript.player.seekTo(seekToTime, true);// nhảy time video lại chỗ ghi chú
                transcript.player.pauseVideo();
                pos = $(this).data('position');
                var isExistCheck = false;
                var posData;
                for(var i = 0; i < arrnote.length; i++){
                	if(arrnote[i].pos == pos){
                		isExistCheck = true;
                		posData = i;
                		break;// thoát vòng lặp ngay và luôn đê =)))z
                	}
                }
                if(isExistCheck){
                	$("#add").html('Update');
                	CKEDITOR.instances.editor1.setData(arrnote[posData].content);

                }else{
                	$("#add").html('Add');
                	CKEDITOR.instances.editor1.setData("");
                }
                document.getElementById('note').innerHTML = src[pos].textContent.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/&#39;/g, "'").replace(/&amp;/g, "&");
                //alert(src[pos].textContent.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/&#39;/g, "'").replace(/&amp;/g, "&"));
            });
        	$(document).on('click', '#show', function() {
        		var pos = $(this).data('pos');
        		$("#t"+pos).popover('show');
        	});
        });

        //These functions must be global as YouTube API will call them. 
        var previousOnYouTubePlayerAPIReady = window.onYouTubePlayerAPIReady; 
        window.onYouTubePlayerAPIReady = function() {
        	if(previousOnYouTubePlayerAPIReady)
        	{
        		previousOnYouTubePlayerAPIReady();
        	}
        	mmooc.youtube.APIReady();
        };

        // The API will call this function when the video player is ready.
        // It can be used to auto start the video f.ex.
        window.onPlayerReady = function(event) {
        }

        // The API calls this function when the player's state changes.
        //    The function indicates that when playing a video (state=1),
        //    the player should play for six seconds and then stop.
        window.onPlayerStateChange = function(event) {
        	console.log("onPlayerStateChange " + event.data);
        	var transcript = this.mmooc.youtube.getTranscriptFromVideoId(event.target.getIframe().id);
        	if (event.data == YT.PlayerState.PLAYING) {
        		transcript.playerPlaying();
        	}
        	else
        	{
        		transcript.playerNotPlaying();
        	}
        }

        return {
        	getTranscriptFromTranscriptId(transcriptId)
        	{
        		for (index = 0; index < transcriptArr.length; ++index) {
        			if(transcriptArr[index].getTranscriptId() == transcriptId)
        			{
        				return transcriptArr[index];
        			}
        		}
        		return null;
        	},
        	getTranscriptFromVideoId(videoId)
        	{
        		for (index = 0; index < transcriptArr.length; ++index) {
        			if(transcriptArr[index].getVideoId() == videoId)
        			{
        				return transcriptArr[index];
        			}
        		}
        		return null;
        	},

        	APIReady : function ()
        	{
        		if(!initialized)
        		{
        			$(".mmocVideoTranscript" ).each(function( i ) {
        				var language = $(this).data('language');
        				var name = $(this).data('name');
        				var oTranscript = new transcript(this.id, language, name);
        				oTranscript.getTranscript();
        				transcriptArr.push(oTranscript);
        			});
        			initialized = true;
        		}
        	},
        	init : function ()
        	{
        		this.APIReady();
        	}       
        }

    }();
    //Everything is ready, load the youtube iframe_api
    $.getScript("https://www.youtube.com/iframe_api");
</script>
@endsection