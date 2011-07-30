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
            $document =& JFactory::getDocument();
            
            //Get 'Global' params
            $u =&JURI::getInstance();
            $url = $u->toString();
            $buttonsstyle = new stdClass();
            $buttonsstyle->size = $this->params->get('showbuttonsbig', 1);//1 big, 0 small
            $buttonsstyle->alignment = $this->params->get('showbuttonsleft', 1);//1 left, 0 rigth
            $buttonsstyle->showbuttonshorizoltal = $this->params->get('showbuttonshorizoltal', 1);//1 horizontal, 0 vertical
            $buttonsstyle->showbuttonscount = $this->params->get('showbuttonscount', 1);//1 show, 0 do not show
            $lang =& JFactory::getLanguage();
            $buttonsstyle->lang = $lang->getTag();
            $buttonsstyle->lang_ = str_replace('-', '_', $buttonsstyle->lang);
            
            if ( $this->params->get('showbuttonsafter',1) ){
                $article->text .= $this->_getSocialButtons( $url , $buttonsstyle );
            } else {
                $article->text = $this->_getSocialButtons( $url , $buttonsstyle ) . $article->text;
            }
            
            if ( $this->params->get('showfacebookcomments', 1) ){                
                $document->addScript( 'http://connect.facebook.net/'.$buttonsstyle->lang_.'/all.js#xfbml=1' );
                $article->text .= $this->_getFacebookComment( $url );//Add after
            }
            
            // Add styles

            $document->addStyleSheet( JURI::base( true ).'/plugins/content/socializeme/css/socializeme.css');
            
            return;
            
        }
        
        /* 
         * Function to build the Socialize-me html output
         * @return      String      HTML result for add to article
         */
        protected function _getSocialButtons($url, $buttonsstyle){
            
            $document =& JFactory::getDocument();

            $socializemeMainClass = ( $buttonsstyle->size ) ? 'socializeme' : 'socializeme-small';
            
            $socializeme .= '<div class="'.$socializemeMainClass.'">';
            
            if( $this->params->get('showgoogleplus',1) ){
                $document->addScript( 'https://apis.google.com/js/plusone.js' );
                $socializeme .= $this->_getGooglePlus($url, $buttonsstyle);;
            }
            if( $this->params->get('showtwittertweet',1) ){
                $document->addScript( 'http://platform.twitter.com/widgets.js' );
                $socializeme .= $this->_getTwitterTwitterButton($url, $buttonsstyle);
            }
            if( $this->params->get('showfacebooklike',1) ){                
                $document->addScript( 'http://connect.facebook.net/'.$buttonsstyle->lang_.'/all.js#xfbml=1' );
                $socializeme .= $this->_getFacebookLike($url, $buttonsstyle);
            }
            $socializeme .= '</div>';
            
            
            
            return $socializeme;
            
        }
        
        /* 
         * Function to build the Facebook Comments html output
         * @return      String      HTML result for add to article
         */
        protected function _getFacebookComment($url){
           
            $facebookComment ='<div id="fb-root"></div><fb:comments href="'.$url.'" num_posts="'.$num_posts.'" width="'.$width.'"></fb:comments>';
            
            return $facebookComment;
            
        }
        
        /* 
         * Function to build the Facebook Like html output
         * @return      String      HTML result for add to article
         */
        protected function _getFacebookLike($url, $buttonsstyle){
          
           $btclass = ($buttonsstyle->alignment) ? ' sml' : ' smr';
           $btclass .= ($buttonsstyle->showbuttonshorizoltal) ? 'h' : '';
           $datasize = ($buttonsstyle->size) ? 'data-size="tall"' : 'data-size="medium"';
           //$datacount = ($buttonsstyle->showbuttonscount) ? 'data-count="true"' : 'data-count="false"';
           if( $buttonsstyle->size == 1 && $buttonsstyle->showbuttonscount == 1 ){
               $layout = 'box_count';
           } elseif ( $buttonsstyle->size != 1 && $buttonsstyle->showbuttonscount == 1){
               $layout = 'button_count';
           } else {
               $layout = 'standart';
           }            
            $facebookLike = '<div class="sm-fblike'.$btclass.'"><iframe seamless="seamless" src="http://www.facebook.com/plugins/like.php?locale='.$buttonsstyle->lang_.'&amp;href='.rawurlencode($url).'&amp;layout='.$layout.'&amp;show_faces=false&amp;action=like&amp;colorscheme=light"></iframe></div>';
            return $facebookLike;            
        }
        
        /* 
         * Function to build the GooglePlus html output
         * @return      String      HTML result for add to article
         */
        protected function _getGooglePlus($url, $buttonsstyle){
           $btclass = ($buttonsstyle->alignment) ? ' sml' : ' smr';
           $btclass .= ($buttonsstyle->showbuttonshorizoltal) ? 'h' : '';
           $datasize = ($buttonsstyle->size) ? 'tall"' : 'medium';
           $datacount = ($buttonsstyle->showbuttonscount) ? 'true' : 'false';
            
            //$googleplus = '<div class="sm-gpb'.$btclass.'"><script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><div class="g-plusone" data-size="tall" data-count="true"></div></div>';
            $googleplus = '<div class="sm-gpb'.$btclass.'"><div class="g-plusone" data-size="'.$datasize.'" data-count="'.$datacount.'" data-lang="'.$buttonsstyle->lang.'"></div></div>';
            return $googleplus;
            
        }
        
        /* 
         * Function to build the TwitterButton html output
         * @return      String      HTML result for add to article
         */
        protected function _getTwitterTwitterButton($url, $buttonsstyle){
           $tdata = '';
           $btclass = ($buttonsstyle->alignment) ? ' sml' : ' smr';
           $btclass .= ($buttonsstyle->showbuttonshorizoltal) ? 'h' : '';
           
           if( $buttonsstyle->size == 1 && $buttonsstyle->showbuttonscount == 1 ){
               $datacount = 'vertical';
           } elseif ( $buttonsstyle->size != 1 && $buttonsstyle->showbuttonscount == 1){
               $datacount = 'horizoltal';
           } else {
               $datacount = 'none';
           }
           
            if( $this->params->get('ttb-via', NULL)) {
                $tdata = $this->params->get('ttb-via', NULL) . ' ';
            }
            if( $this->params->get('ttb-related', NULL)) {
                $tdata = $this->params->get('ttb-related', NULL) . ' ';
            }
            if( $this->params->get('ttb-lang', NULL)) {
                $tdata = $this->params->get('ttb-lang', NULL) . ' ';
            }
            
            $twitterbutton = '<div class="sm-ttb'.$btclass.'"><a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$url.'" data-count="'.$datacount.'" data-lang="'.$buttonsstyle->lang.'" data-via="fititnt">Twitter</a></div>';
            
            return $twitterbutton;            
        }
        
}
