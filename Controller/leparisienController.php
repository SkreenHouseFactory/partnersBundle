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

class LeparisienController extends Controller
{
  /**
  * homes
  */
  public function mainAction(Request $request)
  {
    //$request->request->set('slug','media-center-inconnus');
    $response = $this->forward('SkreenHouseFactoryV3Bundle:Channel:channel', array(
           'slug'    => 'media-center-inconnus',
           'preview' => true,
           'sklayout'  => 'SkreenHouseFactoryPartnersBundle::leparisien',
           'partner'=> true
       ));

    $maxage = 300;
    $response->setPublic();
    $response->setMaxAge($maxage);
    $response->setSharedMaxAge($maxage);
    
    return $response;
  }

}