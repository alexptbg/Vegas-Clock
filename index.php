<!DOCTYPE html>
<html lang="en">
<?php
//options
$dir = "./slider/";
$shuffle = "true";
$duration = "10500";
//init
$dirs = array_diff(scandir($dir),array('..', '.'));
$file_display = array('jpg','jpeg','JPG','JPEG','mp4','MP4');
$dir_contents = scandir($dir);
if(!empty($dir_contents)) {
  foreach ($dir_contents as $file) {
    $ext = explode('.',$file);
    $extension = $ext[1];
    $file_type = strtolower($extension);
    if ($file_type == 'mp4') {
      if ($file !== '.' && $file !== '..' && in_array($file_type,$file_display) == true) {      	
      	$without_ext = explode('.',$file);
      	//get video data from text file (format = [duration offset]) //duraction space offset
        $f = fopen($dir.$without_ext[0].'.txt','r');
        $line = fgets($f);
        fclose($f);
        $tempo = explode(' ',$line);
        $string = "{ src: '".$dir.$without_ext[0].".png', delay: ".($tempo[0]-$tempo[1])."000, cover: true, video: [ '".$dir.$file."'";
        if (file_exists($dir.$without_ext[0].".ogv")) {
        	$string .= ", '".$dir.$without_ext[0].".ogv'";
        }
        if (file_exists($dir.$without_ext[0].".webm")) {
        	$string .= ", '".$dir.$without_ext[0].".webm'";
        }
        $string .= " ], loop: false, mute: false }";
        $paths[] = $string;
      }
    } else {
      if ($file !== '.' && $file !== '..' && in_array($file_type,$file_display) == true) {
        $paths[] = "{ src: '".$dir.$file."', delay: ".$duration.", cover: true }";
      }
    }
  }
  if((!empty($paths)) && ($shuffle == "true")) {
    shuffle($paths);	
  }
  //$slides = "".implode(", ", $paths)."";
}
?>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no" />
  <title>SLIDESHOW</title>
  <link type="text/css" rel="stylesheet" href="css/font-awesome.min.css" />
  <link type="text/css" rel="stylesheet" href="css/digital-7.css" />
  <link type="text/css" rel="stylesheet" href="css/vegas.min.css" />
  <link type="text/css" rel="stylesheet" href="css/demo.css" />
</head>
<body>
	<p id="ctime"></p>
    <div class="controls">
       <button id="start" type="button"><i class="fa fa-power-off" aria-hidden="true"></i></button>
       <button id="play" type="button"><i class="fa fa-play" aria-hidden="true"></i></button> 
       <button id="prev" type="button"><i class="fa fa-step-backward" aria-hidden="true"></i></button> 
       <button id="next" type="button"><i class="fa fa-step-forward" aria-hidden="true"></i></button>
       <button id="pause" type="button"><i class="fa fa-pause" aria-hidden="true"></i></button>
       <button id="stop" type="button"><i class="fa fa-stop" aria-hidden="true"></i></button>
       <button id="shuffle" type="button"><i class="fa fa-random" aria-hidden="true"></i></button>
       <button id="refresh" type="button"><i class="fa fa-refresh" aria-hidden="true"></i></button>
       <button id="reboot" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
    </div>
    <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="js/vegas.js"></script>
    <script type="text/javascript" src="js/moment.min.js"></script>
    <script type="text/javascript" src="js/moment-timezone-with-data-2010-2020.js"></script>
    <!--<script type="text/javascript" src="js/demo.js"></script>-->
    <script type="text/javascript">
	$('html').addClass('animated');
	var $body = $('body');
    function slider() {
      var $body = $('body');
      var backgrounds = [<?=implode(", ", $paths)?>];
      $body.vegas({
        preload: false,
        //overlay: 'img/01.png',
        transitionDuration: 1500,
        //delay: 5000,
        slides: backgrounds,
        transition: 'slideDown',
        shuffle: <?php echo $shuffle; ?>/*,
        walk: function (nb, settings) {
          if (settings.video) {
            $('.logo').addClass('collapsed');
          } else {
            $('.logo').removeClass('collapsed');
          }
        }*/
      });
    }
    slider();
    $(function() {
      $('.controls').mouseover(function(){
        $(this).css({cursor: 'none'});
      });
      $('button').mouseover(function(){
        $(this).css({cursor: 'none'});
      });
      $('body').mouseover(function(){
        $(this).css({cursor: 'none'});
      });
      $('body').click(function(e){
	    e.preventDefault();
        return false;
      });
      //116 = F5
      $('body').keypress(function(e){
        if (e.keyCode == 116) {
	      //console.log("F5");
	    } else {
	      e.preventDefault();
	      return false;
	    }
      });
      $('body').bind("mousewheel",function() {
        return false;
      });
      $('body').on("contextmenu",function(){
        return false;
      });  
      $('body').attr('unselectable','on')
        .css({'-moz-user-select':'-moz-none',
          '-moz-user-select':'none',
          '-o-user-select':'none',
          '-khtml-user-select':'none',
          '-webkit-user-select':'none',
          '-ms-user-select':'none',
          'user-select':'none'
        }).bind('selectstart', function(){ 
          return false; 
      });
      //clock
      startTime();
      function startTime() {
        $('#ctime').html(moment().tz("Europe/Sofia").format('HH:mm:ss'));
      }
      setInterval(startTime,1000);
      //buttons
      $('#start').click(function(x) {
        x.preventDefault();
        console.log('start');
        slider();
      });
      $('#play').click(function(x) {
        x.preventDefault();
        console.log('play');
        $body.vegas('play');
      });
      $('#next').click(function(x) {
        x.preventDefault();
        console.log('next');
        $body.vegas('next');
      });
      $('#prev').click(function(x) {
        x.preventDefault();
        console.log('previous');
        $body.vegas('previous');
      });
      $('#pause').click(function(x) {
        x.preventDefault();
        console.log('pause');
        $body.vegas('pause');
      });
      $('#stop').click(function(x) {
        x.preventDefault();
        console.log('destroy');
        $body.vegas('destroy');
      });
      $('#shuffle').click(function(x) {
        x.preventDefault();
        console.log('shuffle');
        $body.vegas('shuffle');
      });
      $('#refresh').click(function(x) {
        x.preventDefault();
        console.log('refresh');
        window.location.reload(true);
      });
      var funcs = [
        function one() { $('button#reboot').attr('class', 'orange'); },
        function two() { $('button#reboot').attr('class', 'red'); },
        function three() { 
          $.ajax({
            url: 'scripts/reboot.php',
            dataType: 'json',
            type: 'get',
            contentType: 'application/json',
            success: function(data,textStatus,jQxhr){
              console.log(data);
            },
            error: function(jqXhr,textStatus,errorThrown){
              console.log(errorThrown);
            }
          });
        }
      ];
      $('#reboot').data('counter', 0).click(function(x) {
  	    x.preventDefault();
  	    console.log('reboot');
        var counter = $(this).data('counter');
        funcs[ counter ]();
        $(this).data('counter', counter < funcs.length-1 ? ++counter : 0);
      });
    });
    </script>
</body>
</html>
