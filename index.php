<?php
require_once(__DIR__ . '/vendor/autoload.php');

use ScssPhp\ScssPhp\Compiler;

// ---------- INIT ----------

// Instantiate compiler.
$scss = new Compiler();

// define the sources array containing strings we'll compile into scss code.
$sources = [];

// Set local import paths for @import rules found in $sources.
$scss->setImportPaths('scss/');


// ---------- HELPERS ----------

// check if a color is a valid hex color. @TODO: broaden to all formats (rgb, hsl, etc)
function isColorValid($color) {
	$colorValid = false;
	if ($color) {
		$rgb = substr($color, 1);
		if( in_array(strlen($rgb), [3,6]) ) { // color is 3 or 6 chars long, check that each one is [0-f].
			$colorValid = true; // probably valid.
			foreach( str_split($rgb, 1) as $channel) {
				if (!in_array($channel, ['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f'])) {
					$colorValid = false; // not a hex char, invalid.
					break;
				}
			}
		}
	}
	return $colorValid;
}


// ---------- DEFAULT THEME COLORS ----------

// Define some default colors if the user doesn't provide the necessary colors.
$defaultThemeColors = array(
	'accentColor1' => "#fff",
	'backgroundColor' => "#000",
);
foreach($defaultThemeColors as $k => $v) {
	if (isColorValid($v)) $sources[] = '$'.$k.': '.$v.' !default;';
}

// ---------- USER THEME COLORS ----------

// Grab the color values from the database, and create a unique key for this data.
function getDbColors() {
	return array(
		// Yellow & Navy
		'accentColor1' => '#f9d970',
		'backgroundColor' => '#445566',

		// Pink & Purple
		'accentColor1' => '#ff00aa',
		'backgroundColor' => '#309',
		
		// Yellow & Default
		'accentColor1' => '#f9d970',
		'backgroundColor' => '',
		
		// Default & Purple
		'accentColor1' => null,
		'backgroundColor' => '#309',
	);
}
$themeColors = getDbColors();

// Define the unique key for this user's theme.
$themeKey = md5(json_encode($themeColors));

// Filter out invalid colors and use plugin to assign:
foreach($themeColors as $k => $v) {
	if (!isColorValid($v)) unset($themeColors[$k]);
}
$scss->setVariables($themeColors);


// ---------- COMPILE STYLES ----------

// Add in the base theme which utilizes the color variables.
$sources[] = '@import "base.scss";';

// Concatenate the sources and compile into CSS.
$source = implode("\n", $sources);
$inlineCSS = $scss->compile($source);

?>

<html>
<head>
	<title>Sample SCSS Compiler</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" type="text/css" />
	<style type="text/css">
		<?php echo $inlineCSS; ?>
	</style>
</head>
<body>

<div class="fluid-container light">
  <div class="container" id="features">
    <div class="row feature-intro">
      <div class="col-sm-12">
        <h2>SCSS-PHP Compiler Demo</h2>
        <p>
           Basic layout with some cards. Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate, dicta fugit. Neque reiciendis vel eius maiores, dicta quasi optio consequatur, ad corporis itaque eum accusantium ipsam at minus praesentium eligendi.<br/><a class="primary" href="#">Learn about new features &raquo;</a></p>
      </div>
    </div>
    <div class="row features">
      <div class="col-md-4">
        <div class="card panel-scores">
          <div class="card-header">
            <div class="card-title">Panel Scores Released</div>
          </div>
          <div class="card-body">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus adipisci recusandae, officia asperiores minus ea ipsa consequatur dolorum veritatis nulla eligendi quibusdam quae mollitia rem alias quos?</p>
            <p><a href="#">Can you score higher than our panel?</a></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card calculator">
          <div class="card-header">
            <div class="card-title">Calculate Your Savings</div>
          </div>
          <div class="card-body">
            <p>Lorem ipsum dolor, adipisicing elit. Alias, aliquid assumenda consequatur omnis vero rerum!</p>
            <p>Start by entering your data:</p>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1">$</span></div>
              <input class="form-control" type="number" max="10000" min="0" step=".05" placeholder="0.00" aria-label="payment amount" aria-describedby="basic-addon1" onchange="(function(el){el.value=parseFloat(el.value).toFixed(2);})(this)"/>
            </div>
            <button class="btn btn-primary">Estimate Cost</button>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card messages">
          <div class="card-header">
            <div class="card-title">Important Message</div>
          </div>
          <div class="card-body">
            <p class="danger">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eum beatae consectetur illo aliquam suscipit nobis maiores molestiae quam autem, nulla magnam modi vero odit ut dolorum dolores, ratione eaque! Rem.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="fluid-container dark">
  <div class="container" id="topics">
    <div class="row intro">
      <div class="col-sm-12">
        <h2>Topics</h2>
        <p>We are always working to lorem ipsum, dolor blanditiis nulla officia asperiores minus ea ipsa consequatur dolorum. We specialize in three main groups.</p>
      </div>
    </div>
    <div class="row topic">
      <div class="col-md-7 col-lg-6">
        <h4>Exercitationem sit amet.</h4>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae quo, saepe similique ex obcaecati praesentium excepturi nulla, quisquam voluptatem explicabo aliquid delectus id blanditiis iure dolorem distinctio! Exercitationem, sint minima!</p><a href="#">Aliquam suscipit nobis &raquo;</a>
      </div>
      <div class="col-md-5 col-lg-4 offset-lg-1"><img class="img-fluid" src="//placehold.it/360x180&amp;text=FPO" alt=""/></div>
    </div>
    <div class="row topic">
      <div class="col-md-7 col-lg-6 offset-lg-1">
        <h4>Magni ratione illum sit.</h4>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi nostrum possimus accusamus fuga magni molestias, consequatur porro. Ducimus rerum, distinctio incidunt maiores magni ratione illum nobis aliquam, tempora itaque tenetur.</p><a href="#">Lorem ipsum &raquo;</a>
      </div>
      <div class="col-md-5 col-lg-4"><img class="img-fluid" src="//placehold.it/360x180&amp;text=FPO" alt=""/></div>
    </div>
    <div class="row topic">
      <div class="col-md-7 col-lg-6">
        <h4>Explicabo deleniti nemo quos dolor.</h4>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eligendi a cum ullam expedita dicta aperiam incidunt tenetur. Saepe, voluptas nisi, quae ab unde explicabo deleniti reprehenderit nemo quos, consectetur vero.</p><a href="#">Reprehenderit nemo &raquo;</a>
      </div>
      <div class="col-md-5 col-lg-4 offset-lg-1"><img class="img-fluid" src="//placehold.it/360x180&amp;text=FPO" alt=""/></div>
    </div>
  </div>
</div>
</body>
</html>