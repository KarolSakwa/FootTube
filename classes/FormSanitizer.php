<?php

class FormSanitizer
{
    public static function sanitizeFormString($string)
    {
        $string = strip_tags($string);

        $string = str_replace(" ", "", $string);

        return $string;
    }
    
    public static function sanitizeFormUserName($string)
    {
        $string = strip_tags($string);

        return $string;
    }

    public static function sanitizeFormEmail($string)
    {
        $string = strip_tags($string);
        $string = str_replace(" ", "", $string);

        return $string;
    }

    public static function sanitizeFormPassword($string)
    {
        $string = strip_tags($string);

        return $string;
    }
}

?>