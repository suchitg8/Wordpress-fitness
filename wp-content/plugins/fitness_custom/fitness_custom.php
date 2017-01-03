<?php
/*
Plugin Name: Fitness
Description: Question post type
Author: Ken
WDP ID: 223
Version: 0.1
*/

require('fitness_core.php');  // registers custom post type Question
require('fitness_home_page.php'); // registers [fitness_home] shortcode
require('fitness_trainer_list_page.php'); // registers [fitness_list_trainers] shortcode

