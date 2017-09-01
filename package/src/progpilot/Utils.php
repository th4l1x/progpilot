<?php

/*
 * This file is part of ProgPilot, a static analyzer for security
 *
 * @copyright 2017 Eric Therond. All rights reserved
 * @license MIT See LICENSE at the root of the project for more info
 */


namespace progpilot;

use progpilot\Objects\MyOp;

class Utils
{
	public static function encode_characters($string)
	{
		return htmlentities($string, ENT_QUOTES, 'UTF-8');
	}

	public static function print_warning($context, $message)
	{
        if($context->get_print_warning())
            fwrite(STDERR, "progpilot warning : $message\n");
	}

	public static function print_error($context, $message)
	{
        throw new \Exception($message);
	}

	public static function print_properties($props)
	{
		$property_name = "";

		if(is_array($props))
		{
			foreach($props as $prop)
				$property_name .= "->".Utils::encode_characters($prop);
		}

		return $property_name;
	}

	public static function print_definition($def)
	{
		if($def->get_is_property())
			$def_name = "\$".Utils::encode_characters($def->get_name()).Utils::print_properties($def->property->get_properties());
		else
			$def_name = "\$".Utils::encode_characters($def->get_name());

		$name_array = "";
		if($def->get_is_array())
			Utils::print_array($def->get_array_value(), $name_array);

		return $def_name.$name_array;
	}

	public static function print_function($function)
	{
		$function_name = "\$";
		if($function->get_is_method())
			$function_name = Utils::encode_characters($function->get_myclass()->get_name())."->";

		$function_name .= Utils::encode_characters($function->get_name());

		return $function_name;
	}

	public static function print_array($array, &$print)
	{
		if(is_array($array))
		{
			foreach($array as $index => $value)
			{
				if(isset($array[$index]))
				{
					if(is_string($index))
						$print .= "[\"".Utils::encode_characters($index)."\"]";
					else
						$print .= "[".Utils::encode_characters($index)."]";

					Utils::print_array($array[$index], $print);
				}
			}
		}
	}
}

?>
