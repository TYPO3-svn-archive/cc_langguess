<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addService($_EXTKEY, 'textLang' /* sv type */, 'tx_cclangguess_sv1' /* sv key */,
		array(

			'title' => 'Lang guess',
			'description' => 'This is a language guesser implemented in Perl.',

			'subtype' => '',

			'available' => TRUE,
			'priority' => 50,
			'quality' => 69,

			'os' => '',
			'exec' => 'perl',

			'classFile' => t3lib_extMgm::extPath($_EXTKEY).'sv1/class.tx_cclangguess_sv1.php',
			'className' => 'tx_cclangguess_sv1',
		)
	);

?>
