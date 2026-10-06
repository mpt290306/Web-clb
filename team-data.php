<?php
declare(strict_types=1);
require_once __DIR__ . '/storage.php';

function team_members(string $type): array
{
    $data = app_read_json('team.json');
    if (!is_array($data)) return [];
    return array_values(array_filter($data, fn($member) => is_array($member) && ($member['type'] ?? 'board') === $type));
}

function team_render_card(array $member): void
{
    $name = (string) ($member['name'] ?? '');
    $image = (string) ($member['image'] ?? '');
    $role = ($member['type'] ?? 'board') === 'student'
        ? implode(' · ', array_filter([(string) ($member['school'] ?? ''), (string) ($member['major'] ?? '')]))
        : (string) ($member['position'] ?? '');
    preg_match('/^./u', $name, $initialMatch);
    $initial = $initialMatch[0] ?? '';
    if (function_exists('mb_strtoupper')) $initial = mb_strtoupper($initial, 'UTF-8');
    ?>
    <article class="team-card">
        <div class="member-avatar">
            <?php if ($image !== ''): ?>
                <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="Ảnh đại diện của <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
            <?php else: ?>
                <div class="avatar-placeholder"><?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
        </div>
        <h2 class="member-name"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if ($role !== ''): ?><p class="member-role"><?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <?php if (!empty($member['detail'])): ?><p class="member-bio"><?= nl2br(htmlspecialchars((string) $member['detail'], ENT_QUOTES, 'UTF-8')) ?></p><?php endif; ?>
    </article>
    <?php
}
