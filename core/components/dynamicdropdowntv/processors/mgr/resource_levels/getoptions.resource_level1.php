<?php
/**
 * DynamicDropdownTV
 *
 * Copyright 2012-2013 by Bruno Perner <b.perner@gmx.de>
 *
 * DynamicDropdownTV is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * DynamicDropdownTV is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * DynamicDropdownTV; if not, write to the Free Software Foundation, Inc., 59
 * Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package dynamicdropdowntv
 * @subpackage processor
 *
 * DynamicDropdownTV example resource level processor for resource_level2 tv
 */
$query = $modx->getOption('query', $scriptProperties, '');
$parent = $modx->getOption('resource_level0', $scriptProperties, '');

$tv = $modx->getObject('modTemplateVar', array('name' => $scriptProperties['tvname']));
$inputProperties = $tv->get('input_properties');

$modx->lexicon->load('tv_widget', 'dynamicdropdowntv:inputoptions');
$lang = $modx->lexicon->fetch('dynamicdropdowntv.', TRUE);

$firstText = $modx->getOption('firstText', $inputProperties, $lang['firstText_default'], TRUE);

$classname = 'modResource';

$c = $modx->newQuery($classname);

$options = array();
$count = 1;

if (!empty($query)) {
	$c->where(array('pagetitle:LIKE' => $query . '%'));
} else {
	$options[] = array('id' => '', 'name' => $firstText);
}

$c->where(array('parent' => $parent));

//$c->prepare();die($c->toSql());
if ($parent != '' && $collection = $modx->getCollection($classname, $c)) {
	$count += $modx->getCount($classname);
	foreach ($collection as $object) {
		$option['id'] = $object->get('id');
		$option['name'] = $object->get('pagetitle');
		$rows[strtolower($option['name'])] = $option;
	}
	ksort($rows);

	foreach ($rows as $option) {
		$options[] = $option;
	}
}

return $this->outputArray($options, $count);