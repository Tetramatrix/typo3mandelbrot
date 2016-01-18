<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 Chi Hoang <chibox@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'Apfelmaennchen' for the 'ch_mandelbrot' extension.
 *
 * @author	Chi Hoang <chibox@gmail.com>
 */

require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_chmandelbrot_pi1 extends tslib_pibase {
	var $prefixId = 'tx_chmandelbrot_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_chmandelbrot_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'ch_mandelbrot';	// The extension key.
	var $pi_checkCHash = TRUE;

	
    function uploadPath() {
        return $this->confArray['uploadPath'].'/';    
    }
    
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();        

		# Conf
		$this->confArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
        
		$this->pi_initPIflexForm();			// Init and get the flexform data of the plugin

		# Assign the flexform data to a local variable for easier access
		$piFlexForm = $this->cObj->data['pi_flexform'];
        
		foreach ($piFlexForm['data'] as $sheet => $data)
			foreach ($data as $lang => $value)
				foreach ($value as $key => $val)
					$this->lConf[$lang][$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet, $lang);
                        //print_r($this->lConf);
        
            // Assign right language
        $lang = array_keys($this->lConf);
        $this->lConf =  $this->lConf[$lang[$GLOBALS['TSFE']->sys_language_uid]];       
           
            # Get the template
 		$this->templateCode = $this->cObj->fileResource($this->uploadPath().$this->lConf['template_file']);
        
        switch($this->piVars['cmd']) {    
			case 'render':
               
       
			break;

			default:
                $content = $this->form();                
			break;       
        }
        
		return $this->pi_wrapInBaseClass($content);
	}
    
    function form() {
        $template['form'] = $this->cObj->getSubpart($this->templateCode,'###FORMTEMPL###');
        
        $markerArray['###TYPO3SITEURL###'] = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
        $markerArray['###SNDREQ###'] = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->pi_linkTP_keepPIvars_url(array (),0);
        $markerArray['###FORM###'] = $this->pi_linkTP_keepPIvars_url(array (),0);
        $markerArray['###STARTRE###'] = '-2.5';
        $markerArray['###STARTIM###'] = '-1.5';
        $markerArray['###ENDERE###'] = '1.5';
        $markerArray['###ENDEIM###'] = '1.5';
        $markerArray['###STEPSRE###'] = '0';
        $markerArray['###STEPSIM###'] = '0';
        
        $content .= $this->cObj->substituteMarkerArrayCached($template['form'],$markerArray);
		return $content;   
    }
    
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_mandelbrot/pi1/class.tx_chmandelbrot_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_mandelbrot/pi1/class.tx_chmandelbrot_pi1.php']);
}

?>