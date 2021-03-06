<?php

/**
 * sfResquePlugin configuration.
 *
 * @package     sfResquePlugin
 * @subpackage  config
 * @uses        sfPluginConfiguration
 * @author      Stephen Craton <scraton@gmail.com>
 * @license     The MIT License
 * @version     SVN: $Id$
 */
class sfResquePluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.0.0-DEV';

  /**
   * path to config
   *
   * @var string
   */
  const CONFIG_PATH = 'config/resque.yml';

  /**
   * initialize plugin
   *
   * @access public
   * @return void
   */
  public function initialize()
  {
    // Be careful: an appConfig has only been created if the task was run with an --application argument
    // This means --env is ignored if an is not used, so the default resque configuration
    // non-environment specific will be used.
    if ($this->configuration instanceof sfApplicationConfiguration)
    {
      $configCache = $this->configuration->getConfigCache();
      $configCache->registerConfigHandler(self::CONFIG_PATH, 'sfResqueConfigHandler');
      $config = include $configCache->checkConfig(self::CONFIG_PATH);
    }
    else // environment not used!
    {
      $configPaths = $this->configuration->getConfigPaths(self::CONFIG_PATH);
      $config = sfResqueConfigHandler::getConfiguration($configPaths);
    }

    Resque::setBackend($config['server'], $config['database']);
  }
}

