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
		private function blockDomain(Request $request) {
      if ($this->get('kernel')->getEnvironment() == 'prod' && 
          !strstr($request->getHost(), 'www.') && 
          !strstr($request->getHost(), 'preprod.')) {
        throw $this->createNotFoundException('Page does not exist');
      }
		}

    /**
    * homes
    */
    public function mainAction(Request $request)
    {
			//	$this->blockDomain($request);
      /*$api = new ApiManager($this->container->getParameter('kernel.environment'), '.json', 2);
      $datas = $api->fetch('search/' .urlencode(str_replace('.', '%2E',  $request->get('q'))), 
                           array('img_width' => 160,
                                 'img_height' => 200,
                                 'nb_results' => 7,
                                 'facets' => $facets));
      //echo $api->url;
      //print_r($datas);
			*/
      $response = $this->render('SkreenHouseFactoryV3Bundle:Liberation:main.html.twig', array(
        //'results' => $datas,
      ));

      $maxage = 300;
      $response->setPublic();
      $response->setMaxAge($maxage);
      $response->setSharedMaxAge($maxage);
      
      return $response;
    }
}