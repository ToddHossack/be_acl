<?php
namespace Tx\BeAcl\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * This class extends the permissions module in the TYPO3 Backend to provide
 * convenient methods of editing of page permissions (including page ownership
 * (user and group)) via new AjaxRequestHandler facility
 */
class PermissionAjaxController extends \TYPO3\CMS\Beuser\Controller\PermissionAjaxController
{
    /**
     * View object
     * @var view \TYPO3\CMS\Fluid\View\StandaloneView
     */
    protected $view;
    
    /**
     * Extension path
     * @var string
     */
    protected $extPath;
    
    /**
     * ACL table
     * @var string 
     */
    protected $table = 'tx_beacl_acl';
    
    /**
     * Set the extension path
     * @param string $extPath
     */
    protected function setExtPath($extPath = null) {
        $this->extPath = empty($extPath) ? ExtensionManagementUtility::extPath('be_acl') : $extPath;
    }
    
    /**
     * Initialize the viewz
     */
    protected function initializeView() {
        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->setPartialRootPaths(array('default' => $this->extPath . 'Resources/Private/Partials'));
        $this->view->assign('pageId', $this->conf['page']);
    }
    
    /**
     * The main dispatcher function. Collect data and prepare HTML output.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request, ResponseInterface $response)
    {
        // Actions handled by this class
        $handledActions = ['delete_acl'];
        
        // Handle action
        $action = $this->conf['action'];
        if ($this->conf['page'] > 0 && in_array($action,$handledActions)) {
           return $this->handleAction($request,$response,$action);
        }
        // Action handled by parent
        else {
            return parent::dispatch($request,$response);
        }
    }

    protected function handleAction(ServerRequestInterface $request, ResponseInterface $response, $action) {
        $methodName = GeneralUtility::underscoredToLowerCamelCase($action);
        if (method_exists($this, $methodName)) {
            return call_user_func_array(array($this, $methodName), [$request,$response]);
         }
         else {
            $response->getBody()->write('Action method not found');
            $response = $response->withStatus(400);
            return $response;
         }
    }
    
    protected function deleteAcl(ServerRequestInterface $request, ResponseInterface $response) {
        $postData = $request->getParsedBody();
        $aclUid = !empty($postData['acl']) ? $postData['acl'] : NULL;
        
        if(!is_numeric($aclUid)) {
            return $this->errorResponse($response,'No ACL ID provided',400);
        }
        $aclUid = (int) $aclUid;
        // Prepare command map
        $cmdMap = [
            $this->table => [
                    $aclUid => ['delete' => 1]
                ]
        ];
        // Process command map
        $tce = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\DataHandling\\DataHandler');
        $tce->stripslashes_values = 0;
        $tce->start(array(), $cmdMap);
        $tce->process_cmdmap();
        
        // Return result
        $response->getBody()->write(true);
        return $response;
        
    }
    
    protected function errorResponse(ResponseInterface $response,$reason,$status=500) {
        $response = $response->withStatus($status,$reason);
        return $response;
    }
    
   
}
