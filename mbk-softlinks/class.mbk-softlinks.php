<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2017 Malbrock Web <info@malbrock.com>
*  All rights reserved
*
*  This script is part of the SeedDMS project. The SeedDMS project is
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
 * Softlink Extension
 *
 * @author  Sergio Maldonado <smaldona@malbrock.com>
 * @package SeedDMS
 * @subpackage  mbk-softlinks
 */
class SeedDMS_ExtMbkSoftlinks extends SeedDMS_ExtBase {

	/**
	 * Initialization
	 *
	 * Use this method to do some initialization like setting up the hooks
	 * You have access to the following global variables:
	 * $GLOBALS['dms'] : object representing dms
	 * $GLOBALS['user'] : currently logged in user
	 * $GLOBALS['session'] : current session
	 * $GLOBALS['settings'] : current global configuration
	 * $GLOBALS['settings']['_extensions']['example'] : configuration of this extension
	 * $GLOBALS['LANG'] : the language array with translations for all languages
	 * $GLOBALS['SEEDDMS_HOOKS'] : all hooks added so far
	 */
	function init() { 
		$GLOBALS['SEEDDMS_HOOKS']['view']['viewFolder'][] = new SeedDMS_ExtExample_ViewFolder;
	} 

	/**
	 * Main Function
	 */
	function main() { 
	} 
}


/**
 * Class containing methods for hooks when a folder view is ѕhown
 *
 * @author  Sergio Maldonado <smaldona@malbrock.com>
 * @package SeedDMS
 * @subpackage  mbk-softlinks
 */
class SeedDMS_ExtExample_ViewFolder {

	/**
	 * Hook when showing a Folder element
	 *
	 * Check weather "isLink" attribute is true and link to "linkedId" folder. 
	 */
	function folderListItem($view, $subfolder) {
		$dms = $GLOBALS['dms'];
		$user = $GLOBALS['user'];
		$showtree = 1;
		$imgpath = "../ext/mbk-softlinks/images/";
		$attributes = $subfolder->getAttributes();
		
		$isLink = false;
		$linkedId = 0;
		foreach($attributes as $attrdefid=>$attribute) {
			$attrdef = $dms->getAttributeDefinition($attrdefid);
			$attrName = $attrdef->getName();
			if (($attrName == "isLink") && $subfolder->getAttributeValue($attrdef)) {
				$isLink = true;
			}
			elseif ($attrdef->getName() == "linkedId") {
				$linkedId = $subfolder->getAttributeValue($attrdef);
			}
		}
		if ($isLink && $linkedId>0) {
			$owner = $subfolder->getOwner();
			$comment = $subfolder->getComment();
			if (strlen($comment) > 150) $comment = substr($comment, 0, 147) . "...";
			$subsub = $subfolder->getSubFolders();
			$subsub = SeedDMS_Core_DMS::filterAccess($subsub, $user, M_READ);
			$subdoc = $subfolder->getDocuments();
			$subdoc = SeedDMS_Core_DMS::filterAccess($subdoc, $user, M_READ);

			$content = '';
			$content .= "<tr id=\"table-row-folder-".$subfolder->getID()."\" draggable=\"true\" rel=\"folder_".$subfolder->getID()."\" class=\"folder table-row-folder\" formtoken=\"".createFormKey('movefolder')."\">";
			$content .= "<td><a _rel=\"folder_".$subfolder->getID()."\" draggable=\"false\" href=\"out.ViewFolder.php?folderid=".$linkedId."&showtree=".$showtree."\"><img draggable=\"false\" src=\"".$imgpath."folder_linked.png\" width=\"24\" height=\"24\" border=0></a></td>\n";
			$content .= "<td><a draggable=\"false\" _rel=\"folder_".$subfolder->getID()."\" href=\"out.ViewFolder.php?folderid=".$linkedId."&showtree=".$showtree."\">" . htmlspecialchars($subfolder->getName()) . "</a>";
			$content .= "<br /><span style=\"font-size: 85%; font-style: italic; color: #666;\">".getMLText('owner').": <b>".htmlspecialchars($owner->getFullName())."</b>, ".getMLText('creation_date').": <b>".date('Y-m-d', $subfolder->getDate())."</b></span>";
			if($comment) {
				$content .= "<br /><span style=\"font-size: 85%;\">".htmlspecialchars($comment)."</span>";
			}
			$content .= "</td>\n";
			$content .= "<td colspan=\"1\" nowrap><small>";
	
			$content .= $GLOBALS['LANG'][$user->getLanguage()]['linked_folder'];

			$content .= "</small></td>";
			$content .= "<td>";
			$content .= "<div class=\"list-action\">";

			if($subfolder->getAccessMode($user) >= M_READWRITE) {
				$content .= '<a class_="btn btn-mini" href="../out/out.EditFolder.php?folderid='.$subfolder->getID().'" title="'.getMLText("edit_folder_props").'"><i class="icon-edit"></i></a>';
			} else {
				$content .= '<span style="padding: 2px; color: #CCC;"><i class="icon-edit"></i></span>';
			}
			$content .= "</div>";
			$content .= "</td>";
			$content .= "</tr>\n";
			return $content;
		}
	 }


	 /**
	 * Hook when showing a Document element
	 *
	 * Check weather "isLink" attribute is true and link to "linkedId" document. 
	 */
	 function documentListItem ($view, $document) {
		$dms = $GLOBALS['dms'];
		$user = $GLOBALS['user'];
		$showtree = 1;
		$imgpath = "../ext/mbk-softlinks/images/";
		$attributes = $document->getAttributes();

		$isLink = false;
		$linkedId = 0;

		foreach($attributes as $attrdefid=>$attribute) {
			$attrdef = $dms->getAttributeDefinition($attrdefid);
			$attrName = $attrdef->getName();
			if (($attrName == "isLink") && $document->getAttributeValue($attrdef)) {
				$isLink = true;
			}
			elseif ($attrdef->getName() == "linkedId") {
				$linkedId = $document->getAttributeValue($attrdef);
			}
		}
		if ($isLink && $linkedId>0) {
			
			$content = '';

			$owner = $document->getOwner();
			$comment = $document->getComment();
			if (strlen($comment) > 150) $comment = substr($comment, 0, 147) . "...";
			$docID = $document->getID();

			$latestContent = $document->getLatestContent();

			if($latestContent) {
				$version = $latestContent->getVersion();
				$status = $latestContent->getStatus();
				$needwkflaction = false;
				
				/* Retrieve attacheѕ files */
				$files = $document->getDocumentFiles();

				/* Retrieve linked documents */
				$links = $document->getDocumentLinks();
				$links = SeedDMS_Core_DMS::filterDocumentLinks($user, $links);

				$content .= "<td>";
				$content .= "<img draggable=\"false\" class=\"mimeicon\" src=\"".$imgpath."document_link.png\" title=\"Carpeta Enlazada\">";
				$content .= "</td>";

				$content .= "<td>";	
				$content .= "<a draggable=\"false\" href=\"out.ViewDocument.php?documentid=".$linkedId."&showtree=".$showtree."\">" . htmlspecialchars($document->getName()) . "</a>";
				$content .= "<br /><span style=\"font-size: 85%; font-style: italic; color: #666; \">".getMLText('owner').": <b>".htmlspecialchars($owner->getFullName())."</b>, ".getMLText('creation_date').": <b>".date('Y-m-d', $document->getDate())."</b>, ".getMLText('version')." <b>".$version."</b> - <b>".date('Y-m-d', $latestContent->getDate())."</b></span>";
				if($comment) {
					$content .= "<br /><span style=\"font-size: 85%;\">".htmlspecialchars($comment)."</span>";
				}
				$content .= "</td>\n";

				$content .= "<td nowrap>";
				$attentionstr = '';
				if ( $document->isLocked() ) {
					$attentionstr .= "<img src=\"".$this->getImgPath("lock.png")."\" title=\"". getMLText("locked_by").": ".htmlspecialchars($document->getLockingUser()->getFullName())."\"> ";
				}
				if ( $needwkflaction ) {
					$attentionstr .= "<img src=\"".$this->getImgPath("attention.gif")."\" title=\"". getMLText("workflow").": "."\"> ";
				}
				if($attentionstr)
					$content .= $attentionstr."<br />";
				$content .= "<small>";
				if(count($files))
					$content .= count($files)." ".getMLText("linked_files")."<br />";
				if(count($links))
					$content .= count($links)." ".getMLText("linked_documents")."<br />";
				$content .= getOverallStatusText($status["status"])."</small>";
				$content .= "</td>\n";

				$content .= "<td>";
				$content .= "<div class=\"list-action\">";
				if($document->getAccessMode($user) >= M_READWRITE) {
					$content .= '<a href="../out/out.EditDocument.php?documentid='.$docID.'" title="'.getMLText("edit_document_props").'"><i class="icon-edit"></i></a>';
				} else {
					$content .= '<span style="padding: 2px; color: #CCC;"><i class="icon-edit"></i></span>';
				}
				$content .= "</div>";
				$content .= "</td>";
			}
			return $content;
		}

	 }

}

?>

