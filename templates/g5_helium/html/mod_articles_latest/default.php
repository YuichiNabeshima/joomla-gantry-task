<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

if (!$list) {
    return;
}

?>
<ul>
<?php foreach ($list as $item) : ?>
    <?php
      $date  = $item->publish_up ?: $item->created;
      $day   = HTMLHelper::_('date', $date, 'd');
      $month = HTMLHelper::_('date', $date, 'M'); // Aug
    ?>
    <li itemscope itemtype="https://schema.org/Article" class="news-item">
        <div class="news-date">
            <div class="day"><?=$day?></div>
            <div class="month"><?=$month?></div>
        </div>
        <a class="mod-articles-category-title " href="<?php echo $item->link; ?>">
        <div class="news-content">
            <h4><?php echo $item->title; ?></h4>
            <p class="mod-articles-category-readmore">READ MORE &gt;&gt;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        </div>
        </a>
    </li>
<?php endforeach; ?>
</ul>
