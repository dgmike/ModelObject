<?php
require_once('PHPUnit.php');
require_once('config.php');
$classes_before = get_declared_classes();

foreach (glob('*.test.php') as $item) {
    include_once($item);
}
$classes_after = get_declared_classes();
$new_classes   = array_diff($classes_after, $classes_before);
$suite         = new PHPUnit_TestSuite;
foreach ($new_classes as $item) {
    $suite->addTestSuite($item);
}
$result = PHPUnit::run($suite);
if (count($_SERVER['argv'])) {
    print PHP_EOL.$result->toString().PHP_EOL;
} else {
    print $result->toHTML();
}
