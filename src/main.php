<?php
/**
 * Main start point for the PHP Extensions Generator
 *
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/peg-org/peg-src Source code.
 */

// Set the path to peg files by using environment variables if available,
// if not, it uses current path
if(isset($_SERVER["PEG_SKELETON_PATH"]))
    define("PEG_SKELETON_PATH", $_SERVER["PEG_SKELETON_PATH"]);
else
    define("PEG_SKELETON_PATH", __DIR__ . "/skeleton");

if(isset($_SERVER["PEG_LIBRARY_PATH"]))
    define("PEG_LIBRARY_PATH", $_SERVER["PEG_LIBRARY_PATH"]);
else
    define("PEG_LIBRARY_PATH", __DIR__ . "/");

if(isset($_SERVER["PEG_LOCALE_PATH"]))
    define("PEG_LOCALE_PATH", $_SERVER["PEG_LOCALE_PATH"]);
else
    define("PEG_LOCALE_PATH", __DIR__ . "/locale");


if(!file_exists(PEG_LIBRARY_PATH . "lib"))
    throw new Exception("Peg lib path not found.");

if(!file_exists(PEG_SKELETON_PATH))
    throw new Exception("Peg skeleton files path not found.");

// Register class auto-loader
function peg_autoloader($class_name)
{
    $file = str_replace("\\", "/", $class_name) . ".php";

    include(PEG_LIBRARY_PATH . "lib/" . strtolower($file));
}

spl_autoload_register("peg_autoloader");

// Register global function for translating and to facilitate automatic
// generation of po files.
function t($text)
{
    static $language_object;

    if(!$language_object)
    {
        $language_object = new Localization\Language(PEG_LOCALE_PATH);
    }

    return $language_object->Translate($text);
}

// Initialize the application
Peg\Application::Initialize();

// Retrieve a reference of main command line parser
$parser = Peg\Application::GetCLIParser();

// Start the command line parser
$parser->Start($argc, $argv);


?>