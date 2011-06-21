<?php
/**
/* combine _GET _POST _COOKIE variables with provided default values
/* defaults - associative array of default values
/* overwrite - if true, write result to _REQUEST superglobal
/* super_globals - array of super globals to fetch values from
**/
function get_params($defaults = null, $overwrite = false, $super_globals = array('_GET', '_POST', '_COOKIE'))
{
    $ret = array();

    // fetch values from request
    foreach($super_globals as $sg)
        foreach($GLOBALS[$sg] as $k=>$v)
            $ret[$k] = $v;

    // apply defaults for missing parameters
    if($defaults) foreach($defaults as $k=>$v)
        if(!isset($ret[$k]))
            $ret[$k] = $v;

    if($overwrite)
        $_REQUEST = $ret;

    return $ret;
}

// Example: page.php?style=modern

$argv = get_params(array('id'=>42, 'style'=>'medieval'));

// $argv['id'] = 42
// $argv['style'] = 'modern'



// url maker function, remove duplicated vars

// exemple
// makeUrl('index.php', $_SERVER['QUERY_STRING'], 'name=value&name2=value2');

function makeUrl($path, $qs = false, $qsAdd = false)
{   
    $var_array = array();
    $varAdd_array = array();
    $url = $path;
   
    if($qsAdd)
    {
        $varAdd = explode('&', $qsAdd);
        foreach($varAdd as $varOne)
        {
            $name_value = explode('=', $varOne);
           
            $varAdd_array[$name_value[0]] = $name_value[1];
        }
    }

    if($qs)
    {
        $var = explode('&', $qs);
        foreach($var as $varOne)
        {
            $name_value = explode('=', $varOne);
           
            //remove duplicated vars
            if($qsAdd)
            {
                if(!array_key_exists($name_value[0], $varAdd_array))
                {
                    $var_array[$name_value[0]] = $name_value[1];
                }
            }
            else
            {
                $var_array[$name_value[0]] = $name_value[1];
            }
        }
    }
       
    //make url with querystring   
    $delimiter = "?";
   
    foreach($var_array as $key => $value)
    {
        $url .= $delimiter.$key."=".$value;
        $delimiter = "&";
    }
   
    foreach($varAdd_array as $key => $value)
    {
        $url .= $delimiter.$key."=".$value;
        $delimiter = "&";
    }
   
    return $url;
}

?>