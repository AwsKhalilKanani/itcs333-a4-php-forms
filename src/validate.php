<?php
declare(strict_types=1);

/**
 * ITCS333 A4 — pure validation functions.
 * They are unit-tested by the autograder (tests/UnitTest.php).
 */

function validate_required(string $v): bool
{
    // true when $v is not empty after trimming
    return trim($v) !== '';
}

function validate_email(string $v): bool
{
    // true when $v is a valid email address
    return filter_var($v, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_length(string $v, int $min, int $max): bool
{
    // true when mb_strlen($v) is within [$min, $max]
    $len = mb_strlen($v, 'UTF-8');
    return $len >= $min && $len <= $max;
}