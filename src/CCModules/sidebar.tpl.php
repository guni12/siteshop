<div class='box'>
    <h4><?= t('All modules') ?></h4>
    <p><?= t('All Siteshop modules.') ?></p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <li><a href='<?= create_url("modules/view/{$module['name']}") ?>'><?= $module['name'] ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>


<div class='box'>
    <h4><?= t('Siteshop core') ?></h4>
    <p><?= t('Siteshop core modules.') ?></p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isSiteshopCore']): ?>
                <li><a href='<?= create_url("modules/view/{$module['name']}") ?>'><?= $module['name'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>


<div class='box'>
    <h4>Siteshop CMF</h4>
    <p><?= t('Siteshop Content Management Framework (CMF) modules.') ?></p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isSiteshopCMF']): ?>
                <li><a href='<?= create_url("modules/view/{$module['name']}") ?>'><?= $module['name'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>


<div class='box'>
    <h4><?= t('Models') ?></h4>
    <p><?= t('A class is considered a model if its name starts with CM.') ?></p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isModel']): ?>
                <li><a href='<?= create_url("modules/view/{$module['name']}") ?>'><?= $module['name'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>


<div class='box'>
    <h4><?= t('Controllers') ?></h4>
    <p><?= t('Implements interface') ?> <code>IController</code>.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isController']): ?>
                <li><a href='<?= create_url("modules/view/{$module['name']}") ?>'><?= $module['name'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<div class='box'>
    <h4><?= t('Manageable module') ?></h4>
    <p><?= t('Implements interface') ?> <code>IModule</code>.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isManageable']): ?>
                <li><a href='<?= create_url("modules/view/{$module['name']}") ?>'><?= $module['name'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>


<div class='box'>
    <h4><?= t('Contains SQL') ?></h4>
    <p><?= t('Implements interface') ?> <code>IHasSQL</code>.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['hasSQL']): ?>
                <li><a href='<?= create_url("modules/view/{$module['name']}") ?>'><?= $module['name'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>


<div class='box'>
    <h4><?= t('More modules') ?></h4>
    <p><?= t('Modules that does not implement any specific Siteshop interface.') ?></p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if (!($module['isController'] || $module['isSiteshopCore'] || $module['isSiteshopCMF'])): ?>
                <li><a href='<?= create_url("modules/view/{$module['name']}") ?>'><?= $module['name'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
