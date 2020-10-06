<?php

namespace Drupal\addweb\Plugin\rest\resource;

use Drupal\node\Entity\Node;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "custom_rest_resource",
 *   label = @Translation("Custom rest resource"),
 *   uri_paths = {
 *     "canonical" = "/page_json/{siteapikey}/{nid}"
 *   }
 * )
 */
class CustomRestResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * Returns a json response for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($siteapikey,$nid) {

    // You must to implement the logic of your REST Resource here.
    $node = Node::load($nid);
    $nodeType = $node->getType();
    $api_key =  \Drupal::config('siteapikey.configuration')->get('siteapikey');
    
    //return response once met the condition  
    if($nodeType == 'page' && $api_key == $siteapikey) {
        $result[] = array(
          'nid' => $node->id(),
          'type' => $nodeType,
          'title' => $node->getTitle(),
          'created' => gmdate('Y-m-d H:i:s',$node->created->value),
          'changed' => gmdate('Y-m-d H:i:s',$node->changed->value)
        );
        $response = new ResourceResponse($result);
        $response->addCacheableDependency($result);
        return $response; 
    }
    else {
        throw new AccessDeniedHttpException('Access Denied');
    }
  }

}
