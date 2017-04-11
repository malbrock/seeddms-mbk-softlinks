<?php
$EXT_CONF['mbk-softlinks'] = array(
	'title' => 'Malbrock SeedDMS Softlinks',
	'description' => 'This extension allows to have linked folders and documents in the Folder View. It looks for isLink and linkedId attributes in folders and documents.',
	'disable' => false,
	'version' => '1.0.0',
	'releasedate' => '2017-04-09',
	'author' => array('name'=>'Sergio Maldonado', 'email'=>'info@malbrock.com', 'company'=>'Malbrock Web'),
	'config' => array(),
	'constraints' => array(
		'depends' => array('php' => '5.4.4-', 'seeddms' => '4.3.0-'),
	),
	'icon' => 'icon.png',
	'class' => array(
		'file' => 'class.mbk-softlinks.php',
		'name' => 'SeedDMS_ExtMbkSoftlinks'
	),
	'language' => array(
		'file' => 'lang.php',
	),
);
?>
