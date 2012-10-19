<?php
/*
 * Joomla! component MageBridge
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Include the parent controller
jimport( 'joomla.application.component.controller' );

/**
 * MageBridge Controller
 */
class MageBridgeController extends JController
{
    /*
     * Variable containing the current message
     */
    protected $msg = null;

    /*
     * Variable containing the current model-name
     */
    protected $_model_name = null;

    /*
     * Variable containing the current item-type name
     */
    protected $_item_type = 'Item';

    /**
     * Constructor
     * @package MageBridge
     */
    public function __construct()
    {
        parent::__construct();

        // Register extra tasks
        $this->registerTask('login', 'ssoCheck');
        $this->registerTask('logout', 'ssoCheck');
        $this->registerTask('save', 'store');
        $this->registerTask('apply', 'store');

        $request = JRequest::getVar('request');
        if (JRequest::getCmd('view') == 'root' && !empty($request)) {
            JRequest::setVar('format', 'raw');
        }
    }

    /*
     * Method to display the views layout
     *
     * @param null
     * @return null
     */
    public function display($cachable = false, $urlparams = false)
    {
        // If the caching view is called, perform the cache-task instead
        if (JRequest::getCmd('view') == 'cache') {
            return $this->cache();    
        }

        // Redirect to the Magento Admin Panel
        if (JRequest::getCmd('view') == 'magento') {
            $link = MagebridgeModelConfig::load('url').'index.php/'.MagebridgeModelConfig::load('backend');
            $this->setRedirect($link);
            return true;
        }

        // Redirect to the Yireo Forum
        if (JRequest::getCmd('view') == 'forum') {
            $this->setRedirect('http://www.yireo.com/forum/');
            return true;
        }

        // Redirect to the Yireo Tutorials
        if (JRequest::getCmd('view') == 'tutorials') {
            $this->setRedirect('http://www.yireo.com/tutorials/magebridge/');
            return true;
        }

        parent::display();
    }

    /*
     * Method to flush caching
     *
     * @param null
     * @return null
     */
    public function cache()
    {
        // Validate whether this task is allowed
        if ($this->_validate(false) == false) return false;

        // Clean the backend cache 
        $cache = JFactory::getCache('com_magebridge_admin');
        $cache->clean();
        
        // Clean the frontend cache 
        $cache = JFactory::getCache('com_magebridge');
        $cache->clean();

        // Build the next URL
        $view = JRequest::getCmd('view');
        if ($view == 'cache') $view = 'home';
        $link = 'index.php?option=com_magebridge&view='.$view;

        // Redirect
        $msg = 'Cache cleaned';
        $this->setRedirect($link, $msg);
        return true;
    }

    /*
     * Method to save the MageBridge configuration to the database
     *
     * @param null
     * @return null
     */
    public function store($post = null)
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Get the data from post
        if (empty($post)) {
            $post = JRequest::get('post');
        }

        // Load the model
        $model = $this->_loadModel();
        if (!empty($model)) {

            // Set the ID
            $cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
            if (!empty($cid[0])) $post['id'] = (int)$cid[0];

            // Try to save the post-data
            if ($model->store($post)) {
                $msg = JText::_('Saved data');
                $type = 'message';

            } else {
                $msg = JText::_('Error when saving model').': '.$model->getError();
                $type = 'error';
            }

        // If the model could not be loaded, this has something to do with an unidentified view
        } else {
            $msg = JText::_('Unknown view') . ': ' . JRequest::getCmd('view');
            $type = 'error';
        }

        // Set the message for the next page
        $application = JFactory::getApplication();
        $application->enqueueMessage( $msg, $type );

        // Use the task to determine the next page
        if (JRequest::getCmd('task') == 'apply') {

            if (JRequest::getCmd('view') == 'config') {
                $link = 'index.php?option=com_magebridge&view=config';
            } else {
                $next_view = $this->_getViewName(true);
                $link = 'index.php?option=com_magebridge&view='.$next_view.'&task=edit&cid[]='.(int)$model->getId();
            }

        } else { 
            $next_view = $this->_getViewName(false);
            $link = 'index.php?option=com_magebridge&view='.$next_view;
        }

        // Redirect
        $this->setRedirect($link);
    }

    /*
     * Method to add an item
     *
     * @param null
     * @return null
     */
    public function add()
    {
        // Set the form and display it
        $rt = $this->_setForm();
        if ($rt) {
            JRequest::setVar( 'edit', false );
            parent::display();
        }

        return $rt;
    }

    /*
     * Method to edit an item
     *
     * @param null
     * @return null
     */
    public function edit()
    {
        // Set the form and display it
        $rt = $this->_setForm() ;
        if ($rt) {
            JRequest::setVar( 'edit', true );
            $model = $this->_loadModel();
            $model->checkout();
            parent::display();
        }

        return $rt;
    }

    /*
     * Method to remove an item
     *
     * @param null
     * @return null
     */
    public function remove()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Get a list of IDs
        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($cid);

        // No selection made
        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_( 'Select an item to delete' ) );
        }

        // Load the model and delete the items
        $model = $this->_loadModel();
        if (!$model->delete($cid)) {
            $this->msg = $model->getError() ;
        } else {
            $this->msg = JText::sprintf( $this->_item_type . ' Removed', count($cid) );
        }

        // Redirect
        return $this->_setOverviewRedirect();
    }

    /*
     * Method to toggle the configuration mode (advanced/basic)
     *
     * @param null
     * @return null
     */
    public function toggleMode()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Determine the toggle value
        $name = 'advanced';
        $value = MagebridgeModelConfig::load($name);
        if ($value == 1) {
            $value = 0;
        } else {
            $value = 1;
        }
        MagebridgeModelConfig::saveValue($name, $value);
        
        $link = 'index.php?option=com_magebridge&view=config';
        $this->setRedirect($link);
    }

    /*
     * Method to cancel a certain editing action
     *
     * @param null
     * @return null
     */
    public function cancel()
    {
        // Redirect
        $view = $this->_getViewName(false);
        $link = 'index.php?option=com_magebridge&view='.$view;
        $this->setRedirect($link);
    }

    /*
     * Method to publish an item
     *
     * @param null
     * @return null
     */
    public function publish()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Get the IDs
        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($cid);

        // No selection made
        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_( 'Select an item to publish' ) );
        }

        // Load the model and publish the items
        $model = $this->_loadModel();
        if (!$model->publish($cid, 1)) {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        } else {
            $this->msg = JText::sprintf( $this->_item_type . ' Published', count($cid) );
        }

        // Redirect
        $this->_setOverviewRedirect();
    }

    /*
     * Method to unpublish an item 
     *
     * @param null
     * @return null
     */
    public function unpublish()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Get the IDs
        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($cid);

        // No selection made
        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
        }

        // Load the model and unpublish the items
        $model = $this->_loadModel();
        if (!$model->publish($cid, 0)) {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        } else {
            $this->msg = JText::sprintf( $this->_item_type . ' Unpublished', count($cid) );
        }

        // Redirect
        $this->_setOverviewRedirect();
    }

    /*
     * Method to change the ordering of items
     *
     * @param null
     * @return null
     */
    public function orderup()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Load the model and alter ordering
        $model = $this->_loadModel();
        $model->move(-1);

        // Redirect
        $this->_setOverviewRedirect();
    }

    /*
     * Method to change the ordering of items
     *
     * @param null
     * @return null
     */
    public function orderdown()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Load the model and alter ordering
        $model = $this->_loadModel();
        $model->move(1);

        // Redirect
        $this->_setOverviewRedirect();
    }

    /*
     * Method 
     *
     * @param null
     * @return null
     */
    public function saveorder()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Get the IDs
        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($cid);

        // Get the ordering
        $order = JRequest::getVar( 'order', array(), 'post', 'array' );
        JArrayHelper::toInteger($order);

        // Load the model and alter ordering
        $model = $this->_loadModel();
        $model->saveorder($cid, $order);

        // Redirect
        $this->_setOverviewRedirect();
    }

    /*
     * Method to upgrade specific extensions
     *
     * @param null
     * @return null
     */
    public function update()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Get the selected packages
        $packages = JRequest::getVar('packages');

        // Get the model and update the packages
        $model = $this->getModel('update');
        $model->updateAll($packages);

        // Clean the cache
        $cache = JFactory::getCache('com_magebridge_admin');
        $cache->clean();

        // Redirect
        $link = 'index.php?option=com_magebridge&view=update';
        $this->setRedirect($link);
    }

    /*
     * Method  to truncate the logs
     *
     * @param null
     * @return null
     */
    public function delete()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Only clean items for the right view
        if (JRequest::getCmd('view') == 'logs') {

            // Clean up the database
            $db = JFactory::getDBO();
            $db->setQuery('DELETE FROM #__magebridge_log WHERE 1 = 1');
            $db->query();

            // Clean up the database
            $conf = JFactory::getConfig();
            $file = $conf->getValue('config.log_path').'/magebridge.txt';
            file_put_contents($file, null);

            // Redirect
            $msg = 'Deleted all log entries';
            $link = 'index.php?option=com_magebridge&view=logs';
            $this->setRedirect($link, $msg);
            return;
        }
            
        // Otherwise display by default
        $this->display();
    }

    /*
     * Method 
     *
     * @param null
     * @return null
     */
    public function export()
    {
        // Validate whether this task is allowed
        if ($this->_validate() == false) return false;

        // Only clean items for the right view
        if (JRequest::getCmd('view') == 'logs') {
            $link = 'index.php?option=com_magebridge&view=logs&format=csv';
            $this->setRedirect($link);
            return;
        }

        // Otherwise display by default
        $this->display();
    }

    /*
     * Method to check SSO coming from Magento
     *
     * @param null
     * @return null
     */
    public function ssoCheck()
    {
        $application = JFactory::getApplication();
        $user = JFactory::getUser();
        if (!$user->guest) {
            MageBridgeModelUserSSO::checkSSOLogin();
            $application->close();
        } else {
            $this->setRedirect(JURI::base());
        }
    }

    /*
     * Method to validate a change-request
     *
     * @param boolean $check_token
     * @param boolean $check_demo
     * @return boolean
     */
    protected function _validate($check_token = true, $check_demo = true)
    {
        // Check the token
        if ($check_token == true && (JRequest::checkToken('post') == false && JRequest::checkToken('get') == false)) {
            $msg = 'Invalid token';
            $link = 'index.php?option=com_magebridge&view=home';
            $this->setRedirect( $link, $msg );
            return false;
        }

        // Check demo-access
        if ($check_demo == true && MageBridgeAclHelper::isDemo() == true) {
            $msg = 'No changes made. You are only allowed read-only access to this demo-site.';
            $link = 'index.php?option=com_magebridge&view=home';
            $this->setRedirect( $link, $msg );
            return false;
        }

        return true;
    }

    /*
     * Method to redirect to the overview-page
     *
     * @param null
     * @return null
     */
    protected function _setOverviewRedirect() 
    {
        // Get the current view
        $view = JRequest::getCmd('view');

        // Determine the overview page 
        if (!empty($view)) {
            $view = preg_replace('/s$/', '', $view).'s';
        } else {
            $view = 'items';
        }

        // Redirect
        $msg = (!empty($this->msg)) ? $this->msg : null;
        $link = 'index.php?option=com_magebridge&view='.$view ;
        $this->setRedirect( $link, $msg );
    }

    /*
     * Method 
     *
     * @param null
     * @return null
     */
    protected function _setForm() 
    {
        // Get the variables
        $view = JRequest::getCmd('view');
        $task = JRequest::getCmd('task');
        $id = JRequest::getInt('id');
        $cid = JRequest::getVar('cid');

        // Determine the next view
        if (!empty($view)) {
            $single_view = preg_replace('/s$/', '', $view);
        } else {
            $single_view = 'item';
        }
        JRequest::setVar('view', $single_view);

        // Hide the menu while editing or adding an item
        JRequest::setVar( 'hidemainmenu', 1 );

        // Set the layout template to 'form'
        JRequest::setVar( 'layout', 'form' );

        // If the request doesn't match the current URL, redirect
        if ( $single_view != $view ) {

            $link = 'index.php?option=com_magebridge&view='.$single_view ;
            if (!empty($task)) $link .= '&task='.$task;
            if (!empty($id)) $link .= '&id='.$id;
            if (!empty($cid)) $link .= '&cid[]='.$cid[0];
            $this->setRedirect( $link, $this->msg);
            return false;

        }

        return true;
    }

    /*
     * Method 
     *
     * @param null
     * @return null
     */
    protected function _loadModel()
    {
        return $this->getModel($this->_getViewName(true));
    }

    /*
     * Method 
     *
     * @param null
     * @return null
     */
    protected function _getViewName($single = true)
    {
        // Determine the view-name
        $view = JRequest::getVar('view');
        if (!empty($view)) {
            $single_view = preg_replace('/s$/', '', $view);
            if ($single) {
                $view = $single_view;
            } else {
                $view = $single_view.'s';
            }
        }

        if ($view == 'configs') $view = 'home';
        if ($view == 'homes') $view = 'home';

        $this->_item_type = JText::_(ucfirst($single_view));

        return $view;
    }
}
