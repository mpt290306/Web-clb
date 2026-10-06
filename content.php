<?php
declare(strict_types=1);

function local_posts(): array
{
    $path = __DIR__ . '/data/posts.json';
    if (!is_file($path)) return [];
    $posts = json_decode((string) file_get_contents($path), true);
    if (!is_array($posts)) return [];
    return array_values(array_filter($posts, fn($post) => ($post['status'] ?? 'draft') === 'published'));
}

function public_posts(): array
{
    return local_posts();
}
