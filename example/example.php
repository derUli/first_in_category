<?php
$firstInCategory = ControllerRegistry::get("FirstInCategory");

$category_id = 2;
$language = "de";

// Get First Page in a category
$firstInCategory->getFirstPageInCategory($category_id, $language);

// Get First List which filters by a category
$firstInCategory->getFirstListWithCategory($category_id, $language);
?>