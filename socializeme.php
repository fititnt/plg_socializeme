<?php
/**
 * Emerson Rocha Luiz { emerson@webdesign.eng.br - http://fititnt.org }
 * Copyright (C) 2005 - 2011 Webdesign Assessoria em Tecnologia da Informacao.
 * GPL3
 */
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Socialize-me Content Plugin
 *
 * @since		1.6
 */
class plgContentSocializeme extends JPlugin
{
	
    
	/**
	 * Example before display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param	string		The context for the content passed to the plugin.
	 * @param	object		The content object.  Note $article->text is also available
	 * @param	object		The content params
	 * @param	int		The 'page' number
	 * @return	string
	 * @since	1.6
	 */
	public function onContentBeforeDisplay($context, &$article, &$params, $limitstart)
	{	
            
            //$article->text .= 'TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!';
            //$article->introtext .= 'TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!TO VIVO!!!!';
            //print_r($article);die();
            
            $article->text .= $this->_getSocializeme();
            
            return;
            
        }
        
        /* 
         * Function to build the Socialize-me html output
         * @return      String      HTML result for add to article
         */
        protected function _getSocializeme(){
            $socializeme = '';
            
            $socializeme .= '<div style="clear:both;">';
            $socializeme .= 'Socialize';
            
            if( $this->params->get('showfacebooklike',1) ){
                $socializeme .= $this->_getFacebookLike();
            }
            if( $this->params->get('showfacebooklike',1) ){
                $socializeme .= $this->_getGooglePlus();;
            }
            if( $this->params->get('showfacebooklike',1) ){
                $socializeme .= $this->_getTwitterTwitterButton();
            }
            $socializeme .= '</div>';
            
            return $socializeme;
            
        }
        
        /* 
         * Function to build the Facebook Like html output
         * @return      String      HTML result for add to article
         */
        protected function _getFacebookLike(){
            $facebookLike = '<div style="margin-left: 10px; width: 47px; height :60px; float: left;"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&amp;href=http%3A%2F%2Fwww.fititnt.org%2Flar-doce-lar%2Fparada-tecnica.html&amp;layout=box_count&amp;show_faces=true&amp;action=like&amp;colorscheme=light" style="border:none; overflow:hidden; width: 47px; height :60px;"></iframe></div>';
           
            return $facebookLike;
            
        }
        
        /* 
         * Function to build the GooglePlus html output
         * @return      String      HTML result for add to article
         */
        protected function _getGooglePlus(){
            $googleplus = '<div style="width: 60px !important; float: left; margin-left: 10px; border: none;"><script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><div class="g-plusone" data-size="tall" data-count="true"></div></div>';
            
            return $googleplus;
            
        }
        
        /* 
         * Function to build the TwitterButton html output
         * @return      String      HTML result for add to article
         */
        protected function _getTwitterTwitterButton(){
            $twitterbutton = '<div style="width: 55px !important; float: left;"><a href="http://twitter.com/share" class="twitter-share-button" style="width: 55px;" data-url="http://www.fititnt.org/lar-doce-lar/parada-tecnica.html" data-count="vertical" data-via="fititnt">Twitter</a><script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script></div>';
            
            return $twitterbutton;
            
        }
}
