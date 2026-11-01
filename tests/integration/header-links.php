<?php
require 'vendor/autoload.php';
$kernel = Contao\ManagerBundle\HttpKernel\ContaoKernel::fromInput(getcwd(), new Symfony\Component\Console\Input\ArrayInput(['--env' => 'prod']));
$kernel->boot();
$c = $kernel->getContainer();
$request = Symfony\Component\HttpFoundation\Request::create('http://localhost/contao?do=survey&table=tl_survey_question&id=66');
$request->attributes->set('_contao_referer_id', 'test');
$c->get('request_stack')->push($request);
$c->get('contao.framework')->initialize();
foreach (['tl_survey' => 3, 'tl_survey_page' => 66] as $table => $id) {
    Contao\Controller::loadDataContainer($table);
    Contao\System::loadLanguageFile($table);
    // Contao 5.3 renders its parent edit link directly in DC_Table::parentView().
    // Additional header operations must not duplicate that built-in action.
    $dc = (new ReflectionClass(Contao\DC_Table::class))->newInstanceWithoutConstructor();
    $dc->id = $id;
    $buttons = (new ReflectionMethod(Contao\DataContainer::class, 'generateHeaderButtons'))->invoke($dc, ['id' => $id], $table);
    if ($buttons !== '') throw new RuntimeException('Duplicate header actions');
    $edit = $GLOBALS['TL_DCA'][$table]['list']['operations']['edit'];
    if ($edit['href'] !== 'act=edit') throw new RuntimeException('Incorrect edit operation');
    echo 'PASS: no duplicate Contao 5.3 header actions for '.$table.'.'.PHP_EOL;
}
