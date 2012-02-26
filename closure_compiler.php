<?php

class Closure_compiler
{
    
    public static function minify_js()
    {
        // config variables

        $scripts = Config::get('closure-compiler::closure-compiler.minify_scripts');
        $script_path = Config::get('closure-compiler::closure-compiler.script_path');
        $script_output_file = Config::get('closure-compiler::closure-compiler.script_output_file');
        $java_binary_path_overrides = Config::get('closure-compiler::closure-compiler.java_binary_path_overrides');

        // by default don't compile the javascript

        $compile = false;

        // ensure that the scripts array is indeed an array

        if(!is_array($scripts))
            $scripts = array($scripts);

        $scripts_to_minify = '';

        // does the output file exist?  if so, check the timestamp, if not minify the scripts

        if(file_exists($script_output_file))
        {
            $min_script_timestamp = filemtime($script_output_file);
        }
        else
        {
            $min_script_timestamp = 0;
            $compile = true;
        }

        // loop through the scripts and see if any of them have been updated since our output file has

        foreach($scripts as $script)
        {
            if(file_exists($script_path . $script))
            {
                if(filemtime($script_path . $script) > $min_script_timestamp) $compile = TRUE;

                if(!empty($scripts_to_minify)) $scripts_to_minify .= ' ';

                $scripts_to_minify .= '--js=' . $script_path . $script;
            }
        }

        // compile scripts

        if($compile)
        {
            $java = 'java';

            // find java binary

            foreach($java_binary_path_overrides as $java_bin_path)
            {
                if(file_exists($java_bin_path)) $java = $java_bin_path;
            }
            
            // generate command line

            $jar = '-jar ' . __DIR__ . '/vendor/compiler.jar';
            $out_script = '--js_output_file=' . $script_output_file;

            // red team, go!

            exec("$java $jar $scripts_to_minify $out_script");
        }

    }
};