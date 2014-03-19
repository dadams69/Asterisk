<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class CronLoader {

    public function __construct()
    {
        if (!interface_exists("cron\FieldInterface")) include "cron/FieldInterface.php";   
        if (!class_exists("cron\AbstractField")) include "cron/AbstractField.php";   
        if (!class_exists("cron\CronExpression")) include "cron/CronExpression.php";
        if (!class_exists("cron\DayOfMonthField")) include "cron/DayOfMonthField.php";   
        if (!class_exists("cron\DayOfWeekField")) include "cron/DayOfWeekField.php";   
        if (!class_exists("cron\FieldFactory")) include "cron/FieldFactory.php";   
        if (!class_exists("cron\HoursField")) include "cron/HoursField.php";   
        if (!class_exists("cron\MinutesField")) include "cron/MinutesField.php";   
        if (!class_exists("cron\MonthField")) include "cron/MonthField.php";   
        if (!class_exists("cron\YearField")) include "cron/YearField.php"; 
    }
}

/* End of file Someclass.php */