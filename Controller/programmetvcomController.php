<?php

/*
 * This file is part of the SkreenHouseFactoryV3Bundle package.
 *
 * (c) Brickstorm <http://brickstorm.org/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SkreenHouseFactory\partnersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use SkreenHouseFactory\v3Bundle\Api\ApiManager;

use \DOMDocument;

class ProgrammetvcomController extends Controller
{
    /**
    * homes
    */
    public function mainAction(Request $request)
    {
      $layout = dirname(__FILE__) . '/../Resources/views/programmetvcom.html.twig';
      @unlink($layout);
      //exec('rm -rf '.$this->get('kernel')->getRootDir().'app/cache/prod/twig/');
      $titles = array(
        'tv-replay' => 'Replay TV',
        'vod' => 'Vidéo à la demande',
        'cinema' => 'Cinéma',
      );
      $home = str_replace('cinema', 'cine', $request->get('home', 'tv-replay'));
      $api = $this->get('api');
      $data = $api->fetch('www/home/' . $home, array(
        'without_footer' => true,
        'with_programs' => true,
        'img_width' => 160,
        'img_height' => 200,
        'with_teaser' => true,
        'with_pass' => true,
        'slider_width' => 990
      ));
     //echo $api->url;
     //print_r($datas);

      $dom = new DOMDocument;
      libxml_use_internal_errors(true);
      $dom->loadHTMLFile('http://www.programme-tv.com/myskreen.html?time='.time());
      libxml_clear_errors();

      // create the new element
      $newNode = $dom->createElement('div', '{% block content endblock %}');
      $newNode->setAttribute('id', 'myskreen_content');

      // fetch and replace the old element
      $oldNode = $dom->getElementById('myskreen_content');
      $oldNode->parentNode->replaceChild($newNode, $oldNode);

      // print xml
      $page = $dom->saveXml($dom->documentElement);
      $page = preg_replace(
        '/^\s*\/\/<!\[CDATA\[([\s\S]*)\/\/\]\]>\s*\z/', 
        '$1', 
        $page
      );
      $page = str_replace(array('<![CDATA[', ']]>'), '', $page);
      $page = str_replace('="/', '="http://www.programme-tv.com/', $page);
      $page = str_replace('="#/', '="#http://www.programme-tv.com/', $page);

      $fichier = fopen($layout,'w+');
      fputs($fichier, $page);
      fclose($fichier);

      $response = $this->render('SkreenHouseFactoryPartnersBundle:ProgrammeTvCom:main.html.twig', array(
        'home' => $data,
        'title' => $titles[$request->get('home', 'tv-replay')]
      ));

      $maxage = 300;
      $response->setPublic();
      $response->setMaxAge($maxage);
      $response->setSharedMaxAge($maxage);
    
      return $response;
    }
}
