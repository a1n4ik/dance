<?php
// ========== includes/seo-meta.php - Менеджер SEO метатегов ==========

class SEOMetaManager {
    
    // Базовые мета-данные сайта
    private $site_name = "Театр танца \"Столица\"";
    private $site_description = "Профессиональное обучение классическому танцу, народному танцу, современной хореографии в Москве";
    private $base_keywords = "театр танца, балет, классический танец, народный танец, москва, дети, хореография";
    
    // Мета-данные для статических страниц
    private $pages_meta = [
        'index' => [
            'title' => 'Театр танца "Столица" - Танцевальная школа в Москве',
            'description' => 'Театр танца Столица - профессиональное обучение классическому танцу, народному танцу, современной хореографии. Педагоги - солисты театра Гжель.',
            'keywords' => 'театр танца, балет, классический танец, народный танец, москва, дети, хореография, школа танцев'
        ],
        'contacts' => [
            'title' => 'Контакты - Театр танца "Столица"',
            'description' => 'Контакты театра танца Столица в Москве. Адрес, телефон, расписание занятий. Запишитесь на пробный урок по классическому и народному танцу.',
            'keywords' => 'контакты, театр танца столица, адрес, телефон, москва, запись на занятия'
        ],
        'classical-dance' => [
            'title' => 'Классический танец и балет - Театр танца "Столица"',
            'description' => 'Обучение классическому танцу и балету в театре танца Столица. Профессиональные педагоги, классическая хореография для детей и взрослых.',
            'keywords' => 'классический танец, балет, хореография, классика, москва, дети, взрослые'
        ],
        'folk-dance' => [
            'title' => 'Народный танец - Театр танца "Столица"',
            'description' => 'Изучение народного танца в театре Столица. Русские народные танцы, характерный танец, народная хореография для всех возрастов.',
            'keywords' => 'народный танец, русские танцы, характерный танец, народная хореография, фольклор'
        ],
        'jazz-modern' => [
            'title' => 'Джаз и модерн - Театр танца "Столица"',
            'description' => 'Современная хореография в театре танца Столица. Обучение джазу, модерну, контемпорари. Пластика и выразительность движений.',
            'keywords' => 'джаз, модерн, современный танец, контемпорари, пластика, современная хореография'
        ],
        'baby-ballet' => [
            'title' => 'Беби-балет для малышей - Театр танца "Столица"',
            'description' => 'Беби-балет для детей от 3 лет в театре танца Столица. Развитие координации, музыкальности, первые шаги в мире танца.',
            'keywords' => 'беби-балет, дети 3 года, малыши, развитие, координация, музыкальность'
        ],
        'gymnastics' => [
            'title' => 'Хореографическая гимнастика - Театр танца "Столица"',
            'description' => 'Хореографическая гимнастика для танцоров. Развитие гибкости, силы, пластики. Подготовительные упражнения для танца.',
            'keywords' => 'хореографическая гимнастика, гибкость, растяжка, акробатика, подготовка'
        ],
        'acrobatics' => [
            'title' => 'Акробатика для танцоров - Театр танца "Столица"',
            'description' => 'Акробатика в танце в театре Столица. Развитие силы, ловкости, акробатических элементов для хореографии.',
            'keywords' => 'акробатика, сила, ловкость, акробатические элементы, танцевальная акробатика'
        ],
        'news' => [
            'title' => 'Новости театра танца "Столица"',
            'description' => 'Актуальные новости театра танца Столица. События, концерты, достижения учеников, мастер-классы и выступления.',
            'keywords' => 'новости, события, концерты, выступления, достижения, мастер-классы'
        ],
        'projects' => [
            'title' => 'Проекты и постановки - Театр танца "Столица"',
            'description' => 'Танцевальные проекты и постановки театра Столица. Спектакли, концертные номера, участие в фестивалях и конкурсах.',
            'keywords' => 'проекты, постановки, спектакли, концерты, фестивали, конкурсы'
        ],
        'schedule' => [
            'title' => 'Расписание занятий - Театр танца "Столица"',
            'description' => 'Расписание занятий в театре танца Столица. График уроков по классическому, народному танцу, современной хореографии.',
            'keywords' => 'расписание, график занятий, уроки танца, время занятий'
        ],
        'reviews' => [
            'title' => 'Отзывы о театре танца "Столица"',
            'description' => 'Отзывы учеников и родителей о театре танца Столица. Опыт обучения, достижения, впечатления от занятий.',
            'keywords' => 'отзывы, мнения, опыт обучения, родители, ученики, впечатления'
        ]
    ];
    
    /**
     * Получить мета-данные для страницы
     */
    public function getPageMeta($page_name, $additional_data = []) {
        // Определяем базовые мета-данные
        $meta = $this->pages_meta[$page_name] ?? [
            'title' => $this->site_name,
            'description' => $this->site_description,
            'keywords' => $this->base_keywords
        ];
        
        // Дополняем данными из БД или параметров
        if (!empty($additional_data)) {
            $meta = $this->enhanceWithDynamicData($meta, $additional_data, $page_name);
        }
        
        return $meta;
    }
    
    /**
     * Улучшить мета-данные динамическим контентом
     */
    private function enhanceWithDynamicData($meta, $data, $page_name) {
        switch ($page_name) {
            case 'news':
                if (isset($data['news_item'])) {
                    $news = $data['news_item'];
                    $meta['title'] = htmlspecialchars($news['title']) . ' - Новости театра танца "Столица"';
                    $meta['description'] = $this->createDescriptionFromContent($news['content'] ?? $news['excerpt'] ?? '', 155);
                    $meta['keywords'] = $this->base_keywords . ', ' . $this->extractKeywordsFromContent($news['title'] . ' ' . ($news['content'] ?? ''));
                }
                break;
                
            case 'projects':
                if (isset($data['project_item'])) {
                    $project = $data['project_item'];
                    $meta['title'] = htmlspecialchars($project['title']) . ' - Проекты театра танца "Столица"';
                    $meta['description'] = $this->createDescriptionFromContent($project['description'] ?? '', 155);
                    $meta['keywords'] = $this->base_keywords . ', проект, ' . $this->extractKeywordsFromContent($project['title']);
                }
                break;
        }
        
        return $meta;
    }
    
    /**
     * Создать описание из контента с ограничением по длине
     */
    private function createDescriptionFromContent($content, $max_length = 155) {
        // Убираем HTML теги
        $text = strip_tags($content);
        
        // Убираем лишние пробелы
        $text = preg_replace('/\s+/', ' ', trim($text));
        
        // Обрезаем до нужной длины
        if (mb_strlen($text) > $max_length) {
            $text = mb_substr($text, 0, $max_length - 3);
            // Обрезаем до последнего целого слова
            $text = mb_substr($text, 0, mb_strrpos($text, ' ')) . '...';
        }
        
        return $text;
    }
    
    /**
     * Извлечь ключевые слова из контента
     */
    private function extractKeywordsFromContent($content, $max_keywords = 5) {
        // Простая логика извлечения ключевых слов
        $words = explode(' ', strip_tags(strtolower($content)));
        $words = array_filter($words, function($word) {
            return mb_strlen($word) > 3 && !in_array($word, ['для', 'как', 'что', 'это', 'был', 'была', 'были', 'где', 'когда']);
        });
        
        $word_count = array_count_values($words);
        arsort($word_count);
        
        return implode(', ', array_slice(array_keys($word_count), 0, $max_keywords));
    }
    
    /**
     * Генерировать Open Graph данные
     */
    public function generateOpenGraph($meta, $current_url, $image_url = null) {
        $og_data = [
            'type' => 'website',
            'site_name' => $this->site_name,
            'title' => $meta['title'],
            'description' => $meta['description'],
            'url' => $current_url,
            'image' => $image_url ?: 'https://stolitsa-dance.ru/wp-content/uploads/2025/07/logo_new.png'
        ];
        
        return $og_data;
    }
    
    /**
     * Создать JSON-LD разметку для конкретной страницы
     */
    public function generateJsonLD($page_name, $meta, $additional_data = []) {
        $base_schema = [
            "@context" => "https://schema.org",
            "@type" => "DanceSchool",
            "name" => $this->site_name,
            "description" => $meta['description'],
            "url" => "https://stolitsa-dance.ru",
            "logo" => "https://stolitsa-dance.ru/wp-content/uploads/2025/07/logo_new.png",
            "telephone" => "+7-999-930-36-60",
            "address" => [
                "@type" => "PostalAddress",
                "addressLocality" => "Москва",
                "addressCountry" => "RU"
            ],
            "sameAs" => [
                "https://instagram.com/stolitsa_dance",
                "https://t.me/stolitsa_dance"
            ]
        ];
        
        // Дополняем схему в зависимости от типа страницы
        switch ($page_name) {
            case 'news':
                if (isset($additional_data['news_item'])) {
                    $base_schema["@type"] = "Article";
                    $base_schema["headline"] = $additional_data['news_item']['title'];
                    $base_schema["datePublished"] = $additional_data['news_item']['created_at'];
                    $base_schema["author"] = [
                        "@type" => "Organization",
                        "name" => $this->site_name
                    ];
                }
                break;
                
            case 'projects':
                if (isset($additional_data['project_item'])) {
                    $base_schema["@type"] = "Event";
                    $base_schema["name"] = $additional_data['project_item']['title'];
                    $base_schema["description"] = $additional_data['project_item']['description'];
                    if (isset($additional_data['project_item']['project_date'])) {
                        $base_schema["startDate"] = $additional_data['project_item']['project_date'];
                    }
                }
                break;
        }
        
        return json_encode($base_schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}

// ========== Функция для использования в шаблонах ==========

/**
 * Инициализировать SEO данные для страницы
 */
function initSEO($page_name, $additional_data = []) {
    global $page_title, $page_description, $page_keywords, $og_data, $json_ld;
    
    $seo = new SEOMetaManager();
    $meta = $seo->getPageMeta($page_name, $additional_data);
    
    // Устанавливаем глобальные переменные
    $page_title = $meta['title'];
    $page_description = $meta['description'];
    $page_keywords = $meta['keywords'];
    
    // Генерируем Open Graph данные
    $current_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $og_data = $seo->generateOpenGraph($meta, $current_url, $additional_data['image'] ?? null);
    
    // Генерируем JSON-LD
    $json_ld = $seo->generateJsonLD($page_name, $meta, $additional_data);
    
    return $meta;
}

/**
 * Вывести мета-теги в <head>
 */
function renderMetaTags() {
    global $page_title, $page_description, $page_keywords, $og_data, $json_ld;
    ?>
    
    <!-- SEO мета-теги -->
    <title><?= htmlspecialchars($page_title ?? 'Театр танца "Столица"') ?></title>
    <?php if (!empty($page_description)): ?>
        <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <?php endif; ?>
    <?php if (!empty($page_keywords)): ?>
        <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
    <?php endif; ?>
    
    <!-- Open Graph мета-теги -->
    <?php if (!empty($og_data)): ?>
        <meta property="og:type" content="<?= htmlspecialchars($og_data['type']) ?>">
        <meta property="og:site_name" content="<?= htmlspecialchars($og_data['site_name']) ?>">
        <meta property="og:title" content="<?= htmlspecialchars($og_data['title']) ?>">
        <meta property="og:description" content="<?= htmlspecialchars($og_data['description']) ?>">
        <meta property="og:image" content="<?= htmlspecialchars($og_data['image']) ?>">
        <meta property="og:url" content="<?= htmlspecialchars($og_data['url']) ?>">
    <?php endif; ?>
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
    
    <!-- JSON-LD разметка -->
    <?php if (!empty($json_ld)): ?>
        <script type="application/ld+json">
        <?= $json_ld ?>
        </script>
    <?php endif; ?>
    
    <?php
}
?>