<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_page']['title'][0] = 'Título';
$GLOBALS['TL_LANG']['tl_survey_page']['title'][1] = 'Por favor ingrese el título de la página.';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][0] = 'Descripción';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][1] = 'Por favor ingrese la descripción de la página.';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][0] = 'último cambio';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][1] = 'Fecha y hora del último cambio.';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][0] = 'Introducción';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][1] = 'Por favor ingrese una introducción de página. La introducción se mostrará al principio de la página.';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][0] = 'Plantilla de página';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][1] = 'Aquí puede seleccionar la plantilla de página.';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][0] = 'Nueva pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][1] = 'Crear una nueva página';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][0] = 'Mostrar detalles';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][1] = 'Mostrar detalles de la página ID %s';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][0] = 'Mover página';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][1] = 'Mover ID de página %s';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][0] = 'Editar página';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][1] = 'Editar ID de página %s';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][0] = 'Página duplicada';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][1] = 'ID de página duplicada %s';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][0] = 'Eliminar página';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][1] = 'Eliminar ID de página %s';
$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Página';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Título y descripción';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Introducción';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Configuración de plantilla';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Navegación después de esta página';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][0] = 'Reglas de navegación';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][1] = 'Las reglas se evalúan al continuar después de esta página.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['help'] = 'Las reglas se verifican de arriba a abajo. La primera regla coincidente determina la página siguiente. Todas las condiciones dentro de una regla deben coincidir. Las respuestas faltantes nunca satisfacen una condición.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['fallback'] = 'Si ninguna regla coincide, sigue la siguiente página en secuencia. Una regla "Siempre" al final puede definir un destino predeterminado diferente.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchQuestions'] = 'Filtrar opciones de preguntas';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchHelp'] = 'Buscar por título de pregunta o ID';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['rule'] = 'regla';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['when'] = 'cuando';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['all'] = 'Todas las siguientes condiciones coinciden (Y)';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['always'] = 'siempre';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['question'] = 'Pregunta';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operator'] = 'Comparación';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['answer'] = 'respuesta';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['target'] = 'Luego continúa';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseQuestion'] = 'Por favor seleccione una pregunta';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseAnswer'] = 'Por favor seleccione una respuesta';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['choosePage'] = 'Por favor seleccione una página';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addRule'] = 'Agregar otra regla';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeRule'] = 'Eliminar regla';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addCondition'] = 'Añade otra condición AND';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeCondition'] = 'Eliminar condición';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['up'] = 'Mover hacia arriba';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['down'] = 'Mover hacia abajo';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['contains'] = 'contiene respuesta';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['notContains'] = 'no contiene respuesta';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['='] = 'es igual';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['!='] = 'no es igual';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>'] = 'es mayor que';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<'] = 'es menor que';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>='] = 'es mayor o igual a';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<='] = 'es menor o igual a';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['invalid'] = 'Verifique todas las reglas de navegación: pregunta, comparación, respuesta y destino deben ser válidos. El destino debe ser otra página de la misma encuesta. Se permiten como máximo 100 reglas con 100 condiciones cada una.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacy'] = 'Esta regla existente contiene una condición no disponible o actualmente no admitida. Se conserva sin cambios. Puede mover o eliminar la regla y reemplazarla con una regla nueva.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacyValue'] = 'Valor existente:';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['unreachable'] = 'Esta regla siempre coincide. Las reglas siguientes no serán alcanzadas.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['javascript'] = 'JavaScript debe estar habilitado para editar reglas. Las reglas existentes se conservan al guardar.';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][0] = 'Editar preguntas';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][1] = 'Editar preguntas (ID %s)';
