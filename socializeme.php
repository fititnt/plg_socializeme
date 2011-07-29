<?php
/**
 * Emerson Rocha Luiz { emerson@webdesign.eng.br - http://fititnt.org }
 * Copyright (C) 2005 - 2011 Webdesign Assessoria em Tecnologia da Informacao.
 * GPL3
 */
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * GetGitHub Content Plugin
 *
 * @since		1.6
 */
class plgContentGetgithubcode extends JPlugin
{
	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param	string          The context of the content being passed to the plugin.
	 * @param	object          The content object.  Note $article->text is also available
	 * @param	object          The content params
	 * @param	int		The 'page' number
	 * @since	1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		$app = JFactory::getApplication();
                
                // simple performance check to determine whether bot should process further
                $tagname = $this->get('tagname', 'github');
		if (strpos($article->text, $tagname) === false) {
			return true;
		}
                
		// expression to search
                // {github}https://raw.github.com/example...{/github}
                // @todo: rewrite to make able to ask start and end lines
                $regex		= '~{'.$tagname.'}(.*?){/'.$tagname.'}~i'; 
		$matches	= array();

		// find all instances of plugin and put in $matches
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			// $match[0] is full pattern match, $match[1] is the url
			$code = $this->_getGithubCode($match[1]);
			// We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
			$article->text = preg_replace("|$match[0]|", $code, $article->text, 1);
		}
                return '';
	}
        
        /* Function to get get and change the githubcode
         * @author      Emerson Rocha Luiz
         * @var         string          $url: the url to get. Must be RAW url!
         * @var         int             $start: line to start to show
         * @var         int             $end: last line to show
         * @return      string          $github: the final github code to show
         */
        
        protected function _getGithubCode($url, $start = FALSE, $end = FALSE){
            //Get Page
            $page = $this->_getUrlContents($url, FALSE);
            
            //Convert linebreaks
            $page = $this->_Unix2Dos($page);
            
            //Get only desired lines
            if($start !== FALSE || $end !== FALSE){
                $page = $this->_getStringLines($page, $start, $end);
            }
            //Clean up special chars
            $github = htmlspecialchars($page);
            
            //Get start and end tags and apply
            $tagstart = $this->params->get('tagstart', '<pre>');
            $tagstart = str_replace('&lt;', '<', $tagstart);
            $tagstart = str_replace('&gt;', '>', $tagstart);
            $tagsend  = $this->params->get('tagend', '</pre>');
            $tagsend  = str_replace('&lt;', '<', $tagsend);
            $tagsend  = str_replace('&gt;', '>', $tagsend);
            $github   = $tagstart . $github . $tagsend;
            
            return $github;
        }        
        
        /*
         * Return contents of url
         * @author      Emerson Rocha Luiz
         * @var         string      $url
         * @var         string      $certificate path to certificate if is https URL
         * @return      string
         */
        protected function _getUrlContents($url, $certificate = FALSE){
            $ch = curl_init(); //Inicializar a sessao           
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//Retorne os dados em vez de imprimir em tela
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate);//Check certificate if is SSL, default FALSE
            curl_setopt($ch, CURLOPT_URL, $url);//Setar URL
            $content = curl_exec($ch);//Execute
            curl_close($ch);//Feche          
            
            return $content;
        }
        
        /* Return just lines betwen betwen start and end lines
         * @author      Emerson Rocha Luiz
         * @var         string          $string: the string to edit
         * @var         int             $start: initial line
         * @var         int             $end: end line
         * @return      string
         */
        protected function _getStringLines($string, $start, $end){

            $stringArray = explode(PHP_EOL, $string);
            $nLines = count($stringArray)-1;

            //Handle a few errors
            if( $end < $start || $end > $nLines){
                //return FALSE;
            }

            $result = '';
            for( $i=($start-1); $i<=$end ; $i++ ){
                $result .= $stringArray[$i] . PHP_EOL;
            }    
            return $result;    
        }
        
        /* Convert unix linebreaks to windows line breaks
         * @author      Emerson Rocha Luiz
         * @var         string          $string: the string to edit
         * @return      string          $newstring
         */
        protected function _Unix2Dos($string){
            if (strpos($string, "\n") === false) {
                //$newstring = false;
                $newstring = $string;				
            } else {
                $newstring = str_replace("\n", "\r\n", $string); 				 
            }
            return $newstring;
        }
}
