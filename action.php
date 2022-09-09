<?php
/**
 * DokuWiki Plugin hidemenus (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Daniel Weisshaar <daniwei-dev@gmx.de>
 */
class action_plugin_hidemenus extends \dokuwiki\Extension\ActionPlugin
{

    /** @inheritDoc */
    public function register(Doku_Event_Handler $controller)
    {
        // for information about sequence number see https://www.dokuwiki.org/devel:action_plugins#register_method
        $sequence_number = 3999; // react on event as late as possible
        $controller->register_hook('MENU_ITEMS_ASSEMBLY', 'AFTER', $this, 'handle_menu_items_assembly', array(), $sequence_number);
   
    }

    /**
     * Event handler for MENU_ITEMS_ASSEMBLY AFTER event
     *
     * Looks if the user is logged in, otherwise hides the page tools by clearing the item data array.
     * 
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  optional parameter passed when event was registered
     * @return void
     */
    public function handle_menu_items_assembly(Doku_Event $event, $param)
    {
        global $INFO;
        
        if(!empty($INFO['userinfo']))
        {
            return; // user is already logged in
        }
        
        // check the configuration if hiding is desired
        $allowPageToolsHiding = $this->getConf('hidePageTools');
        $allowSiteToolsHiding = $this->getConf('hideSiteTools');
        
        if(
            ($allowPageToolsHiding && $event->data['view'] === 'page')
            || ($allowSiteToolsHiding && $event->data['view'] === 'site')
        )
        {
            $event->data['items'] = []; // remove all existing item entries
        }
    }

}

