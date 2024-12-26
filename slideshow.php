<?php
$pageTitle = "AI Thots 2023.04.16";

// duration each slide shows in seconds
$slideTime = '8';
// duration for zoom/scale, 3x slideTime seems to be good
$scaleTime = '24s';
// duration for opacity fading
$opacityTime = '4s';
// how much each slide zooms in
$scaleAmount = '1.024';
// allowed file extensions
$extensions = array('png', 'jpg', 'jpeg');
// Check if 'dir' is set in the URL; use the current directory if not
$directory = isset($_GET['dir']) ? rtrim($_GET['dir'], '/') . '/' : __DIR__ . '/';
// Get files from the directory
$files = glob($directory . '*.' . '{' . implode(',', $extensions) . '}', GLOB_BRACE);
// Total number of files found
$total_files = count($files);
// Initialize imgCounter for unique IDs
$imgCounter = 0;
?>
	<title><?php echo $pageTitle; ?></title>
</head>
<body>
	<p class="cent"><?= $total_files ?> files loaded. <button>F for Fullscreen</button></p>
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
						<img id="img<?=($imgCounter++)?>" src='<?= basename($file) ?>' alt='<?= basename($file) ?>' class="<?= $aspectClass ?>" />
				<?php endforeach; ?>
		</figure>

	</div>
	<script defer>
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
		(function () {
			// Set the 'visible' class on the first image when the page loads
			document.getElementById('slideshow').getElementsByTagName('img')[0].classList.add("visible");
			// Call the kenBurns function every X seconds
			window.setInterval(kenBurns, <?=$slideTime?>000);
			// Track the current image in the loop
			// if it is set to 1 (instead of 0) it is because the first image is styled when the page loads
			var images = document.getElementById('slideshow').getElementsByTagName('img'),
				numberOfImages = images.length,
				i = 1;
			function kenBurns() {
				if (i == numberOfImages) {
					i = 0;
				}
				// Add the 'visible' class to the current image
				images[i].classList.add("visible");
				// Remove the 'visible' class from the second-to-last image, last image, or previous image
				// we can't remove the class from the previous element or we'd get a bouncing effect so we clean up the one before last
				// (there must be a smarter way to do this though)
				if (i === 0) {
					// 2nd to last img in loop, happens after we reach the end
					images[numberOfImages - 2].classList.remove("visible");
				}
				if (i === 1) {
					// last img in loop, happens when 2nd image in loop appears
					images[numberOfImages - 1].classList.remove("visible");
				}
				if (i > 1) {
					// happens when is previous img
					images[i - 2].classList.remove("visible");
				}
				i++;
			}
		})();
	</script>
	<style>
		body{
			display:flex;
			flex-direction: column;
			margin:0;
			min-height: 100vh;
			background:black;
			color:#333;
		}
		figure{
			position: relative;
			margin:0;
			height: 90vh;
			max-width: 100vw;
			display: grid;
			place-content: center;
			align-items: center;
			overflow: hidden;
		}
		.slideshow{
			margin-top: auto;
/*			background: #333;*/
		}
		figure img{
			grid-column: 1;
			grid-row: 1;
			place-self: center;
			object-fit: contain;
			transition-property: opacity, transform;
			transition-duration: <?=$opacityTime?>, <?=$scaleTime?>;
			transition-timing-function: ease-in-out, ease;
			opacity:0;
			height: 100%;
/*			width: 100%;*/
			height: fit-content;
/*			width: fit-content;*/
			max-height: 88cqh;
			max-width: 88cqh;
		}
		figure img:nth-of-type(2n+1){transform-origin:top;}
		figure img:nth-of-type(3n+1){transform-origin:top right;}
		figure img:nth-of-type(4n+1){transform-origin:right;}
		figure img:nth-of-type(5n+1){transform-origin:bottom right;}
		figure img:nth-of-type(6n+1){transform-origin:bottom;}
		figure img:nth-of-type(7n+1){transform-origin:bottom left;}
		figure img:nth-of-type(8n+1){transform-origin:left;}
		figure img:nth-of-type(9n+1){transform-origin:top left;}
		img.portrait{
/*			max-width: 94cqw;*/
		}
		img.landscape{
			max-width: 100cqw;
		}
		.visible{
			transform: scale(<?=$scaleAmount?>);
			opacity: 1;
		}
		.slideshow{
			display: flex;
			place-content: center;
			justify-content: center;
		}
		.slideshow:fullscreen{cursor:none;}
		.slideshow:fullscreen figure{height:100vh;}
		.slideshow:fullscreen img{height:99vh;width:99vw;}
		p.cent{
			text-align: center;
		}
		button{
			appearance: none;
			background: transparent;
			color: inherit;
			border: none;
		}
	</style>
</body>
</html>
