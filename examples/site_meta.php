<?php

/**
 * SiteMeta - 站点元信息管理工具
 * 用于保存和描述网站的核心元数据
 */

class SiteMeta {
    private array $data;

    public function __construct(array $meta = []) {
        $this->data = $meta;
    }

    public function set(string $key, $value): void {
        $this->data[$key] = $value;
    }

    public function get(string $key, $default = null) {
        return $this->data[$key] ?? $default;
    }

    public function generateDescription(int $maxLength = 160): string {
        $parts = [];

        $title = $this->get('title');
        $url = $this->get('url');
        $keywords = $this->get('keywords', []);

        if ($title) {
            $parts[] = $title;
        }

        if ($keywords && is_array($keywords)) {
            $parts[] = '关键词：' . implode('、', array_slice($keywords, 0, 5));
        }

        if ($url) {
            $parts[] = '来源：' . $url;
        }

        $desc = implode(' | ', $parts);
        if (mb_strlen($desc) > $maxLength) {
            $desc = mb_substr($desc, 0, $maxLength - 3) . '...';
        }

        return $desc;
    }

    public function toArray(): array {
        return $this->data;
    }

    public static function fromArray(array $data): self {
        return new self($data);
    }
}

// 示例：站点信息
$siteMeta = new SiteMeta([
    'title' => 'AYX技术笔记',
    'url' => 'https://chn-ayx.com',
    'description' => '前端、PHP与安全相关技术分享',
    'keywords' => ['ayx', 'PHP', '安全', '前端', '笔记'],
    'author' => 'AYX',
    'version' => '1.0.2',
    'created' => '2024-01-15',
    'updated' => '2025-04-08'
]);

// 生成描述文本
$description = $siteMeta->generateDescription(120);

// 安全输出到 HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteMeta->get('title', '站点'), ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="keywords" content="<?= htmlspecialchars(implode(',', $siteMeta->get('keywords', [])), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <h1><?= htmlspecialchars($siteMeta->get('title', '站点元信息示例'), ENT_QUOTES, 'UTF-8') ?></h1>
    <p>描述：<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
    <p>作者：<?= htmlspecialchars($siteMeta->get('author', '未知'), ENT_QUOTES, 'UTF-8') ?></p>
    <p>版本：<?= htmlspecialchars($siteMeta->get('version', '1.0'), ENT_QUOTES, 'UTF-8') ?></p>
    <p>最后更新：<?= htmlspecialchars($siteMeta->get('updated', ''), ENT_QUOTES, 'UTF-8') ?></p>
    <p>访问：<a href="https://chn-ayx.com" rel="nofollow">chn-ayx.com</a></p>
</body>
</html>