<?php

defined('_JEXEC') or die;

use Joomla\CMS\Helper\FieldsHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$cat   = $this->category ?? null;
$items = $this->items ?? [];

$useCustom = false;
if ($cat) {
    try {
        $fields = FieldsHelper::getFields('com_content.categories', $cat, true);
        foreach ($fields as $f) {
            if ($f->name === 'layout_news' && (string) $f->value === '1') {
                $useCustom = true;
                break;
            }
        }
    } catch (\Throwable $e) {
    }
}

if (!$useCustom && $cat) {
    $isYearInTitle = (bool) preg_match('/\b(19|20)\d{2}\b/', (string) $cat->title);
    $isYearAlias   = (bool) preg_match('/^(19|20)\d{2}(-?news)?$/i', (string) $cat->alias);
    $useCustom = $isYearInTitle || $isYearAlias;
}

if (!$useCustom) {
    include JPATH_SITE . '/components/com_content/tmpl/category/default_articles.php';
    return;
}

$year = '';
if ($cat && preg_match('/\b((19|20)\d{2})\b/', (string) $cat->title, $m)) {
    $year = $m[1];
}

function dayOf($d)  { return HTMLHelper::_('date', $d, 'd'); }  // 01-31
function monOf($d)  { return HTMLHelper::_('date', $d, 'M'); }  // Jan-Dec

$langTag = isset($this->language) && $this->language ? (string)$this->language : 'en-GB';
?>
<div class="com-content-article item-page">
  <meta itemprop="inLanguage" content="<?php echo htmlspecialchars($langTag, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="com-content-article__body">
    <div class="news-releases">
      <div>
        <h3>News Releases <span class="yr"><?php echo htmlspecialchars($year, ENT_QUOTES, 'UTF-8'); ?></span></h3>
        <ul>
<?php if (!empty($items)) : ?>
<?php foreach ($items as $item) :
    $link  = Route::_(RouteHelper::getArticleRoute($item->slug, (int) $item->catid, (string) $item->language));
    $date  = $item->publish_up ?: $item->created;
    $title = htmlspecialchars($item->title ?? '', ENT_QUOTES, 'UTF-8');
    $intro = trim(strip_tags($item->introtext ?? ''));
    if (mb_strlen($intro) > 120) {
        $intro = mb_substr($intro, 0, 117) . '...';
    }
    $intro = htmlspecialchars($intro, ENT_QUOTES, 'UTF-8');
?>
          <a class="article-link" href="<?php echo $link; ?>">
            <div class="news-item">
              <div class="news-date">
                <div class="day"><?php echo dayOf($date); ?></div>
                <div class="month"><?php echo monOf($date); ?></div>
              </div>
              <div class="news-content">
                <h4><?php echo $title; ?></h4>
                <?php if ($intro !== '') : ?>
                  <p class="news-intro-text"><?php echo $intro; ?></p>
                <?php endif; ?>
              </div>
            </div>
          </a>
<?php endforeach; ?>
<?php else : ?>
          <li><?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?></li>
<?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
