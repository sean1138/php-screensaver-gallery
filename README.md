# php-screensaver-gallery
made this sometime in 2022 to somewhat replicate/replace an ancient screensaver ("GPhotos.scr" probably from [Picasa]([url](https://en.wikipedia.org/wiki/Picasa))) i've used forever.

## Features
1. Automatically gets every image in the **same folder** as `slideshow.php` and builds the html for the slideshow.
2. Images move a little bit in different directions.
3. Configurable timings.
4. Fullscreen mode button, of course.
5. Detects landscape/portrait/square aspect ratios.

## issues
1. ~~MUST set an aspect ratio PHP var (`$aspectRatio`) for all images because the slideshow javascript solution i found at the time relies on CSS `position:absolute;`.~~
2. ~~MUST set number of files PHP var (`$perpage`) because i wasn't smart enough to figure out a better way at the time i first made this.~~
3. ~~PHP Warning:  Undefined array key "dir" in slideshow.php on line 37.~~
4. ~~now that we aren't setting an aspect-ratio for ALL images we've lost the illusion of only 1 image being "visible" at a time.~~
