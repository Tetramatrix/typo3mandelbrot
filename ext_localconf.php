<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

  ## Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,'editorcfg','
	tt_content.CSS_editor.ch.tx_chmandelbrot_pi1 = < plugin.tx_chmandelbrot_pi1.CSS_editor
',43);


t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_chmandelbrot_pi1.php','_pi1','list_type',0);

$TYPO3_CONF_VARS['FE']['eID_include']['ch_mandelbrot'] = 'EXT:ch_mandelbrot/lib/eID.php';

    
?>