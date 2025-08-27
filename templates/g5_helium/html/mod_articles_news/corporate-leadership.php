<?php

defined('_JEXEC') or die;

use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

/** @var array $list   */
?>
<h2 class="corporate-cat-title">Management</h2>
<div class="mod-articlesnews newsflash">
<?php foreach ($list as $item) : ?>
  <?php
    echo "<!-- intro_has_newsflash: " .
    (strpos($item->introtext ?? '', 'mod-articlesnews__item') !== false ? 'YES' : 'NO') .
    " -->";
  
    $role = '';
    try {
      $fields = FieldsHelper::getFields('com_content.article', $item, true);
      foreach ($fields as $f) {
        if ($f->name === 'role' && !empty($f->rawvalue)) {
          $role = $f->rawvalue;
          break;
        }
      }
    } catch (\Throwable $e) {}

    $intro = $item->introtext ?? '';
  ?>
  <div class="mod-articlesnews__item yuichi" itemscope itemtype="https://schema.org/Article">
    <div class="corporate-title">
      <h3><?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></h3>
      <?php if ($role) : ?>
        <h4><?php echo htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?></h4>
      <?php endif; ?>
    </div>
    <div class="corporate-content">
      <?php echo $intro; ?>
    </div>
  </div>
<?php endforeach; ?>
</div>
