<?php
$pageTitle = "AI Thots 2023.04.16";
?>
	<title><?php echo $pageTitle; ?></title>
</head>
<body>
	<div class="text"></div>

	<!-- https://gist.github.com/Jerl92/5817b18f979207b8b258124507d5e5df - pagination.php -->
<?php
// duration each slide shows in seconds
$slideTime = '8';
// duration for zoom/scale, 3x slideTime seems to be good
$scaleTime = '24s';
// duration for opacity fading
$opacityTime = '4s';
// how much each slide zooms in
$scaleAmount = '1.024';
// set the aspect ratio of your images, width/height, set to '' if you want, might not look as good tho
// $aspectRatio = '';
$aspectRatio = 'aspect-ratio:4/5;overflow:hidden;';
// set perpage to the total number of images in folder for slideshow because using $total_files in the js doesn't seem to work, get js errors in console doing that
$perpage = 511;
if (isset($_GET['page'])) {
	$page = (int) $_GET['page'];
}
if (!($page > 0)) {
	$page = 1;
}
$offset = ($page - 1) * $perpage;
$extensions = array('png', 'jpg', 'jpeg');
$files = glob($_GET['dir'] . '*.' . '{' . implode(',', $extensions) . '}', GLOB_BRACE);
$total_files = sizeof($files);
$total_pages = ceil($total_files / $perpage);
$files = array_slice($files, $offset, $perpage);
?>

	<p class="cent"><?=($offset + $perpage)?> of <?=$total_files?> files loaded. <br><button>F for Fullscreen</button></p>
		<div id="slideshow" class="slideshow">
			<figure class="image">
					<?php
					shuffle($files);
					foreach ($files as $file):
							// Get the dimensions of the image
							list($width, $height) = getimagesize($file);
							// Determine the aspect ratio
							if ($width > $height) {
									$aspectClass = 'landscape';
							} elseif ($width < $height) {
									$aspectClass = 'portrait';
							} else {
									$aspectClass = 'square';
							}
					?>
							<!-- Add the class based on aspect ratio -->
							<img id="img<?=($offset++)?>" src='<?=$_GET['dir']?><?=basename($file)?>' alt='<?=basename($file)?>' class="one <?= $aspectClass ?>" />
					<?php endforeach; ?>
			</figure>
	</div>
	<script>
			// fullscreen button/mode
			let fullscreen = document.querySelector(".slideshow");
			let button = document.querySelector("button");
			// Button click event for fullscreen toggle
			button.addEventListener("click", () => {
				toggleFullscreen();
			});
			// Keydown event for pressing "F"
			document.addEventListener("keydown", (event) => {
				if (event.key.toLowerCase() === "f") { // Checks if the key is "F" (case-insensitive)
					toggleFullscreen();
				}
			});
			// Function to toggle fullscreen mode
			function toggleFullscreen() {
				if (!document.fullscreenElement) {
					fullscreen?.requestFullscreen();
				} else {
					document.exitFullscreen();
				}






			}
			/**
			 * See: http://www.css-101.org/articles/ken-burns_effect/css-transition.php
			 https://web.archive.org/web/20130407093601/http://www.css-101.org/articles/ken-burns_effect/css-transition.php
			 */
			/**
			 * The idea is to cycle through the images to apply the "visible" class to them every n seconds.
			 * We can't simply set and remove that class though, because that would make the previous image move back into its original position while the new one fades in.
			 * We need to keep the class on two images at a time (the two that are involved with the transition).
			 */
			(function(){
			// we set the 'visible' class on the first image when the page loads
				document.getElementById('slideshow').getElementsByTagName('img')[0].className = "visible";
			// this calls the kenBurns function every 4 seconds
			// you can increase or decrease this value to get different effects








				window.setInterval(kenBurns, <?=$slideTime?>000);
			// the third variable is to keep track of where we are in the loop
			// if it is set to 1 (instead of 0) it is because the first image is styled when the page loads
				var images = document.getElementById('slideshow').getElementsByTagName('img'),
						numberOfImages  = images.length,
						i = 1;
				function kenBurns() {
				if(i==numberOfImages){ i = 0;}
				images[i].className = "visible";
			// we can't remove the class from the previous element or we'd get a bouncing effect so we clean up the one before last
			// (there must be a smarter way to do this though)
				if(i===0){ images[numberOfImages-2].className = "";}
				if(i===1){ images[numberOfImages-1].className = "";}
				if(i>1){ images[i-2].className = "";}
				i++;
				}
			})();
		</script>
	<style>
		body, article, aside, canvas, details, embed, figcaption, figure, footer, header, hgroup, menu, nav, section, summary, div {
		  display: block;
		  display: flex;
		  flex-direction: column;
		  justify-content: center;
		  align-items: center;
		}
		body{
			background:black;
			color:#333;
		}
		.cent{align-self:center;}
		figure{
			position:relative;
			display: flex;
		  flex-direction: column;
		  margin: 0;
		  padding: .5em 0;
		  max-width: 100%;
		  height: calc(100vh - 160px);
		  align-items: center;
		}
		figure img{
			position:absolute;
			height:100%;
			width:auto;
			background-color:black;
			opacity:0;
			transition-property: opacity, transform;
			transition-duration: <?=$opacityTime?>, <?=$scaleTime?>;
			transition-timing-function: ease-in-out, ease-in;opacity:0;
		}
/*		figure img:first-of-type,*/
		figure img:nth-of-type(2n+1){transform-origin:top;}
		figure img:nth-of-type(3n+1){transform-origin:top right;}
		figure img:nth-of-type(4n+1){transform-origin:right;}
		figure img:nth-of-type(5n+1){transform-origin:bottom right;}
		figure img:nth-of-type(6n+1){transform-origin:bottom;}
		figure img:nth-of-type(7n+1){transform-origin:bottom left;}
		figure img:nth-of-type(8n+1){transform-origin:left;}
		figure img:nth-of-type(9n+1){transform-origin:top left;}
		.visible{transform: scale(1.25);opacity:1;mix-blend-mode:screen;}
		.slideshow figure{aspect-ratio: 5/4;overflow:hidden;}
		.slideshow img{}
		.slideshow:fullscreen{cursor:none;}
		.slideshow:fullscreen figure{height:100vh;overflow:visible;}
		.slideshow:fullscreen img{height:99vh;}
	</style>
</body>
</html>


