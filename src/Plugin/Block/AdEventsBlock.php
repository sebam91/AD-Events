<?php

namespace Drupal\ad_events\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Agiledrop events.
 *
 * @Block(
 *   id = "block_ad_events",
 *   admin_label = @Translation("Agiledrop Events"),
 * )
 */
class AdEventsBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  protected $ProcessDate;
  protected $CurrentNode;

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('ad_events.process_date')
    );
  }

  public function __construct(array $configuration, $plugin_id, $plugin_definition, $ProcessDate)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->ProcessDate = $ProcessDate;

    if (\Drupal::routeMatch()->getParameter('node') != null) {

      $this->CurrentNode = \Drupal::routeMatch()->getParameter('node');

    }

  }

  public function build()
  {

    if ($this->CurrentNode->getType() == 'event') {

      $EventDate = $this->CurrentNode->field_event_date->value;

      $DayDifference = $this->ProcessDate->CalculateDayDifference($EventDate);

      if ($DayDifference > 0) {

        $response = '<b>' . $DayDifference . '</b> days left until event starts';

      } else if ($DayDifference == 0) {

        $response = '<span style="color:green;font-weight:bold;">This event is happening today</span>';

      } else $response = '<span style="color:red;">This event already passed</span>';


    } else {

      $response = '<b>Not the event page!<br>Please fix the layout positioning.</b>';

    }

    return [
      '#markup' => $this->t($response)
    ];
  }

  public function getCacheMaxAge()
  {
    return 0;
  }
}
