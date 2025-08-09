<?php

// Redirect to public directory if accessing root
if ($_SERVER['REQUEST_URI'] === '/') {
    header('Location: /public/');
    exit;
}

// Forward Vercel requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
