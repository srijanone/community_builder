<?php

namespace Drupal\community_builder\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines AskQuestion class.
 */
class AskQuestion extends ControllerBase {

  /**
   * Render node posts add form.
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function render() {
    // Create form instance of type posts.
    $entity = $this->entityTypeManager()->getStorage('node')->create(array(
      'type' => 'posts',
    ));
    // build form
    $form = $this->entityFormBuilder()->getForm($entity);
    $form['field_meta_tags']['#access'] = FALSE;
    $form['meta']['#access'] = FALSE;
    $form['revision_information']['#access'] = FALSE;
    return $form;
  }

}
