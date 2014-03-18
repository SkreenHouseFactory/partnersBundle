<?php

/*
 * This file is part of the SkreenHouseFactoryV3Bundle package.
 *
 * (c) Benoît Bergsorm <benoit@myskreen.com>
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

class RireetchansonsController extends Controller
{
  /**
  * homes
  */
  public function mainAction(Request $request)
  {
    $request->attributes->set('slug','media-center-inconnus');
    $request->attributes->set('partner',1);
    $response = $this->render('SkreenHouseFactoryPartnersBundle:Rireetchansons:inconnus-integrale.html.twig', array(
          'css' =>  (@file_get_contents("http://www.rireetchansons.fr/index/css/?partenaire=rire_les_inconnus&type=sans", False)),
          'header'=> (@file_get_contents("http://www.rireetchansons.fr/index/header/?partenaire=rire_les_inconnus&type=sans", False)),
          'footer'=>(@file_get_contents("http://www.rireetchansons.fr/index/footer/?partenaire=rire_les_inconnus", False))
       ));
    


    $maxage = 300;
    $response->setPublic();
    $response->setMaxAge($maxage);
    $response->setSharedMaxAge($maxage);
    
    return $response;
  }

}