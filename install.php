#!/usr/bin/php
<?php
/*
 *  CryptoPaste Installer - The installer script for CrpyptoPaste
 *  Copyright (C) 2017  Patrick Childs
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define("VERSION", "0.1-DEV");

/**
 * 	Short Options:
 * 	h - Help
 * 	v - Verbosity
 * 	V - version
 */
$shortOpts = "hv::V";

// Long options
$longOpts = array(
	"db:host:",
	"db:port:",
	"db:user:",
	"db:pass:",
	"www:host:"

);
$license = "\n\tCryptoPaste Installer Copyright (C) 2017 Patrick Childs\n\tThis program comes with ABSOLUTELY NO WARRANTY\n\n";
$printMap = array(
	"h"=>"Usage:
	install.php -h
	install.php -V
	install.php [-v <verbosity>] --db:host=<host> --db:port=<port> --db:user=<user> --db:pass=<pass> --www:host=<host>\n",
	"V"=>"CryptoPaste installer: Version ".VERSION."\n"
);


function alreadyInstalled(){
	return file_exists("inc/config.php");
}

/**
 * A callback that walks through an array, and prints the first quitable option it finds
 * Checks only short options
 * Compares the index of the passed array to the passed $printMap's index and prints the coliding string
 * @param $_unused	- The value of the element in the array, which is not ever used
 * @param $index	- The index of the element in the array
 * @param $printMap	- The associative array containing the string to print, and the option (index) that corresponds
 */
$printQuitableOptions = function(&$_unused, $option, $printMap){

	// Checks if it's a short option (index only 1 character long)
	if(strlen($option)===1){

		// Checks to see if the short option exists in the print map
		if(array_key_exists($option, $printMap)){

			// Print the string that corresponds to the short option
			echo $printMap[$option];

			exit();
		}
	}	
};

/**
 * Callback used to filter down to only long options, or options that have more thena  single character
 * @param $option - The current option to check
 */
$filterToLongOptions = function($option){
	return strlen($option)>1;
};

/**
 * Checks to see if all the parsed options are valid options, and they are all present
 * @param $validOptions - An array of valid options
 * @param $parsedOptions - An array of parsed options
 */
function checkRequiredOptions($requiredOptions, $parsedOptions){

	// Make sure the options provided are the same length as the required options
	if(sizeof($parsedOptions) !== sizeof($requiredOptions)){
		return false;
	}

	return true;

}

// TODO: Write document block
function writeConfiguration($templateConfigFileLocation, $realConfigFileLocation, $options){

	// Open the config file in read only and read the contents
	$templateFile = fopen($templateConfigFileLocation, "r") or die("Unable to open config file!");

	// Read the contents of the file to the variable $configContents
	$configContents = fread($templateFile,filesize($templateConfigFileLocation));

	// Close the read only handle on the config file
	fclose($templateFile);

	// open config file in write only mode
	$configFile = fopen($realConfigFileLocation, "w") or die("Unable to open config file!");


	// TODO Replace the for loop with an array walk
	// Iterate through each provided option
	foreach($options as $option=>$value){

		// Replace the values of existing defines with the value of the provided associative array
		$configContents = preg_replace('/(define\("'.$option.'", ")('.$option.')("\);)/i',
			'$1'.$value.'$3', $configContents);
	}

	// Replace the false on the install with true to indicate a successful installation
	$configContents = preg_replace('/(define\("INSTALLED", )(false)(\);)/', '$1true$3', $configContents);

	// Write the modified contents to the config file
	fwrite($configFile, $configContents);

	// Close the config file
	fclose($configFile);
}

echo $license;

$options = getopt($shortOpts,$longOpts);

// Check if it's already installed, and if it is error out with details about how to uninstall
if(alreadyInstalled()){
	echo "This has already been installed, there is no need to re-install.\n If you wish to un-install the application, please refer to the documentation at https://github.com/faximilie/cryptopaste/README.md\n";
	exit();
}

// Iterate through the supplied options and determine if there are any that we should print and then exit
array_walk($options, $printQuitableOptions, $printMap);

// Filter down all the options to only long options
$longOptions = array_filter($options, $filterToLongOptions, ARRAY_FILTER_USE_KEY);

// check to see if the supplied options have all the required long options
$hasRequiredLongOptions = checkRequiredOptions($longOpts, $longOptions);

// Error and quit if it does not have all required arguments
if (! $hasRequiredLongOptions){
	echo "Please specify the right number of required options to configure this software\n";
	exit();
}


// Error and quit if inc/config.php is not writable
if (! is_writable("inc/")){
	echo "Please ensure the directory \"inc/\" is writable\n";
	exit();
}


echo "Attempting to write to config file\n";

// Write the options to a configuration file
writeConfiguration("inc/config.dist.php", "inc/config.php", $options);

echo "Install complete\n";
exit();
?>
