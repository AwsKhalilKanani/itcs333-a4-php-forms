<?php
declare(strict_types=1);
error_reporting(E_ALL);

/**
 * ITCS333 A4 — contact form with server-side validation and PRG.
 */

require_once __DIR__ . '/../src/validate.php';


function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function post_str(string $key): string
{
    $v = $_POST[$key] ?? '';
    return is_string($v) ? $v : '';
}

$values = ['name' => '', 'email' => '', 'message' => ''];
$errors = [];
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'POST') {
    $values['name']    = post_str('name');
    $values['email']   = post_str('email');
    $values['message'] = post_str('message');

    $name    = trim($values['name']);
    $email   = trim($values['email']);
    $message = trim($values['message']);

    
    if (!validate_required($name)) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($name, 2, 60)) {
        $errors['name'] = 'Name must be between 2 and 60 characters.';
    }

    
    if (!validate_required($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!validate_email($email)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    
    if (!validate_required($message)) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($message, 10, 1000)) {
        $errors['message'] = 'Message must be between 10 and 1000 characters.';
    }

    
    if (empty($errors)) {
        header('Location: index.php?success=1', true, 303);
        exit;
    }

    
    http_response_code(200);
}

$success = ($method === 'GET' && ($_GET['success'] ?? '') === '1');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
  <style>
    .error { color: #b00020; }
    .success { color: #1e6b34; font-weight: bold; }
  </style>
</head>
<body>
  <h1>Contact Us</h1>

  <?php if ($success): ?>
    <p class="success">Thank you! Your message has been sent.</p>
  <?php endif; ?>

  <form method="post" action="index.php" novalidate>
    <label>Name <input type="text" name="name" value="<?= e($values['name']) ?>"></label>
    <?php if (isset($errors['name'])): ?>
      <span class="error"><?= e($errors['name']) ?></span>
    <?php endif; ?>
    <br>

    <label>Email <input type="email" name="email" value="<?= e($values['email']) ?>"></label>
    <?php if (isset($errors['email'])): ?>
      <span class="error"><?= e($errors['email']) ?></span>
    <?php endif; ?>
    <br>

    <label>Message <textarea name="message"><?= e($values['message']) ?></textarea></label>
    <?php if (isset($errors['message'])): ?>
      <span class="error"><?= e($errors['message']) ?></span>
    <?php endif; ?>
    <br>

    <button type="submit">Send</button>
  </form>
</body>
</html>