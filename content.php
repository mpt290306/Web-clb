<?php
declare(strict_types=1);
require_once __DIR__ . '/storage.php';

function local_posts(): array
{
    $posts = app_read_json('posts.json');
    return array_values(array_filter($posts, fn($post) => ($post['status'] ?? 'draft') === 'published'));
}

function public_posts(): array
{
    return local_posts();
}
