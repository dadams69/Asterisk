<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SESSION)) {
    session_start();
}

if(! function_exists('get_environment')){
    function get_environment(){
        if (isset($_SESSION["asterisk"] ) && isset($_SESSION["asterisk"]["environment"] )) {
            return $_SESSION["asterisk"]["environment"];
        } else {
            return "PRD";
        }
    }
}

if(! function_exists('set_environment')){
    function set_environment($environment){
        $_SESSION["asterisk"]["environment"] = $environment;
    }
}


if(! function_exists('get_now')){
    function get_now(){
        if (isset($_SESSION["asterisk"] ) && isset($_SESSION["asterisk"]["now"] )) {
            return DateTime::createFromFormat("Y-m-d h:i:s", $_SESSION["asterisk"]["now"]);
        } else {
            return new DateTime();
        }
    }
}

if(! function_exists('get_mysql_now')){
    function get_mysql_now(){
        return '"' . get_now()->format("Y-m-d H:i:s") .'"'; 
    }
}

if(! function_exists('set_now')){
    function set_now($now){
        $_SESSION["asterisk"]["now"] = $now;
    }
}

if(! function_exists('get_now_value')){
    function get_now_value(){
        if (isset($_SESSION["asterisk"] ) && isset($_SESSION["asterisk"]["now"] )) {
            return $_SESSION["asterisk"]["now"];
        } else {
            return false;
        }
    }
}