<!DOCTYPE html>
<html>
<head>
<title>Search Images</title>
<style>
body{
	 background:#e8e8e8;
}
/*--------------------------------------------------------------
1.0 - BASE SITE STYLES
--------------------------------------------------------------*/
*,*:after,*:before {
  box-sizing:border-box;
  -moz-box-sizing:border-box;
  -webkit-box-sizing:border-box;
}
</style>
<style>.cf:before,.cf:after{content:"";display:table}.cf:after{clear:both}.searchform{background:#f4f4f4;background:rgba(244,244,244,.79);border:1px solid #d3d3d3;padding:2px 5px;width:347px;box-shadow:0 4px 9px rgba(0,0,0,.37);-moz-box-shadow:0 4px 9px rgba(0,0,0,.37);-webkit-box-shadow:0 4px 9px rgba(0,0,0,.37);border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px}.searchform input,.searchform button{float:left}.searchform input{background:#fefefe;border:none;font:12px/12px HelveticaNeue,Helvetica,Arial,sans-serif;margin-right:5px;padding:10px;width:216px;box-shadow:0 0 4px rgba(0,0,0,.4) inset,1px 1px 1px rgba(255,255,255,.75);-moz-box-shadow:0 0 4px rgba(0,0,0,.4) inset,1px 1px 1px rgba(255,255,255,.75);-webkit-box-shadow:0 0 4px rgba(0,0,0,.4) inset,1px 1px 1px rgba(255,255,255,.75);border-radius:9px;-moz-border-radius:9px;-webkit-border-radius:9px}.searchform input:focus{outline:none;box-shadow:0 0 4px #0d76be inset;-moz-box-shadow:0 0 4px #0d76be inset;-webkit-box-shadow:0 0 4px #0d76be inset}.searchform input::-webkit-input-placeholder{font-style:italic;line-height:15px}.searchform input:-moz-placeholder{font-style:italic;line-height:15px}.searchform button{background:#34adec;background:-moz-linear-gradient(top,rgba(52,173,236,1) 0%,rgba(38,145,220,1) 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,rgba(52,173,236,1)),color-stop(100%,rgba(38,145,220,1)));background:-webkit-linear-gradient(top,rgba(52,173,236,1) 0%,rgba(38,145,220,1) 100%);background:-o-linear-gradient(top,rgba(52,173,236,1) 0%,rgba(38,145,220,1) 100%);background:-ms-linear-gradient(top,rgba(52,173,236,1) 0%,rgba(38,145,220,1) 100%);background:linear-gradient(to bottom,rgba(52,173,236,1) 0%,rgba(38,145,220,1) 100%);border:none;color:#fff;cursor:pointer;font:13px/13px HelveticaNeue,Helvetica,Arial,sans-serif;padding:10px;width:106px;box-shadow:0 0 2px #2692dd inset;-moz-box-shadow:0 0 2px #2692dd inset;-webkit-box-shadow:0 0 2px #2692dd inset;border-radius:9px;-moz-border-radius:9px;-webkit-border-radius:9px}.searchform button:hover{opacity:.9}.floating-box{float:left;width:auto;height:auto;margin:10px;border:3px solid #8AC007}.op{opacity:.4}</style>
<style>.pagination{text-align:center;padding:.3em;cursor:default}.pagination a,.pagination span,.pagination em{padding:.2em .5em}.pagination .disabled{color:#aaa}.pagination .current{font-style:normal;font-weight:700;color:#ff0084}.pagination a{border:1px solid #ddd;color:#0063dc;text-decoration:none}.pagination a:hover,.pagination a:focus{border-color:#036;background:#0063dc;color:#fff}.pagination .page_info{color:#aaa;padding-top:.8em}.pagination .previous_page,.pagination .next_page{border-width:2px}.pagination .previous_page{margin-right:1em}.pagination .next_page{margin-left:1em}</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
var gClickCallback = function(img){
	alert(img);
};
var gPage = 0;
jQuery(document).ready(function(){
	jQuery('#phrase').keypress(function(e) {
		if (e.which == 13) {
			jQuery("#search").trigger('click');
		}
	});
	jQuery("#search").off('click').on('click',function(){
		if(jQuery('#phrase').val()=="") return;
		var btn = jQuery(this);
		btn.html('Searching...').prop('disabled', true);
		gPage = 0;
		searchimages();
		return false;
	});			
	jQuery('#form-search').submit(function(){
		return false;
	});			
});

function searchimages()
{
	jQuery.ajax({
		dataType: 'jsonp',
		type: 'POST',
		url: "http://tools.contrib.com/ImageSearch/Search",
		data: {search:jQuery('#phrase').val(),page:gPage},
		success: function(result){
			var img = '';
			
			for(var x=0;x<result.images.length;x++){							
				var src = result.images[x].url;
				var thumb = result.images[x].thumb;							
				img +='<div class="floating-box"><a class="img-result" data-url="'+src+'" href="javascript:;"><img style="" src="'+thumb+'" /></a></div>';
			}
			var page = '';
			
			for(var x=0;x<result.pages.length;x++){
				if(result.currentPageIndex == x){
					page +='<em class="current">'+result.pages[x].label+'</em>';
				}else{
					page +='<a class="page" data-start="'+result.pages[x].start+'" href="javascript:;">'+result.pages[x].label+'</a>';
				}
			}
			
			Popup(img,page);
			jQuery('.img-result').off('click').on('click',function(){
				var src = jQuery(this).attr('data-url');
				if(gClickCallback!=undefined)
					gClickCallback(src);
			});
			
			jQuery('.page').off('click').on('click',function(){
				jQuery('#Popupmsg').html('<img src="http://tools.contrib.com/images/loadingAnimation.gif">');				
				gPage = jQuery(this).attr('data-start');
				searchimages();
			});
		},
		complete: function(a){
			jQuery("#search").html('Search').prop('disabled', false);
		}
	});
}

function Popup(msg,page)
{
	if(document.getElementById('Popup')==undefined){
		var css = '<style>.cd-popup{left:0;top:0;height:100%;width:100%;opacity:0;visibility:hidden;-webkit-transition:opacity .3s 0s,visibility 0 .3s;-moz-transition:opacity .3s 0s,visibility 0 .3s;transition:opacity .3s 0s,visibility 0 .3s}.cd-popup.is-visible{opacity:1;visibility:visible;-webkit-transition:opacity .3s 0s,visibility 0 0;-moz-transition:opacity .3s 0s,visibility 0 0;transition:opacity .3s 0s,visibility 0 0}.cd-popup-container{position:relative;margin:4em auto;background:#FFF;border-radius:.25em .25em .4em .4em;text-align:center;box-shadow:0 0 20px rgba(0,0,0,0.2);-webkit-transform:translateY(-40px);-moz-transform:translateY(-40px);-ms-transform:translateY(-40px);-o-transform:translateY(-40px);transform:translateY(-40px);-webkit-backface-visibility:hidden;-webkit-transition-property:-webkit-transform;-moz-transition-property:-moz-transform;transition-property:transform;-webkit-transition-duration:.3s;-moz-transition-duration:.3s;transition-duration:.3s}.cd-popup-container p{padding:3em 1em}.cd-popup-container .cd-buttons:after{content:"";display:table;clear:both}.cd-popup-container .cd-buttons li{float:left;width:50%}.cd-popup-container .cd-buttons a{display:block;height:60px;line-height:60px;text-transform:uppercase;color:#FFF;-webkit-transition:background-color .2s;-moz-transition:background-color .2s;transition:background-color .2s}.cd-popup-container .cd-buttons li:first-child a{background:#fc7169;border-radius:0 0 0 .25em}.no-touch .cd-popup-container .cd-buttons li:first-child a:hover{background-color:#fc8982}.cd-popup-container .cd-buttons li:last-child a{background:#b6bece;border-radius:0 0 .25em 0}.no-touch .cd-popup-container .cd-buttons li:last-child a:hover{background-color:#c5ccd8}.cd-popup-container .cd-popup-close{position:absolute;top:8px;right:8px;width:30px;height:30px}.cd-popup-container .cd-popup-close::before,.cd-popup-container .cd-popup-close::after{content:"";position:absolute;top:12px;width:14px;height:3px;background-color:#8f9cb5}.cd-popup-container .cd-popup-close::before{-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:rotate(45deg);left:8px}.cd-popup-container .cd-popup-close::after{-webkit-transform:rotate(-45deg);-moz-transform:rotate(-45deg);-ms-transform:rotate(-45deg);-o-transform:rotate(-45deg);transform:rotate(-45deg);right:8px}.is-visible .cd-popup-container{-webkit-transform:translateY(0);-moz-transform:translateY(0);-ms-transform:translateY(0);-o-transform:translateY(0);transform:translateY(0)}@media only screen and (min-width: 1170px){.cd-popup-container{margin:8em auto}}</style>';
		var html = '<div id="'+'Popup'+'" class="cd-popup" role="alert">	<div  class="cd-popup-container"><p id="Popupmsg'+'">...?</p><div class="pagination" style="clear:both">'+page+'</div><a href="#0" class="cd-popup-close img-replace"></a></div></div>';
		jQuery('body').append('<div style="position:fixed;top:10%;z-index:9999999" id="content'+'">'+css+html+'</div>');
	}
	jQuery('.cd-popup').off('click').on('click', function(event){
		if( jQuery(event.target).is('.cd-popup-close') || jQuery(event.target).is('.cd-popup') ) {
			event.preventDefault();
			jQuery('#content').remove();
		}
	}); 
	
	jQuery('#Popupmsg').html(msg);
	jQuery('#Popupmsg').parent().find('.pagination').html(page);
	jQuery('.cd-popup').addClass('is-visible');
}
		
	
</script>
</head>
<body>

<form id="form-search" class="searchform cf">
  <input id="phrase" name="phrase" type="text" placeholder="Search Images">
  <button id="search" type="submit">Search</button>
</form>

</body>
</html>
