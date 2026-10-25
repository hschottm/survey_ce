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
    // Token generation is irrelevant to this URL regression and requires an HTTP session.
    $GLOBALS['TL_DCA'][$table]['list']['operations']['edit']['prefetch'] = true;
    $dc = (new ReflectionClass(Contao\DC_Table::class))->newInstanceWithoutConstructor();
    $dc->id = $id;
    $builder = $c->get('contao.data_container.operations_builder')->initializeWithHeaderButtons($table, ['id' => $id], $dc);
    $operations = (new ReflectionProperty(Contao\CoreBundle\DataContainer\AbstractDataContainerOperationsBuilder::class, 'operations'))->getValue($builder);
    if (count($operations) !== 1) throw new RuntimeException('Unexpected header actions');
    parse_str(parse_url(html_entity_decode($operations[0]['href']), PHP_URL_QUERY) ?? '', $params);
    if (($params['act'] ?? null) !== 'edit' || ($params['table'] ?? null) !== $table || (int) ($params['id'] ?? 0) !== $id) {
        throw new RuntimeException('Incorrect edit URL');
    }
    if (!str_contains((string) $operations[0]['attributes'], 'openModalIframe')) throw new RuntimeException('Modal behavior lost');
    echo 'PASS: real Contao header builder generates act=edit for '.$table.' and the correct record ID.'.PHP_EOL;
}
