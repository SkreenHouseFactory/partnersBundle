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

class LiberationController extends Controller
{
    /**
    * homes
    */
    public function mainAction(Request $request)
    {
      $layout = dirname(__FILE__) . '/../Resources/views/liberation.html.twig';
      @unlink($layout);
      //exec('rm -rf '.$this->get('kernel')->getRootDir().'app/cache/prod/twig/');

      $api = $this->get('api');
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

      $page = file_get_contents('http://www.liberation.fr/partenaires/100/?time='.time());
      $page = str_ireplace(array('<head>','<!-- contenu partenaire ici -->'), array('<head><meta name="robots" content="noindex, nofollow">','{% block content endblock %}'), $page);
      $fichier = fopen($layout,'w+');
      fputs($fichier, $page);
      fclose($fichier);

      $response = $this->render('SkreenHouseFactoryPartnersBundle:Liberation:main.html.twig', array(
        'home' => $data,
      ));

      $maxage = 3600;
      $response->setPublic();
      $response->setMaxAge($maxage);
      $response->setSharedMaxAge($maxage);
      
      return $response;
    }
}