<?php

return array(
    
    /*
	|--------------------------------------------------------------------------
	| Script Root Path
	|--------------------------------------------------------------------------
	*/

    'script_path' => path('public') . 'js/',

    /*
    |--------------------------------------------------------------------------
    | Script Output File
    |--------------------------------------------------------------------------
    */

    'script_output_file' => path('public') . 'js/script.min.js',

	/*
	|--------------------------------------------------------------------------
	| Scripts to Be Minified - Relative to Root Path
	|--------------------------------------------------------------------------
	*/
    
    'minify_scripts' => array(
        // included some examples, replace these with your own
        'libs/less-1.1.3.min.js',
        'libs/google-code-prettify/prettify.js',
    ),

    /*
    |--------------------------------------------------------------------------
    | Java Binary Override Paths
    |--------------------------------------------------------------------------
    |
    | This doesn't typically need to be set as the Java binary is typically
    | already in path.  However, if it's not then feel free to to include a
    | full absolute path to the Java binary.  Should this site run against
    | different environments which have different Java binary paths simply add
    | multiple paths to this array and they'll be checked until a valid binary
    | is found.
    */

    'java_binary_path_overrides' => array(),
);