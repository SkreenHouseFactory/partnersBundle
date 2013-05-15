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
use \DOMXPath;

class DirectmatinController extends Controller
{
  /**
  * homes
  */
  public function mainAction(Request $request)
  {
    $layout = dirname(__FILE__) . '/../Resources/views/directmatin.html.twig';
    @unlink($layout);
    
    $api = new ApiManager($this->container->getParameter('kernel.environment'), '.json', 2);
    $data = $api->fetch('www/home/tv-replay', array(
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

    //$page = utf8_encode(file_get_contents('http://www.programme-tv.com/'));
    //$page = preg_replace('/<div id="content">(.*?)<\/div>/i', '<div id="content">{% block content endblock %}</div>', $page);

    $dom = new DOMDocument;
    libxml_use_internal_errors(TRUE);
    $dom->loadHTMLFile('http://www.directmatin.fr/myskreen');
    libxml_clear_errors();

    $xp = new DOMXPath($dom);

    // create the new element
    $newNode = $dom->createElement('div', '{% block content endblock %}');
    $newNode->setAttribute('id', 'div_to_replace');

    // fetch and replace the old element
    $oldNode = $dom->getElementById('div_to_replace');
    $oldNode->parentNode->replaceChild($newNode, $oldNode);

    // print xml
    $page = $dom->saveXml($dom->documentElement);

    $page = str_ireplace(array('charset=iso-8859-1', '<![CDATA[', ']]>'),array('charset=utf-8', ''),$page);

    $fichier = fopen($layout,'w+');
    fputs($fichier, $page);
    fclose($fichier);

    $response = $this->render('SkreenHouseFactoryPartnersBundle:DirectMatin:main.html.twig', array(
      'home' => $data,
    ));

    $maxage = 300;
    $response->setPublic();
    $response->setMaxAge($maxage);
    $response->setSharedMaxAge($maxage);
    
    return $response;
  }

}